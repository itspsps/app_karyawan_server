<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecruitmentReferensi extends Model
{
    use HasFactory;
    protected $table = 'recruitment_referensi';
    public $incrementing = false;
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'alamat',
        'tempat_link',
        'created_at',
        'updated_at',
    ];
}
