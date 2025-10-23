<?php

namespace App\Console\Commands;

use App\Models\AttendanceLog;
use App\Models\FingerMachine;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class SyncFingerLogs extends Command
{
    protected $signature = 'finger:sync';
    protected $description = 'Sync logs dari semua mesin fingerprint via API .NET (paralel)';

    public function handle()
    {
        $machines = FingerMachine::all();

        if ($machines->isEmpty()) {
            $this->warn('⚠️ Tidak ada mesin fingerprint di database.');
            return Command::SUCCESS;
        }

        $this->info('🔄 Mulai sync dari ' . $machines->count() . ' mesin fingerprint...');

        // buat semua request paralel
        $requests = [];
        foreach ($machines as $machine) {
            $key = "{$machine->Ip}:{$machine->Port}";
            $requests[$key] = fn() =>
            Http::timeout(60)->get('http://localhost:5257/api/ZK/logs', [
                'ip' => $machine->Ip,
                'port' => $machine->Port,
                'dateFrom' => now()->subHour(1)->toDateTimeString(),
                'dateTo' => now()->toDateTimeString(),
            ]);
        }

        // kirim semua request bersamaan (paralel)
        $responses = Http::pool($requests);

        // proses semua hasilnya
        foreach ($responses as $key => $response) {
            [$ip, $port] = explode(':', $key);

            if ($response->successful()) {
                $logs = $response->json();

                if (empty($logs)) {
                    $this->line("ℹ️ Tidak ada data baru dari {$ip}");
                    continue;
                }

                // batch insert agar lebih cepat
                $insertData = [];
                foreach ($logs as $log) {
                    $insertData[] = [
                        'machine_ip' => $ip,
                        'pin' => $log['EnrollNumber'],
                        'timestamp' => $log['LogTime'],
                        'verify_mode' => $log['VerifyMode'] ?? null,
                        'in_out_mode' => $log['InOutMode'] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // insert sekaligus (abaikan duplikat)
                DB::table('attendance_logs')->insertOrIgnore($insertData);

                $this->info("✅ {$ip}: berhasil sync " . count($logs) . " log baru.");
            } else {
                $this->error("❌ {$ip}: Gagal diakses, status {$response->status()}");
            }
        }

        $this->info('🎯 Semua mesin fingerprint sudah diproses secara paralel!');
        return Command::SUCCESS;
    }
}
