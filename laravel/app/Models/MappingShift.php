<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MappingShift extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public $incrementing = true;
    protected $fillable = [
        'user_id',
        'nik_karyawan',
        'nama_karyawan',
        'shift_id',
        'nama_shift',
        'koordinator_id',
        'lokasi_bekerja',
        'tanggal_masuk',
        'jam_absen',
        'telat',
        'lat_absen',
        'long_absen',
        'jarak_masuk',
        'foto_jam_absen',
        'keterangan_absensi',
        'tanggal_pulang',
        'jam_pulang',
        'pulang_cepat',
        'lembur',
        'foto_jam_pulang',
        'lat_pulang',
        'long_pulang',
        'jarak_pulang',
        'keterangan_absensi_pulang',
        'total_jam_kerja',
        'status_absen',
        'kelengkapan_absensi',
        'izin_id',
        'cuti_id',
        'penugasaan_id',
        'keterangan_izin',
        'keterangan_cuti',
        'keterangan_dinas'
    ];

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function Koordinator()
    {
        return $this->belongsTo(User::class, 'koordinator_id', 'id');
    }

    public function Shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
