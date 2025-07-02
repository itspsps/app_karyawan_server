<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecruitmentUser extends Model
{
    use HasFactory;
    protected $table = 'recruitment_user';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'id',
        'holding',
        'nama_dept',
        'nama_divisi',
        'nama_bagian',
        'nama_depan',
        'nama_tengah',
        'nama_belakang',
        'nik',
        'file_kk',
        'file_ktp',
        'status_recruitmentuser',
    ];

    public function Jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'nama_jabatan', 'id');
    }

    public function Bagian(): BelongsTo
    {
        return $this->belongsTo(Bagian::class, 'nama_bagian', 'id');
    }
    public function Departemen(): BelongsTo
    {
        return $this->belongsTo(Departemen::class, 'nama_dept', 'id');
    }
    public function Divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class, 'nama_divisi', 'id');
    }
    public function DataInterview(): BelongsTo
    {
        return $this->belongsTo(RecruitmentInterview::class, 'id', 'recruitment_userid');
    }
    public function Cv(): BelongsTo
    {
        return $this->belongsTo(RecruitmentCV::class, 'users_career_id', 'users_career_id');
    }
    public function AuthLogin(): BelongsTo
    {
        return $this->belongsTo(UserCareer::class, 'users_career_id', 'id');
    }
    public function WaktuUjian(): BelongsTo
    {
        return $this->belongsTo(WaktuUjian::class, 'users_career_id', 'auth_id');
    }
    // Alamat User
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
    // End Alamat User
}
