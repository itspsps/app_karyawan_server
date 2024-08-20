@extends('users.layouts.main')
@section('title') APPS | KARYAWAN - SP @endsection
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
            <div class="notification-content" style="overflow: auto; height: 100%;">
                <a href="profile.html">
                    <div class="notification" style="box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.2), 0 3px 10px 0 rgba(0, 0, 0, 0.1);">
                        <h6>Izin Cuti</h6>
                        <p>Acara Keluarga</p>
                        <div class="notification-footer">
                            <span>
                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6 11C8.76142 11 11 8.76142 11 6C11 3.23858 8.76142 1 6 1C3.23858 1 1 3.23858 1 6C1 8.76142 3.23858 11 6 11Z" stroke="#787878" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M6 3V6L8 7" stroke="#787878" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                10h ago
                            </span>
                            <p class="mb-0">Mark as read</p>
                        </div>
                    </div>
                </a>
                <a href="profile.html">
                    <div class="notification" style="box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.2), 0 3px 10px 0 rgba(0, 0, 0, 0.1);">
                        <h6>Izin Cuti</h6>
                        <p>Acara Keluarga</p>
                        <div class="notification-footer">
                            <span>
                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6 11C8.76142 11 11 8.76142 11 6C11 3.23858 8.76142 1 6 1C3.23858 1 1 3.23858 1 6C1 8.76142 3.23858 11 6 11Z" stroke="#787878" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M6 3V6L8 7" stroke="#787878" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                10h ago
                            </span>
                            <p class="mb-0">Mark as read</p>
                        </div>
                    </div>
                </a>
                <a href="profile.html">
                    <div class="notification" style="box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.2), 0 3px 10px 0 rgba(0, 0, 0, 0.1);">
                        <h6>Izin Cuti</h6>
                        <p>Acara Keluarga</p>
                        <div class="notification-footer">
                            <span>
                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6 11C8.76142 11 11 8.76142 11 6C11 3.23858 8.76142 1 6 1C3.23858 1 1 3.23858 1 6C1 8.76142 3.23858 11 6 11Z" stroke="#787878" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M6 3V6L8 7" stroke="#787878" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                10h ago
                            </span>
                            <p class="mb-0">Mark as read</p>
                        </div>
                    </div>
                </a>
                <a href="profile.html">
                    <div class="notification">
                        <h6>Izin Cuti</h6>
                        <p>Acara Keluarga</p>
                        <div class="notification-footer">
                            <span>
                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6 11C8.76142 11 11 8.76142 11 6C11 3.23858 8.76142 1 6 1C3.23858 1 1 3.23858 1 6C1 8.76142 3.23858 11 6 11Z" stroke="#787878" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M6 3V6L8 7" stroke="#787878" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                10h ago
                            </span>
                            <p class="mb-0">Mark as read</p>
                        </div>
                    </div>
                </a>
                <a href="profile.html">
                    <div class="notification">
                        <h6>Izin Cuti</h6>
                        <p>Acara Keluarga</p>
                        <div class="notification-footer">
                            <span>
                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6 11C8.76142 11 11 8.76142 11 6C11 3.23858 8.76142 1 6 1C3.23858 1 1 3.23858 1 6C1 8.76142 3.23858 11 6 11Z" stroke="#787878" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M6 3V6L8 7" stroke="#787878" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                1-02-2024
                            </span>
                            <p class="mb-0">Mark as read</p>
                        </div>
                    </div>
                </a>
                <a href="profile.html">
                    <div class="notification">
                        <h6>Izin Cuti</h6>
                        <p>Acara Keluarga</p>
                        <div class="notification-footer">
                            <span>
                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6 11C8.76142 11 11 8.76142 11 6C11 3.23858 8.76142 1 6 1C3.23858 1 1 3.23858 1 6C1 8.76142 3.23858 11 6 11Z" stroke="#787878" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M6 3V6L8 7" stroke="#787878" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                14-02-2024
                            </span>
                            <p class="mb-0">Mark as read</p>
                        </div>
                    </div>
                </a>
                <a href="profile.html">
                    <div class="notification">
                        <h6>Izin Cuti</h6>
                        <p>Acara Keluarga</p>
                        <div class="notification-footer">
                            <span>
                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6 11C8.76142 11 11 8.76142 11 6C11 3.23858 8.76142 1 6 1C3.23858 1 1 3.23858 1 6C1 8.76142 3.23858 11 6 11Z" stroke="#787878" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M6 3V6L8 7" stroke="#787878" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                28-02-2024
                            </span>
                            <p class="mb-0">Mark as read</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection