@extends('layouts.ppdb-landing-page.main')
@section('content')
    @php
        $currentYear = (int) date('Y');
        $currentMonth = (int) date('n');

        if ($currentMonth >= 7) {
            $tahunAjaran = $currentYear . '-' . ($currentYear + 1);
        } else {
            $tahunAjaran = $currentYear - 1 . '-' . $currentYear;
        }
    @endphp
    <div class="row row-height">
        <div class="col-lg-6 content-left">
            <div class="content-left-wrapper">
                <div>
                    <figure>
                        <img src="{{ asset('frontend-ppdb-online/img/ppdb-online-image.svg') }}" alt=""
                            class="img-fluid img-ppdb-online">
                    </figure>
                    <h2 class="title-white">Penerimaan Peserta Didik Baru<br>Tahun Ajaran {{ $tahunAjaran }}</h2>
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
                    <img src="{{ asset('frontend-ppdb-online/img/logo-serviam.png') }}" class="logo-serviam mb-3">
                    <div class="row">
                        <div class="col-12 text-center">
                            <div class="">
                                <a href="{{ route('ppdb.index') }}">
                                    <h2 class="">Lupa Password?</h2>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /top-wizard -->
                <form id="wrapped" method="POST" action="{{ route('ppdb.email-sended') }}">
                    <input id="website" name="website" type="text" value="">
                    <!-- Leave for security protection, read docs for details -->
                    <div class="text-center">
                        <p class="text-center">Silakan masukkan username Anda untuk reset password</p>
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul style="margin: 0 0 0 0;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="form-group">
                            <input type="text" name="username" class="form-control required" placeholder="Username"
                                value="{{ old('username', @$request->username) }}" onchange="getVals(this, 'username');">
                        </div>

                        <button type="submit" name="login" class="btn btn-login">Kirim</button>
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
    <script src="{{ asset('frontend-ppdb-online/js/registration_func.js') }}"></script>
@endpush
