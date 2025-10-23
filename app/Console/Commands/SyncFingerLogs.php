<?php

namespace App\Console\Commands;

use App\Models\AttendanceLog;
use App\Models\FingerMachine;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncFingerLogs extends Command
{
    protected $signature = 'finger:sync';
    protected $description = 'Sync logs dari semua mesin fingerprint via API .NET';
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $machines = FingerMachine::all();

        foreach ($machines as $machine) {
            $this->info("Sync dari {$machine->Ip}:{$machine->Port}");

            try {
                $response = Http::timeout(30)->get('http://localhost:5257/api/ZK/logs', [
                    'ip' => $machine->Ip,
                    'port' => $machine->Port,
                    'dateFrom' => now()->subHour(1)->toDateTimeString(),
                    'dateTo' => now()->toDateTimeString(),
                ]);

                if ($response->successful()) {
                    $logs = $response->json();

                    foreach ($logs as $log) {
                        AttendanceLog::insertOrIgnore(
                            [
                                'machine_ip' => $machine->Ip,
                                'pin' => $log['EnrollNumber'],
                                'timestamp' => $log['LogTime'],
                            ],
                            [
                                'verify_mode' => $log['VerifyMode'] ?? null,
                                'in_out_mode' => $log['InOutMode'] ?? null,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        );
                    }

                    $this->info("✅ Berhasil sync dari {$machine->Ip} dengan " . count($logs) . " log baru.");
                } else {
                    $this->error("❌ Gagal: " . $response->status());
                }
            } catch (\Exception $e) {
                $this->error("⚠️ Error dari {$machine->Ip}: " . $e->getMessage());
            }
        }

        $this->info("Semua mesin sudah diproses!");
    }
}
