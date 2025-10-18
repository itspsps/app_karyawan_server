<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory, UuidTrait;
    public $incrementing = false;
    protected $guarded = ['id'];
    protected $fillable = [
        'nama_shift',
        'jam_terlambat',
        'jam_masuk',
        'jam_min_masuk',
        'jam_pulang_cepat',
        'jam_keluar',
        'hari_libur',
        'kode_warna'
    ];

    public function MappingShift()
    {
        return $this->hasMany(MappingShift::class);
    }
}
