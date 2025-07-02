<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecruitmentUserRecord extends Model
{
    use HasFactory;
    protected $table = 'recruitment_user_record';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'id',
        'recruitment_user_id',
        'status',
        'created_at',
        'updated_at'
    ];
}
