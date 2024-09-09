<?php

namespace App\Imports;

use App\Models\Departemen;
use Maatwebsite\Excel\Concerns\ToModel;
use Ramsey\Uuid\Uuid;

class DepartemenImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        return new Departemen([
            'id' => Uuid::uuid4(),
            "holding" => $row[0],
            "nama_departemen" => $row[1],
            "created_at" => now(),
            "updated_at" => now(),
        ]);
    }
}
