<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use maliklibs\Zkteco\Lib\Helper\Attendance;

class Karyawan extends Model
{
    use HasFactory, UuidTrait;
    // protected $primaryKey = null;
    public $incrementing = false;
    protected $fillable = [
        'id',
        'nomor_identitas_karyawan',
        'name',
        'nik',
        'status_npwp',
        'nama_pemilik_npwp',
        'npwp',
        'nama_bank',
        'nama_pemilik_rekening',
        'nomor_rekening',
        'agama',
        'golongan_darah',
        'status_nomor',
        'nomor_wa',
        'foto_karyawan',
        'email',
        'telepon',
        'tempat_lahir',
        'tgl_lahir',
        'gender',
        'tgl_join',
        'status_nikah',
        'strata_pendidikan',
        'instansi_pendidikan',
        'jurusan_akademik',
        'kuota_cuti_tahunan',
        'kategori',
        'tgl_mulai_kontrak',
        'tgl_selesai_kontrak',
        'kontrak_kerja',
        'file_kontrak_kerja',
        'lama_kontrak_kerja',
        'kontrak_site',
        'penempatan_kerja',
        'approval_site',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'desa',
        'rt',
        'rw',
        'detail_alamat',
        'alamat',
        'status_alamat',
        'provinsi_domisili',
        'kabupaten_domisili',
        'kecamatan_domisili',
        'desa_domisili',
        'rt_domisili',
        'rw_domisili',
        'detail_alamat_domisili',
        'alamat_domisili',
        'kategori_jabatan',
        'shift',
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
        'atasan_1',
        'atasan_2',
        'ptkp',
        'bpjs_ketenagakerjaan',
        'nama_pemilik_bpjs_ketenagakerjaan',
        'no_bpjs_ketenagakerjaan',
        'bpjs_pensiun',
        'bpjs_kesehatan',
        'nama_pemilik_bpjs_kesehatan',
        'no_bpjs_kesehatan',
        'kelas_bpjs',
        'face_id',
        'status_aktif',
        'tanggal_nonactive',
        'alasan_nonactive'
    ];


    public function MappingShift()
    {
        return $this->hasMany(MappingShift::class, 'karyawan_id', 'id');
    }
    public function Absensi()
    {
        return $this->hasMany(AttendanceLog::class, 'EnrollNumber', 'nomor_identitas_karyawan');
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
        return $this->hasMany(Cuti::class, 'user_id', 'id');
    }
    public function Izin()
    {
        return $this->hasMany(Izin::class, 'user_id', 'id');
    }

    public function PenempatanKerja(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'penempatan_kerja', 'id');
    }
    public function KontrakKerja(): BelongsTo
    {
        return $this->belongsTo(Holding::class, 'kontrak_kerja', 'id');
    }
    public function Penugasan()
    {
        return $this->hasMany(Penugasan::class);
    }

    public function Departemen(): BelongsTo
    {
        return $this->belongsTo(Departemen::class, 'dept_id', 'id');
    }
    public function Departemen1(): BelongsTo
    {
        return $this->belongsTo(Departemen::class, 'dept_id1', 'id');
    }
    public function Departemen2(): BelongsTo
    {
        return $this->belongsTo(Departemen::class, 'dept_id2', 'id');
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

    public function Bagian(): BelongsTo
    {
        return $this->belongsTo(Bagian::class, 'bagian_id', 'id');
    }
    public function Bagian1(): BelongsTo
    {
        return $this->belongsTo(Bagian::class, 'bagian1_id', 'id');
    }
    public function Bagian2(): BelongsTo
    {
        return $this->belongsTo(Bagian::class, 'bagian2_id', 'id');
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

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'karyawan_id');
    }
    public function Provinsi(): BelongsTo
    {
        return $this->belongsTo(Provincies::class, 'code', 'provinsi');
    }
    public function Kabupaten(): BelongsTo
    {
        return $this->belongsTo(Cities::class, 'code', 'kabupaten');
    }
    public function Kecamatan(): BelongsTo
    {
        return $this->belongsTo(District::class, 'code', 'kecamatan');
    }
    public function Desa(): BelongsTo
    {
        return $this->belongsTo(Village::class, 'code', 'desa');
    }
    public function karyawanKesehatan(): HasOne
    {
        return $this->hasOne(KaryawanKesehatan::class, 'id_karyawan', 'id');
    }
    public function karyawanPendidikan(): HasOne
    {
        return $this->hasOne(KaryawanPendidikan::class, 'id_karyawan', 'id');
    }
    public function karyawanKesehatanRS(): HasOne
    {
        return $this->hasOne(KaryawanKesehatanRS::class, 'id_karyawan', 'id');
    }
    public function karyawanKesehatanPengobatan(): HasOne
    {
        return $this->hasOne(KaryawanKesehatanPengobatan::class, 'id_karyawan', 'id');
    }
    public function karyawanKesehatanKecelakaan(): HasOne
    {
        return $this->hasOne(KaryawanKesehatanKecelakaan::class, 'id_karyawan', 'id');
    }
}
