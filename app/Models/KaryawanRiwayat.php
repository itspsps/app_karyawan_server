<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryawanRiwayat extends Model
{
    use HasFactory, UuidTrait;
    protected $table = 'karyawan_riwayat';
    public $incrementing = false;
    protected $primaryKey = 'id_riwayat';
    public $timestamps = false;
    protected $fillable = [
        'id_karyawan',
        'nama_perusahaan',
        'gaji',
        'alamat_perusahaan',
        'posisi',
        'tanggal_masuk',
        'tanggal_keluar',
        'alasan_keluar',
        'surat_keterangan',
        'nomor_referensi',
        'jabatan_referensi',
        'created_at',
        'expired_at',
    ];
}
