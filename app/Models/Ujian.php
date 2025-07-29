<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ujian extends Model
{
    public $table = 'ujian';
    use HasFactory;
    protected $guarded = ['id'];
    protected $with = ['detailujian'];

    // Relasi Ke Guru
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function ujianKategori()
    {
        return $this->belongsTo(UjianKategori::class, 'kategori_id', 'id');
    }

    // relasi Ke kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // relasi Ke WaktuUjian
    public function waktuujian()
    {
        return $this->hasMany(WaktuUjian::class, 'kode', 'kode');
    }

    // relasi Ke DetailUjian
    public function detailujian()
    {
        return $this->hasMany(DetailUjian::class, 'kode', 'kode');
    }

    // relasi Ke DetailEssay
    public function detailesai()
    {
        return $this->hasMany(DetailEsai::class, 'kode', 'kode');
    }

    // DEFAULT KEY DI UBAH JADI KODE BUKAN ID LAGI
    public function getRouteKeyName()
    {
        return 'kode';
    }
    public function esaiJawab(): BelongsTo
    {
        return $this->belongsTo(UjianEsaiJawab::class, 'kode', 'kode');
    }
}
