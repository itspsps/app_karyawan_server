<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailEsai extends Model
{
    use HasFactory;
    public $table = 'ujian_esai_detail';
    protected $guarded = ['id'];

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }
    public function ujianEsaiJawabDetail(): BelongsTo
    {
        return $this->belongsTo(ujianEsaiJawabDetail::class, 'id', 'id_soal');
    }
}
