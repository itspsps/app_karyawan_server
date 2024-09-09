<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelJabatan extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $guarded = ['id', 'level_jabatan', 'created_at', 'updated_at'];
}
