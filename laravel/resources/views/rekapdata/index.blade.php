@extends('layouts.dashboard')
@section('isi')
<div class="container-fluid">
    <div class="card card-outline card-primary shadow mb-4">
        <div class="card-header py-3">
            <form action="{{ url('/rekap-data/'.$holding) }}">
                <div class="form-row">
                    <div class="col-3">
                        <input type="datetime" class="form-control" name="mulai" placeholder="Tanggal Mulai" id="mulai" value="{{ request('mulai') }}">
                    </div>
                    <div class="col-3">
                        <input type="datetime" class="form-control" name="akhir" placeholder="Tanggal Akhir" id="akhir" value="{{ request('akhir') }}">
                    </div>
                    <div>
                        <button type="submit" id="search" class="form-control btn btn-primary"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="tableprintrekap" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama Karyawan</th>
                            <th>Total Cuti Dadakan</th>
                            <th>Total Cuti Bersama</th>
                            <th>Total Cuti Menikah</th>
                            <th>Total Cuti Diluar Tanggungan</th>
                            <th>Total Cuti Khusus</th>
                            <th>Total Cuti Melahirkan</th>
                            <th>Total Izin Telat</th>
                            <th>Total Izin Pulang Cepat</th>
                            <th>Total Hadir</th>
                            <th>Total Alfa</th>
                            <th>Total Libur</th>
                            <th>Total Telat</th>
                            <th>Total Pulang Cepat</th>
                            <th>Total Lembur</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data_user as $du)
                        <tr>
                            <td>
                                {{ $du->name }}
                            </td>

                            <td>
                                @php
                                echo $du->Cuti->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])->where('nama_cuti', 'Cuti Dadakan')->where('status_cuti', 'Diterima')->count() . " x";
                                @endphp
                            </td>
                            <td>
                                @php
                                echo $du->Cuti->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])->where('nama_cuti', 'Cuti Bersama')->where('status_cuti', 'Diterima')->count() . " x";
                                @endphp
                            </td>
                            <td>
                                @php
                                echo $du->Cuti->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])->where('nama_cuti', 'Cuti Menikah')->where('status_cuti', 'Diterima')->count() . " x";
                                @endphp
                            </td>
                            <td>
                                @php
                                echo $du->Cuti->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])->where('nama_cuti', 'Cuti Diluar Tanggungan')->where('status_cuti', 'Diterima')->count() . " x";
                                @endphp
                            </td>
                            <td>
                                @php
                                echo $du->Cuti->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])->where('nama_cuti', 'Cuti Khusus')->where('status_cuti', 'Diterima')->count() . " x";
                                @endphp
                            </td>
                            <td>
                                @php
                                echo $du->Cuti->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])->where('nama_cuti', 'Cuti Melahirkan')->where('status_cuti', 'Diterima')->count() . " x";
                                @endphp
                            </td>
                            <td>
                                @php
                                $jumlah_izin_telat = $du->MappingShift->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])->where('status_absen', 'Izin Telat')->count()
                                @endphp
                                {{ $jumlah_izin_telat . " x" }}
                            </td>
                            <td>
                                @php
                                $jumlah_izin_pulang_cepat = $du->MappingShift->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])->where('status_absen', 'Izin Pulang Cepat')->count()
                                @endphp
                                {{ $jumlah_izin_pulang_cepat . " x" }}
                            </td>
                            <td>
                                @php
                                $jumlah_hadir = $du->MappingShift->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])->where('status_absen', '=', 'Masuk')->count();
                                @endphp
                                {{ $jumlah_hadir + $jumlah_izin_telat + $jumlah_izin_pulang_cepat. " x" }}
                            </td>
                            <td>
                                @php
                                echo $du->MappingShift->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])->where('status_absen', 'Tidak Masuk')->count() . " x";
                                @endphp
                            </td>
                            <td>
                                @php
                                echo $du->MappingShift->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])->where('status_absen', 'Libur')->count() . " x";
                                @endphp
                            </td>
                            <td>
                                @php
                                $total_telat = $du->MappingShift->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])->sum('telat');
                                $jam = floor($total_telat / (60 * 60));
                                $menit = $total_telat - ( $jam * (60 * 60) );
                                $menit2 = floor($menit / 60);
                                $detik = $total_telat % 60;
                                @endphp

                                @if($jam <= 0 && $menit2 <=0) <span class="badge badge-success">Tidak Pernah Telat</span>
                                    @else
                                    <span class="badge badge-danger">{{ $jam." Jam ".$menit2." Menit" }}</span>
                                    @endif
                            </td>
                            <td>
                                @php
                                $total_pulang_cepat = $du->MappingShift->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])->sum('pulang_cepat');
                                $jam = floor($total_pulang_cepat / (60 * 60));
                                $menit = $total_pulang_cepat - ( $jam * (60 * 60) );
                                $menit2 = floor($menit / 60);
                                $detik = $total_pulang_cepat % 60;
                                @endphp

                                @if($jam <= 0 && $menit2 <=0) <span class="badge badge-success">Tidak Pernah Pulang Cepat</span>
                                    @else
                                    <span class="badge badge-danger">{{ $jam." Jam ".$menit2." Menit" }}</span>
                                    @endif
                            </td>
                            <td>
                                @php
                                $total_lembur = $du->Lembur->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])->sum('total_lembur');
                                $jam = floor($total_lembur / (60 * 60));
                                $menit = $total_lembur - ( $jam * (60 * 60) );
                                $menit2 = floor($menit / 60);
                                $detik = $total_lembur % 60;
                                @endphp

                                <span class="badge badge-success">{{ $jam." Jam ".$menit2." Menit" }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<br>
@endsection