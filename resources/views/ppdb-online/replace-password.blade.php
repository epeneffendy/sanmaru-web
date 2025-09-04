@extends('layouts.ppdb-landing-page.main')
@section('content')
    <div class="row row-height">
        <div class="col-lg-6 content-left">
            <div class="content-left-wrapper">
                <div>
                    <figure>
                        <img src="{{asset('frontend-ppdb-online/img/ppdb-online-image.svg')}}" alt=""
                             class="img-fluid img-ppdb-online">
                    </figure>
                    <h2 class="title-white">Penerimaan Peserta Didik Baru<br>Tahun Ajaran 2022-2023</h2>
                    <p>YAYASAN PARATHA BHAKTI<br>
                        Kampus Santa Maria<br>
                        Jl. Raya Darmo No. 49<br>
                        SURABAYA 60265<br>
                        Telp. (031)5665064, 5684408 , 5673967<br>
                        Fax. (031)5667840, 5677963</p>
                </div>
                <div class="copy">© {{ date('Y') }} Santa Maria</div>
            </div>
            <!-- /content-left-wrapper -->
        </div>

        <div class="col-lg-6 content-right" id="start">
            <div id="wizard_container">
                <div id="top-wizard">
                </div>
                <div class="header-form text-center">
                    <img src="{{asset('frontend-ppdb-online/img/logo-serviam.png')}}" class="logo-serviam mb-3">
                    <div class="row">
                        <div class="col-12 text-center">
                            <div class="">
                                <a href="{{ route('ppdb.index') }}">
                                    <h2 class="">Replace Password</h2>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /top-wizard -->
                <form id="wrapped" method="POST" action="{{route('ppdb.new-password')}}">
                    <input id="website" name="website" type="text" value="">
                    <!-- Leave for security protection, read docs for details -->
                    <div class="text-center">
                        <p class="text-center">Masukkan Password Baru.</p>
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul style="margin: 0 0 0 0;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <input type="hidden" id="remember_token" name="remember_token" value="{{$id}}">
                        <div class="form-group">
                            <input type="password" name="password" class="form-control required" placeholder="New Password"
                                   value="{{ old('password') }}" onchange="getVals(this, 'email');">
                        </div>

                        <div class="form-group">
                            <input type="password" name="password_confirmation" class="form-control required" placeholder="Repeat New Password"
                                   value="{{ old('password_confirmation') }}" onchange="getVals(this, 'password_confirmation');">
                        </div>

                        <button type="submit" name="login" class="btn-login">Submit</button>
                    </div>
                    @csrf
                </form>
            </div>
            <!-- /Wizard container -->
        </div>
        <!-- /content-right-->
    </div>
@endsection
@push('scripts')
    <!-- Wizard script -->
    <script src="{{asset('frontend-ppdb-online/js/registration_func.js')}}"></script>
@endpush
