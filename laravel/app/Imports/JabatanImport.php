<?php

namespace App\Imports;

use App\Models\Bagian;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\LevelJabatan;
use Maatwebsite\Excel\Concerns\ToModel;
use Ramsey\Uuid\Uuid;

class JabatanImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Jabatan([
            'id' => Uuid::uuid4(),
            "holding" => $row[0],
            "divisi_id" => Divisi::where('nama_divisi', $row[1])->where('holding', 'sps')->value('id'),
            "bagian_id" => Bagian::where('nama_bagian', $row[2])->where('holding', 'sps')->value('id'),
            "nama_jabatan" => $row[3],
            "level_id" => LevelJabatan::where('id', $row[4])->value('id'),
            "created_at" => now(),
            "updated_at" => now(),
        ]);
    }
}
