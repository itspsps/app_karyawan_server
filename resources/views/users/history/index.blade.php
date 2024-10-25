@extends('users.layouts.main')
@section('title') APPS | KARYAWAN - SP @endsection

@section('css')
<style>
    .nav-fill {
        flex-wrap: nowrap;
        overflow-x: auto;
        overflow-y: hidden;
    }
</style>
@endsection
@section('content')
<div class="fixed-content p-0" style=" border-radius: 10px; margin-top: 0%;box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); ">
    <div class=" container" style="margin-top: -5%;">
        <div class="card" style="box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.2), 0 3px 10px 0 rgba(0, 0, 0, 0.1);">
            <div class="card-body">
                <div class="row">

                    <h5 class="dz-title">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="18px" width="18px" version="1.1" id="Layer_1" viewBox="0 0 512 512" xml:space="preserve">
                            <circle style="fill:#006775;" cx="256" cy="255.977" r="255.977" />
                            <path style="fill:#055661;" d="M506.211,310.479C481.241,425.705,378.75,512,256.052,512c-17.299,0-34.197-1.705-50.543-5.014  L91.086,392.562l74.812-29.884l102.54-26.825l58.766-204.38L506.211,310.479z" />
                            <path style="fill:#FEFEFE;" d="M146.693,305.215c-3.911,1.655-8.724,7.822-12.034,13.137l-42.269,67.44  c-3.309,5.265-1.755,8.774,2.106,7.12l71.352-30.236c1.304,1.805,55.758,46.532,57.613,45.83l94.969-34.548  c2.005,0.501,87.749,30.085,92.963,31.639c6.569,2.005,10.229-0.803,10.128-7.822l-1.003-69.447  c-0.1-7.02,1.003-13.388-5.415-15.695c-9.627-3.46-64.834-26.725-67.19-25.873c-14.441,5.164-79.626,28.631-79.626,28.631  c-10.179-7.572-54.003-36.855-55.858-38.208c-0.702-0.501-3.009-0.301-6.318,0.802c-7.472,2.508-51.797,23.968-59.369,27.227h-0.05  V305.215z" />
                            <g>
                                <path style="fill:#D9DADA;" d="M318.479,374.009c3.259,0.953,87.749,30.085,92.863,31.639l0.301,0.1l0.301,0.1l0.301,0.05   l0.301,0.05l0.301,0.05l0.301,0.05l0,0l0.301,0.05l0,0l0.251,0.05l0.251,0.05l0.251,0.05h0.251l0,0h0.251h0.251l0,0h0.251h0.251   h0.251h0.251l0.251-0.05l0.251-0.05l0,0l0.2-0.05l0.2-0.05l0,0l0.2-0.05l0,0l0.2-0.05l0.2-0.05l0,0l0.2-0.05l0,0l0.2-0.1l0.2-0.1   l0.2-0.1l0,0l0.2-0.1l0.15-0.1l0,0l0.15-0.1l0.15-0.1l0.15-0.15l0.15-0.15l0.15-0.15l0.15-0.15l0.15-0.15l0,0l0.15-0.15l0.15-0.15   l0.15-0.15l0,0l0.1-0.2l0.1-0.2l0.1-0.2l0.1-0.2l0.1-0.2l0.1-0.2l0.1-0.2l0.1-0.2l0.1-0.251l0.05-0.251l0.05-0.251l0.05-0.251   l0.05-0.251l0.05-0.251l0,0l0.05-0.251l0,0l0.05-0.251l0.05-0.301l0.05-0.301l0.05-0.301l0,0v-0.301v-0.301v-0.301v-0.301v-0.301   l-1.003-69.447c-0.1-7.02,1.003-13.388-5.415-15.695c-9.627-3.46-64.834-26.725-67.19-25.873l-29.383,87.247L318.479,374.009z" />
                                <path style="fill:#D9DADA;" d="M165.848,362.677c1.304,1.755,53.451,44.676,57.412,45.83h0.2l44.827-93.113   c-10.179-7.572-54.003-36.855-55.858-38.208l-46.532,85.442L165.848,362.677z" />
                            </g>
                            <path style="fill:#00CC96;" d="M268.388,335.851h3.059c0.953-4.061,5.164-11.382,7.12-15.193l23.015-47.033  c10.529-21.01,20.358-41.769,30.787-62.577c11.984-24.018,14.14-47.534-0.05-72.004c-11.182-19.355-34.548-35.551-57.814-35.551  c-17.349,0-29.333,1.655-43.674,11.132c-9.677,6.418-16.647,13.438-22.915,23.768c-14.692,24.369-12.686,48.087-0.401,72.656  C209.32,214.608,267.284,331.038,268.388,335.851z" />
                            <path style="fill:#07B587;" d="M269.19,335.851h2.256c0.953-4.061,5.164-11.382,7.12-15.193l23.015-47.033  c10.529-21.01,20.358-41.769,30.787-62.577c11.984-24.018,14.14-47.534-0.05-72.004c-11.182-19.355-34.548-35.551-57.814-35.551  c-1.805,0-3.61,0-5.315,0.05C269.19,192.696,269.19,182.617,269.19,335.851z" />
                            <circle style="fill:#E1E5E6;" cx="269.889" cy="171.235" r="43.227" />
                            <path style="fill:#CCCCCC;" d="M269.19,128.063v86.395h0.702c23.868,0,43.223-19.355,43.223-43.223s-19.355-43.223-43.223-43.223  h-0.702V128.063z" />
                            <path style="fill:#E84F4F;" d="M269.892,155.491c8.674,0,15.745,7.02,15.745,15.745c0,8.674-7.02,15.745-15.745,15.745  c-8.674,0-15.745-7.02-15.745-15.745C254.147,162.56,261.167,155.491,269.892,155.491z" />
                            <path style="fill:#C94545;" d="M269.19,155.541v31.439h0.702c8.674,0,15.745-7.02,15.745-15.745c0-8.674-7.02-15.745-15.745-15.745  h-0.702V155.541z" />
                        </svg>
                        History Aktivitas
                    </h5>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card" style="margin-top: 0%;">
                <nav>
                    <div class="nav nav-pills nav-fill" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-absen-tab" data-bs-toggle="tab" data-bs-target="#nav-absen" type="button" role="tab" aria-controls="nav-absen" aria-selected="true">Absensi</button>
                        <button class="nav-link" id="nav-izin-tab" data-bs-toggle="tab" data-bs-target="#nav-izin" type="button" role="tab" aria-controls="nav-izin" aria-selected="true">Izin</button>
                        <button class="nav-link" id="nav-cuti-tab" data-bs-toggle="tab" data-bs-target="#nav-cuti" type="button" role="tab" aria-controls="nav-cuti" aria-selected="false">Cuti</button>
                        <button class="nav-link" id="nav-penugasan-tab" data-bs-toggle="tab" data-bs-target="#nav-penugasan" type="button" role="tab" aria-controls="nav-penugasan" aria-selected="false">Penugasan</button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent" style="margin-top: 2%;">
                    <div class="tab-pane fade show active" id="nav-absen" role="tabpanel" aria-labelledby="nav-absen-tab">
                        <div class="notification-content" style="overflow: auto; height: 100%;">
                            @if($history_absensi->count() == 0)
                            <div class="text-center">
                                <span>
                                    History Tidak Ada
                                </span>
                            </div>
                            @else
                            @foreach($history_absensi as $history_absensi)
                            <a href="">
                                <div class="notification" style="box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.2), 0 3px 10px 0 rgba(0, 0, 0, 0.1);">
                                    <h6>{{$history_absensi->activity}}</h6>
                                    <p>{{ \Illuminate\Support\Str::limit(strip_tags($history_absensi->description), 70) }}
                                        @if (strlen(strip_tags($history_absensi->description)) > 70)
                                        Baca Selengkapnya..
                                        @endif
                                    </p>
                                    <div class="notification-footer">
                                        <span class="badge bg-labels-info">
                                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M6 11C8.76142 11 11 8.76142 11 6C11 3.23858 8.76142 1 6 1C3.23858 1 1 3.23858 1 6C1 8.76142 3.23858 11 6 11Z" stroke="#787878" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M6 3V6L8 7" stroke="#787878" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                            {{\Carbon\Carbon::parse($history_absensi->created_at)->format('d-m-Y')}}
                                        </span>
                                        <p class="mb-0">@if($history_absensi->read_status=='0') @else Read @endif</p>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="tab-pane fade show" id="nav-izin" role="tabpanel" aria-labelledby="nav-izin-tab">
                        <div class="notification-content" style="overflow: auto; height: 100%;">
                            @if($history_izin->count() == 0)
                            <div class="text-center">
                                <span>
                                    History Tidak Ada
                                </span>
                            </div>
                            @else
                            @foreach($history_izin as $history_izin)
                            <a href="">
                                <div class="notification" style="box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.2), 0 3px 10px 0 rgba(0, 0, 0, 0.1);">
                                    <h6>{{$history_izin->activity}}</h6>
                                    <p>{{ \Illuminate\Support\Str::limit(strip_tags($history_izin->description), 70) }}
                                        @if (strlen(strip_tags($history_izin->description)) > 70)
                                        Baca Selengkapnya..
                                        @endif
                                    </p>
                                    <div class="notification-footer">
                                        <span class="badge bg-labels-info">
                                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M6 11C8.76142 11 11 8.76142 11 6C11 3.23858 8.76142 1 6 1C3.23858 1 1 3.23858 1 6C1 8.76142 3.23858 11 6 11Z" stroke="#787878" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M6 3V6L8 7" stroke="#787878" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                            {{\Carbon\Carbon::parse($history_izin->created_at)->format('d-m-Y')}}
                                        </span>
                                        <p class="mb-0">@if($history_izin->read_status=='0') @else Read @endif</p>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="tab-pane fade show" id="nav-cuti" role="tabpanel" aria-labelledby="nav-cuti-tab">
                        <div class="notification-content" style="overflow: auto; height: 100%;">
                            @if($history_cuti->count() == 0)
                            <div class="text-center">
                                <span>
                                    History Tidak Ada
                                </span>
                            </div>
                            @else
                            @foreach($history_cuti as $history_cuti)
                            <a href="">
                                <div class="notification" style="box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.2), 0 3px 10px 0 rgba(0, 0, 0, 0.1);">
                                    <h6>{{$history_cuti->activity}}</h6>
                                    <p>{{ \Illuminate\Support\Str::limit(strip_tags($history_cuti->description), 70) }}
                                        @if (strlen(strip_tags($history_cuti->description)) > 70)
                                        Baca Selengkapnya..
                                        @endif
                                    </p>
                                    <div class="notification-footer">
                                        <span class="badge bg-labels-info">
                                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M6 11C8.76142 11 11 8.76142 11 6C11 3.23858 8.76142 1 6 1C3.23858 1 1 3.23858 1 6C1 8.76142 3.23858 11 6 11Z" stroke="#787878" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M6 3V6L8 7" stroke="#787878" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                            {{\Carbon\Carbon::parse($history_cuti->created_at)->format('d-m-Y')}}
                                        </span>
                                        <p class="mb-0">@if($history_cuti->read_status=='0') @else Read @endif</p>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="tab-pane fade show" id="nav-penugasan" role="tabpanel" aria-labelledby="nav-cuti-tab">
                        <div class="notification-content" style="overflow: auto; height: 100%;">
                            @if($history_penugasan->count() == 0)
                            <div class="text-center">
                                <span>
                                    History Tidak Ada
                                </span>
                            </div>
                            @else
                            @foreach($history_penugasan as $history_penugasan)
                            <a href="">
                                <div class="notification" style="box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.2), 0 3px 10px 0 rgba(0, 0, 0, 0.1);">
                                    <h6>{{$history_penugasan->activity}}</h6>
                                    <p>{{ \Illuminate\Support\Str::limit(strip_tags($history_penugasan->description), 70) }}
                                        @if (strlen(strip_tags($history_penugasan->description)) > 70)
                                        Baca Selengkapnya..
                                        @endif
                                    </p>
                                    <div class="notification-footer">
                                        <span class="badge bg-labels-info">
                                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M6 11C8.76142 11 11 8.76142 11 6C11 3.23858 8.76142 1 6 1C3.23858 1 1 3.23858 1 6C1 8.76142 3.23858 11 6 11Z" stroke="#787878" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M6 3V6L8 7" stroke="#787878" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                            {{\Carbon\Carbon::parse($history_penugasan->created_at)->format('d-m-Y')}}
                                        </span>
                                        <p class="mb-0">@if($history_penugasan->read_status=='0') @else Read @endif</p>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="offcanvas offcanvas-bottom" tabindex="-1" id="offcanvas_logout" aria-labelledby="offcanvasBottomLabel">
    <div class="offcanvas-body text-center small">
        <h5 class="title">KONFIRMASI LOGOUT APP</h5>
        <p>Apakah Anda Ingin Keluar Dari Aplikasi Ini ?</p>
        <a id="btn_klik" href="{{url('/logout')}}" class="btn btn-sm btn-danger light pwa-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none">
                <path d="M5.46967 12.5303C5.17678 12.2374 5.17678 11.7626 5.46967 11.4697L7.46967 9.46967C7.76257 9.17678 8.23744 9.17678 8.53033 9.46967C8.82323 9.76256 8.82323 10.2374 8.53033 10.5303L7.81066 11.25L15 11.25C15.4142 11.25 15.75 11.5858 15.75 12C15.75 12.4142 15.4142 12.75 15 12.75L7.81066 12.75L8.53033 13.4697C8.82323 13.7626 8.82323 14.2374 8.53033 14.5303C8.23744 14.8232 7.76257 14.8232 7.46967 14.5303L5.46967 12.5303Z" fill="#1C274C" />
                <path fill-rule="evenodd" clip-rule="evenodd" d="M13.9453 1.25H15.0551C16.4227 1.24998 17.525 1.24996 18.392 1.36652C19.2921 1.48754 20.05 1.74643 20.6519 2.34835C21.2538 2.95027 21.5127 3.70814 21.6337 4.60825C21.7503 5.47522 21.7502 6.57754 21.7502 7.94513V16.0549C21.7502 17.4225 21.7503 18.5248 21.6337 19.3918C21.5127 20.2919 21.2538 21.0497 20.6519 21.6517C20.05 22.2536 19.2921 22.5125 18.392 22.6335C17.525 22.75 16.4227 22.75 15.0551 22.75H13.9453C12.5778 22.75 11.4754 22.75 10.6085 22.6335C9.70836 22.5125 8.95048 22.2536 8.34857 21.6517C7.94963 21.2527 7.70068 20.7844 7.54305 20.2498C6.59168 20.2486 5.79906 20.2381 5.15689 20.1518C4.39294 20.0491 3.7306 19.8268 3.20191 19.2981C2.67321 18.7694 2.45093 18.1071 2.34822 17.3431C2.24996 16.6123 2.24998 15.6865 2.25 14.5537V9.44631C2.24998 8.31349 2.24996 7.38774 2.34822 6.65689C2.45093 5.89294 2.67321 5.2306 3.20191 4.7019C3.7306 4.17321 4.39294 3.95093 5.15689 3.84822C5.79906 3.76188 6.59168 3.75142 7.54305 3.75017C7.70068 3.21562 7.94963 2.74729 8.34857 2.34835C8.95048 1.74643 9.70836 1.48754 10.6085 1.36652C11.4754 1.24996 12.5778 1.24998 13.9453 1.25ZM7.25197 17.0042C7.25555 17.6487 7.2662 18.2293 7.30285 18.7491C6.46836 18.7459 5.848 18.7312 5.35676 18.6652C4.75914 18.5848 4.46611 18.441 4.26257 18.2374C4.05903 18.0339 3.91519 17.7409 3.83484 17.1432C3.7516 16.5241 3.75 15.6997 3.75 14.5V9.5C3.75 8.30029 3.7516 7.47595 3.83484 6.85676C3.91519 6.25914 4.05903 5.9661 4.26257 5.76256C4.46611 5.55902 4.75914 5.41519 5.35676 5.33484C5.848 5.2688 6.46836 5.25415 7.30285 5.25091C7.2662 5.77073 7.25555 6.35129 7.25197 6.99583C7.24966 7.41003 7.58357 7.74768 7.99778 7.74999C8.41199 7.7523 8.74964 7.41838 8.75194 7.00418C8.75803 5.91068 8.78643 5.1356 8.89448 4.54735C8.9986 3.98054 9.16577 3.65246 9.40923 3.40901C9.68599 3.13225 10.0746 2.9518 10.8083 2.85315C11.5637 2.75159 12.5648 2.75 14.0002 2.75H15.0002C16.4356 2.75 17.4367 2.75159 18.1921 2.85315C18.9259 2.9518 19.3144 3.13225 19.5912 3.40901C19.868 3.68577 20.0484 4.07435 20.1471 4.80812C20.2486 5.56347 20.2502 6.56459 20.2502 8V16C20.2502 17.4354 20.2486 18.4365 20.1471 19.1919C20.0484 19.9257 19.868 20.3142 19.5912 20.591C19.3144 20.8678 18.9259 21.0482 18.1921 21.1469C17.4367 21.2484 16.4356 21.25 15.0002 21.25H14.0002C12.5648 21.25 11.5637 21.2484 10.8083 21.1469C10.0746 21.0482 9.68599 20.8678 9.40923 20.591C9.16577 20.3475 8.9986 20.0195 8.89448 19.4527C8.78643 18.8644 8.75803 18.0893 8.75194 16.9958C8.74964 16.5816 8.41199 16.2477 7.99778 16.25C7.58357 16.2523 7.24966 16.59 7.25197 17.0042Z" fill="#1C274C" />
            </svg>
            &nbsp;Logout
        </a>
        <a href="javascrpit:void(0);" class="btn btn-sm light btn-primary ms-2" data-bs-dismiss="offcanvas" aria-label="Close">Batal</a>
    </div>
</div>
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script>
    $(document).on('click', '#btn_klik', function(e) {
        Swal.fire({
            allowOutsideClick: false,
            background: 'transparent',
            html: ' <div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div><div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div><div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div>',
            showCancelButton: false,
            showConfirmButton: false,
            onBeforeOpen: () => {
                Swal.showLoading()
            },
            onAfterClose() {
                Swal.close()
            }
        });
    });
    window.onbeforeunload = function() {
        Swal.fire({
            allowOutsideClick: false,
            background: 'transparent',
            html: ' <div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div><div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div><div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div>',
            showCancelButton: false,
            showConfirmButton: false,
            onBeforeOpen: () => {
                // Swal.showLoading()
            },
        });
    };
</script>
@endsection