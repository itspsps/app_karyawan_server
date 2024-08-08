<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Divisi extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $guarded = ['id'];
    protected $fillable = ['id', 'holding', 'nama_divisi', 'dept_id', 'created_at', 'updated_at'];


    public function Departemen(): BelongsTo
    {
        return $this->belongsTo(Departemen::class, 'dept_id', 'id');
    }
    public function Jabatan(): HasMany
    {
        return $this->hasMany(Jabatan::class, 'divisi_id', 'id');
    }
}
