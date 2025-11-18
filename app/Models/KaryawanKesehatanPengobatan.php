<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryawanKesehatanPengobatan extends Model
{
    use HasFactory;
    protected $table = 'karyawan_kesehatan_pengobatan';
    public $incrementing = false;
    protected $primaryKey = 'id_pengobatan';
    public $timestamps = false;
    protected $fillable = [
        'id_karyawan',
        'obat',
        'alasan',
        'created_at',
        'updated_at',
    ];
}
