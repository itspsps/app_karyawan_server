<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, UuidTrait, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guard = 'web';
    public $incrementing = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'nomor_identitas_karyawan',
        'name',
        'nik',
        'npwp',
        'nama_bank',
        'nomor_rekening',
        'fullname',
        'motto',
        'foto_karyawan',
        'email',
        'telepon',
        'username',
        'password',
        'tempat_lahir',
        'tgl_lahir',
        'gender',
        'tgl_join',
        'status_nikah',
        'kuota_cuti_tahunan',
        'is_admin',
        'kategori',
        'tgl_mulai_kontrak',
        'tgl_selesai_kontrak',
        'kontrak_kerja',
        'file_kontrak_kerja',
        'lama_kontrak_kerja',
        'kontrak_site',
        'penempatan_kerja',
        'site_job',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'desa',
        'rt',
        'rw',
        'detail_alamat',
        'alamat',
        'kategori_jabatan',
        'dept_id',
        'divisi_id',
        'bagian_id',
        'jabatan_id',
        'dept1_id',
        'divisi1_id',
        'bagian1_id',
        'jabatan1_id',
        'dept2_id',
        'divisi2_id',
        'bagian2_id',
        'jabatan2_id',
        'dept3_id',
        'divisi3_id',
        'bagian3_id',
        'jabatan3_id',
        'dept4_id',
        'divisi4_id',
        'bagian4_id',
        'jabatan4_id',
        'atasan_1',
        'atasan_2',
        'access_1',
        'face_id',
        'no_bpjs_ketenagakerjaan',
        'no_bpjs_kesehatan',
        'bpjs_ketenagakerjaan',
        'bpjs_kesehatan',
        'bpjs_pensiun',
        'kelas_bpjs',
        'ptkp',
        'status_npwp',
        'status_aktif',
    ];


    public function MappingShift()
    {
        return $this->hasMany(MappingShift::class);
    }

    public function Sip()
    {
        return $this->hasMany(Sip::class);
    }

    public function Lembur()
    {
        return $this->hasMany(Lembur::class);
    }

    public function Cuti()
    {
        return $this->hasMany(Cuti::class);
    }
    public function Izin()
    {
        return $this->hasMany(Izin::class);
    }

    public function Penugasan()
    {
        return $this->hasMany(Penugasan::class);
    }

    public function Jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id', 'id');
    }
    public function Jabatan1(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'jabatan1_id', 'id');
    }
    public function Jabatan2(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'jabatan2_id', 'id');
    }
    public function Jabatan3(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'jabatan3_id', 'id');
    }
    public function Jabatan4(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'jabatan4_id', 'id');
    }
    public function Departemen(): BelongsTo
    {
        return $this->belongsTo(Departemen::class, 'dept_id', 'id');
    }
    public function Divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class, 'divisi_id', 'id');
    }
    public function Divisi1(): BelongsTo
    {
        return $this->belongsTo(Divisi::class, 'divisi1_id', 'id');
    }
    public function Divisi2(): BelongsTo
    {
        return $this->belongsTo(Divisi::class, 'divisi2_id', 'id');
    }
    public function Divisi3(): BelongsTo
    {
        return $this->belongsTo(Divisi::class, 'divisi3_id', 'id');
    }
    public function Divisi4(): BelongsTo
    {
        return $this->belongsTo(Divisi::class, 'divisi4_id', 'id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    // public static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $model->id = Str::uuid();
    //         $model->jabatan_id = Jabatan::where('id', $model->id)->value('id');
    //     });
    // }
}
