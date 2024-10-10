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
            'ID KARYAWAN',
            'NAMA LENGKAP',
            'NIK',
            'AGAMA',
            'GOLONGAN DARAH',
            'EMAIL',
            'TELEPON',
            'NOMOR WA',
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
            'NAMA PEMILIK REKENING',
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
            'PTKP',
            'NPWP',
            'BPJS KETENAGAKERJAAN',
            'NAMA PEMILIK BPJS KETENAGAKERJAAN',
            'NO BPJS KETENAGAKERJAAN',
            'BPJS PENSIUN',
            'NAMA PEMILIK BPJS KESEHATAN',
            'BPJS KESEHATAN',
            'NO BPJS KESEHATAN',
            'KELAS BPJS'
        ];
    }
    public function title(): string
    {
        return "DATA KARYAWAN";
    }
    public function startCell(): string
    {
        return 'A2';
    }
    public function collection()
    {
        return Karyawan::leftJoin('departemens as a', 'a.id', 'karyawans.dept_id')
            ->leftJoin('divisis as b', 'b.id', 'karyawans.divisi_id')
            ->leftJoin('bagians as c', 'c.id', 'karyawans.bagian_id')
            ->leftJoin('jabatans as d', 'd.id', 'karyawans.jabatan_id')
            ->leftJoin('divisis as e', 'e.id', 'karyawans.divisi1_id')
            ->leftJoin('bagians as f', 'f.id', 'karyawans.bagian1_id')
            ->leftJoin('jabatans as g', 'g.id', 'karyawans.jabatan1_id')
            ->leftJoin('divisis as h', 'h.id', 'karyawans.divisi2_id')
            ->leftJoin('bagians as i', 'i.id', 'karyawans.bagian2_id')
            ->leftJoin('jabatans as j', 'j.id', 'karyawans.jabatan2_id')
            ->leftJoin('divisis as k', 'k.id', 'karyawans.divisi3_id')
            ->leftJoin('bagians as l', 'l.id', 'karyawans.bagian3_id')
            ->leftJoin('jabatans as m', 'm.id', 'karyawans.jabatan3_id')
            ->leftJoin('divisis as n', 'n.id', 'karyawans.divisi4_id')
            ->leftJoin('bagians as o', 'o.id', 'karyawans.bagian4_id')
            ->leftJoin('jabatans as p', 'p.id', 'karyawans.jabatan4_id')
            ->leftJoin('departemens as u', 'u.id', 'karyawans.dept1_id')
            ->leftJoin('departemens as v', 'v.id', 'karyawans.dept2_id')
            ->leftJoin('departemens as w', 'w.id', 'karyawans.dept3_id')
            ->leftJoin('departemens as x', 'x.id', 'karyawans.dept4_id')
            ->leftJoin('indonesia_provinces as q', 'q.code', 'karyawans.provinsi')
            ->leftJoin('indonesia_cities as r', 'r.code', 'karyawans.kabupaten')
            ->leftJoin('indonesia_districts as s', 's.code', 'karyawans.kecamatan')
            ->leftJoin('indonesia_villages as t', 't.code', 'karyawans.desa')
            ->leftJoin('users as y', 'y.karyawan_id', 'karyawans.id')
            ->where('karyawans.kontrak_kerja', $this->holding)
            ->where('y.is_admin', 'user')
            // ->where('karyawans.status_aktif', 'AKTIF')
            ->select(
                'karyawans.nomor_identitas_karyawan',
                'karyawans.name',
                'nik',
                'agama',
                'golongan_darah',
                'email',
                'telepon',
                'nomor_wa',
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
                'nama_pemilik_rekening',
                'nomor_rekening',
                'karyawans.kategori_jabatan',
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
                'karyawans.ptkp',
                'npwp',
                'karyawans.bpjs_ketenagakerjaan',
                'karyawans.nama_pemilik_bpjs_ketenagakerjaan',
                'karyawans.no_bpjs_ketenagakerjaan',
                'karyawans.bpjs_pensiun',
                'karyawans.bpjs_kesehatan',
                'karyawans.nama_pemilik_bpjs_kesehatan',
                'karyawans.no_bpjs_kesehatan',
                'karyawans.kelas_bpjs'
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
                    ->getStyle('A2:BJ2')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet
                    ->getDelegate()->getStyle('I1')->getFont()->setSize(14);
                $event->sheet
                    ->getDelegate()->getStyle('I1')->getFont()->setBold(true);
                $event->sheet
                    ->getDelegate()->getStyle('A2:BJ2')->getFont()->setBold(true);
                $event->sheet
                    ->setCellValue('I1', 'DATA MASTER KARYAWAN ' . $holding);
            },
        ];
    }
}
