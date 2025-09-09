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
                                            <th>: Soal</th>
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
