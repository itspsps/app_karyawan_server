<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceLog extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $guarded = ['Id'];
    protected $casts = [
        'LogTime' => 'datetime',
    ];
    protected $fillable = [
        'EnrollNumber ',
        'LogTime',
        'VerifyMode',
        'InOutMode',
        'WorkCode',
        'MachineIp'
    ];
    public function Karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'EnrollNumber', 'nomor_identitas_karyawan');
    }
}
