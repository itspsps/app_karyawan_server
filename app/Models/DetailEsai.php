<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailEsai extends Model
{
    use HasFactory;
    public $table = 'ujian_esai_detail';
    protected $guarded = ['id'];

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }
}
