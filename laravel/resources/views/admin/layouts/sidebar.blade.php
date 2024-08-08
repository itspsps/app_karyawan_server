<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{url('dashboard/holding')}}" class="app-brand-link">
            <span class="app-brand-logo demo me-1">
                <span style="color: var(--bs-primary)">
                    @if($holding=='sp')
                    <img src="{{ url('public/holding/assets/img/logosp.png') }}" width="50">
                    @elseif($holding=='sps')
                    <img src="{{ url('public/holding/assets/img/logosps.png') }}" width="50">
                    @else
                    <img src="{{ url('public/holding/assets/img/logosipbaru.png') }}" width="50">
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
        @if($holding=='sp')
        <li class="menu-item {{ Request::is('dashboard*') ? 'active' : '' }}">
            <a href="{{ url('/dashboard/holding/'.$holding) }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                <div style="font-size: 10pt;" data-i18n="Dashboards">Dashboards</div>
            </a>
        </li>
        <li class="menu-header fw-medium mt-4">
            <span class="menu-header-text">MAIN MENU</span>
        </li>
        <li class="menu-item {{ Request::is('karyawan*') ? 'active open' : '' }}{{ Request::is('detail_jabatan*') ? 'active open' : '' }} {{ Request::is('struktur_organisasi*') ? 'active open' : '' }}{{ Request::is('shift*') ? 'active open' : '' }}{{ Request::is('rekap-data*') ? 'active open' : '' }} {{ Request::is('lokasi-kantor*') ? 'active open' : '' }}{{ Request::is('reset-cuti*') ? 'active open' : '' }}{{ Request::is('departemen*') ? 'active open' : '' }}{{ Request::is('divisi*') ? 'active open' : '' }} {{ Request::is('bagian*') ? 'active open' : '' }} {{ Request::is('jabatan*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi mdi-database-outline"></i>
                <div style="font-size: 10pt;" data-i18n="Data Master">Data&nbsp;Master&nbsp;Karyawan</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ Request::is('karyawan*') ? 'active' : '' }}">
                    <a href="{{ url('/karyawan/'.$holding) }}" class="menu-link">
                        <div style="font-size: 10pt;" data-i18n="Data Karyawan"> Master&nbsp;Karyawan</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('struktur_organisasi*') ? 'active' : '' }}">
                    <a href="{{ url('/struktur_organisasi/'.$holding) }}" class="menu-link">
                        <div style="font-size: 10pt;" data-i18n="Struktur Organisasi"> Struktur&nbsp;Organisasi</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('lokasi-kantor*') ? 'active' : '' }}">
                    <a href="{{ url('/lokasi-kantor/'.$holding) }}" class="menu-link">
                        <div style="font-size: 10pt;" data-i18n="Fluid"> Master&nbsp;Lokasi</div>
                    </a>
                </li>
                <!-- <li class="menu-item {{ Request::is('reset-cuti*') ? 'active' : '' }}">
                    <a href="{{ url('/reset-cuti/'.$holding) }}" class="menu-link">
                        <div style="font-size: 10pt;" data-i18n="Blank">Reset Cuti</div>
                    </a>
                </li> -->
                <li class="menu-item {{ Request::is('departemen*') ? 'active' : '' }}">
                    <a href="{{ url('/departemen/'.$holding) }}" class="menu-link">
                        <div style="font-size: 10pt;" data-i18n="Blank"> Master&nbsp;Departmen</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('divisi*') ? 'active' : '' }}">
                    <a href="{{ url('/divisi/'.$holding) }}" class="menu-link">
                        <div style="font-size: 10pt;" data-i18n="Blank"> Master&nbsp;Divisi</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('bagian*') ? 'active' : '' }}">
                    <a href="{{ url('/bagian/'.$holding) }}" class="menu-link">
                        <div style="font-size: 10pt;" data-i18n="Blank"> Master&nbsp;Bagian</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('jabatan*') ? 'active' : '' }} {{ Request::is('detail_jabatan*') ? 'active' : '' }}">
                    <a href="{{ url('/jabatan/'.$holding) }}" class="menu-link">
                        <div style="font-size: 10pt;" data-i18n="Blank">Master&nbsp;Jabatan</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item {{ Request::is('shift*') ? 'active open' : '' }}{{ Request::is('rekap-data*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi mdi-table-account"></i>
                <div style="font-size: 10pt;" data-i18n="Data Master">Absensi&nbsp;Karyawan</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Request::is('shift*') ? 'active' : '' }}">
                    <a href="{{ url('/shift/'.$holding) }}" class=" menu-link">
                        <div style="font-size: 10pt;" data-i18n="Without navbar"><i class="mdi mdi-timetable"></i>&nbsp;Master Shift</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('rekap-data*') ? 'active' : '' }}">
                    <a href="{{ url('/rekap-data/'.$holding) }}" class="menu-link">
                        <div style="font-size: 10pt;" data-i18n="Container"><i class="mdi mdi-file-chart-check-outline"></i>&nbsp;Rekap Data Absensi</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item {{ Request::is('izin*') ? 'active' : '' }}">
            <a href="{{ url('/izin/'.$holding) }}" class=" menu-link">
                <i class="menu-icon tf-icons mdi mdi-calendar-filter-outline"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">Izin</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('cuti*') ? 'active' : '' }}">
            <a href="{{ url('/cuti/'.$holding) }}" class=" menu-link">
                <i class="menu-icon tf-icons mdi mdi-clipboard-text-clock-outline"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">Cuti</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('penugasan*') ? 'active' : '' }}">
            <a href="{{ url('/penugasan/'.$holding) }}" class=" menu-link">
                <i class="menu-icon tf-icons mdi mdi-airplane-clock"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">Perjalanan Dinas</div>
            </a>
        </li>

        <li class="menu-item {{ Request::is('inventaris*') ? 'active' : '' }}">
            <a href="{{ url('/inventaris/'.$holding) }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-archive-outline"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">Data Inventaris</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('access*') ? 'active' : '' }}">
            <a href="{{ url('/access/'.$holding) }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-account-key"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">User Access</div>
            </a>
        </li>
        @elseif($holding=='sps')
        <li class="menu-item {{ Request::is('dashboard*') ? 'active' : '' }}">
            <a href="{{ url('/dashboard/holding/'.$holding) }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                <div style="font-size: 10pt;" data-i18n="Dashboards">Dashboards</div>
            </a>
        </li>
        <li class="menu-header fw-medium mt-4">
            <span class="menu-header-text">MAIN MENU</span>
        </li>
        <li class="menu-item {{ Request::is('karyawan*') ? 'active open' : '' }}{{ Request::is('detail_jabatan*') ? 'active open' : '' }} {{ Request::is('struktur_organisasi*') ? 'active open' : '' }}{{ Request::is('shift*') ? 'active open' : '' }}{{ Request::is('rekap-data*') ? 'active open' : '' }} {{ Request::is('lokasi-kantor*') ? 'active open' : '' }}{{ Request::is('reset-cuti*') ? 'active open' : '' }}{{ Request::is('departemen*') ? 'active open' : '' }}{{ Request::is('divisi*') ? 'active open' : '' }} {{ Request::is('bagian*') ? 'active open' : '' }} {{ Request::is('jabatan*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi mdi-database-outline"></i>
                <div style="font-size: 10pt;" data-i18n="Data Master">Data&nbsp;Master&nbsp;Karyawan</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ Request::is('karyawan*') ? 'active' : '' }}">
                    <a href="{{ url('/karyawan/'.$holding) }}" class="menu-link">
                        <div style="font-size: 10pt;" data-i18n="Data Karyawan"> Master&nbsp;Karyawan</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('struktur_organisasi*') ? 'active' : '' }}">
                    <a href="{{ url('/struktur_organisasi/'.$holding) }}" class="menu-link">
                        <div style="font-size: 10pt;" data-i18n="Struktur Organisasi"> Struktur&nbsp;Organisasi</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('lokasi-kantor*') ? 'active' : '' }}">
                    <a href="{{ url('/lokasi-kantor/'.$holding) }}" class="menu-link">
                        <div style="font-size: 10pt;" data-i18n="Fluid"> Master&nbsp;Lokasi</div>
                    </a>
                </li>
                <!-- <li class="menu-item {{ Request::is('reset-cuti*') ? 'active' : '' }}">
                    <a href="{{ url('/reset-cuti/'.$holding) }}" class="menu-link">
                        <div style="font-size: 10pt;" data-i18n="Blank">Reset Cuti</div>
                    </a>
                </li> -->
                <li class="menu-item {{ Request::is('departemen*') ? 'active' : '' }}">
                    <a href="{{ url('/departemen/'.$holding) }}" class="menu-link">
                        <div style="font-size: 10pt;" data-i18n="Blank"> Master&nbsp;Departmen</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('divisi*') ? 'active' : '' }}">
                    <a href="{{ url('/divisi/'.$holding) }}" class="menu-link">
                        <div style="font-size: 10pt;" data-i18n="Blank"> Master&nbsp;Divisi</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('bagian*') ? 'active' : '' }}">
                    <a href="{{ url('/bagian/'.$holding) }}" class="menu-link">
                        <div style="font-size: 10pt;" data-i18n="Blank"> Master&nbsp;Bagian</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('jabatan*') ? 'active' : '' }} {{ Request::is('detail_jabatan*') ? 'active' : '' }}">
                    <a href="{{ url('/jabatan/'.$holding) }}" class="menu-link">
                        <div style="font-size: 10pt;" data-i18n="Blank">Master&nbsp;Jabatan</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item {{ Request::is('shift*') ? 'active open' : '' }}{{ Request::is('rekap-data*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi mdi-table-account"></i>
                <div style="font-size: 10pt;" data-i18n="Data Master">Absensi&nbsp;Karyawan</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Request::is('shift*') ? 'active' : '' }}">
                    <a href="{{ url('/shift/'.$holding) }}" class=" menu-link">
                        <div style="font-size: 10pt;" data-i18n="Without navbar"><i class="mdi mdi-timetable"></i>&nbsp;Master Shift</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('rekap-data*') ? 'active' : '' }}">
                    <a href="{{ url('/rekap-data/'.$holding) }}" class="menu-link">
                        <div style="font-size: 10pt;" data-i18n="Container"><i class="mdi mdi-file-chart-check-outline"></i>&nbsp;Rekap Data Absensi</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item {{ Request::is('izin*') ? 'active' : '' }}">
            <a href="{{ url('/izin/'.$holding) }}" class=" menu-link">
                <i class="menu-icon tf-icons mdi mdi-calendar-filter-outline"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">Izin</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('cuti*') ? 'active' : '' }}">
            <a href="{{ url('/cuti/'.$holding) }}" class=" menu-link">
                <i class="menu-icon tf-icons mdi mdi-clipboard-text-clock-outline"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">Cuti</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('penugasan*') ? 'active' : '' }}">
            <a href="{{ url('/penugasan/'.$holding) }}" class=" menu-link">
                <i class="menu-icon tf-icons mdi mdi-airplane-clock"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">Perjalanan Dinas</div>
            </a>
        </li>

        <li class="menu-item {{ Request::is('inventaris*') ? 'active' : '' }}">
            <a href="{{ url('/inventaris/'.$holding) }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-archive-outline"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">Data Inventaris</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('access*') ? 'active' : '' }}">
            <a href="{{ url('/access/'.$holding) }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-account-key"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">User Access</div>
            </a>
        </li>
        @else
        < <li class="menu-item {{ Request::is('dashboard*') ? 'active' : '' }}">
            <a href="{{ url('/dashboard/holding/'.$holding) }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                <div style="font-size: 10pt;" data-i18n="Dashboards">Dashboards</div>
            </a>
            </li>
            <li class="menu-item {{ Request::is('karyawan*') ? 'active open' : '' }}{{ Request::is('detail_jabatan*') ? 'active open' : '' }} {{ Request::is('struktur_organisasi*') ? 'active open' : '' }}{{ Request::is('shift*') ? 'active open' : '' }}{{ Request::is('rekap-data*') ? 'active open' : '' }} {{ Request::is('lokasi-kantor*') ? 'active open' : '' }}{{ Request::is('reset-cuti*') ? 'active open' : '' }}{{ Request::is('departemen*') ? 'active open' : '' }}{{ Request::is('divisi*') ? 'active open' : '' }} {{ Request::is('bagian*') ? 'active open' : '' }} {{ Request::is('jabatan*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-database-outline"></i>
                    <div style="font-size: 10pt;" data-i18n="Data Master">Data&nbsp;Master&nbsp;Karyawan</div>
                </a>

                <ul class="menu-sub">
                    <li class="menu-item {{ Request::is('karyawan*') ? 'active' : '' }}">
                        <a href="{{ url('/karyawan/'.$holding) }}" class="menu-link">
                            <div style="font-size: 10pt;" data-i18n="Data Karyawan"> Master&nbsp;Karyawan</div>
                        </a>
                    </li>
                    <li class="menu-item {{ Request::is('struktur_organisasi*') ? 'active' : '' }}">
                        <a href="{{ url('/struktur_organisasi/'.$holding) }}" class="menu-link">
                            <div style="font-size: 10pt;" data-i18n="Struktur Organisasi"> Struktur&nbsp;Organisasi</div>
                        </a>
                    </li>
                    <li class="menu-item {{ Request::is('lokasi-kantor*') ? 'active' : '' }}">
                        <a href="{{ url('/lokasi-kantor/'.$holding) }}" class="menu-link">
                            <div style="font-size: 10pt;" data-i18n="Fluid"> Master&nbsp;Lokasi</div>
                        </a>
                    </li>
                    <!-- <li class="menu-item {{ Request::is('reset-cuti*') ? 'active' : '' }}">
                    <a href="{{ url('/reset-cuti/'.$holding) }}" class="menu-link">
                        <div style="font-size: 10pt;" data-i18n="Blank">Reset Cuti</div>
                    </a>
                </li> -->
                    <li class="menu-item {{ Request::is('departemen*') ? 'active' : '' }}">
                        <a href="{{ url('/departemen/'.$holding) }}" class="menu-link">
                            <div style="font-size: 10pt;" data-i18n="Blank"> Master&nbsp;Departmen</div>
                        </a>
                    </li>
                    <li class="menu-item {{ Request::is('divisi*') ? 'active' : '' }}">
                        <a href="{{ url('/divisi/'.$holding) }}" class="menu-link">
                            <div style="font-size: 10pt;" data-i18n="Blank"> Master&nbsp;Divisi</div>
                        </a>
                    </li>
                    <li class="menu-item {{ Request::is('bagian*') ? 'active' : '' }}">
                        <a href="{{ url('/bagian/'.$holding) }}" class="menu-link">
                            <div style="font-size: 10pt;" data-i18n="Blank"> Master&nbsp;Bagian</div>
                        </a>
                    </li>
                    <li class="menu-item {{ Request::is('jabatan*') ? 'active' : '' }} {{ Request::is('detail_jabatan*') ? 'active' : '' }}">
                        <a href="{{ url('/jabatan/'.$holding) }}" class="menu-link">
                            <div style="font-size: 10pt;" data-i18n="Blank">Master&nbsp;Jabatan</div>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="menu-header fw-medium mt-4">
                <span class="menu-header-text">ABSENSI</span>
            </li>
            <li class="menu-item {{ Request::is('shift*') ? 'active' : '' }}">
                <a href="{{ url('/shift/'.$holding) }}" class=" menu-link">
                    <div style="font-size: 10pt;" data-i18n="Without navbar"><i class="mdi mdi-timetable"></i>&nbsp;Master Shift</div>
                </a>
            </li>
            <li class="menu-item {{ Request::is('rekap-data*') ? 'active' : '' }}">
                <a href="{{ url('/rekap-data/'.$holding) }}" class="menu-link">
                    <div style="font-size: 10pt;" data-i18n="Container"><i class="mdi mdi-file-chart-check-outline"></i>&nbsp;Rekap Data Absensi</div>
                </a>
            </li>
            <li class="menu-header fw-medium mt-4">
                <span class="menu-header-text">IZIN</span>
            </li>
            <li class="menu-item {{ Request::is('izin*') ? 'active' : '' }}">
                <a href="{{ url('/izin/'.$holding) }}" class=" menu-link">
                    <div style="font-size: 10pt;" data-i18n="Without navbar"><i class="mdi mdi-calendar-filter-outline"></i> Izin</div>
                </a>
            </li>

            <li class="menu-header fw-medium mt-4">
                <span class="menu-header-text">INVENTARIS</span>
            </li>
            <li class="menu-item {{ Request::is('inventaris*') ? 'active' : '' }}">
                <a href="{{ url('/inventaris/'.$holding) }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-archive-outline"></i>
                    <div style="font-size: 10pt;" data-i18n="Blank">Data Inventaris</div>
                </a>
            </li>
            <li class="menu-header fw-medium mt-4">
                <span class="menu-header-text">ACCESS</span>
            </li>
            <li class="menu-item {{ Request::is('access*') ? 'active' : '' }}">
                <a href="{{ url('/access/'.$holding) }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-account-key"></i>
                    <div style="font-size: 10pt;" data-i18n="Blank">User Access</div>
                </a>
            </li>
            @endif


            <li class="menu-header fw-medium mt-4">
                <span class="menu-header-text">ABSENSI</span>
            </li>
            <!-- Apps -->
            <li class="menu-item {{ Request::is('absen*') ? 'active' : '' }}">
                <a href="{{ url('/absen') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                    <div style="font-size: 10pt;" data-i18n="Blank">Absen</div>
                </a>
            </li>
            <li class="menu-item {{ Request::is('id-card*') ? 'active' : '' }}">
                <a href="{{ url('/id-card') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-card-account-details-outline"></i>
                    <div style="font-size: 10pt;" data-i18n="Blank">ID Card</div>
                    <div class="badge bg-label-primary fs-tiny rounded-pill ms-auto">1</div>
                </a>
            </li>
            <li class="menu-item {{ Request::is('data-absen*') ? 'active' : '' }}">
                <a href="{{ url('/data-absen') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-table-account"></i>
                    <div style="font-size: 10pt;" data-i18n="Blank">Data Absen</div>
                    <div class="badge bg-label-primary fs-tiny rounded-pill ms-auto">1</div>
                </a>
            </li>
            <li class="menu-item {{ Request::is('my-absen*') ? 'active' : '' }}">
                <a href="{{ url('/my-absen') }}" target="_blank" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-fingerprint"></i>
                    <div style="font-size: 10pt;" data-i18n="Blank">Presensi</div>
                    <div class="badge bg-label-primary fs-tiny rounded-pill ms-auto">1</div>
                </a>
            </li>
            <li class="menu-item {{ Request::is('kpi*') ? 'active' : '' }}">
                <a href="{{ url('/kpi') }}" target="_blank" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-chart-line"></i>
                    <div style="font-size: 10pt;" data-i18n="Blank">KPI</div>
                    <div class="badge bg-label-primary fs-tiny rounded-pill ms-auto">1</div>
                </a>
            </li>
            <li class="menu-item {{ Request::is('slip*') ? 'active' : '' }}">
                <a href="{{ url('/slip') }}" target="_blank" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-credit-card-outline"></i>
                    <div style="font-size: 10pt;" data-i18n="Blank">My Slip</div>
                    <div class="badge bg-label-primary fs-tiny rounded-pill ms-auto">1</div>
                </a>
            </li>
            <!-- Components -->
            <li class="menu-header fw-medium mt-4"><span class="menu-header-text">Activity Logs</span></li>
            <!-- Cards -->
            <li class="menu-item  {{ Request::is('activity-logs*') ? 'active' : '' }}">
                <a href="{{ url('/activity-logs/'.$holding) }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-history"></i>
                    <div style="font-size: 10pt;" data-i18n="Basic">Activity Log</div>
                </a>
            </li>
            <li class="menu-item">
                <form action="{{url('/logout')}}" method="post">
                    @csrf
                    <button type="submit" class="menu-link" style="border: none; background: none;">
                        <i class="menu-icon tf-icons mdi mdi-logout"></i>
                        <div style="font-size: 10pt;" data-i18n="Blank">Log Out</div>
                    </button>
                </form>
                </a>
            </li>
    </ul>
</aside>