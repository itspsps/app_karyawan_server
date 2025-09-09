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
                                        {{-- <tr>
                                            <th>Kelas</th>
                                            <th>: {{ $ujian->kelas->nama_kelas }}</th>
                                        </tr>
                                        <tr>
                                            <th>Mapel</th>
                                            <th>: {{ $ujian->mapel->nama_mapel }}</th>
                                        </tr> --}}
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
            <br>
            {{-- soal ujian & jawaban --}}
            <div id="toggleAccordion" class="shadow">
                <div class="card">
                    <div class="card-header bg-white" id="...">
                        <section class="mb-0 mt-0">
                            <div role="menu" class="" data-toggle="collapse" data-target="#defaultAccordionOne"
                                aria-expanded="true" aria-controls="defaultAccordionOne" style="cursor: pointer;">
                                Soal Ujian & Jawaban (Klik untuk lihat & tutup)
                            </div>
                        </section>
                    </div>

                    <div id="defaultAccordionOne" class="collapse show" aria-labelledby="..."
                        data-parent="#toggleAccordion">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-lg-9">
                                    <form id="examwizard-question" action="#" method="POST">
                                        <div class="widget shadow p-2">
                                            <div>
                                                @php
                                                    $no = 1;
                                                    $soal_hidden = '';
                                                @endphp
                                                @foreach ($ujian->detailujian as $soal)
                                                    <div class="question <?= $soal_hidden ?> question-{{ $no }}"
                                                        data-question="{{ $no }}">
                                                        <div class="widget-heading pl-2 pt-2"
                                                            style="border-bottom: 1px solid #e0e6ed;">
                                                            <div class="">
                                                                <h6 class="" style="font-weight: bold">Soal No.
                                                                    <span class="badge badge-primary no-soal"
                                                                        style="color: #000;">{{ $no }}</span>
                                                                </h6>
                                                            </div>
                                                        </div>

                                                        <div class="widget p-3 mt-3">
                                                            <div class="widget-heading"
                                                                style="border-bottom: 1px solid #e0e6ed;">
                                                                <h6 class="question-title color-green"
                                                                    style="word-wrap: break-word">
                                                                    {!! $soal->soal !!}
                                                                </h6>
                                                            </div>
                                                            <div class="widget-content mt-3">
                                                                <div class="alert alert-danger hidden"></div>
                                                                <div class="green-radio color-green">
                                                                    <ol type="A"
                                                                        style="color: #000; margin-left: -20px;">
                                                                        <li class="answer-number">
                                                                            <label
                                                                                for="answer-{{ $soal->id }}-{{ substr($soal->pg_1, 0, 1) }}"
                                                                                class="answer-text" style="color: #000;">
                                                                                <span></span>{{ substr($soal->pg_1, 3, strlen($soal->pg_1)) }}
                                                                            </label>
                                                                        </li>
                                                                        <li class="answer-number">
                                                                            <label
                                                                                for="answer-{{ $soal->id }}-{{ substr($soal->pg_2, 0, 1) }}"
                                                                                class="answer-text" style="color: #000;">
                                                                                <span></span>{{ substr($soal->pg_2, 3, strlen($soal->pg_2)) }}
                                                                            </label>
                                                                        </li>
                                                                        <li class="answer-number">
                                                                            <label
                                                                                for="answer-{{ $soal->id }}-{{ substr($soal->pg_3, 0, 1) }}"
                                                                                class="answer-text" style="color: #000;">
                                                                                <span></span>{{ substr($soal->pg_3, 3, strlen($soal->pg_3)) }}
                                                                            </label>
                                                                        </li>
                                                                        <li class="answer-number">
                                                                            <label
                                                                                for="answer-{{ $soal->id }}-{{ substr($soal->pg_4, 0, 1) }}"
                                                                                class="answer-text" style="color: #000;">
                                                                                <span></span>{{ substr($soal->pg_4, 3, strlen($soal->pg_4)) }}
                                                                            </label>
                                                                        </li>
                                                                        <li class="answer-number">
                                                                            <label
                                                                                for="answer-{{ $soal->id }}-{{ substr($soal->pg_5, 0, 1) }}"
                                                                                class="answer-text" style="color: #000;">
                                                                                <span></span>{{ substr($soal->pg_5, 3, strlen($soal->pg_5)) }}
                                                                            </label>
                                                                        </li>
                                                                    </ol>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    @php
                                                        $soal_hidden = 'hidden';
                                                        $no++;
                                                    @endphp
                                                @endforeach
                                            </div>
                                            <!-- SOAL -->

                                            <input type="hidden" value="1" id="currentQuestionNumber"
                                                name="currentQuestionNumber" />
                                            <input type="hidden" value="{{ $ujian->detailujian->count() }}"
                                                id="totalOfQuestion" name="totalOfQuestion" />
                                            <input type="hidden" value="[]" id="markedQuestion"
                                                name="markedQuestions" />
                                            <!-- END SOAL -->
                                        </div>
                                    </form>

                                </div>

                                <div class="col-lg-3" id="quick-access-section" class="table-responsive">
                                    <div class="widget shadow p-3">
                                        <div class="widget-content">
                                            <table class="table text-center table-hover">
                                                <thead class="question-response-header">
                                                    <tr>
                                                        <th class="text-center">No. Soal</th>
                                                        <th class="text-center">Jawaban</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $no = 1;
                                                    @endphp
                                                    @foreach ($ujian->detailujian as $soal)
                                                        <tr class="question-response-rows"
                                                            data-question="{{ $no }}" style="cursor: pointer;">
                                                            <td style="font-weight: bold;">{{ $no }}</td>
                                                            <td class="question-response-rows-value">{{ $soal->jawaban }}
                                                            </td>
                                                        </tr>
                                                        @php
                                                            $no++;
                                                        @endphp
                                                    @endforeach

                                                </tbody>
                                            </table>
                                            <div class="text-nowrap text-center">
                                                <a href="javascript:void(0)" class="btn btn-success"
                                                    id="quick-access-prev">
                                                    &laquo;
                                                </a>
                                                <span class="alert alert-info" id="quick-access-info"></span>
                                                <a href="javascript:void(0)" class="btn btn-success"
                                                    id="quick-access-next">&raquo;</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- Exmas Footer - Multi Step Pages Footer -->
                            <div class="row mt-3">
                                <div class="col-lg-12 exams-footer p-3">
                                    <div class="row">
                                        <div class="col-sm-1 back-to-prev-question-wrapper text-center">
                                            <a href="javascript:void(0);" id="back-to-prev-question"
                                                class="btn btn-success disabled">
                                                Back
                                            </a>
                                        </div>
                                        <div class="col-sm-2 footer-question-number-wrapper text-center">
                                            <div>
                                                <span id="current-question-number-label">1</span>
                                                <span>Dari <b>{{ $ujian->detailujian->count() }}</b></span>
                                            </div>
                                            <div>
                                                Nomor Soal
                                            </div>
                                        </div>
                                        <div class="col-sm-1 go-to-next-question-wrapper text-center">
                                            <a href="javascript:void(0);" id="go-to-next-question"
                                                class="btn btn-success">
                                                Next
                                            </a>
                                        </div>
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
