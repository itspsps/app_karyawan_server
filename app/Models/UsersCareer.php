<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersCareer extends Model
{
    use HasFactory;
    protected $table = 'users_career';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'nama',
        'email',
        'nomor_whatsapp',
        'password',
        'is_active'
    ];
}
