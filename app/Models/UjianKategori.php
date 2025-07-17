<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UjianKategori extends Model
{
    use HasFactory;
    protected $table = 'ujian_kategori';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'nama_kategori',
        'created_at',
        'updated_at',
    ];
}
