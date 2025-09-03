<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $guarded = ['Id'];
    protected $fillable = [
        'EnrollNumber ',
        'LogTime',
        'VerifyMode',
        'InOutMode',
        'WorkCode',
        'MachineIp'
    ];
}
