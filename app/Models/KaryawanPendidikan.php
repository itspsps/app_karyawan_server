<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryawanPendidikan extends Model
{
    use HasFactory;
    protected $table = 'karyawan_pendidikan';
    public $incrementing = false;
    protected $primaryKey = 'id_pendidikan';
    public $timestamps = false;
    protected $fillable = [
        'id_pendidikan',
        'id_karyawan',
        'institusi',
        'jurusan',
        'jenjang',
        'tanggal_masuk',
        'tanggal_keluar',
        'created_at',
        'updated_at',
    ];
}
