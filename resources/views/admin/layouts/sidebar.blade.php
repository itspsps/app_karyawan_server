<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="@if(Auth::user()->is_admin=='hrd')
        {{url('hrd/dashboard/holding')}}
        @else
        {{url('dashboard/holding')}}
        @endif
        " class="app-brand-link">
            <span class="app-brand-logo demo me-1">
                <span style="color: var(--bs-primary)">
                    <img src="{{ asset('holding/assets/img/'.$holding->holding_image) }}" width="50">
                </span>
            </span>
            <span class="app-brand-text demo menu-text fw-semibold ms-2">{{$holding=='' ? '':$holding->holding_name_hint}}</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="mdi menu-toggle-icon d-xl-block align-middle mdi-20px"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item
        @if(Auth::user()->is_admin =='hrd')
        {{ Request::is('hrd/dashboard*') ? 'active' : '' }}
        @else {{ Request::is('dashboard*') ? 'active' : '' }}
         @endif">
            <a href="@if(Auth::user()->is_admin=='hrd')
            {{ url('/hrd/dashboard/option/'.$holding->holding_code) }}
            @else
            {{ url('/dashboard/option/'.$holding->holding_code) }}
        @endif
            " class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                <div style="font-size: 10pt;" data-i18n="Dashboards">Dashboards</div>
            </a>
        </li>
        <li class="menu-header fw-medium mt-4">
            <span class="menu-header-text">MAIN MENU</span>
        </li>
        <!-- DATA MASTER -->
        <li class="menu-item
            @if(Auth::user()->is_admin =='hrd') ||
                {{ Request::is('hrd/reset-cuti*') ||
                Request::is('hrd/departemen*') ||
                Request::is('hrd/divisi*') ||
                Request::is('hrd/bagian*') ||
                Request::is('hrd/jabatan*') ||
                Request::is('hrd/karyawan/shift/*') ||
                Request::is('hrd/shift*') ||
                Request::is('hrd/detail_jabatan*')||
                Request::is('hrd/holding*')||
                Request::is('hrd/site*')||
                Request::is('hrd/shift*')||
                Request::is('hrd/lokasi*') ||
                Request::is('hrd/lokasi-kantor*') ? 'active open' : '' }}
                @else
                {{ Request::is('reset-cuti*') ||
                 Request::is('departemen*') ||
                 Request::is('divisi*') ||
                 Request::is('bagian*') ||
                 Request::is('jabatan*') ||
                 Request::is('detail_jabatan*')||
                 Request::is('holding*')||
                 Request::is('shift*')||
                 Request::is('site*')||
                 Request::is('lokasi*') ||
                 Request::is('karyawan/shift/*') ? 'active open' : '' }}
            @endif
         ">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi mdi-database-outline"></i>
                <div style="font-size: 10pt;" data-i18n="Data Master">Data&nbsp;Master</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item
                    @if(Auth::user()->is_admin =='hrd')
                    {{ Request::is('hrd/departemen*') ? 'active' : '' }}
                    @else
                    {{ Request::is('departemen*') ? 'active' : '' }}
                    @endif
                 ">
                    <a href="@if(Auth::user()->is_admin=='hrd')
                    {{ url('hrd/departemen/'.$holding->holding_code) }}
                    @else
                    {{ url('/departemen/'.$holding->holding_code) }}
                            @endif" class="menu-link">
                        <i class="menu-icon tf-icons mdi mdi-database-cog-outline"></i>
                        <div style="font-size: 10pt;" data-i18n="Blank">&nbsp;Master&nbsp;Departemen</div>
                    </a>
                </li>
                <li class="menu-item
                    @if(Auth::user()->is_admin =='hrd')
                    {{ Request::is('hrd/divisi*') ? 'active' : '' }}
                    @else
                    {{ Request::is('divisi*') ? 'active' : '' }}
                    @endif
                 ">
                    <a href="@if(Auth::user()->is_admin=='hrd')
                    {{ url('/hrd/divisi/'.$holding->holding_code) }}
                    @else
                    {{ url('/divisi/'.$holding->holding_code) }}
                            @endif" class="menu-link">
                        <i class="menu-icon tf-icons mdi mdi-database-cog-outline"></i>
                        <div style="font-size: 10pt;" data-i18n="Blank">&nbsp;Master&nbsp;Divisi</div>
                    </a>
                </li>
                <li class="menu-item
                    @if(Auth::user()->is_admin =='hrd')
                    {{ Request::is('hrd/bagian*') ? 'active' : '' }}
                    @else
                    {{ Request::is('bagian*') ? 'active' : '' }}
                    @endif
                 ">
                    <a href="@if(Auth::user()->is_admin=='hrd')
                    {{ url('/hrd/bagian/'.$holding->holding_code) }}
                    @else
                    {{ url('/bagian/'.$holding->holding_code) }}
                            @endif" class="menu-link">
                        <i class="menu-icon tf-icons mdi mdi-database-cog-outline"></i>
                        <div style="font-size: 10pt;" data-i18n="Blank">&nbsp;Master&nbsp;Bagian</div>
                    </a>
                </li>
                <li class="menu-item
                    @if(Auth::user()->is_admin =='hrd')
                    {{ Request::is('hrd/jabatan*') ? 'active' : '' }}
                    {{ Request::is('hrd/detail_jabatan*') ? 'active' : '' }}
                    @else
                    {{ Request::is('jabatan*') ? 'active' : '' }}
                    {{ Request::is('detail_jabatan*') ? 'active' : '' }}
                    @endif
                 ">
                    <a href="@if(Auth::user()->is_admin=='hrd')
                    {{ url('/hrd/jabatan/'.$holding->holding_code) }}
                    @else
                    {{ url('/jabatan/'.$holding->holding_code) }}
                            @endif" class="menu-link">
                        <i class="menu-icon tf-icons mdi mdi-database-cog-outline"></i>
                        <div style="font-size: 10pt;" data-i18n="Blank">&nbsp;Master&nbsp;Jabatan</div>
                    </a>
                </li>
                <li class="menu-item
                @if(Auth::user()->is_admin =='hrd')
                {{ Request::is('hrd/holding*') ? 'active' : '' }}
                @else
                    {{ Request::is('holding*') ? 'active' : '' }}
                    @endif
                ">
                    <a href=" @if(Auth::user()->is_admin =='hrd'){{ url('hrd/holding/'.$holding->holding_code) }}@else {{ url('/holding/'.$holding->holding_code) }}@endif" class=" menu-link">
                        <div style="font-size: 10pt;" data-i18n="Without navbar"><i class="mdi mdi-office-building-cog-outline"></i>&nbsp;Master Holding</div>
                    </a>
                </li>
                <li class="menu-item
                 @if(Auth::user()->is_admin =='hrd')
                 {{ Request::is('hrd/site*') ? 'active' : '' }}
                 @else
                 {{ Request::is('site*') ? 'active' : '' }}
                 @endif
                 ">
                    <a href="@if(Auth::user()->is_admin =='hrd'){{ url('/hrd/site/'.$holding->holding_code) }} @else {{ url('/site/'.$holding->holding_code)}} @endif" class="menu-link">
                        <i class="menu-icon tf-icons mdi mdi-office-building-marker"></i>
                        <div style="font-size: 10pt;" data-i18n="Fluid">&nbsp;Master&nbsp;Site</div>
                    </a>
                </li>
                <li class="menu-item
                 @if(Auth::user()->is_admin =='hrd')
                 {{ Request::is('hrd/lokasi*') ? 'active' : '' }}
                 @else
                 {{ Request::is('lokasi*') ? 'active' : '' }}
                 @endif
                 ">
                    <a href="@if(Auth::user()->is_admin =='hrd'){{ url('/hrd/lokasi/'.$holding->holding_code) }} @else {{ url('/lokasi/'.$holding->holding_code)}} @endif" class="menu-link">
                        <i class="menu-icon tf-icons mdi mdi-office-building-marker"></i>
                        <div style="font-size: 10pt;" data-i18n="Fluid">&nbsp;Master&nbsp;Lokasi</div>
                    </a>
                </li>
                <li class="menu-item
                    @if(Auth::user()->is_admin =='hrd')
                    {{ Request::is('hrd/shift*') ? 'active' : '' }}
                    @else
                    {{ Request::is('shift*') ? 'active' : '' }}
                    @endif
                ">
                    <a href=" @if(Auth::user()->is_admin =='hrd'){{ url('hrd/shift/'.$holding->holding_code) }}@else {{ url('/shift/'.$holding->holding_code) }}@endif" class=" menu-link">
                        <div style="font-size: 10pt;" data-i18n="Without navbar"><i class="mdi mdi-timetable"></i>&nbsp;Master Shift</div>
                    </a>
                </li>
                <li class="menu-item
                    @if(Auth::user()->is_admin =='hrd')
                    {{ Request::is('hrd/finger*') ? 'active' : '' }}
                    @else
                    {{ Request::is('finger*') ? 'active' : '' }}
                    @endif
                ">
                    <a href=" @if(Auth::user()->is_admin =='hrd'){{ url('hrd/finger/'.$holding->holding_code) }}@else {{ url('/finger/'.$holding->holding_code) }}@endif" class=" menu-link">
                        <div style="font-size: 10pt;" data-i18n="Without navbar"><i class="mdi mdi-fingerprint"></i>&nbsp;Master Finger</div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- END DATA MASTER -->
        <!-- DATA KARYAWAN -->
        <li class="menu-item
                    @if(Auth::user()->is_admin =='hrd')
                    {{ Request::is('hrd/struktur_organisasi*') ? 'active open' : '' }}
                    @else
                    {{ Request::is('struktur_organisasi*') ? 'active open' : '' }}
                    @endif
                    @if(Auth::user()->is_admin =='hrd')
                    {{ Request::is('hrd/karyawan/tambah-karyawan*') ? 'active open' : '' }}
                    @else
                    {{ Request::is('karyawan/tambah-karyawan*') ? 'active open' : '' }}
                    @endif
                    @if(Auth::user()->is_admin =='hrd')
                    {{ Request::is('hrd/karyawan/detail*') ? 'active open' : '' }}
                    @else
                    {{ Request::is('karyawan/detail*') ? 'active open' : '' }}
                    @endif
                    @if(Auth::user()->is_admin =='hrd')
                    {{ Request::is('hrd/karyawan/karyawan_masa_tenggang_kontrak/'.$holding->holding_code) ? 'active open' : '' }}
                    @else
                    {{ Request::is('karyawan/karyawan_masa_tenggang_kontrak/'.$holding->holding_code) ? 'active open' : '' }}
                    @endif
                    @if(Auth::user()->is_admin =='hrd')
                    {{ Request::is('hrd/karyawan_ingin_bergabung/'.$holding->holding_code) ? 'active open' : '' }}
                    @else
                    {{ Request::is('hrd/karyawan_ingin_bergabung/'.$holding->holding_code) ? 'active open' : '' }}
                    @endif
                    @if(Auth::user()->is_admin =='hrd')
                    {{ Request::is('hrd/karyawan/'.$holding->holding_code) ? 'active open' : '' }}
                    @else
                    {{ Request::is('karyawan/'.$holding->holding_code) ? 'active open' : '' }}
                    @endif
                    @if(Auth::user()->is_admin =='hrd')
                    {{ Request::is('hrd/karyawan/edit-password/*') ? 'active' : '' }}
                    @else
                    {{ Request::is('karyawan/edit-password/*') ? 'active' : '' }}
                    @endif
                    @if(Auth::user()->is_admin =='hrd')
                    {{ Request::is('hrd/users*') ? 'active open' : '' }}
                    @else
                    {{ Request::is('users*') ? 'active open' : '' }}
                    @endif
                    @if(Auth::user()->is_admin =='hrd')
                    {{ Request::is('hrd/karyawan_non_aktif*') ? 'active open' : '' }}
                    @else
                    {{ Request::is('karyawan_non_aktif*') ? 'active open' : '' }}
                    @endif
                    @if(Auth::user()->is_admin =='hrd')
                    {{ Request::is('hrd/karyawan/shift/*') ? 'active open' : '' }}
                    @else
                    {{ Request::is('karyawan/shift/*') ? 'active open' : '' }}
                    @endif
                 ">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi mdi-account-group-outline"></i>
                <div style="font-size: 10pt;" data-i18n="Data Master">Data&nbsp;Karyawan</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item
                        @if(Auth::user()->is_admin =='hrd')
                        {{ Request::is('hrd/karyawan/'.$holding->holding_code) ? 'active' : '' }}
                        {{ Request::is('hrd/karyawan/tambah-karyawan/'.$holding->holding_code) ? 'active' : '' }}
                        {{ Request::is('hrd/karyawan/detail*') ? 'active' : '' }}
                        {{ Request::is('hrd/karyawan/shift*') ? 'active' : '' }}
                        @else
                        {{ Request::is('karyawan/'.$holding->holding_code) ? 'active' : '' }}
                        {{ Request::is('karyawan/tambah-karyawan/'.$holding->holding_code) ? 'active' : '' }}
                        {{ Request::is('karyawan/detail*') ? 'active' : '' }}
                        {{ Request::is('karyawan/shift*') ? 'active' : '' }}
                         @endif
                         ">
                    <a href="@if(Auth::user()->is_admin=='hrd')
                            {{ url('/hrd/karyawan/'.$holding->holding_code) }}
                            @else
                            {{ url('/karyawan/'.$holding->holding_code) }}
                            @endif
                            " class="menu-link">
                        <i class="menu-icon tf-icons mdi mdi-database-outline"></i>
                        <div style="font-size: 10pt;" data-i18n="Data Karyawan">&nbsp;Database&nbsp;Karyawan</div>
                    </a>
                </li>
                <li class="menu-item
                    @if(Auth::user()->is_admin =='hrd')
                    {{ Request::is('hrd/struktur_organisasi*') ? 'active' : '' }}
                    @else
                    {{ Request::is('struktur_organisasi*') ? 'active' : '' }}
                    @endif
                 ">
                    <a href="@if(Auth::user()->is_admin=='hrd')
                    {{ url('/hrd/struktur_organisasi/'.$holding->holding_code) }}
                    @else
                    {{ url('/struktur_organisasi/'.$holding->holding_code) }}
                    @endif" class="menu-link">
                        <i class="menu-icon tf-icons mdi mdi-family-tree"></i>
                        <div style="font-size: 10pt;" data-i18n="Struktur Organisasi">&nbsp;Struktur&nbsp;Organisasi</div>
                    </a>
                </li>

                <li class="menu-item
                        @if(Auth::user()->is_admin =='hrd')
                        {{ Request::is('hrd/users*') ? 'active open' : '' }}
                        {{ Request::is('hrd/karyawan/edit-password/*') ? 'active open' : '' }}
                        @else
                        {{ Request::is('users*') ? 'active open' : '' }}
                        {{ Request::is('karyawan/edit-password/*') ? 'active open' : '' }}
                        @endif
                    ">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons mdi mdi-table-account"></i>
                        <div style="font-size: 10pt;" data-i18n="Data Master">User&nbsp;Karyawan</div>
                    </a>
                    <ul class="menu-sub">

                        <li class="menu-item @if(Auth::user()->is_admin =='hrd'){{ Request::is('hrd/users/*') ? 'active' : '' }}@else {{ Request::is('users/*') ? 'active' : '' }} @endif">
                            <a href="@if(Auth::user()->is_admin=='hrd')
                            {{ url('/hrd/users/'.$holding->holding_code) }}
                            @else
                            {{ url('/users/'.$holding->holding_code) }}
                            @endif" class=" menu-link">
                                <div style="font-size: 10pt;" data-i18n="Without navbar"><i class="mdi mdi-account-clock-outline"></i>&nbsp;Akun&nbsp;Apps</div>
                            </a>
                        </li>
                        <li class="menu-item @if(Auth::user()->is_admin =='hrd'){{ Request::is('hrd/users_finger*') ? 'active' : '' }}@else {{ Request::is('users_finger*') ? 'active' : '' }} @endif">
                            <a href="@if(Auth::user()->is_admin =='hrd'){{ url('hrd/users_finger/'.$holding->holding_code) }}@else  {{ url('/users_finger/'.$holding->holding_code) }} @endif" class=" menu-link">
                                <div style="font-size: 10pt;" data-i18n="Without navbar"><i class="mdi mdi-account-clock-outline"></i>&nbsp;Akun&nbsp;Finger</div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-item
                        @if(Auth::user()->is_admin =='hrd')
                        {{ Request::is('hrd/karyawan_ingin_bergabung*') ? 'active' : '' }}
                         @else
                         {{ Request::is('karyawan_ingin_bergabung*') ? 'active' : '' }}
                         @endif
                        ">
                    <a href="@if(Auth::user()->is_admin=='hrd')
                            {{ url('/hrd/karyawan_ingin_bergabung/'.$holding->holding_code) }}
                            @else
                            {{ url('/karyawan_ingin_bergabung/'.$holding->holding_code) }}
                            @endif
                            " class="menu-link" data-bs-toggle="tooltip" data-bs-placement="right" title="Karyawan Yang Ingin Bergabung">
                        <i class="menu-icon tf-icons mdi mdi-account-clock-outline"></i>
                        <div style="font-size: 10pt;" data-i18n="Karyawan Non Aktif">&nbsp;Karyawan&nbsp;Yang&nbsp;Ingin&nbsp;Bergabung</div>
                    </a>
                </li>
                <li class="menu-item
                        @if(Auth::user()->is_admin =='hrd')
                        {{ Request::is('hrd/karyawan/karyawan_masa_tenggang_kontrak*') ? 'active' : '' }}
                        @else
                        {{ Request::is('karyawan/karyawan_masa_tenggang_kontrak*') ? 'active' : '' }}
                        @endif
                         ">
                    <a href="@if(Auth::user()->is_admin=='hrd')
                            {{ url('/hrd/karyawan/karyawan_masa_tenggang_kontrak/'.$holding->holding_code) }}
                            @else
                            {{ url('/karyawan/karyawan_masa_tenggang_kontrak/'.$holding->holding_code) }}
                            @endif" class="menu-link" data-bs-toggle="tooltip" data-bs-placement="right" title="Karyawan Masa Tenggang Kontrak">
                        <i class="menu-icon tf-icons mdi mdi-account-alert"></i>
                        <div style="font-size: 10pt;" data-i18n="Karyawan Masa Tenggang Kontrak">&nbsp;Karyawan&nbsp;Masa&nbsp;Tenggang&nbsp;Kontrak</div>
                    </a>
                </li>
                <li class="menu-item
                        @if(Auth::user()->is_admin =='hrd')
                        {{ Request::is('hrd/karyawan_non_aktif*') ? 'active' : '' }}
                        @else
                        {{ Request::is('karyawan_non_aktif*') ? 'active' : '' }}
                        @endif
                        ">
                    <a href="@if(Auth::user()->is_admin=='hrd')
                            {{ url('/hrd/karyawan_non_aktif/'.$holding->holding_code) }}
                            @else
                            {{ url('/karyawan_non_aktif/'.$holding->holding_code) }}
                            @endif" class="menu-link" data-bs-toggle="tooltip" data-bs-placement="right" title="Karyawan Non Aktif">
                        <i class="menu-icon tf-icons mdi mdi-account-multiple-remove-outline"></i>
                        <div style="font-size: 10pt;" data-i18n="Karyawan Non Aktif">&nbsp;Karyawan&nbsp;Non&nbsp;Aktif</div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- END DATA KARYAWAN -->
        <!-- ABSENSI KARYAWAN -->
        <li class="menu-item
            @if(Auth::user()->is_admin =='hrd')
            
            {{ Request::is('hrd/karyawan/mapping_shift*') ? 'active open' : '' }}
            @else
            {{ Request::is('shift*') ? 'active open' : '' }}
            {{ Request::is('lokasi-kantor*') ? 'active open' : '' }}
            {{ Request::is('karyawan/mapping_shift*') ? 'active open' : '' }}
            @endif
         ">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi mdi-table-account"></i>
                <div style="font-size: 10pt;" data-i18n="Data Master">Mapping&nbsp;Karyawan</div>
            </a>
            <ul class="menu-sub">

                <li class="menu-item @if(Auth::user()->is_admin =='hrd'){{ Request::is('hrd/karyawan/mapping_shift*') ? 'active' : '' }}@else {{ Request::is('karyawan/mapping_shift*') ? 'active' : '' }} @endif">
                    <a href="@if(Auth::user()->is_admin =='hrd'){{ url('hrd/karyawan/mapping_shift/'.$holding->holding_code) }}@else {{ url('karyawan/mapping_shift/'.$holding->holding_code) }} @endif" class=" menu-link">
                        <div style="font-size: 10pt;" data-i18n="Without navbar"><i class="mdi mdi-account-clock-outline"></i>&nbsp;Mapping&nbsp;Shift</div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- END ABSENSI KARYAWAN -->
        <li class="menu-item {{ Request::is('pg-data-recruitment*') ? 'active open' : '' }}{{ Request::is('pg-data-interview*') ? 'active open' : '' }} {{ Request::is('pg-data-ranking*') ? 'active open' : '' }} {{ Request::is('pg-data-ujian*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi mdi-table-account"></i>
                <div style="font-size: 10pt;" data-i18n="Data Master">Recruitment</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Request::is('pg-data-recruitment*') ? 'active' : '' }}">
                    <a href="{{ url('/pg-data-recruitment/'.$holding->holding_code) }}" class=" menu-link">
                        <div style="font-size: 10pt;" data-i18n="Without navbar"><i class="mdi mdi-account-arrow-left"></i>&nbsp;Data&nbsp;Recruitment</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('pg-data-interview*') ? 'active' : '' }}">
                    <a href="{{ url('/pg-data-interview/'.$holding->holding_code) }}" class="menu-link">
                        <div style="font-size: 10pt;" data-i18n="Container"><i class="mdi mdi-file-chart-check-outline"></i>&nbsp;Data&nbsp;Interview</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('pg-data-ranking*') ? 'active' : '' }}">
                    <a href="{{ url('/pg-data-ranking/'.$holding->holding_code) }}" class="menu-link">
                        <div style="font-size: 10pt;" data-i18n="Container"><i class="mdi mdi-file-chart-check-outline"></i>&nbsp;Data&nbsp;Rangking</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('laporan_recruitment*') ? 'active' : '' }}">
                    <a href="{{ url('/laporan_recruitment/'.$holding->holding_code) }}" class="menu-link">
                        <div style="font-size: 10pt;" data-i18n="Container"><i class="mdi mdi-file-chart-check-outline"></i>&nbsp;Laporan&nbsp;Recruitment</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('pg-data-ujian*') ? 'active' : '' }}">
                    <a href="{{ url('/pg-data-ujian/'.$holding->holding_code) }}" class=" menu-link">
                        <div style="font-size: 10pt;" data-i18n="Without navbar"><i class="mdi mdi-account-arrow-left"></i>&nbsp;Data&nbsp;Ujian</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item {{ Request::is('izin*') ? 'active' : '' }}">
            <a href="{{ url('/izin/'.$holding->holding_code) }}" class=" menu-link">
                <i class="menu-icon tf-icons mdi mdi-calendar-filter-outline"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">Izin</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('cuti*') ? 'active' : '' }}">
            <a href="{{ url('/cuti/'.$holding->holding_code) }}" class=" menu-link">
                <i class="menu-icon tf-icons mdi mdi-clipboard-text-clock-outline"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">Cuti</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('penugasan*') ? 'active' : '' }}">
            <a href="{{ url('/penugasan/'.$holding->holding_code) }}" class=" menu-link">
                <i class="menu-icon tf-icons mdi mdi-airplane-clock"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">Perjalanan Dinas</div>
            </a>
        </li>

        <li class="menu-item {{ Request::is('inventaris*') ? 'active' : '' }}">
            <a href="{{ url('/inventaris/'.$holding->holding_code) }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-archive-outline"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">Data Inventaris</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('access*') ? 'active' : '' }}">
            <a href="{{ url('/access/'.$holding->holding_code) }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-account-key"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">User Level Access</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('report*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi mdi-chart-box-multiple"></i>
                <div style="font-size: 10pt;" data-i18n="Data Master">Report</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Request::is('report*') ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons mdi mdi-table-account"></i>
                        <div style="font-size: 10pt;" data-i18n="Data Master">Absensi</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item {{ Request::is('report_kedisiplinan*') ? 'active' : '' }}">
                            <a href="@if(Auth::user()->is_admin =='hrd'){{ url('/report_kedisiplinan/'.$holding->holding_code) }}@else{{ url('/report_kedisiplinan/'.$holding->holding_code) }}@endif" class="menu-link">
                                <div style="font-size: 10pt;" data-i18n="Container"><i class="mdi mdi-file-chart-check-outline"></i>&nbsp;Absensi&nbsp;&&nbsp;Kedisiplinan</div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-item {{ Request::is('report*') ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons mdi mdi-table-account"></i>
                        <div style="font-size: 10pt;" data-i18n="Data Master">Absensi</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item {{ Request::is('report_kedisiplinan*') ? 'active' : '' }}">
                            <a href="@if(Auth::user()->is_admin =='hrd'){{ url('/report_kedisiplinan/'.$holding->holding_code) }}@else{{ url('/report_kedisiplinan/'.$holding->holding_code) }}@endif" class="menu-link">
                                <div style="font-size: 10pt;" data-i18n="Container"><i class="mdi mdi-file-chart-check-outline"></i>&nbsp;Absensi&nbsp;&&nbsp;Kedisiplinan</div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-item {{ Request::is('pg-data-ranking*') ? 'active' : '' }}">
                    <a href="{{ url('/pg-data-ranking/'.$holding->holding_code) }}" class="menu-link">
                        <div style="font-size: 10pt;" data-i18n="Container"><i class="mdi mdi-file-chart-check-outline"></i>&nbsp;Data&nbsp;Rangking</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('pg-data-ujian*') ? 'active' : '' }}">
                    <a href="{{ url('/pg-data-ujian/'.$holding->holding_code) }}" class=" menu-link">
                        <div style="font-size: 10pt;" data-i18n="Without navbar"><i class="mdi mdi-account-arrow-left"></i>&nbsp;Data&nbsp;Ujian</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-header fw-medium mt-4">
            <span class="menu-header-text">ABSENSI</span>
        </li>
        <!-- Apps -->
        <li class="menu-item {{ Request::is('hrd/absen*') ? 'active' : '' }}">
            <a href="{{ url('/absen') }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">Absen</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('hrd/id-card*') ? 'active' : '' }}">
            <a href="{{ url('/id-card') }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-card-account-details-outline"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">ID Card</div>
                <div class="badge bg-label-primary fs-tiny rounded-pill ms-auto">1</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('hrd/data-absen*') ? 'active' : '' }}">
            <a href="{{ url('/data-absen') }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-table-account"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">Data Absen</div>
                <div class="badge bg-label-primary fs-tiny rounded-pill ms-auto">1</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('hrd/my-absen*') ? 'active' : '' }}">
            <a href="{{ url('/my-absen') }}" target="_blank" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-fingerprint"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">Presensi</div>
                <div class="badge bg-label-primary fs-tiny rounded-pill ms-auto">1</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('hrd/kpi*') ? 'active' : '' }}">
            <a href="{{ url('/kpi') }}" target="_blank" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-chart-line"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">KPI</div>
                <div class="badge bg-label-primary fs-tiny rounded-pill ms-auto">1</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('hrd/slip*') ? 'active' : '' }}">
            <a href="{{ url('/slip') }}" target="_blank" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-credit-card-outline"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">My Slip</div>
                <div class="badge bg-label-primary fs-tiny rounded-pill ms-auto">1</div>
            </a>
        </li>
        <!-- Components -->
        <li class="menu-header fw-medium mt-4"><span class="menu-header-text">Activity Logs</span></li>
        <!-- Cards -->
        <li class="menu-item  {{ Request::is('hrd/activity-logs*') ? 'active' : '' }}">
            <a href="{{ url('/activity-logs/'.$holding->holding_code) }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-history"></i>
                <div style="font-size: 10pt;" data-i18n="Basic">Activity Log</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="{{url('/logout')}}" class="menu-link" style="border: none; background: none;">
                <i class="menu-icon tf-icons mdi mdi-logout"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">Log Out</div>
            </a>
            </a>
        </li>
    </ul>
</aside>