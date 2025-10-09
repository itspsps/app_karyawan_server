@extends('admin.layouts.dashboard')
@section('css')
<style>
    /* Custom Card Styles for Dashboard Options */
    .dashboard-option-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        border-radius: 12px;
        min-height: 150px;
        cursor: pointer;
        background-color: #ffffff;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        height: 100%;
        text-decoration: none !important;
        display: flex;
        flex-direction: column;
        justify-content: center;
        /* Tambahan: Pastikan konten di tengah secara horizontal */
        text-align: center;
    }

    .dashboard-option-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .card-content-wrap {
        padding: 20px;
    }

    .card-title-option {
        font-weight: 600;
        color: #673ab7;
        margin-bottom: 5px;
        font-size: 1.2rem;
    }

    .card-description {
        color: #6c757d;
        font-size: 0.9rem;
    }

    .card-icon {
        color: #673ab7;
        font-size: 3.5rem;
        margin-bottom: 10px;
    }

    .dashboard-link {
        text-decoration: none;
        display: block;
        height: 100%;
    }
</style>
@endsection
@section('isi')
@include('sweetalert::alert')
<div class="container-xxl flex-grow-1 container-p-y" style="font-size: small;">
    <div class="row gy-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">DASHBOARD OPTIONS</h5>

                    </div>
                    <p class="mt-3"><span class="fw-medium">Pilih salah satu dashboard untuk melanjutkan.</span></p>
                </div>

                <div class="card-body">
                    <div class="row g-4 justify-content-center">

                        {{-- *********************************** --}}
                        {{-- START: Dashboard Selection Cards --}}
                        {{-- *********************************** --}}

                        {{-- Mock Data for demonstration. Replace with your actual $dashboards loop --}}
                        @php
                        $dashboards = [
                        ['title' => 'Dashboard HRD', 'description' => 'Grafik Karyawan, Grafik Absensi,Grafik Status Karyawan.', 'url' => url('dashboard/hrd/{$holding->holding_code}'), 'icon' => 'mdi mdi-view-dashboard'],
                        ['title' => 'Dashboard PORTAL KARIR', 'description' => '', 'url' => url('dashboard/portal'), 'icon' => 'mdi mdi-account-group']
                        ];
                        @endphp

                        <div class="col-sm-6 col-md-4">
                            <a href="{{ url('dashboard/hrd',$holding->holding_code) }}" class="dashboard-link">
                                <div class="card dashboard-option-card">
                                    <div class="card-content-wrap">
                                        <i class="mdi mdi-view-dashboard card-icon"></i>
                                        <h5 class="card-title-option">Dashboard HRD</h5>
                                        <p class="card-description">Grafik Karyawan, Grafik Absensi,Grafik Status Karyawan.</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <a href="{{ url('dashboard/portal',$holding->holding_code) }}" class="dashboard-link">
                                <div class="card dashboard-option-card">
                                    <div class="card-content-wrap">
                                        <i class="mdi mdi-account-group card-icon"></i>
                                        <h5 class="card-title-option">Dashboard PORTAL KARIR</h5>
                                        <p class="card-description">Lihat Status Rekrutmen Karyawan Baru, Grafik Calon Karyawan, dan Grafik Rekrutmen.</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        {{-- *********************************** --}}
                        {{-- END: Dashboard Selection Cards --}}
                        {{-- *********************************** --}}

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection