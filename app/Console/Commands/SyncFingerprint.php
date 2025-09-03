<?php

namespace App\Console\Commands;

use App\Services\FingerprintService;
use Illuminate\Console\Command;

class SyncFingerprint extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fingerprint:sync';
    protected $description = 'Sync attendance logs from Solution Fingerprint';

    /**
     * The console command description.
     *
     * @var string
     */
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(FingerprintService $fingerprintService)
    {
        if ($fingerprintService->fetchAttendance()) {
            $this->info('Fingerprint logs synced successfully!');
        } else {
            $this->error('Failed to connect to fingerprint device.');
        }
    }
}
