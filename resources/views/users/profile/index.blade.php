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
                    <span class="detail">{{ $user_karyawan->name }}</span>
                </div>
                <p>{{ $user_karyawan->penempatan_kerja }}</p>
            </div>
            <ul class="contact-profile">
                <li class="d-flex align-items-center">
                    <a href="messages.html" class="contact-icon">
                        <svg class="text-primary" width="24" height="24" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M26.2806 19.775C26.2089 19.7181 21 15.9635 19.5702 16.233C18.8877 16.3538 18.4975 16.8193 17.7144 17.7511C17.5884 17.9016 17.2856 18.2621 17.0503 18.5185C16.5553 18.3571 16.0726 18.1606 15.6056 17.9305C13.1955 16.7571 11.2481 14.8098 10.0747 12.3996C9.84451 11.9327 9.648 11.45 9.48675 10.955C9.744 10.7188 10.1045 10.416 10.2585 10.2865C11.186 9.50775 11.6524 9.1175 11.7731 8.43325C12.0208 7.01575 8.26875 1.771 8.22937 1.72375C8.05914 1.48056 7.83698 1.27825 7.57896 1.13147C7.32095 0.984676 7.03353 0.897075 6.7375 0.875C5.21675 0.875 0.875 6.50737 0.875 7.45587C0.875 7.511 0.954625 13.1145 7.8645 20.1434C14.8864 27.0454 20.489 27.125 20.5441 27.125C21.4935 27.125 27.125 22.7832 27.125 21.2625C27.1032 20.9675 27.0161 20.681 26.8701 20.4238C26.724 20.1666 26.5227 19.945 26.2806 19.775Z" fill="#40189D"></path>
                        </svg>
                    </a>
                </li>
                <li class="d-flex align-items-center px-4">
                    <a href="messages.html" class="contact-icon">
                        <svg class="text-primary" width="24" height="24" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M27.0761 6.24662C26.9621 5.48439 26.5787 4.78822 25.9955 4.28434C25.4123 3.78045 24.6679 3.50219 23.8972 3.5H4.10295C3.33223 3.50219 2.58781 3.78045 2.00462 4.28434C1.42143 4.78822 1.03809 5.48439 0.924072 6.24662L14.0001 14.7079L27.0761 6.24662Z" fill="#40189D"></path>
                            <path d="M14.4751 16.485C14.3336 16.5765 14.1686 16.6252 14 16.6252C13.8314 16.6252 13.6664 16.5765 13.5249 16.485L0.875 8.30025V21.2721C0.875926 22.1279 1.2163 22.9484 1.82145 23.5535C2.42659 24.1587 3.24707 24.4991 4.10288 24.5H23.8971C24.7529 24.4991 25.5734 24.1587 26.1786 23.5535C26.7837 22.9484 27.1241 22.1279 27.125 21.2721V8.29938L14.4751 16.485Z" fill="#40189D"></path>
                        </svg>
                    </a>
                </li>
                <li class="d-flex align-items-center">
                    <a href="messages.html" class="contact-icon">
                        <svg class="text-primary" width="24" height="24" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.3077 18.7583C11.3567 18.8204 11.4049 18.8895 11.4696 18.9849C11.8494 19.495 12.2212 19.9955 12.5861 20.5179L13.2861 21.5066C13.367 21.6208 13.474 21.7138 13.5982 21.7781C13.7224 21.8423 13.8603 21.8758 14.0001 21.8758C14.14 21.8758 14.2778 21.8423 14.402 21.7781C14.5262 21.7138 14.6333 21.6208 14.7141 21.5066L15.7361 20.0655C15.9916 19.7076 16.2567 19.3498 16.5534 18.9683C16.5989 18.9018 16.6461 18.8405 16.7161 18.7513C17.732 17.4256 18.8301 16.1621 20.1654 14.6711C21.2564 13.452 21.9617 11.9369 22.1922 10.3172C22.4227 8.69754 22.1681 7.04586 21.4604 5.57079C20.7528 4.09571 19.6239 2.86341 18.2164 2.02952C16.8089 1.19562 15.1858 0.797519 13.5521 0.885501C11.4209 1.01966 9.42195 1.96501 7.96611 3.52728C6.51026 5.08956 5.70807 7.1501 5.72436 9.2855C5.75188 11.3927 6.58904 13.4085 8.06236 14.9153C9.22542 16.1254 10.3094 17.409 11.3077 18.7583ZM14.0001 5.6875C14.7178 5.68664 15.4196 5.89873 16.0167 6.29692C16.6138 6.69511 17.0794 7.26151 17.3544 7.92441C17.6295 8.58731 17.7017 9.31692 17.5619 10.0209C17.4221 10.7248 17.0766 11.3715 16.5691 11.879C16.0616 12.3865 15.4149 12.732 14.711 12.8718C14.007 13.0116 13.2774 12.9394 12.6145 12.6643C11.9516 12.3893 11.3852 11.9237 10.987 11.3266C10.5888 10.7295 10.3767 10.0277 10.3776 9.31C10.3788 8.34961 10.7608 7.42889 11.4399 6.74979C12.119 6.07069 13.0397 5.68866 14.0001 5.6875Z" fill="#40189D"></path>
                            <path d="M14.0002 11.1921C14.3714 11.193 14.7345 11.0838 15.0437 10.8782C15.3528 10.6727 15.594 10.3802 15.7369 10.0375C15.8797 9.69492 15.9178 9.31763 15.8462 8.95339C15.7746 8.58914 15.5967 8.2543 15.3348 7.99121C15.0729 7.72811 14.7389 7.54858 14.375 7.47531C14.0111 7.40204 13.6336 7.43833 13.2904 7.57958C12.9471 7.72084 12.6534 7.96072 12.4464 8.26889C12.2395 8.57705 12.1286 8.93967 12.1277 9.31088C12.1272 9.80844 12.3241 10.2859 12.6751 10.6385C13.0261 10.9912 13.5026 11.1903 14.0002 11.1921Z" fill="#40189D"></path>
                            <path d="M19.3498 18.2709C18.9123 18.7906 18.4984 19.3007 18.1038 19.8152C18.0504 19.8844 17.9349 20.0401 17.9349 20.0401C17.654 20.4024 17.4029 20.7401 17.1596 21.0814L16.1411 22.5172C15.8986 22.8594 15.5775 23.1385 15.2049 23.3311C14.8323 23.5237 14.419 23.6242 13.9996 23.6242C13.5801 23.6242 13.1668 23.5237 12.7942 23.3311C12.4216 23.1385 12.1006 22.8594 11.858 22.5172L11.158 21.5285C10.808 21.0262 10.458 20.5546 10.1001 20.0751C10.1001 20.0751 9.9575 19.8704 9.92513 19.8336C9.5025 19.271 9.09212 18.7547 8.68525 18.2586C6.02 19.1047 4.375 20.5782 4.375 22.3125C4.375 25.0565 8.51288 27.125 14 27.125C19.4871 27.125 23.625 25.0565 23.625 22.3125C23.625 20.5861 21.9958 19.1187 19.3498 18.2709Z" fill="#40189D"></path>
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
@endsection