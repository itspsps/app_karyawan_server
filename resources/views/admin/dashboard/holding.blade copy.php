@extends('admin.layouts.holding')
@section('isi')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row gy-4">
        <h1>Welcome to <span>APP HRD</span></h1>
        <h2>We are team of talented designers making websites with Bootstrap</h2>
        <!-- Congratulations card -->
        <div class="col-md-4 col-lg-4">
            <a href="{{ url('dashboard/holding/sp') }}">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-1">CV. SUMBER PANGAN</h4>
                        <p class="pb-0">Best seller of the month</p>
                        <h4 class="text-primary mb-1">$42.8k</h4>
                        <p class="mb-2 pb-1">78% of target 🚀</p>
                    </div>
                    <img src="{{asset('admin/assets/img/icons/misc/triangle-light.png')}}" class="scaleX-n1-rtl position-absolute bottom-0 end-0" width="166" alt="triangle background" data-app-light-img="icons/misc/triangle-light.png" data-app-dark-img="icons/misc/triangle-dark.png" />
                    <img src="{{ url('public/holding/assets/img/logosp.png') }}" class="scaleX-n1-rtl position-absolute bottom-0 end-0 me-4 pb-2" width="150" alt="view sales" />
                </div>
            </a>
        </div>
        <div class="col-md-4 col-lg-4">
            <a href="{{ url('dashboard/holding/sps') }}">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-1">PT. SURYA PANGAN SEMESTA</h4>
                        <p class="pb-0">Best seller of the month</p>
                        <h4 class="text-primary mb-1">$42.8k</h4>
                        <p class="mb-2 pb-1">78% of target 🚀</p>
                    </div>
                    <img src="{{asset('admin/assets/img/icons/misc/triangle-light.png')}}" class="scaleX-n1-rtl position-absolute bottom-0 end-0" width="166" alt="triangle background" data-app-light-img="icons/misc/triangle-light.png" data-app-dark-img="icons/misc/triangle-dark.png" />
                    <img src="{{ url('public/holding/assets/img/logosps.png') }}" class="scaleX-n1-rtl position-absolute bottom-0 end-0 me-4 pb-2" width="150" alt="view sales" />
                </div>
            </a>
        </div>
        <div class="col-md-4 col-lg-4">
            <a href="{{ url('dashboard/holding/sip') }}">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-1">CV. SURYA INTI PANGAN</h4>
                        <p class="pb-0">Best seller of the month</p>
                        <h4 class="text-primary mb-1">$42.8k</h4>
                        <p class="mb-2 pb-1">78% of target 🚀</p>
                    </div>
                    <img src="{{asset('admin/assets/img/icons/misc/triangle-light.png')}}" class="scaleX-n1-rtl position-absolute bottom-0 end-0" width="166" alt="triangle background" data-app-light-img="icons/misc/triangle-light.png" data-app-dark-img="icons/misc/triangle-dark.png" />
                    <img src="{{ url('public/holding/assets/img/logosip.png') }}" class="scaleX-n1-rtl position-absolute bottom-0 end-0 me-4 pb-2" width="150" alt="view sales" />
                </div>
            </a>
        </div>
    </div>
</div>
@endsection