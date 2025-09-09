<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewAdmin extends Model
{
    use HasFactory, UuidTrait;
    protected $table = 'interview_admin';
    public $incrementing = false;

    protected $guarded = ['id'];
    protected $fillable = [
        'parameter',
        'deskripsi',
        'created_at',
        'updated_at'
    ];
}
