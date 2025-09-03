<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Site extends Model
{
    use HasFactory, UuidTrait;
    public $incrementing = false;
    protected $guarded = ['id'];
    protected $fillable = [
        'site_name',
        'site_holding_category',
        'site_status',
        'site_alamat',
        'site_lat',
        'site_long',
        'site_radius'
    ];

    public function Holding(): BelongsTo
    {
        return $this->belongsTo(Holding::class, 'site_holding_category', 'id');
    }
}
