<?php

namespace App\Imports;

use App\Models\Bagian;
use App\Models\Divisi;
use Maatwebsite\Excel\Concerns\ToModel;
use Ramsey\Uuid\Uuid;

class BagianImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Bagian([
            'id' => Uuid::uuid4(),
            "holding" => $row[0],
            "nama_bagian" => $row[1],
            "divisi_id" => Divisi::where('nama_divisi', $row[2])->where('holding', 'sps')->value('id'),
            "created_at" => now(),
            "updated_at" => now(),
        ]);
    }
}
