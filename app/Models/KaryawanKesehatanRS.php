<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryawanKesehatanRS extends Model
{
    use HasFactory;
    protected $table = 'karyawan_kesehatan_rs';
    public $incrementing = false;
    protected $primaryKey = 'id_kesehatan_rs';
    public $timestamps = false;
    protected $fillable = [
        'id_karyawan',
        'tahun_rs',
        'penyebab_rs',
        'created_at',
        'updated_at',
    ];
}
