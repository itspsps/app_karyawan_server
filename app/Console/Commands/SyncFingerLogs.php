<?php

namespace App\Console\Commands;

use App\Models\AttendanceLog;
use App\Models\FingerMachine;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Client\Pool;

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

        // kirim semua request bersamaan (paralel)
        $responses = Http::pool(function (Pool $pool) use ($machines) {
            $requests = [];

            foreach ($machines as $machine) {
                $requests["{$machine->Ip}:{$machine->Port}"] = $pool
                    ->timeout(60)
                    ->get('http://localhost:5257/api/ZK/logs', [
                        'ip' => $machine->Ip,
                        'port' => $machine->Port,
                        'dateFrom' => now()->subHour(1)->toDateTimeString(),
                        'dateTo' => now()->toDateTimeString(),
                    ]);
            }

            return $requests;
        });

        // proses hasilnya
        foreach ($responses as $key => $response) {
            // key bisa integer, jadi kita ambil ip:port dari $machines
            $machine = $machines[$key];
            $ip = $machine->Ip;
            $port = $machine->Port;

            if ($response->successful()) {
                $logs = $response->json();

                if (empty($logs)) {
                    $this->line("ℹ️ Tidak ada data baru dari {$ip}");
                    continue;
                }

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

                DB::table('attendance_logs')->insertOrIgnore($insertData);

                $this->info("✅ {$ip}: berhasil sync " . count($logs) . " log baru.");
            } else {
                $this->error("❌ {$ip}: gagal, status {$response->status()}");
            }
        }

        $this->info('🎯 Semua mesin fingerprint sudah diproses secara paralel!');
        return Command::SUCCESS;
    }
}
