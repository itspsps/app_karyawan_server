<?php

namespace App\Console\Commands;

use App\Models\ResetCuti as ModelsResetCuti;
use App\Models\User;
use Illuminate\Console\Command;

class resetCuti extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:cuti';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset Cuti Pertanggal Masuk Kantor';

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
    public function handle()
    {        
        $now = date('Y-m-d');
        $month = date('m', strtotime($now));
        $date = date('d', strtotime($now));
        $users = \App\Models\User::whereMonth('tgl_join', '=', $month)->whereDay('tgl_join', '=', $date)->get();
        $reset_cuti = \App\Models\ResetCuti::first();
        foreach($users as $user) {
            \App\Models\User::where('id', 3)->update([
                "cuti_dadakan" => $reset_cuti->cuti_dadakan,
                "cuti_bersama" => $reset_cuti->cuti_bersama,
                "cuti_menikah" => $reset_cuti->cuti_menikah,
                "cuti_diluar_tanggungan" => $reset_cuti->cuti_diluar_tanggungan,
                "cuti_khusus" => $reset_cuti->cuti_khusus,
                "cuti_melahirkan" => $reset_cuti->cuti_melahirkan,
                "izin_telat" => $reset_cuti->izin_telat,
                "izin_pulang_cepat" => $reset_cuti->izin_pulang_cepat
            ]);
        }
    }
}
