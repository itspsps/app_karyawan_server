<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holding extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $guarded = ['id'];
    protected $fillable = [
        'holding_code',
        'holding_name',
        'holding_name_hint',
        'holding_category'
    ];

    public function Site()
    {
        return $this->hasMany(Site::class, 'site_holding_category', 'id');
    }
}
