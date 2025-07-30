<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembobotan extends Model
{
    use HasFactory;
    protected $table = 'pembobotan';

    protected $guarded = ['pembobotan_id'];
    protected $fillable = ['esai', 'pilihan_ganda', 'interview', 'interview_user'];
}
