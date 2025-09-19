<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FingerMachine extends Model
{
    use HasFactory, UuidTrait;
    public $incrementing = false;
    protected $table = 'fingerprint_machines';
    protected $guarded = ['Id'];
    protected $fillable = [
        'Name',
        'Ip',
        'Port',
        'IsActive',
        'status'
    ];
}
