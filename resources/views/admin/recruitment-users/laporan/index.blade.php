@extends('admin.layouts.dashboard')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
<style type="text/css">
    .my-swal {
        z-index: X;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endsection
@section('isi')
@include('sweetalert::alert')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row gy-4">
        <!-- Transactions -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">REPORT RECRUITMENT</h5>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table" id="table_recruitment" style="width: 100%;">
                        <thead class="table-primary">
                            <tr>
                                <!-- <th>No.</th> -->
                                <th>Waktu Melamar</th>
                                <th>Nama Lengkap</th>
                                <th>Alamat</th>
                                <th>Tanggal Lahir</th>
                                <th>Usia Saat Ini</th>
                                <th>Gender</th>
                                <th>Nomor Whatsapp</th>
                                <th>Lama Nomor Whatsapp</th>
                                <th>Status Pernikahan</th>
                                <th>Pendidikan Terakhir</th>
                                <th>Nama Lembaga Pendidikan</th>
                                <th>Jurusan Yang Diambil</th>
                                <th>Pengalaman Kerja</th>
                                <th>No. Referensi Pengalaman Kerja</th>
                                <th>Alamat Perusahaan</th>
                                <th>Posisi/Jabatan Terakhir</th>
                                <th>Periode Masa Kerja</th>
                                <th>Range gaji yang diterima terakhir</th>
                                <th>Skill Yang Dikuasai</th>
                                <th>CV</th>
                                <th>Foto</th>
                                <th>Posisi yang Dilamar</th>
                                <th>Riwayat Lamaran</th>
                                <th>Hasil Final</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<!-- {{-- start datatable  --}} -->
<script>
    let holding = window.location.pathname.split("/").pop();
    var table = $('#table_recruitment').DataTable({
        "scrollY": true,
        "scrollX": true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('/dt_laporan_recruitment') }}" + '/' + holding,
        },
        columns: [{
                data: 'waktu_melamar',
                name: 'waktu_melamar'
            },
            {
                data: 'nama_lengkap',
                name: 'nama_lengkap'
            },
            {
                data: 'alamat',
                name: 'alamat'
            },
            {
                data: 'tanggal_lahir',
                name: 'tanggal_lahir'
            },
            {
                data: 'usia',
                name: 'usia'
            },
            {
                data: 'gender',
                name: 'gender'
            },
            {
                data: 'nomor_whatsapp',
                name: 'nomor_whatsapp'
            },
            {
                data: 'lama_nomor_wa',
                name: 'lama_nomor_wa'
            },
            {
                data: 'status_pernikahan',
                name: 'status_pernikahan',
            },
            {
                data: 'pendidikan_terakhir',
                name: 'pendidikan_terakhir'
            },
            {
                data: 'lembaga_pendidikan',
                name: 'lembaga_pendidikan'
            },

            {
                data: 'jurusan',
                name: 'jurusan'
            },
            {
                data: 'pengalaman_kerja',
                name: 'pengalaman_kerja'
            },
            {
                data: 'no_referensi',
                name: 'no_referensi'
            },
            {
                data: 'alamat_perusahaan',
                name: 'alamat_perusahaan'
            },
            {
                data: 'jabatan_terakhir',
                name: 'jabatan_terakhir'
            },
            {
                data: 'masa_kerja',
                name: 'masa_kerja'
            },
            {
                data: 'gaji_terakhir',
                name: 'gaji_terakhir'
            },
            {
                data: 'keahlian',
                name: 'keahlian'
            },
            {
                data: 'cv',
                name: 'cv'
            },
            {
                data: 'foto',
                name: 'foto'
            },
            {
                data: 'posisi_yang_dilamar',
                name: 'posisi_yang_dilamar'
            },
            {
                data: 'riwayat_lamaran',
                name: 'riwayat_lamaran'
            },
            {
                data: 'hasil_final',
                name: 'hasil_final'
            },
        ]
    });
</script>
@endsection