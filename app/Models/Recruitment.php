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
        'nama_dept',
        'nama_divisi',
        'nama_bagian',
        'desc_recruitment',
        'status_recruitment',
        'status_recruitment',
        'created_recruitment',
    ];

    public function Bagian(): BelongsTo
    {
        return $this->belongsTo(Bagian::class, 'nama_bagian', 'id');
    }
}
