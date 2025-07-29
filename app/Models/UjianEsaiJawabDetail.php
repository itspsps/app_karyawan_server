<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UjianEsaiJawabDetail extends Model
{
    use HasFactory;
    protected $table = 'ujian_esai_detail_jawab';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = [
        'kode',
        'recruitment_user_id',
        'jawaban',
        'id_soal',
        'created_at',
        'updated_at',
    ];
}
