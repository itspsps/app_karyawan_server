<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class KaryawanExport implements FromCollection, WithEvents, WithHeadings, WithTitle, WithCustomStartCell
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
            'ID',
            'ID KARYAWAN',
            'NAMA',
            'NIK',
            'NPWP',
            'NAMA LENGKAP',
            'MOTTO',
            'EMAIL',
            'TELEPON',
            'USERNAME',
            'TEMPAT LAHIR',
            'TANGGAL LAHIR',
            'KELAMIN',
            'TANGGAL BERGABUNG',
            'STATUS PERNIKAHAAN',
            'PROVINSI',
            'KABUPATEN/KOTA',
            'KECAMATAN',
            'DESA',
            'RT',
            'RW',
            'KETERANGAN ALAMAT',
            'SALDO CUTI',
            'KATEGORI KARYAWAN',
            'LAMA KONTRAK',
            'TANGGAL MULAI KONTRAK',
            'TANGGAL SELESAI KONTRAK',
            'KONTRAK KERJA',
            'PENEMPATAN KERJA',
            'SITE JOB',
            'BANK',
            'NOMOR REKENING',
            'KATEGORI JABATAN',
            'DEPARTEMEN',
            'DIVISI',
            'BAGIAN',
            'JABATAN',
            'DEPARTEMEN 2',
            'DIVISI 2',
            'BAGIAN 2',
            'JABATAN 2',
            'DEPARTEMEN 3',
            'DIVISI 3',
            'BAGIAN 3',
            'JABATAN 3',
            'DEPARTEMEN 4',
            'DIVISI 4',
            'BAGIAN 4',
            'JABATAN 4',
            'DEPARTEMEN 5',
            'DIVISI 5',
            'BAGIAN 5',
            'JABATAN 5',
            'BPJS KETENAGAKERJAAN',
            'NO BPJS KETENAGAKERJAAN',
            'BPJS PENSIUN',
            'BPJS KESEHATAN',
            'NO BPJS KESEHATAN',
            'KELAS BPJS',
            'PTKP'
        ];
    }
    public function title(): string
    {
        return "DATA KARYAWAN";
    }
    public function startCell(): string
    {
        return 'A4';
    }
    public function collection()
    {
        return User::leftJoin('departemens as a', 'a.id', 'users.dept_id')
            ->leftJoin('divisis as b', 'b.id', 'users.divisi_id')
            ->leftJoin('bagians as c', 'c.id', 'users.bagian_id')
            ->leftJoin('jabatans as d', 'd.id', 'users.jabatan_id')
            ->leftJoin('divisis as e', 'e.id', 'users.divisi1_id')
            ->leftJoin('bagians as f', 'f.id', 'users.bagian1_id')
            ->leftJoin('jabatans as g', 'g.id', 'users.jabatan1_id')
            ->leftJoin('divisis as h', 'h.id', 'users.divisi2_id')
            ->leftJoin('bagians as i', 'i.id', 'users.bagian2_id')
            ->leftJoin('jabatans as j', 'j.id', 'users.jabatan2_id')
            ->leftJoin('divisis as k', 'k.id', 'users.divisi3_id')
            ->leftJoin('bagians as l', 'l.id', 'users.bagian3_id')
            ->leftJoin('jabatans as m', 'm.id', 'users.jabatan3_id')
            ->leftJoin('divisis as n', 'n.id', 'users.divisi4_id')
            ->leftJoin('bagians as o', 'o.id', 'users.bagian4_id')
            ->leftJoin('jabatans as p', 'p.id', 'users.jabatan4_id')
            ->leftJoin('departemens as u', 'u.id', 'users.dept1_id')
            ->leftJoin('departemens as v', 'v.id', 'users.dept2_id')
            ->leftJoin('departemens as w', 'w.id', 'users.dept3_id')
            ->leftJoin('departemens as x', 'x.id', 'users.dept4_id')
            ->leftJoin('indonesia_provinces as q', 'q.code', 'users.provinsi')
            ->leftJoin('indonesia_cities as r', 'r.code', 'users.kabupaten')
            ->leftJoin('indonesia_districts as s', 's.code', 'users.kecamatan')
            ->leftJoin('indonesia_villages as t', 't.code', 'users.desa')
            ->where('users.kontrak_kerja', $this->holding)
            ->where('users.is_admin', 'user')
            ->select(
                'users.id',
                'users.nomor_identitas_karyawan',
                'users.name',
                'nik',
                'npwp',
                'fullname',
                'motto',
                'email',
                'telepon',
                'username',
                'tempat_lahir',
                'tgl_lahir',
                'gender',
                'tgl_join',
                'status_nikah',
                'q.name as nama_provinsi',
                'r.name as nama_kabupaten',
                's.name as nama_kecamatan',
                't.name as nama_desa',
                'rt',
                'rw',
                'alamat',
                'kuota_cuti_tahunan',
                'kategori',
                'lama_kontrak_kerja',
                'tgl_mulai_kontrak',
                'tgl_selesai_kontrak',
                'kontrak_kerja',
                'penempatan_kerja',
                'site_job',
                'nama_bank',
                'nomor_rekening',
                'users.kategori_jabatan',
                'a.nama_departemen',
                'b.nama_divisi',
                'c.nama_bagian',
                'd.nama_jabatan',
                'u.nama_departemen as nama_departemen1',
                'e.nama_divisi as nama_divisi1',
                'f.nama_bagian as nama_bagian1',
                'g.nama_jabatan as nama_jabatan1',
                'v.nama_departemen as nama_departemen2',
                'h.nama_divisi as nama_divisi2',
                'i.nama_bagian as nama_bagian2',
                'j.nama_jabatan as nama_jabatan2',
                'w.nama_departemen as nama_departemen3',
                'k.nama_divisi as nama_divisi3',
                'l.nama_bagian as nama_bagian3',
                'm.nama_jabatan as nama_jabatan3',
                'x.nama_departemen as nama_departemen4',
                'n.nama_divisi as nama_divisi4',
                'o.nama_bagian as nama_bagian4',
                'p.nama_jabatan as nama_jabatan4',
                'users.bpjs_ketenagakerjaan',
                'users.no_bpjs_ketenagakerjaan',
                'users.bpjs_pensiun',
                'users.bpjs_kesehatan',
                'users.no_bpjs_kesehatan',
                'users.kelas_bpjs',
                'users.ptkp'
            )
            ->orderBy('name', 'ASC')
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
                    ->setCellValue('I2', 'DATA MASTER KARYAWAN ' . $holding);
            },
        ];
    }
}
