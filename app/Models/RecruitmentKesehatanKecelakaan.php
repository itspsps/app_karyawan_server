<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecruitmentKesehatanKecelakaan extends Model
{
    use HasFactory;
    protected $table = 'recruitment_cv_kesehatan_kecelakaan';
    public $incrementing = false;
    protected $primaryKey = 'id_kecelakaan';
    public $timestamps = false;
    protected $fillable = [
        'id_user',
        'tahun_kecelakaan',
        'penyebab_kecelakaan',
        'created_at',
        'updated_at',
    ];
}
