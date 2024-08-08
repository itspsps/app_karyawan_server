<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResetCuti extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'reset_cutis';
    public $incrementing = false;
    protected $fillable = [
        'nama_cuti',
        'jumlah_cuti',
        'status'
    ];
}
