@extends('layouts.holding')
@section('isi')
<!-- ======= Top Bar ======= -->
<section id="topbar" class="d-flex align-items-center">
    <div class="container d-flex justify-content-center justify-content-md-between">
        <div class="contact-info d-flex align-items-center">
        </div>
    </div>
</section>
<!-- ======= Hero Section ======= -->
<section id="hero" class="d-flex align-items-center" style="height: 100%">
    <div class="container" data-aos="zoom-out" data-aos-delay="100">
        <div class="row">
            <!-- <div class="col-3"> -->
            <h1>Welcome to <span>APP HRD</span></h1>
            <h2>We are team of talented designers making websites with Bootstrap</h2>
            <div class="d-flex">
                <a href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="btn-get-started scrollto">Logout</a>
                <form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </div>
            <!-- </div> -->
            <div class="col-12">
                <section id="featured-services" class="featured-services">
                    <div class="container" data-aos="fade-up">
                        <div class="row">
                            <div class="col-md-6 col-lg-4 d-flex align-items-stretch mb-5 mb-lg-0">
                                <div class="icon-box" data-aos="fade-up" data-aos-delay="100">
                                    <div class="row">
                                        <a href="{{ url('dashboard/holding/sp') }}">
                                            <div class="col-12">
                                                <img src="{{ url('public/holding/assets/img/logosp.png') }}" width="50%" style="margin-right: 25%;margin-left: 25%">
                                            </div>
                                            <h4 class="title" style="text-align: center;"><a href="">CV. SUMBER PANGAN</a></h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 d-flex align-items-stretch mb-5 mb-lg-0">
                                <div class="icon-box" data-aos="fade-up" data-aos-delay="200">
                                    <div class="row">
                                        <a href="{{ url('dashboard/holding/sps') }}">
                                            <div class="col-12">
                                                <img src="{{ url('public/holding/assets/img/logosps.png') }}" width="50%" style="margin-right: 25%;margin-left: 25%">
                                            </div>
                                            <h4 class="title" style="text-align: center;"><a href="">PT. SURYA PANGAN SEMESTA</a></h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 d-flex align-items-stretch mb-5 mb-lg-0">
                                <div class="icon-box" data-aos="fade-up" data-aos-delay="300">
                                    <div class="row">
                                        <a href="{{ url('dashboard/holding/sip') }}">
                                            <div class="col-12">
                                                <img src="{{ url('public/holding/assets/img/logosipbaru.png') }}" width="50%" style="margin-right: 25%;margin-left: 25%">
                                            </div>
                                            <h4 class="title" style="text-align: center;"><a href="">CV. SURYA INTI PANGAN</a></h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</section><!-- End Hero -->

<footer id="footer">
    <div class="container py-4">
        <div class="copyright">
            &copy; Copyright <strong><span>BizLand</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
            <!-- All the links in the footer should remain intact. -->
            <!-- You can delete the links only if you purchased the pro version. -->
            <!-- Licensing information: https://bootstrapmade.com/license/ -->
            <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/bizland-bootstrap-business-template/ -->
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div>
    </div>
</footer><!-- End Footer -->

<section id="topbar" class="d-flex align-items-center">
    <div class="container d-flex justify-content-center justify-content-md-between">
        <div class="contact-info d-flex align-items-center">
        </div>
    </div>
</section>
@endsection