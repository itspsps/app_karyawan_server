@extends('admin.layouts.dashboard')
@section('isi')
@include('sweetalert::alert')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row gy-4">
        <!-- Transactions -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">ERROR</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-12">
                        <div class="col-md-12 col-12">
                            <div class="d-flex align-items-center">
                                ERROR
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @endsection