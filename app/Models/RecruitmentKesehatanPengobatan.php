<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecruitmentKesehatanPengobatan extends Model
{
    use HasFactory;
    protected $table = 'recruitment_cv_kesehatan_pengobatan';
    public $incrementing = false;
    protected $primaryKey = 'id_pengobatan';
    public $timestamps = false;
    protected $fillable = [
        'id_user',
        'obat',
        'alasan',
        'created_at',
        'updated_at',
    ];
}
