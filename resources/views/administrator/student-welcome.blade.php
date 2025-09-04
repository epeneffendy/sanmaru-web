@extends('layouts.welcome-page.main')
@section('content')

<div class="row-height">

    <div class="wrapper-content-desktop">
        
    </div>

    <div class="wrapper-content-mobile">
        <div class="col-lg-7 content-top" id="start">
            <div id="wizard_container">
                <div id="top-wizard"></div>
                <div class="header-form">
                    <img src="{{asset('frontend-ppdb-online/img/logo-serviam.png')}}" class="logo-serviam-top">
                    <h5 class="text-center"> SELAMAT DATANG DI<br>
                        <span class="span-name text-extra-bold">SANMARU</span><br>
                        Sistem Informasi Sekolah Kampus Santa Maria 
                    </h5>
                </div>
                <div class="clear-50"></div>
            </div>
            <!-- /Wizard container -->
        </div>
    </div>
    <!-- /content-right-->
</div>

@endsection
