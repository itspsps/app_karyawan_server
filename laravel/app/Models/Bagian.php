<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bagian extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $guarded = ['id'];
    protected $fillable = ['id', 'holding', 'nama_bagian', 'divisi_id', 'created_at', 'updated_at'];


    public function Divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class, 'divisi_id', 'id');
    }
}
