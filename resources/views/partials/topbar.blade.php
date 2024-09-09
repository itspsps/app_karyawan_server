<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link btn bg-primary" data-widget="pushmenu" href="#" role="button"><b class="font-weight-bold btn-shine logo-xs"><i class="fa fa-angle-right text-white"></i> &nbsp;</b>
                <b class="font-weight-bold btn-shine logo-xl"><i class="fa fa-angle-left text-white"></i></b> &nbsp;</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link"><span class="font-weight-bold text-primary"> Sistem Informasi
                    Absensi Pegawai</span></a>

        </li>
    </ul>
    <!-- <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul> -->

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">{{ auth()->user()->name }}</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                <li><a href="{{ url('/my-profile/edit-password') }}" class="dropdown-item"><i class="fa fa-solid fa-key fa-sm fa-fw mr-2 text-gray-400"></i> Edit Password</a></li>
                <li><a href="{{ url('/my-profile') }}" class="dropdown-item"><i class="fa fa-solid fa-user fa-sm fa-fw mr-2 text-gray-400"></i> My Profile</a></li>
                <li><a href="#" class="dropdown-item" data-toggle="modal" data-target="#logoutModal"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout</a></li>
            </ul>
        </li>
    </ul>
</nav>