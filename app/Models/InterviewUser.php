<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewUser extends Model
{
    use HasFactory, UuidTrait;
    protected $table = 'interview_user';
    public $incrementing = false;

    protected $guarded = ['id'];
    protected $fillable = [
        'recruitment_user_id',
        'parameter',
        'deskripsi',
        'nilai',
        'created_at',
        'updated_at'
    ];
}
