<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryawanKesehatan extends Model
{
    use HasFactory;
    protected $table = 'karyawan_kesehatan';
    public $incrementing = false;
    protected $primaryKey = 'id_kesehatan';
    public $timestamps = false;
    protected $fillable = [
        'id_kesehatan',
        'id_karyawan',
        'perokok',
        'alkohol',
        'alergi',
        'sebutkan_alergi',
        'pengobatan_rutin',
        'asma',
        'hipertensi',
        'jantung',
        'tbc',
        'hepatitis',
        'epilepsi',
        'gangguan_mental',
        'gangguan_pengelihatan',
        'gangguan_lainnya',
        'pernah_dirawat_rs',
        'kecelakaan_serius',
        'keterbatasan_fisik',
        'mampu_shift',
        'pemeriksaan_kerja_sebelumnya',
        'covid',
        'tetanus',
        'vaksin_lainnya',
        'persetujuan_kesehatan',
        'created_at',
        'updated_at',
    ];
}
