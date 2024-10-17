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
            'STATUS PERNIKAHAAN',
            'TINGKAT PENDIDIKAN',
            'INSTANSI PENDIDIKAN',
            'JURUSAN AKADEMIK',
            'PROVINSI',
            'KABUPATEN/KOTA',
            'KECAMATAN',
            'DESA',
            'RT',
            'RW',
            'KETERANGAN ALAMAT',
            'PROVINSI DOMISILI',
            'KABUPATEN/KOTA DOMISILI',
            'KECAMATAN DOMISILI',
            'DESA DOMISILI',
            'RT DOMISILI',
            'RW DOMISILI',
            'KETERANGAN ALAMAT DOMISILI',
            'SALDO CUTI',
            'KATEGORI KARYAWAN',
            'TANGGAL BERGABUNG',
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
            'NAMA PEMILIK NPWP',
            'NPWP',
            'NAMA PEMILIK BPJS KETENAGAKERJAAN',
            'NO BPJS KETENAGAKERJAAN',
            'BPJS PENSIUN',
            'NAMA PEMILIK BPJS KESEHATAN',
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
            ->leftJoin('indonesia_provinces as aa', 'aa.code', 'karyawans.provinsi_domisili')
            ->leftJoin('indonesia_cities as ab', 'ab.code', 'karyawans.kabupaten_domisili')
            ->leftJoin('indonesia_districts as ac', 'ac.code', 'karyawans.kecamatan_domisili')
            ->leftJoin('indonesia_villages as ad', 'ad.code', 'karyawans.desa_domisili')
            ->leftJoin('users as y', 'y.karyawan_id', 'karyawans.id')
            ->where('karyawans.kontrak_kerja', $this->holding)
            ->where('y.is_admin', 'user')
            // ->where('karyawans.status_aktif', 'AKTIF')
            ->select(
                'karyawans.nomor_identitas_karyawan', //1
                'karyawans.name', //2
                'nik', //3
                'agama', //4
                'golongan_darah', //5
                'email', //6
                'telepon', //7
                'nomor_wa', //8
                'tempat_lahir', //9
                'tgl_lahir', //10
                'gender', //11
                'status_nikah', //12
                'strata_pendidikan', //12
                'instansi_pendidikan', //12
                'jurusan_akademik', //12
                'status_nikah', //12
                'q.name as nama_provinsi', //13
                'r.name as nama_kabupaten', //14
                's.name as nama_kecamatan', //15
                't.name as nama_desa', //16
                'rt', //17
                'rw', //18
                'alamat', //19
                'aa.name as nama_provinsi_domisili', //20
                'ab.name as nama_kabupaten_domisili', //21
                'ac.name as nama_kecamatan_domisili', //22
                'ad.name as nama_desa_domisili', //23
                'rt_domisili', //24
                'rw_domisili', //25
                'alamat_domisili', //26
                'kuota_cuti_tahunan', //27
                'kategori', //28
                'tgl_join', //29
                'lama_kontrak_kerja', //30
                'tgl_mulai_kontrak', //31
                'tgl_selesai_kontrak', //32
                'kontrak_kerja', //33
                'penempatan_kerja', //34
                'site_job', //35
                'nama_bank', //36
                'nama_pemilik_rekening', //37
                'nomor_rekening', //38
                'karyawans.kategori_jabatan', //39
                'a.nama_departemen', //40
                'b.nama_divisi', //41
                'c.nama_bagian', //42
                'd.nama_jabatan', //43
                'u.nama_departemen as nama_departemen1', //44
                'e.nama_divisi as nama_divisi1', //45
                'f.nama_bagian as nama_bagian1', //46
                'g.nama_jabatan as nama_jabatan1', //47
                'v.nama_departemen as nama_departemen2', //48
                'h.nama_divisi as nama_divisi2', //49
                'i.nama_bagian as nama_bagian2', //50
                'j.nama_jabatan as nama_jabatan2', //51
                'w.nama_departemen as nama_departemen3', //52
                'k.nama_divisi as nama_divisi3', //53
                'l.nama_bagian as nama_bagian3', //54
                'm.nama_jabatan as nama_jabatan3', //55
                'x.nama_departemen as nama_departemen4', //56
                'n.nama_divisi as nama_divisi4', //57
                'o.nama_bagian as nama_bagian4', //58
                'p.nama_jabatan as nama_jabatan4', //59
                'karyawans.ptkp', //60
                'nama_pemilik_npwp', //61
                'npwp', //62
                'karyawans.nama_pemilik_bpjs_ketenagakerjaan', //63
                'karyawans.no_bpjs_ketenagakerjaan', //64
                'karyawans.bpjs_pensiun', //65
                'karyawans.nama_pemilik_bpjs_kesehatan', //66
                'karyawans.no_bpjs_kesehatan', //67
                'karyawans.kelas_bpjs' //68
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
                    ->getStyle('A2:BS2')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet
                    ->getDelegate()->getStyle('I1')->getFont()->setSize(14);
                $event->sheet
                    ->getDelegate()->getStyle('I1')->getFont()->setBold(true);
                $event->sheet
                    ->getDelegate()->getStyle('A2:BS2')->getFont()->setBold(true);
                $event->sheet
                    ->setCellValue('I1', 'DATA MASTER KARYAWAN ' . $holding);
            },
        ];
    }
}
