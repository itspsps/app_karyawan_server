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
                    @if($holding=='sp')
                    <img src="{{ asset('holding/assets/img/logosp.png') }}" width="50">
                    @elseif($holding=='sps')
                    <img src="{{ asset('holding/assets/img/logosps.png') }}" width="50">
                    @else
                    <img src="{{ asset('holding/assets/img/logosipbaru.png') }}" width="50">
                    @endif
                </span>
            </span>
            @if($holding=='sp')
            <span class="app-brand-text demo menu-text fw-semibold ms-2">CV. SP</span>
            @elseif($holding=='sps')
            <span class="app-brand-text demo menu-text fw-semibold ms-2">PT. SPS</span>
            @else
            <span class="app-brand-text demo menu-text fw-semibold ms-2">CV. SIP</span>
            @endif
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
        {{ Request::is('hrd/dashboard*') ? 'active' : '' }} @else {{ Request::is('hrd/dashboard*') ? 'active' : '' }} @endif">
            <a href="@if(Auth::user()->is_admin=='hrd')
            {{ url('/hrd/dashboard/holding/'.$holding) }}
            @else
            {{ url('/dashboard/holding/'.$holding) }}
        @endif 
            " class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                <div style="font-size: 10pt;" data-i18n="Dashboards">Dashboards</div>
            </a>
        </li>
        <li class="menu-header fw-medium mt-4">
            <span class="menu-header-text">MAIN MENU</span>
        </li>
        <li class="menu-item 
        @if(Auth::user()->is_admin =='hrd'){{ Request::is('hrd/karyawan/tambah-karyawan*') ? 'active open' : '' }} 
        @else 
        {{ Request::is('karyawan/tambah-karyawan*') ? 'active open' : '' }}
        @endif 
        @if(Auth::user()->is_admin =='hrd'){{ Request::is('hrd/karyawan/detail*') ? 'active open' : '' }} 
        @else 
        {{ Request::is('karyawan/detail*') ? 'active open' : '' }}
        @endif
        @if(Auth::user()->is_admin =='hrd'){{ Request::is('hrd/karyawan/karyawan_masa_tenggang_kontrak/'.$holding) ? 'active open' : '' }}
        @else 
        {{ Request::is('karyawan/karyawan_masa_tenggang_kontrak/'.$holding) ? 'active open' : '' }}
        @endif 
        @if(Auth::user()->is_admin =='hrd'){{ Request::is('hrd/karyawan_ingin_bergabung/'.$holding) ? 'active open' : '' }} 
        @else 
        {{ Request::is('karyawan_ingin_bergabung/'.$holding) ? 'active open' : '' }}
        @endif 
        @if(Auth::user()->is_admin =='hrd'){{ Request::is('hrd/karyawan/'.$holding) ? 'active open' : '' }} 
        @else 
        {{ Request::is('karyawan/'.$holding) ? 'active open' : '' }}
        @endif 
        @if(Auth::user()->is_admin =='hrd'){{ Request::is('hrd/users*') ? 'active open' : '' }} 
        @else 
        {{ Request::is('users*') ? 'active open' : '' }}
        @endif 
        @if(Auth::user()->is_admin =='hrd'){{ Request::is('hrd/karyawan_non_aktif*') ? 'active open' : '' }} 
        @else 
        {{ Request::is('karyawan_non_aktif*') ? 'active open' : '' }}
        @endif
        @if(Auth::user()->is_admin =='hrd'){{ Request::is('hrd/detail_jabatan*') ? 'active open' : '' }} 
        @else 
        {{ Request::is('detail_jabatan*') ? 'active open' : '' }}
        @endif
        @if(Auth::user()->is_admin =='hrd'){{ Request::is('hrd/struktur_organisasi*') ? 'active open' : '' }} 
        @else 
        {{ Request::is('detail_jabatan*') ? 'active open' : '' }}
        @endif
        @if(Auth::user()->is_admin =='hrd'){{ Request::is('hrd/reset-cuti*') ? 'active open' : '' }} 
        @else 
        {{ Request::is('reset-cuti*') ? 'active open' : '' }}
        @endif
        @if(Auth::user()->is_admin =='hrd'){{ Request::is('hrd/departemen*') ? 'active open' : '' }} 
        @else 
        {{ Request::is('departemen*') ? 'active open' : '' }} 
        @endif
        @if(Auth::user()->is_admin =='hrd'){{ Request::is('hrd/divisi*') ? 'active open' : '' }} 
        @else 
        {{ Request::is('divisi*') ? 'active open' : '' }}
        @endif 
        @if(Auth::user()->is_admin =='hrd'){{ Request::is('hrd/bagian*') ? 'active open' : '' }} 
        @else 
        {{ Request::is('bagian*') ? 'active open' : '' }}
        @endif 
        @if(Auth::user()->is_admin =='hrd'){{ Request::is('hrd/jabatan*') ? 'active open' : '' }} 
        @else 
        {{ Request::is('jabatan*') ? 'active open' : '' }}
        @endif">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi mdi-database-outline"></i>
                <div style="font-size: 10pt;" data-i18n="Data Master">Data&nbsp;Master&nbsp;Karyawan</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item 
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
                {{ Request::is('hrd/karyawan/karyawan_masa_tenggang_kontrak/'.$holding) ? 'active open' : '' }}
                 @else 
                {{ Request::is('karyawan/karyawan_masa_tenggang_kontrak/'.$holding) ? 'active open' : '' }}
                @endif 
                @if(Auth::user()->is_admin =='hrd')
                {{ Request::is('hrd/karyawan_ingin_bergabung/'.$holding) ? 'active open' : '' }} 
                 @else 
                 {{ Request::is('hrd/karyawan_ingin_bergabung/'.$holding) ? 'active open' : '' }} 
                @endif 
                @if(Auth::user()->is_admin =='hrd')
                {{ Request::is('hrd/karyawan/'.$holding) ? 'active open' : '' }} 
                @else 
                {{ Request::is('karyawan/'.$holding) ? 'active open' : '' }} 
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
                  @endif">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons mdi mdi-account-group-outline"></i>
                        <div style="font-size: 10pt;" data-i18n="Data Master">&nbsp;Karyawan</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item 
                        @if(Auth::user()->is_admin =='hrd')
                        {{ Request::is('hrd/karyawan/'.$holding) ? 'active' : '' }} 
                        {{ Request::is('hrd/karyawan/tambah-karyawan/'.$holding) ? 'active' : '' }} 
                        {{ Request::is('hrd/karyawan/detail*') ? 'active' : '' }} 
                        {{ Request::is('hrd/karyawan/shift*') ? 'active' : '' }}
                        @lse 
                        {{ Request::is('karyawan/'.$holding) ? 'active' : '' }} 
                        {{ Request::is('karyawan/tambah-karyawan/'.$holding) ? 'active' : '' }} 
                        {{ Request::is('karyawan/detail*') ? 'active' : '' }} 
                        {{ Request::is('karyawan/shift*') ? 'active' : '' }}
                         @endif
                         ">
                            <a href="@if(Auth::user()->is_admin=='hrd')
                            {{ url('/hrd/karyawan/'.$holding) }}
                            @else
                            {{ url('/karyawan/'.$holding) }}
                            @endif 
                            " class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-database-outline"></i>
                                <div style="font-size: 10pt;" data-i18n="Data Karyawan">&nbsp;Database&nbsp;Karyawan</div>
                            </a>
                        </li>
                        <li class="menu-item 
                        @if(Auth::user()->is_admin =='hrd'){{ Request::is('hrd/users*') ? 'active' : '' }} 
                       {{ Request::is('hrd/karyawan/edit-password/*') ? 'active' : '' }}
                       @else
                       {{ Request::is('karyawan/edit-password/*') ? 'active' : '' }}
                       @endif 
                       ">
                            <a href="@if(Auth::user()->is_admin=='hrd')
                            {{ url('/hrd/users/'.$holding) }}
                            @else
                            {{ url('/users/'.$holding) }}
                            @endif 
                            " class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-account-key"></i>
                                <div style="font-size: 10pt;" data-i18n="Users">&nbsp;User Karyawan&nbsp;</div>
                            </a>
                        </li>
                        <li class="menu-item 
                        @if(Auth::user()->is_admin =='hrd')
                        {{ Request::is('hrd/karyawan_ingin_bergabung*') ? 'active' : '' }}
                         @else
                         {{ Request::is('karyawan_ingin_bergabung*') ? 'active' : '' }}
                         @endif
                        ">
                            <a href="@if(Auth::user()->is_admin=='hrd')
                            {{ url('/hrd/karyawan_ingin_bergabung/'.$holding) }}
                            @else
                            {{ url('/karyawan_ingin_bergabung/'.$holding) }}
                            @endif 
                            " class="menu-link">
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
                            {{ url('/hrd/karyawan/karyawan_masa_tenggang_kontrak/'.$holding) }}
                            @else
                            {{ url('/karyawan/karyawan_masa_tenggang_kontrak/'.$holding) }}
                            @endif" class="menu-link">
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
                            {{ url('/hrd/karyawan_non_aktif/'.$holding) }}
                            @else
                            {{ url('/karyawan_non_aktif/'.$holding) }}
                            @endif" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-account-multiple-remove-outline"></i>
                                <div style="font-size: 10pt;" data-i18n="Karyawan Non Aktif">&nbsp;Karyawan&nbsp;Non&nbsp;Aktif</div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-item 
                @if(Auth::user()->is_admin =='hrd')
                {{ Request::is('hrd/struktur_organisasi*') ? 'active' : '' }}
                @else
                {{ Request::is('struktur_organisasi*') ? 'active' : '' }}
                 @endif
                 ">
                    <a href="@if(Auth::user()->is_admin=='hrd')
                    {{ url('/hrd/struktur_organisasi/'.$holding) }}
                    @else
                    {{ url('/struktur_organisasi/'.$holding) }}
                    @endif" class="menu-link">
                        <i class="menu-icon tf-icons mdi mdi-family-tree"></i>
                        <div style="font-size: 10pt;" data-i18n="Struktur Organisasi">&nbsp;Struktur&nbsp;Organisasi</div>
                    </a>
                </li>
                <li class="menu-item 
                @if(Auth::user()->is_admin =='hrd')
                {{ Request::is('hrd/departemen*') ? 'active' : '' }}
                @else
                {{ Request::is('departemen*') ? 'active' : '' }}
                 @endif
                 ">
                    <a href="@if(Auth::user()->is_admin=='hrd')
                    {{ url('/hrd/departemen/'.$holding) }}
                    @else
                    {{ url('/departemen/'.$holding) }}
                            @endif" class="menu-link">
                        <i class="menu-icon tf-icons mdi mdi-database-cog-outline"></i>
                        <div style="font-size: 10pt;" data-i18n="Blank">&nbsp;Master&nbsp;Departmen</div>
                    </a>
                </li>
                <li class="menu-item 
                @if(Auth::user()->is_admin =='hrd')
                {{ Request::is('hrd/divisi*') ? 'active' : '' }}
                @else
                {{ Request::is('divisi*') ? 'active' : '' }}
                 @endif">
                    <a href="@if(Auth::user()->is_admin=='hrd')
                    {{ url('/hrd/divisi/'.$holding) }}
                    @else
                    {{ url('/divisi/'.$holding) }}
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
                    {{ url('/hrd/bagian/'.$holding) }}
                    @else
                    {{ url('/bagian/'.$holding) }}
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
                    {{ url('/hrd/jabatan/'.$holding) }}
                    @else
                    {{ url('/jabatan/'.$holding) }}
                            @endif" class="menu-link">
                        <i class="menu-icon tf-icons mdi mdi-database-cog-outline"></i>
                        <div style="font-size: 10pt;" data-i18n="Blank">&nbsp;Master&nbsp;Jabatan</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item 
        {{ Request::is('shift*') ? 'active open' : '' }}
        {{ Request::is('lokasi-kantor*') ? 'active open' : '' }}
        {{ Request::is('rekap-data*') ? 'active open' : '' }} 
        {{ Request::is('karyawan/mapping_shift*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi mdi-table-account"></i>
                <div style="font-size: 10pt;" data-i18n="Data Master">Absensi&nbsp;Karyawan</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item 
                {{ Request::is('lokasi-kantor*') ? 'active' : '' }}
                 
                 ">
                    <a href="{{ url('/lokasi-kantor/'.$holding) }}" class="menu-link">
                        <i class="menu-icon tf-icons mdi mdi-database-marker-outline"></i>
                        <div style="font-size: 10pt;" data-i18n="Fluid">&nbsp;Master&nbsp;Lokasi</div>
                    </a>
                </li>
                <li class="menu-item 
                {{ Request::is('shift*') ? 'active' : '' }}
                ">
                    <a href="{{ url('/shift/'.$holding) }}" class=" menu-link">
                        <div style="font-size: 10pt;" data-i18n="Without navbar"><i class="mdi mdi-timetable"></i>&nbsp;Master Shift</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('karyawan/mapping_shift*') ? 'active' : '' }}">
                    <a href="{{ url('/karyawan/mapping_shift/'.$holding) }}" class=" menu-link">
                        <div style="font-size: 10pt;" data-i18n="Without navbar"><i class="mdi mdi-account-clock-outline"></i>&nbsp;Master Shift</div>
                    </a>
                </li>
                <li class="menu-item  {{ Request::is('rekap-data*') ? 'active' : '' }}">
                    <a href="{{ url('/rekap-data/'.$holding) }}" class="menu-link">
                        <div style="font-size: 10pt;" data-i18n="Container"><i class="mdi mdi-file-chart-check-outline"></i>&nbsp;Rekap Data Absensi</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item {{ Request::is('hrd/izin*') ? 'active' : '' }}">
            <a href="{{ url('/izin/'.$holding) }}" class=" menu-link">
                <i class="menu-icon tf-icons mdi mdi-calendar-filter-outline"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">Izin</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('hrd/cuti*') ? 'active' : '' }}">
            <a href="{{ url('/cuti/'.$holding) }}" class=" menu-link">
                <i class="menu-icon tf-icons mdi mdi-clipboard-text-clock-outline"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">Cuti</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('hrd/penugasan*') ? 'active' : '' }}">
            <a href="{{ url('/penugasan/'.$holding) }}" class=" menu-link">
                <i class="menu-icon tf-icons mdi mdi-airplane-clock"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">Perjalanan Dinas</div>
            </a>
        </li>

        <li class="menu-item {{ Request::is('hrd/inventaris*') ? 'active' : '' }}">
            <a href="{{ url('/inventaris/'.$holding) }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-archive-outline"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">Data Inventaris</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('hrd/access*') ? 'active' : '' }}">
            <a href="{{ url('/access/'.$holding) }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-account-key"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">User Level Access</div>
            </a>
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
            <a href="{{ url('/activity-logs/'.$holding) }}" class="menu-link">
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