<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


class UserCareer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users_career';
    public $incrementing = false;
    protected $guarded = ['id'];

    protected $fillable = [
        'id',
        'nama',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function recruitmentCV(): HasOne
    {
        return $this->hasOne(RecruitmentCV::class, 'users_career_id', 'id');
    }
    public function waktuujian()
    {
        return $this->belongsTo(WaktuUjian::class, 'id', 'auth_id');
    }
}
