<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jabatan extends Model
{
    use HasFactory, UuidTrait;
    public $incrementing = false;
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $fillable = ['divisi_id', 'holding', 'atasan_id', 'atasan2_id', 'bagian_id', 'nama_jabatan', 'level_id', 'lintas_departemen', 'created_at', 'updated_at'];

    public function LevelJabatan(): BelongsTo
    {
        return $this->belongsTo(LevelJabatan::class, 'level_id', 'id');
    }
    public function Bagian(): BelongsTo
    {
        return $this->belongsTo(Bagian::class, 'bagian_id', 'id');
    }
    public function Divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class, 'divisi_id', 'id');
    }
    public function User(): HasMany
    {
        return $this->hasMany(User::class);
    }
    public function User1(): HasMany
    {
        return $this->hasMany(User::class, 'jabatan1_id', 'id');
    }
    public function User2(): HasMany
    {
        return $this->hasMany(User::class, 'jabatan2_id', 'id');
    }
    public function User3(): HasMany
    {
        return $this->hasMany(User::class, 'jabatan3_id', 'id');
    }
    public function User4(): HasMany
    {
        return $this->hasMany(User::class, 'jabatan4_id', 'id');
    }
}
