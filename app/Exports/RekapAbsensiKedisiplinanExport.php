<?php

namespace App\Exports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\FromCollection;

class RekapAbsensiKedisiplinanExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        // misal data sudah diproses dari controller dan dikirim via $data
        return collect($this->data);
    }
}
