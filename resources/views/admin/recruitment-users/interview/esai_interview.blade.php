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
                                            <th>: {{ $ujian->detailEsai->count() }} Soal</th>
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
                                    <h4 class="">Soal</h4>
                                </div>
                                <div class="widget-heading p-3">
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($esaiDetailjawab as $uu)
                                        <div class="">
                                            <label>
                                                <div class="d-flex">
                                                    <h5 class="pe-1">{{ $no++ }}. </h5>
                                                    <h5>{!! $uu->soal !!}</h5>
                                                </div>
                                            </label>
                                        </div>
                                        <div class="mb-3">
                                            @if ($uu->jawaban != null)
                                                <textarea class="form-control" name="" id="" readonly>{{ $uu->jawaban }}</textarea>
                                            @else
                                                <textarea class="form-control" name="" id="" readonly>-</textarea>
                                            @endif
                                        </div>
                                    @endforeach
                                    <label>
                                        Total Nilai (1-100)
                                    </label>
                                    <div class="row">
                                        <div class="col-lg">
                                            <form action={{ route('penilaian_esai') }} method="POST">
                                                @csrf
                                                <input class="form-control" type="number" name="nilai" id=""
                                                    value="{{ $ujianEsaiJawab == null ? '0' : $ujianEsaiJawab->nilai }}">
                                                <input class="form-control" type="hidden" name="recruitment_user_id"
                                                    id="" value="{{ $recruitment_user_id }}">
                                                <input class="form-control" type="hidden" name="holding" id=""
                                                    value="{{ $holding->holding_code }}">
                                                <input class="form-control" type="hidden" name="kode" id=""
                                                    value="{{ $ujian->kode }}">
                                                <div class="py-2">
                                                    <button type="submit" class="btn btn-info">Masukkan Nilai</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-lg"></div>
                                        <div class="col-lg"></div>
                                    </div>
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
