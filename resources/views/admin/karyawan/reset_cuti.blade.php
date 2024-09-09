@extends('admin.layouts.dashboard')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
<style type="text/css">
    .my-swal {
        z-index: X;
    }
</style>
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
                        <h5 class="card-title m-0 me-2">DATA MASTER RESET CUTI</h5>
                    </div>
                </div>
                <div class="card-body text-center">
                    <hr class="my-5">
                    <div class="col-12">
                        <form method="post" action="{{ url('/reset-cuti/'.$data_cuti->id.'/'.$holding) }}">
                            @method('put')
                            @csrf
                            <div class="row g-2">
                                <div class="col mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="number" class="form-control @error('cuti_dadakan') is-invalid @enderror" id="cuti_dadakan" name="cuti_dadakan" value="{{ old('cuti_dadakan', $data_cuti->cuti_dadakan) }}">
                                        <label for="cuti_dadakan">Cuti Dadakan</label>
                                    </div>
                                    @error('cuti_dadakan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="number" class="form-control @error('cuti_bersama') is-invalid @enderror" id="cuti_bersama" name="cuti_bersama" value="{{ old('cuti_bersama', $data_cuti->cuti_bersama) }}">
                                        <label for="cuti_bersama">Cuti Bersama</label>
                                    </div>
                                    @error('cuti_bersama')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="number" class="form-control @error('cuti_menikah') is-invalid @enderror" id="cuti_menikah" name="cuti_menikah" value="{{ old('cuti_menikah', $data_cuti->cuti_menikah) }}">
                                        <label for="cuti_menikah">Cuti Menikah</label>
                                    </div>
                                    @error('cuti_menikah')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="number" class="form-control @error('cuti_diluar_tanggungan') is-invalid @enderror" id="cuti_diluar_tanggungan" name="cuti_diluar_tanggungan" value="{{ old('cuti_diluar_tanggungan', $data_cuti->cuti_diluar_tanggungan) }}">
                                        <label for="cuti_diluar_tanggungan">Cuti Diluar Tanggungan</label>
                                    </div>
                                    @error('cuti_diluar_tanggungan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="number" class="form-control @error('cuti_khusus') is-invalid @enderror" id="cuti_khusus" name="cuti_khusus" value="{{ old('cuti_khusus', $data_cuti->cuti_khusus) }}">
                                        <label for="cuti_khusus">Cuti Khusus</label>
                                    </div>
                                    @error('cuti_khusus')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="number" class="form-control @error('cuti_melahirkan') is-invalid @enderror" id="cuti_melahirkan" name="cuti_melahirkan" value="{{ old('cuti_melahirkan', $data_cuti->cuti_melahirkan) }}">
                                        <label for="cuti_melahirkan">Cuti Melahirkan</label>
                                    </div>
                                    @error('cuti_melahirkan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="number" class="form-control @error('izin_telat') is-invalid @enderror" id="izin_telat" name="izin_telat" value="{{ old('izin_telat', $data_cuti->izin_telat) }}">
                                        <label for="izin_telat">Izin Telat</label>
                                    </div>
                                    @error('izin_telat')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="number" class="form-control @error('izin_pulang_cepat') is-invalid @enderror" id="izin_pulang_cepat" name="izin_pulang_cepat" value="{{ old('izin_pulang_cepat', $data_cuti->izin_pulang_cepat) }}">
                                        <label for="izin_pulang_cepat">Izin Pulang Cepat</label>
                                    </div>
                                    @error('izin_pulang_cepat')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary float-right">SIMPAN</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Transactions -->
        <!--/ Data Tables -->
    </div>
</div>
@endsection
@section('js')
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script>
    $(document).on("click", "#btn_edit_shift", function() {
        let id = $(this).data('id');
        let shift = $(this).data("shift");
        let jammasuk = $(this).data("jammasuk");
        let jamkeluar = $(this).data("jamkeluar");
        let holding = $(this).data("holding");
        // console.log(jamkeluar);
        $('#id_shift').val(id);
        $('#nama_shift_update').val(shift);
        $('#jam_masuk_update').val(jammasuk);
        $('#jam_keluar_update').val(jamkeluar);
        $('#modal_edit_shift').modal('show');

    });
    $(document).on('click', '#btn_delete_shift', function() {
        var id = $(this).data('id');
        let holding = $(this).data("holding");
        console.log(id);
        Swal.fire({
            title: 'Apakah kamu yakin?',
            text: "Kamu tidak dapat mengembalikan data ini",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "{{ url('/shift/delete/') }}" + '/' + id + '/' + holding,
                    type: "GET",
                    error: function() {
                        alert('Something is wrong');
                    },
                    success: function(data) {
                        Swal.fire({
                            title: 'Terhapus!',
                            text: 'Data anda berhasil di hapus.',
                            icon: 'success',
                            timer: 1500
                        })
                        $('#table_shift').DataTable().ajax.reload();
                    }
                });
            } else {
                Swal.fire({
                    title: 'Cancelled!',
                    text: 'Your data is safe :',
                    icon: 'error',
                    timer: 1500
                })
            }
        });

    });
</script>
@endsection