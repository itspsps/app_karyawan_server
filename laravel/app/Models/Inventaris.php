<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model
{
    use HasFactory;
    protected $guarded = ['id_inventaris'];
    public $incrementing = false;
    protected $fillable = [
        'holding_inventaris',
        'kode_inventaris',
        'nama_inventaris',
        'type_inventaris',
        'serial_number_inventaris',
        'foto_inventaris',
        'kategori_inventaris'
    ];
}
