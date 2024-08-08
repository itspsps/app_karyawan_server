<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    @if(auth()->user()->is_admin == 'admin')
    <a href="{{ url('/dashboard/holding') }}" class="brand-link" style="background-color: #3c86d8;;color: #ffffff;">
        @else
        <a href="{{ url('/absen') }}" class="brand-link">
            @endif
            @if($holding=='sp')
            <img src="{{ url('public/assets/img/logosp.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            @elseif($holding=='sps')
            <img src="{{ url('public/holding/assets/img/logosps.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            @else
            <img src="{{ url('public/holding/assets/img/logosip.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            @endif
            <span class="brand-text font-weight-light">@if($holding=='sp')CV. SP @elseif($holding=='sps') PT. SPS @else CV. SIP @endif</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @if(auth()->user()->foto_karyawan == null)
                    <img src="{{ url('assets/img/foto_default.jpg') }}" class="img-circle elevation-2" alt="User Image">
                @else
                    <img src="{{ url('storage/'.auth()->user()->foto_karyawan) }}" class="img-circle elevation-2" alt="User Image">
                @endif
            </div>
            <div class="info">
                <a href="{{ url('/my-profile') }}" class="d-block">My Profile</a>
            </div>
        </div> -->

            @can('admin')
            <!-- Sidebar Menu -->

            <!-- <ul class="n
                <ul class=" nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ url('/dashboard') }}" class="nav-link {{ Request::is('dashboard*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
            </ul>
            </nav> -->
            <!-- /.sidebar-menu -->

            <!-- <hr style="background-color:dimgray"> -->
            @endcan

            @can('admin')
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-header">DATA MASTER</li>
                    @if($holding == 'sp')
                    <li class="nav-item">
                        <a href="{{ url('/karyawan/'.$holding) }}" class="nav-link {{ Request::is('karyawan*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-solid fa-user merah"></i>
                            <p>
                                Karyawan
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/shift/'.$holding) }}" class="nav-link {{ Request::is('shift*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-fw fa-clock merah"></i>
                            <p>
                                Master Shift
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/rekap-data/'.$holding) }}" class="nav-link {{ Request::is('rekap-data*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-database merah"></i>
                            <p>
                                Rekap Data Absensi
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/lokasi-kantor/'.$holding) }}" class="nav-link {{ Request::is('lokasi-kantor*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-map-marked-alt merah"></i>
                            <p>
                                Master Lokasi
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/reset-cuti/'.$holding) }}" class="nav-link {{ Request::is('reset-cuti*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-sync-alt merah"></i>
                            <p>
                                Reset Cuti
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/departemen/'.$holding) }}" class="nav-link {{ Request::is('departemen*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-universal-access merah"></i>
                            <p>
                                Master Departmen
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/divisi/'.$holding) }}" class="nav-link {{ Request::is('divisi*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-universal-access merah"></i>
                            <p>
                                Master Divisi
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/jabatan/'.$holding) }}" class="nav-link {{ Request::is('jabatan*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-universal-access merah"></i>
                            <p>
                                Master Jabatan
                            </p>
                        </a>
                    </li>
                    @elseif($holding == 'sps')
                    <li class="nav-item">
                        <a href="{{ url('/karyawan/'.$holding) }}" class="nav-link {{ Request::is('karyawan*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-solid fa-user merah"></i>
                            <p>
                                Karyawan
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/shift/'.$holding) }}" class="nav-link {{ Request::is('shift*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-fw fa-clock merah"></i>
                            <p>
                                Master Shift
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/rekap-data/'.$holding) }}" class="nav-link {{ Request::is('rekap-data*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-database merah"></i>
                            <p>
                                Rekap Data Absensi
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/lokasi-kantor/'.$holding) }}" class="nav-link {{ Request::is('lokasi-kantor*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-map-marked-alt merah"></i>
                            <p>
                                Master Lokasi
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/reset-cuti/'.$holding) }}" class="nav-link {{ Request::is('reset-cuti*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-sync-alt merah"></i>
                            <p>
                                Reset Cuti
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/departemen/'.$holding) }}" class="nav-link {{ Request::is('departemen*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-universal-access merah"></i>
                            <p>
                                Master Departmen
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/divisi/'.$holding) }}" class="nav-link {{ Request::is('divisi*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-universal-access merah"></i>
                            <p>
                                Master Divisi
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/jabatan/'.$holding) }}" class="nav-link {{ Request::is('jabatan*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-universal-access merah"></i>
                            <p>
                                Master Jabatan
                            </p>
                        </a>
                    </li>
                    @elseif($holding = 'sip')
                    <li class="nav-item">
                        <a href="{{ url('/karyawan/'.$holding) }}" class="nav-link {{ Request::is('karyawan*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-solid fa-user merah"></i>
                            <p>
                                Karyawan
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/shift/'.$holding) }}" class="nav-link {{ Request::is('shift*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-fw fa-clock merah"></i>
                            <p>
                                Master Shift
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/rekap-data/'.$holding) }}" class="nav-link {{ Request::is('rekap-data*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-database merah"></i>
                            <p>
                                Rekap Data Absensi
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/lokasi-kantor/'.$holding) }}" class="nav-link {{ Request::is('lokasi-kantor*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-map-marked-alt merah"></i>
                            <p>
                                Master Lokasi
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/reset-cuti/'.$holding) }}" class="nav-link {{ Request::is('reset-cuti*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-sync-alt merah"></i>
                            <p>
                                Reset Cuti
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/departemen/'.$holding) }}" class="nav-link {{ Request::is('departemen*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-universal-access merah"></i>
                            <p>
                                Master Departmen
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/divisi/'.$holding) }}" class="nav-link {{ Request::is('divisi*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-universal-access merah"></i>
                            <p>
                                Master Divisi
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/jabatan/'.$holding) }}" class="nav-link {{ Request::is('jabatan*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-universal-access merah"></i>
                            <p>
                                Master Jabatan
                            </p>
                        </a>
                    </li>
                    @endif
                </ul>
            </nav>

            <hr style="background-color:dimgray">
            @endcan

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-header">ABSENSI</li>
                    <li class="nav-item">
                        <a href="{{ url('/absen') }}" class="nav-link {{ Request::is('absen*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-home biruMuda"></i>
                            <p>
                                Home
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/id-card') }}" class="nav-link {{ Request::is('id-card*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-id-card biruMuda"></i>
                            <p>
                                Id Card
                            </p>
                        </a>
                    </li>
                    @can('admin')
                    <li class="nav-item">
                        <a href="{{ url('/data-absen') }}" class="nav-link {{ Request::is('data-absen*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-solid fa-table biruMuda"></i>
                            <p>
                                Data Absen
                            </p>
                        </a>
                    </li>
                    @endcan
                    <li class="nav-item">
                        <a href="{{ url('/my-absen') }}" class="nav-link {{ Request::is('my-absen*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-fingerprint biruMuda"></i>
                            <p>
                                Presensi
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/kpi') }}" class="nav-link {{ Request::is('kpi*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-book  biruMuda"></i>
                            <p>
                                KPI
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/my-slip') }}" class="nav-link {{ Request::is('slip*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-receipt  biruMuda"></i>
                            <p>
                                My Slip
                            </p>
                        </a>
                    </li>

                </ul>
            </nav>

            <hr style="background-color:dimgray">

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-header">OVERTIME</li>
                    <li class="nav-item">
                        <a href="{{ url('/lembur') }}" class="nav-link {{ Request::is('lembur*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-fw fa-user-clock hijauMuda"></i>
                            <p>
                                Lembur
                            </p>
                        </a>
                    </li>
                    @can('admin')
                    <li class="nav-item">
                        <a href="{{ url('/data-lembur') }}" class="nav-link {{ Request::is('data-lembur*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-solid fa-table hijauMuda"></i>
                            <p>
                                Data Lembur
                            </p>
                        </a>
                    </li>
                    @endcan
                    <li class="nav-item">
                        <a href="{{ url('/my-lembur') }}" class="nav-link {{ Request::is('my-lembur*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-business-time hijauMuda"></i>
                            <p>
                                My Lembur
                            </p>
                        </a>
                    </li>
                </ul>
            </nav>

            <hr style="background-color:dimgray">

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-header">CUTI</li>
                    <li class="nav-item">
                        <a href="{{ url('/cuti') }}" class="nav-link {{ Request::is('cuti*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-hourglass-half unguMuda"></i>
                            <p>
                                Permintaan Cuti
                            </p>
                        </a>
                    </li>

                    @can('admin')
                    <li class="nav-item">
                        <a href="{{ url('/data-cuti') }}" class="nav-link {{ Request::is('data-cuti*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-table unguMuda"></i>
                            <p>
                                Data Cuti
                            </p>
                        </a>
                    </li>
                    @endcan
                </ul>
            </nav>

            <hr style="background-color:dimgray">

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-header">Docs</li>

                    @can('admin')
                    <li class="nav-item">
                        <a href="{{ url('/dokumen') }}" class="nav-link {{ Request::is('dokumen*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-folder-open biru"></i>
                            <p>
                                Dokumen Pegawai
                            </p>
                        </a>
                    </li>
                    @endcan

                    <li class="nav-item">
                        <a href="{{ url('/my-dokumen') }}" class="nav-link {{ Request::is('my-dokumen*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-folder biru"></i>
                            <p>
                                Dokumen Saya
                            </p>
                        </a>
                    </li>


                </ul>
            </nav>

            <hr style="background-color:dimgray">
            @can('admin')
            <!-- Activity Logs -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a class="nav-link" href="/activity-logs">
                            <i class="nav-icon fas fa-history merah"></i>
                            <p>
                                Activity Logs
                            </p>
                        </a>
                    </li>
                </ul>
            </nav>
            @endcan

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
                            <i class="nav-icon fas fa-sign-out-alt merah"></i>
                            <p>
                                Log Out
                            </p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <!-- /.sidebar -->
</aside>
<style>
    .merah {
        color: red;
    }

    .hijau {
        color: green;
    }

    .biru {
        color: blue;
    }

    .kuning {
        color: yellow;
    }

    .ungu {
        color: purple;
    }

    .abu {
        color: gray;
    }

    .hitam {
        color: black;
    }

    .putih {
        color: white;
    }

    .biruMuda {
        color: #00bfff;
    }

    .biruTua {
        color: #0000ff;
    }

    .hijauMuda {
        color: #00ff00;
    }

    .hijauTua {
        color: #008000;
    }

    .kuningMuda {
        color: #ffff00;
    }

    .kuningTua {
        color: #ffbf00;
    }

    .unguMuda {
        color: #ff00ff;
    }

    .unguTua {
        color: #800080;
    }

    .abuMuda {
        color: #c0c0c0;
    }

    .abuTua {
        color: #808080;
    }

    .hitamMuda {
        color: #000000;
    }

    .hitamTua {
        color: #000000;
    }

    .putihMuda {
        color: #ffffff;
    }

    .putihTua {
        color: #ffffff;
    }
</style>