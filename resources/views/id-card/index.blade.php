@extends('layouts.dashboard')
@section('isi')

    <style>
        .card{
            height: 3.375in;
            width: 2.275in;
            padding: 1.3rem 0 1.3rem 0;
            box-shadow: 0 0 5px #b4b4b4;
            background-image: url('public/assets/idcard/bgecard.jpg');
            background-repeat: no-repeat;
            background-size: 218.4px 324px;
            border-radius: 20px;
        }
        .companyname{
            position: absolute;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 1.09rem;
            margin-top: .5rem;
            top: 4.3%;
            left: 3%;
            color: oldlace;
        }
        .company-img{
            position: absolute;
            top: 15%;
            left: 35%;
            right:35%;
            /*max-width: 96px;*/
        }
        .profile-img{
            position: absolute;
            top: 40%;
            left: 28%;
            width: 96px;
            height: 96px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, .2);
        }
        .profile-name{
            position: absolute;
            top: 70%;
            font-weight: 400;
            text-transform: uppercase;
            font-size: 1.6rem;
            margin-top: .5rem;
            color: darkblue;
        }
        .profile-username{
            position: absolute;
            top: 78%;
            font-weight: 300;
            text-transform: lowercase;
            font-size: 1rem;
            margin-top: .5rem;
            text-align: center;
            color: navy;
        }
        .squre-qr{
            position: absolute;
            align-items: center;
            top: 20%;
            left: 30%;
            height: 80px;
            width: 80px;
            box-shadow: 0 0 100px #b4b4b4;
        }
        .dt{
            top: 50%;
            position: absolute;
            font-weight: 400;
            font-size: 1.0rem;
            margin-top: .5rem;
            color: darkblue;
            left: 4%;
        }
        .dt1{
            top: 60%;
            position: absolute;
            font-weight: 400;
            font-size: 1.0rem;
            margin-top: .5rem;
            color: darkblue;
            left: 4%;
        }
        .dt2{
            top: 69%;
            position: absolute;
            font-weight: 400;
            font-size: 1.0rem;
            margin-top: .5rem;
            color: darkblue;
            left: 4%;
        }
    </style>
        <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-12">
                <div class="card" style="margin-left:15%;margin-right:15%">
                    <div class="text-xenter">
                        <div class="pp">
                            <h4 class="companyname">CV. SUMBER PANGAN</h4>
                            <img class="company-img" src="{{asset('public/images/logosp250x250.png')}}" alt="" width="30%">
                            <img class="profile-img" src="{{asset('public/dev.jpeg')}}" alt="" >
                        </div>
                        <div class="names">
                            <h2 class="profile-name">FAESOL AR</h2>
                            <h4 class="profile-username">IT PROGRAMER</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12">
                <div class="card" style="margin-left:15%;margin-right:15%">
                    <div class="pp">
                        <h4 class="companyname">CV. SUMBER PANGAN</h4>
                        <img class="squre-qr" src="{{asset('public/assets/idcard/qrsample.png')}}" alt="">
                    </div>
                    <div class="details">
                        <h4 class="dt"><b>Tanggl Lahir :</b>23 Mei 1990</h4>
                        <h4 class="dt1"><b>NO. NIK:</b>123456789</h4>
                        <h4 class="dt2"><b>Address. :</b>Ngronggot, Nganjuk</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
@endsection

