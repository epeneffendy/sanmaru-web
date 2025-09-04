@extends('layouts.ppdb-landing-page.main')
@section('content')
<div id="sanmaru-landing">
    <div class="section-home">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 mobile">
                    <img src="{{asset('frontend-ppdb-online/img/logo-serviam.png')}}" class="ppdb-logo-serviam">
                    <img src="{{asset('img/Sanmaru Logo.png')}}" class="ppdb-logo-sanmaru">
                    <img src="{{asset('frontend-ppdb-online/img/ppdb-main-image.png')}}" class="ppdb-main-image">
                </div>
                <div class="col-sm-5 offset-sm-1">
                    <div class="home-headline">
                        <div class="logo desktop">
                            <img src="{{asset('frontend-ppdb-online/img/logo-serviam.png')}}" class="ppdb-logo-serviam">
                            <img src="{{asset('img/Sanmaru Logo.png')}}" class="ppdb-logo-sanmaru">
                        </div>
                        <div class="headline">
                            <h4>SELAMAT DATANG DI</h4>
                            <h1>SANMARU</h1>
                            <h4>SISTEM INFORMASI SEKOLAH</h4>
                            <h4>KAMPUS SANTA MARIA</h4>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-sm-6 desktop">
                        <img src="{{asset('frontend-ppdb-online/img/ppdb-main-image.png')}}" class="ppdb-main-image">
                    </div> -->
                <div class="col-sm-6 content-right" id="start">
                    <div id="wizard_container">
                        <div class="header-form text-center">
                            <div class="row">
                                <div class="col-lg-12 text-center">
                                    <div class="title"><a>Login</a></div>
                                </div>
                            </div>
                        </div>
                        <!-- /top-wizard -->
                        <form id="wrapped" method="POST" action="{{ route('kantin.submit') }}" autocomplete="off">
                            <input id="website" name="website" type="text" value="">
                            <!-- Leave for security protection, read docs for details -->
                            <div class="text-center">
                                <p class="text-center">Masukkan username dan password Anda.</p>
                                @if (session('status'))
                                <div class="alert alert-danger">
                                    {{ session('status') }}
                                </div>
                                @endif
                                @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul style="margin: 0 0 0 0;">
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                @if (session('message'))
                                <div class="alert alert-success">
                                    {{ session('message') }}
                                </div>
                                @endif
                                @if (session('verified'))
                                <div class="alert alert-success">
                                    <i class="fa fa-check"></i> {!! session('verified') !!}
                                </div>
                                @endif

                                <div class="form-group">
                                    <input type="text" id="username" name="username" class="form-control required"
                                        placeholder="Username" value="{{ old('username') }}"
                                        onchange="getVals(this, 'username');" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control required"
                                        placeholder="Password" value="{{ old('password') }}"
                                        onchange="getVals(this, 'password');" required>
                                </div>
                                <br>
                                <button type="submit" name="login" class="btn btn-login">Login</button>

                                <!-- <div class="form-group mt-3">
                                        <a href="{{url('forgot-password')}}" class="">Lupa Password? Klik di sini</a>
                                    </div> -->
                            </div>
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection