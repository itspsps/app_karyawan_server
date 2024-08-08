<?php

namespace App\Imports;

use App\Models\Departemen;
use App\Models\Divisi;
use Maatwebsite\Excel\Concerns\ToModel;
use Ramsey\Uuid\Uuid;

class DivisiImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Divisi([
            'id' => Uuid::uuid4(),
            "holding" => $row[0],
            "nama_divisi" => $row[1],
            "dept_id" => Departemen::where('nama_departemen', $row[2])->where('holding', 'sps')->value('id'),
            "created_at" => now(),
            "updated_at" => now(),
        ]);
    }
}
