<?php

namespace App\Exports;

use App\Models\Penugasan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class PenugasanExport implements FromCollection, WithEvents, WithMapping, WithHeadings, WithTitle, WithCustomStartCell
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
        if ($this->kategori == 'Cuti Tahunan') {
            return [
                'NO.',
                'NO FORM',
                'NAMA',
                'DEPARTEMEN',
                'DIVISI',
                'JABATAN',
                'KATEGORI CUTI',
                'TANGGAL PENGAJUAN',
                'TANGGAL MULAI CUTI',
                'TANGGAL SELESAI CUTI',
                'TANGGAL MASUK',
                'TOTAL CUTI',
                'KETERANGAN CUTI',
                'NAMA PENGGANTI',
                'NAMA ATASAN 1',
                'WAKTU APPROVE ATASAN 1',
                'CATATAN APPROVE 1',
                'NAMA ATASAN 2',
                'WAKTU APPROVE ATASAN 2',
                'CATATAN APPROVE 2',
                'STATUS',
            ];
        } else if ($this->kategori == 'Diluar Cuti Tahunan') {
            return [
                'NO.',
                'NO FORM',
                'NAMA',
                'DEPARTEMEN',
                'DIVISI',
                'JABATAN',
                'KATEGORI CUTI',
                'NAMA CUTI',
                'TANGGAL PENGAJUAN',
                'TANGGAL MULAI CUTI',
                'TANGGAL SELESAI CUTI',
                'TANGGAL MASUK',
                'TOTAL CUTI',
                'KETERANGAN CUTI',
                'NAMA PENGGANTI',
                'NAMA ATASAN 1',
                'WAKTU APPROVE ATASAN 1',
                'CATATAN APPROVE 1',
                'NAMA ATASAN 2',
                'WAKTU APPROVE ATASAN 2',
                'CATATAN APPROVE 2',
                'STATUS',
            ];
        }
    }
    public function title(): string
    {
        return "DATA PERMOHONAN PERJALANAN DINAS KARYAWAN";
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
        if ($row->status_cuti == 1) {
            $status_cuti = 'Menunggu Approve Atasan 1';
        } else if ($row->status_cuti == 0) {
            $status_cuti = 'Pengajuan Cuti';
        } else if ($row->status_cuti == 2) {
            $status_cuti = 'Menunggu Approve Atasan 2';
        } else if ($row->status_cuti == 3) {
            $status_cuti = 'Cuti Di Approve';
        } else {
            $status_cuti = 'Cuti Not Approve';
        }

        if ($this->kategori == 'Cuti Tahunan') {
            $data = [
                $this->index,
                $row->no_form_cuti,
                $row->name,
                $row->nama_departemen,
                $row->nama_divisi,
                $row->nama_jabatan,
                $row->nama_cuti,
                $row->tanggal,
                $row->tanggal_mulai,
                $row->tanggal_selesai,
                Carbon::parse($row->waktu_approve)->addDays(1)->format('d-m-Y'),
                $row->total_cuti,
                $row->keterangan_cuti,
                $row->nama_user_backup,
                $row->approve_atasan,
                $row->waktu_approve,
                $row->catatan,
                $row->approve_atasan2,
                $row->waktu_approve2,
                $row->catatan2,
                $status_cuti
            ];
        } else if ($this->kategori == 'Diluar Cuti Tahunan') {
            $data = [
                $this->index,
                $row->no_form_cuti,
                $row->name,
                $row->nama_departemen,
                $row->nama_divisi,
                $row->nama_jabatan,
                $row->nama_cuti,
                $row->kategori_cuti,
                $row->tanggal,
                $row->tanggal_mulai,
                $row->tanggal_selesai,
                Carbon::parse($row->waktu_approve)->addDays(1)->format('d-m-Y'),
                $row->total_cuti,
                $row->keterangan_cuti,
                $row->nama_user_backup,
                $row->approve_atasan,
                $row->waktu_approve,
                $row->catatan,
                $row->approve_atasan2,
                $row->waktu_approve2,
                $row->catatan2,
                $status_cuti
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
        if ($this->kategori == 'Cuti Tahunan') {
            $tittle = 'CUTI TAHUNAN';
        } else if ($this->kategori == 'Diluar Cuti Tahunan') {
            $tittle = 'DILUAR CUTI TAHUNAN';
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
                    ->setCellValue('I2', 'PERMOHONAN PERJALANAN DINAS ' . $tittle . ' KARYAWAN ' . $holding);
            }
        ];
    }
};
