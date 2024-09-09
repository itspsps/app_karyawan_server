<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory, UuidTrait;
    public $incrementing = false;
    protected $guarded = ['id'];
    protected $fillable = ['nama_shift', 'jam_masuk', 'jam_keluar'];

    public function MappingShift()
    {
        return $this->hasMany(MappingShift::class);
    }
}
