<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UjianEsaiJawab extends Model
{
    use HasFactory;
    protected $table = 'ujian_esai_jawab';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = [
        'kode',
        'recruitment_user_id',
        'jawaban',
        'waktu_mulai',
        'waktu_berakhir',
        'created_at',
        'status',
    ];
    // relasi Ke DetailUjian
    public function detailujian()
    {
        return $this->hasMany(UjianEsaiJawab::class, 'kode', 'kode');
    }
}
