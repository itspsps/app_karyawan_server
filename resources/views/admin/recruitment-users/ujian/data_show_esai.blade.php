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
                                            <th>: {{ $ujian->detailujian->count() }} Soal</th>
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
                                    <h5 class="">Soal</h5>
                                </div>
                                <div class="widget-heading">
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($ujian->detailesai as $uu)
                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1" class="form-label">
                                                <div class="d-flex">
                                                    <h6 class="pe-1">{{ $no++ }}. </h6>
                                                    <h6>{!! $uu->soal !!}</h6>
                                                </div>
                                            </label>
                                            <input type="" class="form-control" placeholder="Jawaban" disabled>
                                        </div>
                                    @endforeach
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
