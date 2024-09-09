<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Titik extends Model
{
    use HasFactory, UuidTrait;
    public $incrementing = false;
    // protected $table = 'data_titik';
    protected $guarded = ['id'];
    protected $fillable = ['lokasi_id', 'nama_titik', 'lat_titik', 'long_titik', 'radius_titik'];

    public function Lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id', 'id');
    }
}
