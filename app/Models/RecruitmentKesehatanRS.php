<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecruitmentKesehatanRS extends Model
{
    use HasFactory;
    protected $table = 'recruitment_cv_kesehatan_rs';
    public $incrementing = false;
    protected $primaryKey = 'id_kesehatan_rs';
    public $timestamps = false;
    protected $fillable = [
        'id_user',
        'tahun_rs',
        'penyebab_rs',
        'created_at',
        'updated_at',
    ];
}
