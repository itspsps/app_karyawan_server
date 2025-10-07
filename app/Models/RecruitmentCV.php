<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecruitmentCV extends Model
{
    use HasFactory;
    protected $table = 'recruitment_cv';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = [
        'users_career_id',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'nik',
        'agama',
        'ktp',
        'agama',
        'ktp',
        'provinsi_ktp',
        'kabupaten_ktp',
        'kecamatan_ktp',
        'kelurahan_ktp',
        'rt_ktp',
        'rw_ktp',
        'file_pp',
        'provinsi_now',
        'kabupaten_now',
        'kecamatan_now',
        'kelurahan_now',
        'rt_now',
        'rw_now',
        'jenis_kelamin',
        'status_pernikahan',
        'lama_nomor_whatsapp',
        'nomor_whatsapp_darurat',
        'ijazah',
        'persetujuan',
        'created_at',
        'updated_at'
    ];

    public function ToUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_career_id', 'id');
    }
    // Alamat User sesuai KTP
    public function provinsiKTP(): BelongsTo
    {
        return $this->belongsTo(Provincies::class, 'provinsi_ktp', 'code');
    }
    public function kabupatenKTP(): BelongsTo
    {
        return $this->belongsTo(Cities::class, 'kabupaten_ktp', 'code');
    }
    public function kecamatanKTP(): BelongsTo
    {
        return $this->belongsTo(District::class, 'kecamatan_ktp', 'code');
    }
    public function desaKTP(): BelongsTo
    {
        return $this->belongsTo(Village::class, 'desa_ktp', 'code');
    }
    // End Alamat User sesuai KTP
    // Alamat User sekarang
    public function provinsiNOW(): BelongsTo
    {
        return $this->belongsTo(Provincies::class, 'provinsi_now', 'code');
    }
    public function kabupatenNOW(): BelongsTo
    {
        return $this->belongsTo(Cities::class, 'kabupaten_now', 'code');
    }
    public function kecamatanNOW(): BelongsTo
    {
        return $this->belongsTo(District::class, 'kecamatan_now', 'code');
    }
    public function desaNOW(): BelongsTo
    {
        return $this->belongsTo(Village::class, 'desa_now', 'code');
    }

    public function recruitmentPendidikan(): HasMany
    {
        return $this->hasMany(RecruitmentPendidikan::class, 'users_career_id', 'id_user');
    }
    // end alamat user sekarang
}
