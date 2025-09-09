@extends('admin.layouts.dashboard')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
    <style type="text/css">
        .my-swal {
            z-index: X;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/ew/css/style.css') }}">
@endsection
@section('isi')
    @include('sweetalert::alert')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row gy-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="row layout-top-spacing">
                        <div class="col-lg-12 layout-spacing">
                            <div class="widget shadow p-3">
                                <div class="widget-heading">
                                    <h5 class="">{{ $ujian->nama }}</h5>
                                    <table class="mt-2">
                                        <tr>
                                            <th>Jumlah Soal</th>
                                            <th>: {{ $ujian->waktuujian->count() }} Soal</th>
                                        </tr>
                                        <tr>
                                            <th>Waktu Ujian</th>
                                            <th>: {{ $ujian->jam }} Jam {{ $ujian->menit }} Menit</th>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="row layout-top-spacing">
                        <div class="col-lg-12 layout-spacing">
                            <div class="widget shadow p-3">
                                <div class="widget-heading">
                                    <h5 class="">{{ $ujian->nama }}</h5>
                                    <table class="mt-2">
                                        <tr>
                                            <th>Benar</th>
                                            <th>:</th>
                                            <th>{{ $benar }} Soal</th>
                                        </tr>
                                        <tr>
                                            <th>Salah </th>
                                            <th>:</th>
                                            <th>{{ $salah }} Soal</th>
                                        </tr>
                                        <tr>
                                            <th>Total Nilai</th>
                                            <th>:</th>
                                            <th>{{ $total_nilai }}</th>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="row layout-top-spacing">
                        <div class="col-lg-12 layout-spacing">
                            <div class="widget shadow p-3">
                                <div class="widget-heading">
                                    <h4 class="">Soal</h4>
                                </div>
                                <div class="widget-heading p-3">
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($PgSiswa as $uu)
                                        <div class="">
                                            <label>
                                                <h6>{!! $uu->soal !!}</h6>
                                                <div class="px-2">
                                                    <p>A. {{ $uu->pg_1 }}</p>
                                                    <p>B. {{ $uu->pg_2 }}</p>
                                                    <p>C. {{ $uu->pg_3 }}</p>
                                                    <p>D. {{ $uu->pg_4 }}</p>
                                                    <p>E. {{ $uu->pg_5 }}</p>
                                                </div>
                                                <p>Jawaban Yang Benar : {{ $uu->kunci }}</p>
                                                <p>Jawaban Pelamar : {{ $uu->jawaban }}
                                                    @if ($uu->benar == 1)
                                                        (Benar)
                                                    @else
                                                        (Salah)
                                                    @endif
                                                </p>
                                            </label>
                                        </div>
                                        {{-- <div class="mb-3">
                                            @if ($uu->pgSiswa != null)
                                                <textarea class="form-control" name="" id="" readonly>{{ $uu->pgSiswa->jawaban }}</textarea>
                                            @else
                                                <textarea class="form-control" name="" id="" readonly>-</textarea>
                                            @endif
                                        </div> --}}
                                    @endforeach
                                    {{-- <div class="row">
                                        <div class="col-lg">
                                            <form action={{ route('penilaian_esai') }} method="POST">
                                                @csrf
                                                <input class="form-control" type="number" name="nilai" id=""
                                                    value="{{ $ujian->esaiJawab == null ? '' : $ujian->esaiJawab->nilai }}">
                                                <input class="form-control" type="hidden" name="recruitment_user_id"
                                                    id="" value="{{ $recruitment_user_id }}">
                                                <input class="form-control" type="hidden" name="holding" id=""
                                                    value="{{ $holding }}">
                                                <input class="form-control" type="hidden" name="kode" id=""
                                                    value="{{ $ujian->kode }}">
                                                <div class="py-2">
                                                    <button type="submit" class="btn btn-info">Masukkan Nilai</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-lg"></div>
                                        <div class="col-lg"></div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! session('pesan') !!}
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="{{ asset('/assets/ew/js/examwizard.js') }}"></script>

    <script>
        var examWizard = $.fn.examWizard({
            finishOption: {
                enableModal: !0
            },
            quickAccessOption: {
                quickAccessPagerItem: 5
            }
        });
        $(".question-response-rows").click(function() {
            var e = $(this).data("question"),
                s = ".question-" + e;
            $(".question").addClass("hidden"), $(s).removeClass("hidden"), $("input[name=currentQuestionNumber]")
                .val(e), $("#current-question-number-label").text(e), $("#back-to-prev-question").removeClass(
                    "disabled"), $("#go-to-next-question").removeClass("disabled")
        });
    </script>
@endsection
