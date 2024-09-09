<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $fillable = ['kode_kantor', 'kategori_kantor', 'lokasi_kantor', 'lat_kantor', 'long_kantor', 'radius'];

    public function Titik()
    {
        return $this->belongsTo(Titik::class, 'id', 'lokasi_id');
    }
}
