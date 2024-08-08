<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;
    protected $guarded = ['id_asset'];
    public $incrementing = false;
    protected $fillable = [
        'id_asset',
        'site_asset',
        'kode_asset',
        'nama_asset',
        'jumlah_asset',
        'foto_asset',
        'kategori_asset'
    ];
}
