<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Izin extends Model
{
    use HasFactory, UuidTrait;
    public $incrementing = false;
    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'departements_id',
        'jabatan_id',
        'divisi_id',
        'telp',
        'email',
        'fullname',
        'izin',
        'terlambat',
        'pulang_cepat',
        'jam_keluar',
        'jam_kembali',
        'user_id_backup',
        'user_name_backup',
        'catatan_backup',
        'tanggal',
        'jam',
        'keterangan_izin',
        'foto_izin',
        'ttd_pengajuan',
        'waktu_ttd_pengajuan',
        'approve_atasan',
        'id_approve_atasan',
        'ttd_atasan',
        'status_izin',
        'waktu_approve',
        'catatan',
        'no_form_izin',
    ];

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function KategoriIzin(): BelongsTo
    {
        return $this->belongsTo(KategoriIzin::class);
    }
}
