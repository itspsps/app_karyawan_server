<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecruitmentRiwayat extends Model
{
    use HasFactory, UuidTrait;
    protected $table = 'recruitment_cv_riwayat';
    public $incrementing = false;
    protected $primaryKey = 'id_riwayat';
    public $timestamps = false;
    protected $fillable = [
        'id_user',
        'nama_perusahaan',
        'alamat_perusahaan',
        'posisi',
        'tanggal_masuk',
        'tanggal_keluar',
        'surat_keterangan',
        'nomor_referensi',
        'jabatan_referensi',
        'created_at',
        'expired_at',
    ];
}
