<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryawanKeahlian extends Model
{
    use HasFactory;
    protected $table = 'karyawan_keahlian';
    public $incrementing = false;
    protected $primaryKey = 'id_keahlian';
    public $timestamps = false;
    protected $fillable = [
        'id_keahlian',
        'id_karyawan',
        'keahlian',
        'file_keahlian',
        'created_at',
        'expired_at',
    ];
}
