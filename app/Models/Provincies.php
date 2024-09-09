<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provincies extends Model
{
    use HasFactory;
    protected $table = 'indonesia_provinces';
    protected $guarded = ['id'];
    protected $fillable = ['code', 'name', 'meta'];
}
