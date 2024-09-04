<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recruitment extends Model
{
    use HasFactory;
    protected $table = 'recruitment_admin';
    public $incrementing = false;
    protected $fillable = [
        'kategori_recruitment',
        'objek_recruitment',
        'penempatan_recruitment',
        'syarat_keteentuan',
        'status',
    ];
}
