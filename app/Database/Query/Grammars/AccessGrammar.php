<?php

namespace App\Database\Query\Grammars;

use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Database\Query\Builder;

class AccessGrammar extends Grammar
{
    /**
     * Compile a select query into SQL.
     */
    public function compileSelect(Builder $query)
    {
        $sql = parent::compileSelect($query);

        // Ganti LIMIT jadi TOP karena Access pakai TOP
        if ($query->limit) {
            $top = "TOP " . (int) $query->limit;
            $sql = preg_replace('/^select/i', "select {$top}", $sql);
        }

        return $sql;
    }

    /**
     * Quote identifiers with [] instead of ""
     */
    protected function wrapValue($value)
    {
        if ($value === '*') {
            return $value;
        }

        return '[' . str_replace(']', ']]', $value) . ']';
    }
}
