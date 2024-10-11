<?php

namespace App\Exports;

use App\Models\Karyawan;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class UserExport implements FromCollection, WithEvents, WithHeadings, WithTitle, WithCustomStartCell
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $holding;

    function __construct($holding)
    {
        $this->holding = $holding;
    }
    public function headings(): array
    {
        return [
            'NAMA KARYAWAN',
            'USERNAME',
            'PASSWORD',
            'LEVEL',
            'STATUS',
            'ALASAN',
        ];
    }
    public function title(): string
    {
        return "DATA USER";
    }
    public function startCell(): string
    {
        return 'A2';
    }
    public function collection()
    {
        return User::leftJoin('karyawans as a', 'a.id', 'users.karyawan_id')
            ->where('a.kontrak_kerja', $this->holding)
            ->where('users.is_admin', 'user')
            // ->where('karyawans.status_aktif', 'AKTIF')
            ->select(
                'a.name', //2
                'username',
                'password_show', //11
                'is_admin', //12
                'user_aktif', //12
                'alasan', //12

            )
            ->where('a.kontrak_kerja', $this->holding)
            ->orderBy('a.name', 'ASC')
            ->get();
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
        return [
            AfterSheet::class    => function (AfterSheet $event) use ($holding) {
                $event->sheet
                    ->getDelegate()
                    ->getStyle('A2:F2')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet
                    ->getDelegate()->getStyle('I1')->getFont()->setSize(14);
                $event->sheet
                    ->getDelegate()->getStyle('I1')->getFont()->setBold(true);
                $event->sheet
                    ->getDelegate()->getStyle('A2:F2')->getFont()->setBold(true);
                $event->sheet
                    ->setCellValue('I1', 'DATA MASTER USER KARYAWAN ' . $holding);
            },
        ];
    }
}
