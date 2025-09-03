<?php

namespace App\Models;

use App\Database\Query\Grammars\AccessGrammar;
use Illuminate\Database\Eloquent\Model;

class FingerUser extends Model
{
    protected $connection = 'solution_access';
    protected $table = 'USERINFO';
    public $timestamps = false;

    protected static function booted()
    {
        // Pakai grammar Access
        static::addGlobalScope('access_grammar', function ($builder) {
            $builder->getConnection()
                ->setQueryGrammar(new AccessGrammar);
        });
    }
}
