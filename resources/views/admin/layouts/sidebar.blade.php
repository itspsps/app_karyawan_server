<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="@if (Auth::user()->is_admin == 'hrd') {{ url('hrd/dashboard/holding') }}
        @else
        {{ url('dashboard/holding') }} @endif
        "
            class="app-brand-link">
            <span class="app-brand-logo demo me-1">
                <span style="color: var(--bs-primary)">
                    <img src="{{ asset('holding/assets/img/' . $holding->holding_image) }}" width="50">
                </span>
            </span>
            <span
                class="app-brand-text demo menu-text fw-semibold ms-2">{{ $holding == '' ? '' : $holding->holding_name_hint }}</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="mdi menu-toggle-icon d-xl-block align-middle mdi-20px"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li
            class="menu-item
        @if (Auth::user()->is_admin == 'hrd') {{ Request::is('hrd/dashboard*') ? 'active' : '' }}
        @else {{ Request::is('dashboard*') ? 'active' : '' }} @endif">
            <a href="@if (Auth::user()->is_admin == 'hrd') {{ url('/hrd/dashboard/option/' . $holding->holding_code) }}
            @else
            {{ url('/dashboard/option/' . $holding->holding_code) }} @endif
            "
                class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                <div style="font-size: 10pt;" data-i18n="Dashboards">Dashboards</div>
            </a>
        </li>
        <li class="menu-header fw-medium mt-4">
            <span class="menu-header-text">MAIN MENU</span>
        </li>
        @foreach($menus as $menu)
        <li class="menu-item  @foreach($menu->children as $child) {{ Request::is(($child->url ?? '') . '*') ? 'active open' : '' }} @endforeach">
            @if($menu->children->count() > 0)
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi {{ $menu->icon }}"></i>
                <div style="font-size: 10pt;">&nbsp;{{ $menu->name }}</div>
            </a>

            <ul class="menu-sub">
                @foreach($menu->children as $child)
                <li class="menu-item {{ Request::is(($child->url ?? '') . '/'.$holding->holding_code) ? 'active open' : '' }}">
                    @if($child->subchildren->count() > 0)
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons mdi {{ $child->icon }}"></i>
                        <div style="font-size: 10pt;">&nbsp;{{ $child->name }}</div>
                    </a>

                    <ul class="menu-sub">
                        @foreach($child->subchildren as $subchild)
                        <li class="menu-item {{ Request::is(($subchild->url ?? '') . '/'.$holding->holding_code) ? 'active' : '' }}">
                            <a href="{{ $subchild->url ? url($subchild->url, $holding->holding_code) : '#' }}"
                                class="menu-link">
                                <i class="menu-icon tf-icons mdi {{ $subchild->icon }}"></i>
                                <div style="font-size: 10pt;">&nbsp;{{ $subchild->name }}</div>
                            </a>
                            @endforeach
                        </li>
                    </ul>
                    @else
                    <a href="{{ $child->url ? url($child->url, $holding->holding_code) : '#' }}"
                        class="menu-link" data-bs-toggle="tooltip" data-bs-placement="right"
                        title="{{$child->name}}">
                        <i class="menu-icon tf-icons mdi {{ $child->icon }}"></i>
                        <div style="font-size: 10pt;" data-i18n="{{ $child->name }}">
                            &nbsp;{{ $child->name }}
                        </div>
                    </a>
                    @endif

                </li>
                @endforeach
            </ul>
            @else
            {{-- Menu tanpa submenu --}}
            <a href="{{ $menu->url ? url($menu->url, $holding->holding_code) : '#' }}"
                class="menu-link" data-bs-toggle="tooltip" data-bs-placement="right"
                title="{{ $menu->name }}">
                <i class="menu-icon tf-icons mdi {{ $menu->icon }}"></i>
                <div style="font-size: 10pt;" data-i18n="{{ $menu->name }}">
                    {{ $menu->name }}
                </div>
            </a>
            @endif
        </li>
        @endforeach
        <!-- ABSENSI KARYAWAN -->


        <li class="menu-item {{ Request::is('izin*') ? 'active' : '' }}">
            <a href="{{ url('/izin/' . $holding->holding_code) }}" class=" menu-link">
                <i class="menu-icon tf-icons mdi mdi-calendar-filter-outline"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">Izin</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('cuti*') ? 'active' : '' }}">
            <a href="{{ url('/cuti/' . $holding->holding_code) }}" class=" menu-link">
                <i class="menu-icon tf-icons mdi mdi-clipboard-text-clock-outline"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">Cuti</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('penugasan*') ? 'active' : '' }}">
            <a href="{{ url('/penugasan/' . $holding->holding_code) }}" class=" menu-link">
                <i class="menu-icon tf-icons mdi mdi-airplane-clock"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">Perjalanan Dinas</div>
            </a>
        </li>

        <li class="menu-item {{ Request::is('inventaris*') ? 'active' : '' }}">
            <a href="{{ url('/inventaris/' . $holding->holding_code) }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-archive-outline"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">Data Inventaris</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('access*') ? 'active' : '' }}">
            <a href="{{ url('/access/' . $holding->holding_code) }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-account-key"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">User Level Access</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('report*') ? 'active open' : '' }} ">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi mdi-chart-box-multiple"></i>
                <div style="font-size: 10pt;" data-i18n="Data Master">Report</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Request::is('report_kedisiplinan*') ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons mdi mdi-table-account"></i>
                        <div style="font-size: 10pt;" data-i18n="Data Master">Absensi</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item {{ Request::is('report_kedisiplinan*') ? 'active' : '' }}">
                            <a href="@if (Auth::user()->is_admin == 'hrd') {{ url('/report_kedisiplinan/' . $holding->holding_code) }}@else{{ url('/report_kedisiplinan/' . $holding->holding_code) }} @endif"
                                class="menu-link">
                                <div style="font-size: 10pt;" data-i18n="Container"><i
                                        class="mdi mdi-file-chart-check-outline"></i>&nbsp;Absensi&nbsp;&&nbsp;Kedisiplinan
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li
                    class="menu-item {{ Request::is('report_pelamar*') ? 'active open' : '' }} {{ Request::is('report_recruitment*') ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons mdi mdi-table-account"></i>
                        <div style="font-size: 10pt;" data-i18n="Data Master">Rekrutmen</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item {{ Request::is('report_pelamar*') ? 'active' : '' }}">
                            <a href="@if (Auth::user()->is_admin == 'hrd') {{ url('/hrd/report_pelamar/' . $holding->holding_code) }}@else{{ url('/report_pelamar/' . $holding->holding_code) }} @endif"
                                class="menu-link">
                                <div style="font-size: 10pt;" data-i18n="Container"><i
                                        class="mdi mdi-file-chart-check-outline"></i>&nbsp;Data&nbsp;Pelamar</div>
                            </a>
                        </li>
                        <li class="menu-item {{ Request::is('report_recruitment*') ? 'active' : '' }}">
                            <a href="@if (Auth::user()->is_admin == 'hrd') {{ url('hrd//report_recruitment/' . $holding->holding_code) }}@else{{ url('/report_recruitment/' . $holding->holding_code) }} @endif"
                                class="menu-link">
                                <div style="font-size: 10pt;" data-i18n="Container"><i
                                        class="mdi mdi-file-chart-check-outline"></i>&nbsp;Data&nbsp;Rekrutmen</div>
                            </a>
                        </li>
                        <li class="menu-item {{ Request::is('report_per_divisi*') ? 'active' : '' }}">
                            <a href="@if (Auth::user()->is_admin == 'hrd') {{ url('hrd//report_per_divisi/' . $holding->holding_code) }}@else{{ url('/report_per_divisi/' . $holding->holding_code) }} @endif"
                                class="menu-link">
                                <div style="font-size: 10pt;" data-i18n="Container"><i
                                        class="mdi mdi-file-chart-check-outline"></i>&nbsp;Rekrutmen&nbsp;Per&nbsp;Divisi
                                </div>
                            </a>
                        </li>
                    </ul>
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
            <a href="{{ url('/activity-logs/' . $holding->holding_code) }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-history"></i>
                <div style="font-size: 10pt;" data-i18n="Basic">Activity Log</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ url('/logout') }}" class="menu-link" style="border: none; background: none;">
                <i class="menu-icon tf-icons mdi mdi-logout"></i>
                <div style="font-size: 10pt;" data-i18n="Blank">Log Out</div>
            </a>
            </a>
        </li>
    </ul>
</aside>