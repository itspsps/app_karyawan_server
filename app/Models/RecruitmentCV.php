<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecruitmentCV extends Model
{
    use HasFactory;
    protected $table = 'recruitment_cv';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = [
        'id',
        'users_career_id',
        'nama_depan',
        'nama_tengah',
        'nama_belakang',
        'tempat_lahir',
        'tanggal_lahir',
        'gender',
        'status_nikah',
        'nik',
        'email',
        'no_hp',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'desa',
        'rt',
        'rw',
        'rw',
        'jalan',
        'detail_alamat',
        'alamat_ktp',
        'nama_sdmi',
        'tahun_sdmi',
        'nama_smpmts',
        'tahun_smpmts',
        'nama_smamasmk',
        'tahun_smamasmk',
        'nama_universitas',
        'tahun_universitas',
        'judul_keterampilan1',
        'ket_keterampilan1',
        'judul_keterampilan2',
        'ket_keterampilan2',
        'judul_keterampilan3',
        'ket_keterampilan3',
        'judul_pengalaman1',
        'lokasi_pengalaman1',
        'tahun_pengalaman1',
        'judul_pengalaman2',
        'lokasi_pengalaman2',
        'tahun_pengalaman2',
        'judul_pengalaman3',
        'lokasi_pengalaman3',
        'tahun_pengalaman3',
        'prestasi1',
        'prestasi2',
        'prestasi3',
        'file_ktp',
        'file_kk',
        'file_ijazah'
    ];

    public function ToUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_career_id', 'id');
    }

}
