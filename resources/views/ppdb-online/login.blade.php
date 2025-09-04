@extends('layouts.ppdb-landing-page.main')
@section('content')
    <div class="row row-height">

        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
          <a class="navbar-brand" href="{{ route('ppdb.index') }}"><button class="btn btn-outline-success my-2 my-sm-0" type="submit">KEMBALI KE HALAMAN UTAMA</button></a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">

            </ul>
            <form class="form-inline my-2 my-lg-0">
              <!-- <a href="#"><button class="btn btn-outline-success my-2 my-sm-0" type="button">DAFTAR</button></a>
              &nbsp;&nbsp;&nbsp; -->
              <a href="{{ route('ppdb.login') }}"><button class="btn btn-outline-success my-2 my-sm-0" type="button">LOGIN</button></a>
            </form>
          </div>
        </nav>

        @include('ppdb-online.step-left-section')

        <div class="col-lg-6 content-right" id="start">
            <div id="wizard_container">
                <div id="top-wizard">
                </div>
                <div class="header-form text-center">
                    <img src="{{asset('frontend-ppdb-online/img/logo-serviam.png')}}" class="logo-serviam">
                    <div class="row">
                        <div class="col-lg-5 offset-lg-1 text-center">
                            <div class="title-gray"><a href="{{ route('ppdb.index') }}">Register</a></div>
                        </div>
                        <div class="col-lg-5 text-center">
                            <div class="title"><a href="{{ route('ppdb.login') }}">Login</a></div>
                        </div>
                    </div>
                </div>
                <!-- /top-wizard -->
                <form id="wrapped" method="POST" action="{{route('ppdb.login.account-select')}}">
                    <input id="website" name="website" type="text" value="">
                    <!-- Leave for security protection, read docs for details -->
                    <div class="text-center">
                        <p class="text-center">Masukkan email anda.</p>
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul style="margin: 0 0 0 0;">
                                    @foreach ($errors->all() as $error)
                                        <li>{!! $error !!}</li>
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
                            <input type="email" name="email" class="form-control required" placeholder="Email"
                                   value="{{ old('email') }}" onchange="getVals(this, 'email');">
                        </div>
                        <br>
                        <button type="submit" name="login" class="btn btn-login">Login</button>

                        {{-- <div class="form-group mt-3">
                            <a href="{{url('forgot-password')}}" class="">Lupa Password? Klik di sini</a>
                        </div> --}}
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
    <script>
        $(document).ready(function() {
            $('.send-confirmation').click(function(e) {
                var parent = this;
                $.post('{{ route("ppdb.email-confirmation", ["UserId"=>null]) }}/'+ $(parent).data('id'), {
                    _token: '{{ csrf_token() }}'
                });
                $(this).attr("disabled", true);
                $(this).val("Dikirim (60s)...");
                setTimeout(
                () => $(this).val("Kirim Ulang").removeAttr("disabled"),
                60000
                );
            });
        });
    </script>
@endpush
