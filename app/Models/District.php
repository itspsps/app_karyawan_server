<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $table = 'indonesia_districts';
    protected $guarded = ['id'];
    protected $fillable = ['code', 'city_code', 'name', 'meta'];
}
