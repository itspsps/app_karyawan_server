<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecruitmentInterview extends Model
{
    use HasFactory;
    protected $table = 'recruitment_interview';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'holding',
        'recruitment_userid',
        'tanggal_interview',
        'jam_interview',
        'lokasi_interview',
        'status_interview',
        'updated_at',
        'created_at',
    ];

    public function DataRecruitment(): BelongsTo
    {
        return $this->belongsTo(RecruitmentUser::class, 'recruitment_userid', 'id');
    }
    public function Departemen(): BelongsTo
    {
        return $this->belongsTo(Departemen::class, 'nama_dept', 'id');
    }
}
