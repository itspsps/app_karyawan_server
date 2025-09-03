<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lokasi extends Model
{
    use HasFactory, UuidTrait;

    protected $guarded = ['id'];
    public $incrementing = false;
    protected $fillable = [
        'id',
        'site_id',
        'nama_lokasi',
        'lat_lokasi',
        'long_lokasi',
        'radius_lokasi',
    ];

    public function Site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_id', 'id');
    }
}
