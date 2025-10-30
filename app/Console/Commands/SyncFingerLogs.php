<?php

namespace App\Console\Commands;

use App\Models\AttendanceLog;
use App\Models\FingerMachine;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Client\Pool;
use GuzzleHttp\Exception\ConnectException;

class SyncFingerLogs extends Command
{
    protected $signature = 'finger:sync';
    protected $description = 'Sync logs dari semua mesin fingerprint via API .NET (paralel, aman error)';

    public function handle()
    {
        $machines = FingerMachine::where('IsActive', '1')
            // ->limit(1)
            ->get();

        if ($machines->isEmpty()) {
            $this->warn('⚠️ Tidak ada mesin fingerprint di database.');
            return Command::SUCCESS;
        }

        $this->info('🔄 Mulai sync dari ' . $machines->count() . ' mesin fingerprint...');

        // jalankan request paralel
        $responses = Http::pool(function (Pool $pool) use ($machines) {
            $reqs = [];
            foreach ($machines as $machine) {
                $reqs[] = $pool
                    ->timeout(360)
                    ->get('http://localhost:5257/api/ZK/logs', [
                        'ip' => $machine->Ip,
                        'port' => $machine->Port,
                        'dateFrom' => now()->subHour(1)->toDateTimeString(),
                        'dateTo' => now()->toDateTimeString(),
                        // 'dateFrom' => now()->subHour(10)->toDateTimeString(),
                        // 'dateTo' => now()->toDateTimeString(),
                    ]);
            }
            return $reqs;
        });

        // proses hasil
        foreach ($responses as $i => $response) {
            $machine = $machines[$i];
            $ip = $machine->Ip;
            $port = $machine->Port;

            // jika koneksi gagal (exception)
            if ($response instanceof ConnectException) {
                $this->error("❌ {$ip}: Tidak bisa terhubung ({$response->getMessage()})");
                continue;
            }

            // jika response HTTP valid
            if ($response->successful()) {
                $logs = $response->json();
                if (empty($logs)) {
                    $this->line("ℹ️ {$ip}: tidak ada log baru.");
                    continue;
                }
                // $this->line(json_encode($logs, JSON_PRETTY_PRINT));
                $insertData = [];
                foreach ($logs as $log) {
                    $insertData[] = [
                        'MachineIp' => $ip,
                        'EnrollNumber' => $log['enrollNumber'] ?? null,
                        'LogTime' => $log['dateTime'] ?? null,
                        'VerifyMode' => $log['verifyMode'] ?? null,
                        'InOutMode' => $log['inOutMode'] ?? null,
                        'WorkCode' => $log['workCode'] ?? null
                    ];
                }
                // filter data yang invalid (misalnya timestamp kosong)
                $insertData = array_filter($insertData, fn($row) => !empty($row['EnrollNumber']) && !empty($row['LogTime']));

                if (!empty($insertData)) {
                    // Bagi ke batch kecil untuk hindari limit MySQL
                    $chunks = array_chunk($insertData, 1000);
                    foreach ($chunks as $chunk) {
                        DB::table('attendance_logs')->insertOrIgnore($chunk);
                    }
                    $this->info("✅ {$ip}: berhasil sync " . count($insertData) . " log valid.");
                } else {
                    $this->line("ℹ️ {$ip}: tidak ada log valid untuk disimpan.");
                }
            } else {
                $status = $response->status() ?? 'unknown';
                $this->error("⚠️ {$ip}: gagal dengan status {$status}");
            }
        }

        $this->info('🎯 Semua mesin fingerprint sudah diproses!');
        return Command::SUCCESS;
    }
}
