<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriCuti extends Model
{
    use HasFactory ,UuidTrait;
    protected $table = 'kategori_cuti';
    protected $guard = 'id';

    protected $fillable = [
        'nama_cuti',
        'jumlah_cuti',
        'status',
    ];


}
