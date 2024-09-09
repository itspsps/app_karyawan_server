@extends('users.layouts.main')
@section('title') APPS | KARYAWAN - SP @endsection
@section('content')
<div class="page-content bottom-content ">
    <div class="dz-banner-heading">
        <div class="overlay-black-light">
            <img src="{{ asset('assets/assets_users/images/bg_profil.jpg') }}" class="bnr-img" alt="">
        </div>
    </div>
    <div class="container profile-area">
        <div class="profile">
            <div class="media media-100">
                @if(Auth::user()->foto_karyawan == '' || Auth::user()->foto_karyawan == NULL)
                <img width="40px" src="{{asset('admin/assets/img/avatars/1.png')}}" alt="/">
                @else
                <img width="40px" src="{{ url('https://karyawan.sumberpangan.store/laravel/storage/app/public/foto_karyawan/'.Auth::user()->foto_karyawan) }}" alt="author-image">
                @endif
            </div>
            <div class="mb-2">
                <h4 class="mb-0">{{ $data->name}}</h4>
                <span class="detail">{{ $data->fullname }}</span>
            </div>
            <p>{{ $data->motto }}</p>
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