<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleUsers extends Model
{
    use HasFactory;
    protected $fillable = ['role_menu_id', 'role_user_id'];


    public function roleMenu(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_menu_id', 'id');
    }
    public function Users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'role_user_id', 'id');
    }
}
