<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriIzin extends Model
{
    use HasFactory, UuidTrait;
    protected $table = 'kategori_izin';
    protected $guard = 'id';

    protected $fillable = [
        'nama_izin',
        'status',
    ];
}
