<?php

namespace App\Services;

use Maliklibs\Zkteco\Lib\ZKTeco;
use App\Models\Attendance;

class FingerprintService
{
    protected $zk;

    public function __construct()
    {
        // ganti IP sesuai mesin kamu
        $this->zk = new ZKTeco('192.168.1.3');
    }

    public function fetchAttendance()
    {
        if ($this->zk->connect()) {
            $logs = $this->zk->getAttendance();

            foreach ($logs as $log) {
                Attendance::updateOrCreate(
                    [
                        'id' => $log['uid'],
                        'timestamp' => $log['timestamp'],
                    ],
                    [
                        'karyawan_id' => $log['id'],
                        'status'  => $log['state'],
                    ]
                );
            }

            $this->zk->disconnect();
            return true;
        }

        return false;
    }
}
