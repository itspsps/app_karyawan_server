<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

class Role extends Model
{
    use HasFactory, HasRoles;
    protected $fillable = ['role_name', 'role_description'];
    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function roleUsers(): HasMany
    {
        return $this->hasMany(RoleUsers::class, 'role_menu_id', 'id');
    }
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'role_menus')
            ->withPivot(['can_view', 'can_create', 'can_edit', 'can_delete']);
    }
}
