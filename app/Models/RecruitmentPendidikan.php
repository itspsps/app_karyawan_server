<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecruitmentPendidikan extends Model
{
    use HasFactory;
    protected $table = 'recruitment_cv_pendidikan';
    public $incrementing = false;
    protected $primaryKey = 'id_pendidikan';
    public $timestamps = false;
    protected $fillable = [
        'id_user',
        'institusi',
        'jurusan',
        'jenjang',
        'tanggal_masuk',
        'tanggal_keluar',
        'created_at',
        'expired_at',
    ];
}
