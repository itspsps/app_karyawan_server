<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recruitment extends Model
{
    use HasFactory;
    protected $table = 'recruitment_admin';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'holding_recruitment',
        'penempatan',
        'penggantian_penambahan',
        'surat_penambahan',
        'kuota',
        'nama_jabatan',
        'nama_dept',
        'nama_divisi',
        'nama_bagian',
        'desc_recruitment',
        'status_recruitment',
        'created_recruitment',
        'end_recruitment',
        'deadline_recruitment',
    ];

    public function Bagian(): BelongsTo
    {
        return $this->belongsTo(Bagian::class, 'nama_bagian', 'id');
    }
    public function Jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'nama_jabatan', 'id');
    }
    public function Sites(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'penempatan', 'id');
    }
    public function Holding(): BelongsTo
    {
        return $this->belongsTo(Holding::class, 'holding_recruitment', 'id');
    }
}
