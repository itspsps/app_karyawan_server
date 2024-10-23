@extends('users.profile.layouts.main')
@section('title') APPS | KARYAWAN - SP @endsection
@section('css')
<style>
    .modal-backdrop.show:nth-of-type(even) {
        z-index: 1051 !important;
    }
</style>
@endsection
@section('content')
<div class="fixed-content p-0" style=" border-radius: 10px; margin-top: 0%;box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); ">
    <div class=" container" style="margin-top: -5%;">
        @if(Session::has('profile_update_success'))
        <div id="alert_profile_update_success" class="container" style="margin-top:-5%">
            <div class="alert alert-success light alert-lg alert-dismissible fade show">
                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                    <line x1="9" y1="9" x2="9.01" y2="9"></line>
                    <line x1="15" y1="9" x2="15.01" y2="9"></line>
                </svg>
                <strong>Sukses!</strong> Anda Berhasil Update Foto Profile.
                <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>
        @elseif(Session::has('profile_update_error'))
        <div id="alert_profile_update_error" class="container" style="margin-top:-5%">
            <div class="alert alert-danger light alert-lg alert-dismissible fade show">
                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                    <line x1="9" y1="9" x2="9.01" y2="9"></line>
                    <line x1="15" y1="9" x2="15.01" y2="9"></line>
                </svg>
                <strong>Error!</strong> Anda Gagal Update Foto Profile.
                <button class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>
        @endif
        <div class="dz-banner-heading">
            <div class="overlay-black-light">
                <img src="{{ asset('assets/assets_users/images/bg_profil.jpg') }}" class="bnr-img" alt="">
            </div>
        </div>
        <div class="container profile-area">
            <div class="profile">
                <div class="media media-100">
                    @if($user_karyawan->foto_karyawan == '' || $user_karyawan->foto_karyawan == NULL)
                    <img width="60px" src="{{asset('admin/assets/img/avatars/1.png')}}" alt="/">
                    @else
                    <img width="60px" src="{{ url('https://hrd.sumberpangan.store:4430/storage/app/public/foto_karyawan/'.$user_karyawan->foto_karyawan) }}" alt="author-image">
                    @endif
                </div>
                <div class="" style="margin-top: -8%; padding: 0;">
                    <a href="javascript:void(0);" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_edit_profile" aria-controls="offcanvasBottom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30px" height="30px" viewBox="0 -0.5 25 25" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M17.7 5.12758L19.266 6.37458C19.4172 6.51691 19.5025 6.71571 19.5013 6.92339C19.5002 7.13106 19.4128 7.32892 19.26 7.46958L18.07 8.89358L14.021 13.7226C13.9501 13.8037 13.8558 13.8607 13.751 13.8856L11.651 14.3616C11.3755 14.3754 11.1356 14.1751 11.1 13.9016V11.7436C11.1071 11.6395 11.149 11.5409 11.219 11.4636L15.193 6.97058L16.557 5.34158C16.8268 4.98786 17.3204 4.89545 17.7 5.12758Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M12.033 7.61865C12.4472 7.61865 12.783 7.28287 12.783 6.86865C12.783 6.45444 12.4472 6.11865 12.033 6.11865V7.61865ZM9.23301 6.86865V6.11865L9.23121 6.11865L9.23301 6.86865ZM5.50001 10.6187H6.25001L6.25001 10.617L5.50001 10.6187ZM5.50001 16.2437L6.25001 16.2453V16.2437H5.50001ZM9.23301 19.9937L9.23121 20.7437H9.23301V19.9937ZM14.833 19.9937V20.7437L14.8348 20.7437L14.833 19.9937ZM18.566 16.2437H17.816L17.816 16.2453L18.566 16.2437ZM19.316 12.4937C19.316 12.0794 18.9802 11.7437 18.566 11.7437C18.1518 11.7437 17.816 12.0794 17.816 12.4937H19.316ZM15.8863 6.68446C15.7282 6.30159 15.2897 6.11934 14.9068 6.2774C14.5239 6.43546 14.3417 6.87397 14.4998 7.25684L15.8863 6.68446ZM18.2319 9.62197C18.6363 9.53257 18.8917 9.13222 18.8023 8.72777C18.7129 8.32332 18.3126 8.06792 17.9081 8.15733L18.2319 9.62197ZM8.30001 16.4317C7.8858 16.4317 7.55001 16.7674 7.55001 17.1817C7.55001 17.5959 7.8858 17.9317 8.30001 17.9317V16.4317ZM15.767 17.9317C16.1812 17.9317 16.517 17.5959 16.517 17.1817C16.517 16.7674 16.1812 16.4317 15.767 16.4317V17.9317ZM12.033 6.11865H9.23301V7.61865H12.033V6.11865ZM9.23121 6.11865C6.75081 6.12461 4.7447 8.13986 4.75001 10.6203L6.25001 10.617C6.24647 8.96492 7.58269 7.62262 9.23481 7.61865L9.23121 6.11865ZM4.75001 10.6187V16.2437H6.25001V10.6187H4.75001ZM4.75001 16.242C4.7447 18.7224 6.75081 20.7377 9.23121 20.7437L9.23481 19.2437C7.58269 19.2397 6.24647 17.8974 6.25001 16.2453L4.75001 16.242ZM9.23301 20.7437H14.833V19.2437H9.23301V20.7437ZM14.8348 20.7437C17.3152 20.7377 19.3213 18.7224 19.316 16.242L17.816 16.2453C17.8195 17.8974 16.4833 19.2397 14.8312 19.2437L14.8348 20.7437ZM19.316 16.2437V12.4937H17.816V16.2437H19.316ZM14.4998 7.25684C14.6947 7.72897 15.0923 8.39815 15.6866 8.91521C16.2944 9.44412 17.1679 9.85718 18.2319 9.62197L17.9081 8.15733C17.4431 8.26012 17.0391 8.10369 16.6712 7.7836C16.2897 7.45165 16.0134 6.99233 15.8863 6.68446L14.4998 7.25684ZM8.30001 17.9317H15.767V16.4317H8.30001V17.9317Z" fill="#000000" />
                        </svg>
                    </a>
                </div>
                <div class="mb-2">
                    <h4 class="mb-0">{{ $user_karyawan->name}}</h4>
                    <span class="detail">{{ Auth::user()->username }}</span>
                </div>
                <p>{{ $user_karyawan->penempatan_kerja }}</p>
            </div>
            <ul class="contact-profile">
                <li class="d-flex align-items-center">
                    <a href="{{url('detail_profile')}}" class="contact-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" height="26" width="26" version="1.1" id="Layer_1" viewBox="0 0 376.178 376.178" xml:space="preserve">
                            <g>
                                <g>
                                    <path d="M282.44,62.966h-44.8V51.394c0-5.818-4.719-10.408-10.408-10.408h-7.499V10.408C219.733,4.59,215.014,0,209.325,0h-42.731    c-5.818,0-10.408,4.719-10.408,10.408v30.578h-7.499c-5.818,0-10.408,4.719-10.408,10.408v11.572H93.414    c-17.067,0-30.966,13.899-30.966,30.966v251.281c0,17.067,13.899,30.966,30.966,30.966h189.349    c17.067,0,30.966-13.899,30.966-30.966V93.931C313.471,76.865,299.507,62.966,282.44,62.966z M198.788,20.816v20.105h-21.851    l-0.065-20.105H198.788z M158.901,61.867h57.794v17.067h-57.794V61.867z M282.505,355.232H93.156c-5.56,0-10.02-4.461-10.02-10.02    V198.594h20.299c5.818,0,10.408-4.719,10.408-10.408s-4.719-10.408-10.408-10.408H83.071v-20.105h40.792    c5.818,0,10.408-4.719,10.408-10.408s-4.719-10.408-10.408-10.408H83.071V94.19c0-5.56,4.461-10.02,10.02-10.02h44.929v5.495    c0,5.818,4.719,10.408,10.408,10.408h78.675c5.818,0,10.408-4.719,10.408-10.408V84.17h44.8c5.56,0,10.02,4.461,10.02,10.02    v251.152h0.188C292.45,350.84,288.021,355.232,282.505,355.232z" />
                                </g>
                            </g>
                            <g>
                                <g>
                                    <path d="M234.925,250.44c-30.319-11.184-64-11.184-94.255,0c-16.226,6.012-27.216,21.657-27.216,38.788v35.491    c0,5.818,4.719,10.408,10.408,10.408h127.806c5.818,0,10.408-4.719,10.408-10.408v-35.491    C262.141,272.032,251.216,256.453,234.925,250.44z M241.261,314.246H134.4v-24.953c0-8.469,5.43-16.226,13.511-19.135    c25.665-9.438,54.174-9.438,79.838,0c8.145,3.038,13.511,10.731,13.511,19.135V314.246z" />
                                </g>
                            </g>
                            <g>
                                <g>
                                    <path d="M187.798,142.028c-23.273,0-42.214,18.877-42.214,42.15s18.941,42.214,42.214,42.214s42.214-18.941,42.214-42.214    S211.071,142.028,187.798,142.028z M187.798,205.446c-11.766,0-21.269-9.568-21.269-21.269s9.632-21.204,21.269-21.204    c11.766,0,21.269,9.568,21.269,21.269C209.067,195.943,199.434,205.446,187.798,205.446z" />
                                </g>
                            </g>
                        </svg>
                    </a>
                </li>
                <li class="d-flex align-items-center px-4">
                    <a href="{{url('detail_alamat')}}" class="contact-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 64 64" fill="none">
                            <g clip-path="url(#clip0_14_1983)">
                                <path d="M25.926 53.099V47.174C25.926 45.563 26.5659 44.0179 27.705 42.8787C28.844 41.7394 30.389 41.0993 32 41.099V41.099C32.7977 41.099 33.5876 41.2561 34.3246 41.5615C35.0616 41.8668 35.7313 42.3143 36.2953 42.8784C36.8593 43.4425 37.3067 44.1122 37.6119 44.8493C37.9171 45.5863 38.0741 46.3763 38.074 47.174V53.099" stroke="#000000" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M12.8 24.47V48.599C12.8 49.7925 13.2741 50.9371 14.118 51.781C14.9619 52.6249 16.1065 53.099 17.3 53.099H25.931" stroke="#000000" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M38.079 53.099H46.7C47.8935 53.099 49.0381 52.6249 49.882 51.781C50.7259 50.9371 51.2 49.7925 51.2 48.599V26.549C51.1992 25.8505 51.0362 25.1618 50.7239 24.5371C50.4115 23.9123 49.9583 23.3687 49.4 22.949" stroke="#000000" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M8 28.194L29.28 11.927C30.0625 11.329 31.0191 11.0033 32.004 10.9995C32.9888 10.9958 33.9479 11.3141 34.735 11.906L56 27.9" stroke="#426AB2" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M12.8 24.527V16.697C12.8 16.1666 13.0107 15.6579 13.3858 15.2828C13.7608 14.9077 14.2696 14.697 14.8 14.697H18C18.5304 14.697 19.0391 14.9077 19.4142 15.2828C19.7893 15.6579 20 16.1666 20 16.697V18.73" stroke="#426AB2" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                            </g>
                            <defs>
                                <clipPath id="clip0_14_1983">
                                    <rect width="51.999" height="46.1" fill="white" transform="translate(6 9)" />
                                </clipPath>
                            </defs>
                        </svg>
                    </a>
                </li>
                <li class="d-flex align-items-center">
                    <a href="{{url('detail_account')}}" class="contact-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" id="Layer_1" data-name="Layer 1">
                            <defs>
                                <style>
                                    .cls-1 {
                                        fill: none;
                                        stroke: #020202;
                                        stroke-miterlimit: 10;
                                        stroke-width: 1.91px;
                                    }
                                </style>
                            </defs>
                            <circle class="cls-1" cx="12.02" cy="7.24" r="5.74" />
                            <path class="cls-1" d="M2.46,23.5V21.59a9.55,9.55,0,0,1,7-9.21" />
                            <path class="cls-1" d="M16.8,14.89l-1,1.91H9.15L7.24,18.72l1.91,1.91h6.7l1,1.91h2.87a2.86,2.86,0,0,0,2.87-2.87V17.76a2.87,2.87,0,0,0-2.87-2.87Z" />
                            <line class="cls-1" x1="12.02" y1="18.72" x2="12.02" y2="20.63" />
                            <line class="cls-1" x1="19.67" y1="17.76" x2="19.67" y2="19.67" />
                        </svg>
                    </a>
                </li>
            </ul>
            <div class="pdf-bx">
                <a href="javascript:void(0);">
                    <h5 class="text-white">My Resume</h5>
                    <span class="text-white">david_resume.pdf</span>
                </a>
                <div class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
                        <svg width="5" height="23" viewBox="0 0 5 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="2.5" cy="2.5" r="2.5" fill="#fff"></circle>
                            <circle cx="2.5" cy="11.5" r="2.5" fill="#fff"></circle>
                            <circle cx="2.5" cy="20.5" r="2.5" fill="#fff"></circle>
                        </svg>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                        <li><button class="dropdown-item" type="button">Action</button></li>
                        <li><button class="dropdown-item" type="button">Another action</button></li>
                        <li><button class="dropdown-item" type="button">Something else here</button></li>
                    </ul>
                </div>
            </div>
            <div class="skill-section">
                <h5>Skill</h5>
                <div class="row g-3">
                    <div class="col-4">
                        <div class="skill-bar">
                            <div class="donut-chart-sale">
                                <span class="donut" data-peity="{ &quot;fill&quot;: [&quot;#EE8524&quot;, &quot;#EAE4F6&quot;],   &quot;innerRadius&quot;: 26, &quot;radius&quot;: 32 }" style="display: none;">86/100</span><svg class="peity" height="64" width="64">
                                    <path d="M 32 0 A 32 32 0 1 1 7.343576231174733 11.602432328041942 L 11.96665568782947 15.426976266534076 A 26 26 0 1 0 32 6" data-value="86" fill="#EE8524"></path>
                                    <path d="M 7.343576231174733 11.602432328041942 A 32 32 0 0 1 31.999999999999993 0 L 31.999999999999996 6 A 26 26 0 0 0 11.96665568782947 15.426976266534076" data-value="14" fill="#EAE4F6"></path>
                                </svg>
                                <small class="text-black">66%</small>
                            </div>
                            <h6 class="title">PHP</h6>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="skill-bar">
                            <div class="donut-chart-sale">
                                <span class="donut" data-peity="{ &quot;fill&quot;: [&quot;#2AAF50&quot;, &quot;#EAE4F6&quot;],   &quot;innerRadius&quot;: 26, &quot;radius&quot;: 32 }" style="display: none;">48/100</span><svg class="peity" height="64" width="64">
                                    <path d="M 32 0 A 32 32 0 0 1 36.01066347405774 63.74767044206329 L 35.25866407267192 57.79498223417642 A 26 26 0 0 0 32 6" data-value="48" fill="#2AAF50"></path>
                                    <path d="M 36.01066347405774 63.74767044206329 A 32 32 0 1 1 31.999999999999993 0 L 31.999999999999996 6 A 26 26 0 1 0 35.25866407267192 57.79498223417642" data-value="52" fill="#EAE4F6"></path>
                                </svg>
                                <small class="text-black">48%</small>
                            </div>
                            <h6 class="title">Java</h6>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="skill-bar">
                            <div class="donut-chart-sale">
                                <span class="donut" data-peity="{ &quot;fill&quot;: [&quot;#1A88C6&quot;, &quot;#EAE4F6&quot;],   &quot;innerRadius&quot;: 26, &quot;radius&quot;: 32 }" style="display: none;">56/100</span><svg class="peity" height="64" width="64">
                                    <path d="M 32 0 A 32 32 0 1 1 20.220014314090292 61.75284754842404 L 22.428761630198363 56.17418863309453 A 26 26 0 1 0 32 6" data-value="56" fill="#1A88C6"></path>
                                    <path d="M 20.220014314090292 61.75284754842404 A 32 32 0 0 1 31.999999999999993 0 L 31.999999999999996 6 A 26 26 0 0 0 22.428761630198363 56.17418863309453" data-value="44" fill="#EAE4F6"></path>
                                </svg>
                                <small class="text-black">56%</small>
                            </div>
                            <h6 class="title">MySQL</h6>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="skill-bar">
                            <div class="donut-chart-sale">
                                <span class="donut" data-peity="{ &quot;fill&quot;: [&quot;#1A88C6&quot;, &quot;#EAE4F6&quot;],   &quot;innerRadius&quot;: 26, &quot;radius&quot;: 32 }" style="display: none;">34/100</span><svg class="peity" height="64" width="64">
                                    <path d="M 32 0 A 32 32 0 0 1 59.018493616064475 49.146457439327904 L 53.95252606305239 45.93149666945392 A 26 26 0 0 0 32 6" data-value="34" fill="#1A88C6"></path>
                                    <path d="M 59.018493616064475 49.146457439327904 A 32 32 0 1 1 31.999999999999993 0 L 31.999999999999996 6 A 26 26 0 1 0 53.95252606305239 45.93149666945392" data-value="66" fill="#EAE4F6"></path>
                                </svg>
                                <small class="text-black">34%</small>
                            </div>
                            <h6 class="title">React N</h6>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="skill-bar">
                            <div class="donut-chart-sale">
                                <span class="donut" data-peity="{ &quot;fill&quot;: [&quot;#3E1899&quot;, &quot;#EAE4F6&quot;],   &quot;innerRadius&quot;: 26, &quot;radius&quot;: 32 }" style="display: none;">86/100</span><svg class="peity" height="64" width="64">
                                    <path d="M 32 0 A 32 32 0 1 1 7.343576231174733 11.602432328041942 L 11.96665568782947 15.426976266534076 A 26 26 0 1 0 32 6" data-value="86" fill="#3E1899"></path>
                                    <path d="M 7.343576231174733 11.602432328041942 A 32 32 0 0 1 31.999999999999993 0 L 31.999999999999996 6 A 26 26 0 0 0 11.96665568782947 15.426976266534076" data-value="14" fill="#EAE4F6"></path>
                                </svg>
                                <small class="text-black">86%</small>
                            </div>
                            <h6 class="title">CSS</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="offcanvas offcanvas-bottom" tabindex="-1" id="offcanvas_edit_profile" aria-labelledby="offcanvasBottomLabel">
    <div class="offcanvas-body text-center small">
        <h5 class="title">Pilih Metode</h5>
        <p>Pilih Menggunakan Gallery Atau Kamera</p>
        <button type="button" class="btn btn-sm btn-info light pwa-btn" onclick="thisFileUpload();">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                <path d="M21.9998 12.6978C21.9983 14.1674 21.9871 15.4165 21.9036 16.4414C21.8067 17.6308 21.6081 18.6246 21.1636 19.45C20.9676 19.814 20.7267 20.1401 20.4334 20.4334C19.601 21.2657 18.5405 21.6428 17.1966 21.8235C15.8835 22 14.2007 22 12.0534 22H11.9466C9.79929 22 8.11646 22 6.80345 21.8235C5.45951 21.6428 4.39902 21.2657 3.56664 20.4334C2.82871 19.6954 2.44763 18.777 2.24498 17.6376C2.04591 16.5184 2.00949 15.1259 2.00192 13.3967C2 12.9569 2 12.4917 2 12.0009V11.9466C1.99999 9.79929 1.99998 8.11646 2.17651 6.80345C2.3572 5.45951 2.73426 4.39902 3.56664 3.56664C4.39902 2.73426 5.45951 2.3572 6.80345 2.17651C7.97111 2.01952 9.47346 2.00215 11.302 2.00024C11.6873 1.99983 12 2.31236 12 2.69767C12 3.08299 11.6872 3.3952 11.3019 3.39561C9.44749 3.39757 8.06751 3.41446 6.98937 3.55941C5.80016 3.7193 5.08321 4.02339 4.5533 4.5533C4.02339 5.08321 3.7193 5.80016 3.55941 6.98937C3.39683 8.19866 3.39535 9.7877 3.39535 12C3.39535 12.2702 3.39535 12.5314 3.39567 12.7844L4.32696 11.9696C5.17465 11.2278 6.45225 11.2704 7.24872 12.0668L11.2392 16.0573C11.8785 16.6966 12.8848 16.7837 13.6245 16.2639L13.9019 16.0689C14.9663 15.3209 16.4064 15.4076 17.3734 16.2779L20.0064 18.6476C20.2714 18.091 20.4288 17.3597 20.5128 16.3281C20.592 15.3561 20.6029 14.1755 20.6044 12.6979C20.6048 12.3126 20.917 12 21.3023 12C21.6876 12 22.0002 12.3125 21.9998 12.6978Z" fill="#1C274C" />
                <path fill-rule="evenodd" clip-rule="evenodd" d="M17.5 11C15.3787 11 14.318 11 13.659 10.341C13 9.68198 13 8.62132 13 6.5C13 4.37868 13 3.31802 13.659 2.65901C14.318 2 15.3787 2 17.5 2C19.6213 2 20.682 2 21.341 2.65901C22 3.31802 22 4.37868 22 6.5C22 8.62132 22 9.68198 21.341 10.341C20.682 11 19.6213 11 17.5 11ZM19.5303 5.46967L18.0303 3.96967C17.7374 3.67678 17.2626 3.67678 16.9697 3.96967L15.4697 5.46967C15.1768 5.76256 15.1768 6.23744 15.4697 6.53033C15.7626 6.82322 16.2374 6.82322 16.5303 6.53033L16.75 6.31066V8.5C16.75 8.91421 17.0858 9.25 17.5 9.25C17.9142 9.25 18.25 8.91421 18.25 8.5V6.31066L18.4697 6.53033C18.7626 6.82322 19.2374 6.82322 19.5303 6.53033C19.8232 6.23744 19.8232 5.76256 19.5303 5.46967Z" fill="#1C274C" />
            </svg>
            &nbsp;Gallery
        </button>
        <input type="file" hidden id="gallery_image" value="" accept="image/jpeg">
        <a id="btn_klik" href="{{url('change_photoprofile_camera')}}" class="btn btn-sm light btn-primary ms-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="13" r="3" stroke="#1C274C" stroke-width="1.5" />
                <path d="M2 13.3636C2 10.2994 2 8.76721 2.74902 7.6666C3.07328 7.19014 3.48995 6.78104 3.97524 6.46268C4.69555 5.99013 5.59733 5.82123 6.978 5.76086C7.63685 5.76086 8.20412 5.27068 8.33333 4.63636C8.52715 3.68489 9.37805 3 10.3663 3H13.6337C14.6219 3 15.4728 3.68489 15.6667 4.63636C15.7959 5.27068 16.3631 5.76086 17.022 5.76086C18.4027 5.82123 19.3044 5.99013 20.0248 6.46268C20.51 6.78104 20.9267 7.19014 21.251 7.6666C22 8.76721 22 10.2994 22 13.3636C22 16.4279 22 17.9601 21.251 19.0607C20.9267 19.5371 20.51 19.9462 20.0248 20.2646C18.9038 21 17.3433 21 14.2222 21H9.77778C6.65675 21 5.09624 21 3.97524 20.2646C3.48995 19.9462 3.07328 19.5371 2.74902 19.0607C2.53746 18.7498 2.38566 18.4045 2.27673 18" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                <path d="M19 10H18" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
            </svg>
            &nbsp;Camera
        </a>
    </div>
</div>
<div class="offcanvas offcanvas-bottom" tabindex="-1" id="offcanvas_konfirmasi_edit_profile" aria-labelledby="offcanvasBottomLabel">
    <div class="offcanvas-body text-center small">
        <h5 class="title">Konfirmasi</h5>
        <p>Konfirmasi Pengambilan Foto</p>
        <img src="" id="preview_gallery" alt="">
        <form method="POST" action="{{ url('save_capture_profile') }}" enctype="multipart/form-data">
            @csrf
            <div class="col-md-6">
                <div id="results"></div>
                <input type="hidden" name="gallery_image" class="image-tag">
            </div>
            <br>
            <button type="submit" id="btn_klik" class="btn btn-sm btn-info light pwa-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M15 13H9" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M12 10L12 16" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M19 10H18" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M2 13.3636C2 10.2994 2 8.76721 2.74902 7.6666C3.07328 7.19014 3.48995 6.78104 3.97524 6.46268C4.69555 5.99013 5.59733 5.82123 6.978 5.76086C7.63685 5.76086 8.20412 5.27068 8.33333 4.63636C8.52715 3.68489 9.37805 3 10.3663 3H13.6337C14.6219 3 15.4728 3.68489 15.6667 4.63636C15.7959 5.27068 16.3631 5.76086 17.022 5.76086C18.4027 5.82123 19.3044 5.99013 20.0248 6.46268C20.51 6.78104 20.9267 7.19014 21.251 7.6666C22 8.76721 22 10.2994 22 13.3636C22 16.4279 22 17.9601 21.251 19.0607C20.9267 19.5371 20.51 19.9462 20.0248 20.2646C18.9038 21 17.3433 21 14.2222 21H9.77778C6.65675 21 5.09624 21 3.97524 20.2646C3.48995 19.9462 3.07328 19.5371 2.74902 19.0607C2.53746 18.7498 2.38566 18.4045 2.27673 18" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                </svg>
                &nbsp;Simpan
            </button>
            <a href="javascript:void(0);" class="btn btn-sm light btn-primary ms-2" data-bs-dismiss="offcanvas" aria-label="Close">
                &nbsp;Batal
            </a>
        </form>
    </div>
</div>
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#preview_gallery').attr('src', e.target.result);
                $('.image-tag').val(e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#gallery_image").change(function() {
        readURL(this);
        var bsOffcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvas_konfirmasi_edit_profile'))
        bsOffcanvas.show()
    });

    function thisFileUpload() {
        document.getElementById("gallery_image").click();
    };
    $(document).on('click', '#btn_klik', function(e) {
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
<script>
    $("document").ready(function() {
        // console.log('ok');
        setTimeout(function() {
            // console.log('ok1');
            $("#alert_profile_update_success").remove();
            $("#alert_profile_update_error").remove();
        }, 7000); // 7 secs

    });
</script>
@endsection