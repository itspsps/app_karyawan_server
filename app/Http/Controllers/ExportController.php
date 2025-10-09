<?php

namespace App\Http\Controllers;

use App\Exports\RekapAbsensiKedisiplinanExport;
use App\Models\Holding;
use App\Models\Karyawan;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function RekapAbsensiKedisiplinan(Request $request, $holding)
    {
        date_default_timezone_set('Asia/Jakarta');
        $holding = Holding::where('holding_code', $holding)->first();
        // dd($request->all());
        $now = Carbon::parse($request->start_date)->startOfDay();
        $now1 = Carbon::parse($request->end_date)->endOfDay();
        $period = CarbonPeriod::create($now, $now1);
        $query = Karyawan::with(['Departemen' => function ($q) {
            $q->select('id', 'nama_departemen');
        }])
            ->with(['Absensi' => function ($q) use ($now, $now1) {
                $q->whereBetween('LogTime', [$now, $now1])
                    ->select('EnrollNumber', 'LogTime'); // supaya jelas field yg dibawa
            }])
            ->with(['MappingShift' => function ($q) use ($now, $now1) {
                $q->with('Shift');
                $q->whereBetween('tanggal_masuk', [$now, $now1]);
            }])->where('kontrak_kerja', $holding->id)
            // ->where('nomor_identitas_karyawan', '=', '2002305050895')
            ->where('kategori', 'Karyawan Bulanan')
            ->where('status_aktif', 'AKTIF');

        if (!empty($request->departemen_filter)) {
            $query->whereIn('dept_id', (array)$request->departemen_filter ?? []);
        }

        if (!empty($request->divisi_filter)) {
            $query->whereIn('divisi_id', (array)$request->divisi_filter ?? []);
        }

        if (!empty($request->bagian_filter)) {
            $query->whereIn('bagian_id', (array)$request->bagian_filter ?? []);
        }

        if (!empty($request->jabatan_filter)) {
            $query->whereIn('jabatan_id', (array)$request->jabatan_filter ?? []);
        }
        if (!empty($request->shift_filter)) {
            $query->where('shift', $request->shift_filter);
        }
        $data = $query->select('karyawans.dept_id', 'karyawans.name', 'karyawans.id', 'karyawans.nomor_identitas_karyawan', 'karyawans.shift')
            ->orderBy('karyawans.name', 'ASC')
            ->limit(10)
            ->get();

        $rows = $data->map(function ($row, $index) {
            return [
                'No'            => $index + 1,
                'ID Karyawan'   => $row->nomor_identitas_karyawan,
                'Nama Karyawan' => $row->name,
                'Departemen'    => $row->Departemen->nama_departemen ?? '-',
                'Shift'         => $row->Shift->nama_shift ?? '-',
                'Jam Masuk'     => optional($row->Absensi->first())->jam_masuk ?? '-',
                'Terlambat'     => '-', // bisa hitung dari log
                'Ceklog'        => $row->Absensi->count(),
                'Lembur'        => '0',
                'Jam Pulang'    => optional($row->Absensi->last())->jam_pulang ?? '-',
                'Total Hadir'   => $row->Absensi->count(),
                'Keterangan'    => '-', // misal nanti bisa isi sakit/izin
                'Libur'         => 0,
                'Tidak Hadir'   => 0,
                'Net Hadir'     => $row->Absensi->count(),
                'Total Hari'    => 30,
                'Tanggal'       => now()->format('d-m-Y'),
            ];
        });
        // dd($data);
        return Excel::download(new RekapAbsensiKedisiplinanExport($rows), 'REKAP_DATA_ABSENSI.xlsx');
    }
}
