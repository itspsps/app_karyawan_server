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
    {{-- <a href="javascript:void(0);" class="btn btn-primary tambah-pg"
        style="position: fixed; right: -10px; top: 50%; z-index: 9999;">Tambah Soal</a> --}}

    <form method="post" action="{{ url('/ujian/esai-pg-update') }}" enctype="multipart/form-data">
        @csrf
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row gy-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="card-title m-0 me-2">DATA UJIAN ESAI</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mt-3">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="">Nama Ujian / Quiz</label>
                                        <input type="text" name="nama_ujian" value="{{ $ujian->nama }}"
                                            class="form-control" required>
                                        <input type="hidden" name="esai" value="1" class="form-control" required>
                                        <input type="hidden" name="id_soal" value="{{ $ujian->id }}">
                                        <input type="hidden" name="kode" value="{{ $ujian->kode }}">
                                        <input type="hidden" name="holding" value="{{ $holding->holding_code }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="">Kategori</label>
                                        <select class="form-select" name="kategori_id" id="kategori_id" required>
                                            <option
                                                value="{{ $ujian->kategori_id }}"{{ $ujian->kategori_id == old('kategori_id', $ujian) ? 'selected' : '' }}>
                                                {{ $ujian->ujianKategori->nama_kategori }}
                                            </option>
                                            @foreach ($kategori as $k)
                                                <option value="{{ $k->id }}">{{ $k->nama_kategori }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="">Waktu Jam</label>
                                        <select class="form-select" name="jam" id="jam" required>
                                            <option value="{{ $ujian->jam }}"{{ old('jam', $ujian) ? 'selected' : '' }}>
                                                {{ $ujian->jam }}
                                            </option>
                                            @for ($h = 0; $h < 25; $h++)
                                                <option value="{{ $h }}">{{ $h }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="">Waktu Menit</label>
                                        <select class="form-select" name="menit" id="menit" required>
                                            <option
                                                value="{{ $ujian->menit }}"{{ old('menit', $ujian) ? 'selected' : '' }}>
                                                {{ $ujian->menit }}
                                            </option>
                                            @for ($i = 0; $i < 60; $i++)
                                                <option value="{{ $i }}">{{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="form-group">
                                    <label for="">Kategori</label>
                                    <select class="form-select" name="kategori_id" id="kategori_id" required>
                                        <option value="">Pilih</option>
                                        @foreach ($kategori as $k)
                                            <option value="{{ $k->id }}">{{ $k->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="">Bobot Nilai</label>
                                        <input type="text" name="pembobotan_pilihan_ganda"
                                            value="{{ $pembobotan->esai }}%" class="form-control" disabled>
                                        <input type="hidden" name="pembobotan_id" value="{{ $pembobotan->pembobotan_id }}"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-lg-12">

                                    <div class="custom-control custom-checkbox py-2">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" name="nol"
                                            value="1" {{ old('nol', $ujian->nol) == '1' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="customCheck1">Direktur</label>
                                    </div>
                                    <div class="custom-control custom-checkbox py-2">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" name="satu"
                                            value="1" {{ old('satu', $ujian->satu) == '1' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="customCheck1">Head</label>
                                    </div>
                                    <div class="custom-control custom-checkbox py-2">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1"
                                            name="dua" value="1"
                                            {{ old('dua', $ujian->dua) == '1' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="customCheck1">Manager / Regional Sales
                                            Manager</label>
                                    </div>
                                    <div class="custom-control custom-checkbox py-2">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1"
                                            name="tiga" value="1"
                                            {{ old('tiga', $ujian->tiga) == '1' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="customCheck1">Junior Sales Manager / Area
                                            Sales Manager</label>
                                    </div>
                                    <div class="custom-control custom-checkbox py-2">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1"
                                            name="empat" value="1"
                                            {{ old('empat', $ujian->empat) == '1' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="customCheck1">Supervisor</label>
                                    </div>
                                    <div class="custom-control custom-checkbox py-2">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1"
                                            name="lima" value="1"
                                            {{ old('lima', $ujian->lima) == '1' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="customCheck1">Koordinator</label>
                                    </div>
                                    <div class="custom-control custom-checkbox py-2">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1"
                                            name="enam" value="1"
                                            {{ old('enam', $ujian->enam) == '1' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="customCheck1">Admin, Operator, Drafter,
                                            Staff, Sales, Sopir</label>
                                    </div>
                                    {{-- <div class="custom-control custom-checkbox py-2">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1"
                                            name="acak" value="1">
                                        <label class="custom-control-label" for="customCheck1">Acak Soal Siswa</label>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row gy-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="card-title m-0 me-2">Soal Ujian</h5>
                            </div>
                        </div>
                        @foreach ($detail_esai as $esai)
                            <div class="card-body">
                                <div class="isi_soal">
                                    <div class="form-group">
                                        <label for="">Soal</label>
                                        <textarea name="soal_update[{{ $esai->id }}]" cols="30" rows="2" class="summernote" wrap="hard"
                                            required>{{ $esai->soal }}</textarea>
                                        <input type="hidden" name="id_detail_soal" value="{{ $esai->id }}">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="card-body">

                            <div id="soal_pg">
                            </div>
                        </div>
                        <div class="p-3">
                            <button class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </form>


    {!! session('pesan') !!}
@endsection
@section('js')
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#desc_recruitment").summernote();
            // $("#show_desc_recruitment").summernote();
            $("#desc_recruitment_update").summernote();
            $('.dropdown-toggle').dropdown();
        });
    </script>

    <script>
        $(document).ready(function() {
            function uploadImage(e, o) {
                var a = new FormData;
                a.append("image", e), $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    url: "{{ url('summernote_upload') }}",
                    cache: !1,
                    contentType: !1,
                    processData: !1,
                    data: a,
                    type: "post",
                    success: function(e) {
                        $(o).summernote("insertImage", e)
                    },
                    error: function(e) {
                        console.log(e)
                    }
                })
            }

            function deleteImage(e) {
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    data: {
                        src: e
                    },
                    type: "post",
                    url: "{{ route('summernote_delete') }}",
                    cache: !1,
                    success: function(e) {
                        console.log(e)
                    }
                })
            }
            setInterval(() => {
                $(".summernote").summernote({
                    placeholder: "Hello stand alone ui",
                    tabsize: 2,
                    height: 120,
                    toolbar: [
                        ["style", ["style"]],
                        ["font", ["bold", "underline", "clear"]],
                        ["color", ["color"]],
                        ["para", ["ul", "ol", "paragraph"]],
                        ["table", ["table"]],
                        ["insert", ["link", "picture", "video"]],
                        ["view", ["fullscreen", "help"]]
                    ],
                    callbacks: {
                        onImageUpload: function(e, o = this) {
                            uploadImage(e[0], o)
                        },
                        onMediaDelete: function(e) {
                            deleteImage(e[0].src)
                        }
                    }
                })
            }, 1e3);
            var no_soal = 2;
            $('.tambah-pg').click(function() {
                const pg = `
                <div class="isi_soal">
                <hr>
                    <div class="form-group">
                        <label for="">Soal No . ` + no_soal + `</label>
                        <textarea name="soal[]" cols="30" rows="2" class="summernote" wrap="hard" required></textarea>
                        <input type="hidden" name="id_detail_soal[]">
                    </div>

                    <br>
                    <div class="row mt-2">

                    </div>
                    <a href="javascript:void(0);" class="btn btn-danger hapus-pg">Hapus</a>
                </div>
            `;

                $('#soal_pg').append(pg);
            });
            $("#soal_pg").on("click", ".isi_soal a", function() {
                $(this).parents(".isi_soal").remove(), --no_soal
            });
        })
    </script>
@endsection
