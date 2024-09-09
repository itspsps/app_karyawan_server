<?php

namespace App\Exports;

use App\Models\Izin;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class IzinExport implements FromCollection, WithEvents, WithMapping, WithHeadings, WithTitle, WithCustomStartCell
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $holding;
    protected $kategori;
    protected $data;
    private $index = 0;

    function __construct($holding, $kategori, $data)
    {
        $this->holding = $holding;
        $this->kategori = $kategori;
        $this->data = $data;
    }
    public function headings(): array
    {
        if ($this->kategori == 'Datang Terlambat') {
            return [
                'NO.',
                'NO FORM',
                'NAMA',
                'DEPARTEMEN',
                'DIVISI',
                'JABATAN',
                'TANGGAL',
                'JAM KERJA',
                'JAM ABSEN',
                'TOTAL TERLAMBAT',
                'KETERANGAN',
                'NAMA ATASAN',
                'WAKTU APPROVE ATASAN',
                'CATATAN',
                'STATUS',
            ];
        } else if ($this->kategori == 'Keluar Kantor') {
            return [
                'NO.',
                'NO FORM',
                'NAMA',
                'DEPARTEMEN',
                'DIVISI',
                'JABATAN',
                'TANGGAL',
                'JAM KELUAR',
                'JAM KEMBALI',
                'KETERANGAN',
                'NAMA ATASAN',
                'WAKTU APPROVE ATASAN',
                'CATATAN',
                'STATUS',
            ];
        } else if ($this->kategori == 'Pulang Cepat') {
            return [
                'NO.',
                'NO FORM',
                'NAMA',
                'DEPARTEMEN',
                'DIVISI',
                'JABATAN',
                'TANGGAL',
                'JAM PULANG CEPAT',
                'KETERANGAN',
                'NAMA ATASAN',
                'WAKTU APPROVE ATASAN',
                'CATATAN',
                'STATUS',
            ];
        } else if ($this->kategori == 'Sakit') {
            return [
                'NO.',
                'NAMA',
                'DEPARTEMEN',
                'DIVISI',
                'JABATAN',
                'TANGGAL',
                'KETERANGAN',
                'NAMA ATASAN',
                'WAKTU APPROVE ATASAN',
                'CATATAN',
                'STATUS',
            ];
        } else if ($this->kategori == 'Tidak Masuk') {
            return [
                'NO.',
                'NAMA',
                'DEPARTEMEN',
                'DIVISI',
                'JABATAN',
                'TANGGAL MULAI',
                'TANGGAL SELESAI',
                'NAMA PENGGANTI',
                'CATATAN PENGGANTI',
                'KETERANGAN',
                'NAMA ATASAN',
                'WAKTU APPROVE ATASAN',
                'CATATAN',
                'STATUS',
            ];
        }
    }
    public function title(): string
    {
        return "DATA IZIN KARYAWAN";
    }
    public function startCell(): string
    {
        return 'A4';
    }
    public function collection()
    {
        // dd(count($this->data));
        return $this->data;
    }
    public function map($row): array
    {
        $this->index++;
        if ($row->status_izin == 1) {
            $status_izin = 'Menunggu Approve Atasan';
        } else if ($row->status_izin == 0) {
            $status_izin = 'Pengajuan Izin';
        } else if ($row->status_izin == 2) {
            $status_izin = 'Izin Di Approve';
        } else {
            $status_izin = 'Izin Not Approve';
        }

        if ($this->kategori == 'Datang Terlambat') {
            $data = [
                $this->index,
                $row->no_form_izin,
                $row->name,
                $row->nama_departemen,
                $row->nama_divisi,
                $row->nama_jabatan,
                $row->tanggal,
                $row->jam_masuk_kerja,
                $row->jam,
                $row->terlambat,
                $row->keterangan_izin,
                $row->approve_atasan,
                date('d-m-Y H:i:s', strtotime($row->waktu_approve)),
                $row->catatan,
                $status_izin
            ];
        } else if ($this->kategori == 'Keluar Kantor') {
            $data = [
                $this->index,
                $row->no_form_izin,
                $row->name,
                $row->nama_departemen,
                $row->nama_divisi,
                $row->nama_jabatan,
                $row->tanggal,
                $row->jam_keluar,
                $row->jam_kembali,
                $row->keterangan_izin,
                $row->approve_atasan,
                date('d-m-Y H:i:s', strtotime($row->waktu_approve)),
                $row->catatan,
                $status_izin
            ];
        } else if ($this->kategori == 'Pulang Cepat') {
            $data = [
                $this->index,
                $row->no_form_izin,
                $row->name,
                $row->nama_departemen,
                $row->nama_divisi,
                $row->nama_jabatan,
                $row->tanggal,
                $row->pulang_cepat,
                $row->keterangan_izin,
                $row->approve_atasan,
                date('d-m-Y H:i:s', strtotime($row->waktu_approve)),
                $row->catatan,
                $status_izin
            ];
        } else if ($this->kategori == 'Sakit') {
            $data = [
                $this->index,
                $row->no_form_izin,
                $row->name,
                $row->nama_departemen,
                $row->nama_divisi,
                $row->nama_jabatan,
                $row->tanggal,
                $row->keterangan_izin,
                $row->approve_atasan,
                date('d-m-Y H:i:s', strtotime($row->waktu_approve)),
                $row->catatan,
                $status_izin
            ];
        } else if ($this->kategori == 'Tidak Masuk') {
            $data = [
                $this->index,
                $row->no_form_izin,
                $row->name,
                $row->nama_departemen,
                $row->nama_divisi,
                $row->nama_jabatan,
                $row->tanggal_mulai,
                $row->tanggal_selesai,
                $row->user_name_backup,
                $row->catatan_backup,
                $row->keterangan_izin,
                $row->approve_atasan,
                date('d-m-Y H:i:s', strtotime($row->waktu_approve)),
                $row->catatan,
                $status_izin
            ];
        }
        return $data;
    }
    public function registerEvents(): array
    {
        if ($this->holding == 'sp') {
            $holding = 'CV. SUMBER PANGAN';
        } else if ($this->holding == 'sps') {
            $holding = 'PT. SURYA PANGAN SEMESTA';
        } else {
            $holding = 'CV. SURYA INTI PANGAN';
        }
        if ($this->kategori == 'Datang Terlambat') {
            $tittle = 'DATANG TERLAMBAT';
        } else if ($this->kategori == 'Keluar Kantor') {
            $tittle = 'KELUAR KANTOR';
        } else if ($this->kategori == 'Pulang Cepat') {
            $tittle = 'PULANG CEPAT';
        } else if ($this->kategori == 'Sakit') {
            $tittle = 'SAKIT';
        } else if ($this->kategori == 'Tidak Masuk') {
            $tittle = 'TIDAK MASUK';
        }

        return [
            AfterSheet::class    => function (AfterSheet $event) use ($holding, $tittle) {
                $event->sheet
                    ->getDelegate()
                    ->getStyle('A4:BH4')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet
                    ->getDelegate()->getStyle('I2')->getFont()->setSize(14);
                $event->sheet
                    ->getDelegate()->getStyle('I2')->getFont()->setBold(true);
                $event->sheet
                    ->getDelegate()->getStyle('A4:BH4')->getFont()->setBold(true);
                $event->sheet
                    ->setCellValue('I2', 'DATA IZIN ' . $tittle . ' KARYAWAN ' . $holding);
            }
        ];
    }
};
