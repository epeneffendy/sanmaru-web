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
                    <div class="header-form text-center">
                        <h4>Pilih Akun</h4>
                    </div>
                </div>
                <!-- /top-wizard -->
                <div class="accordion" id="accordionAccountSelect">
                    @foreach ($users as $user)
                    <div class="card">
                        <div class="card-header" id="heading{{ $user->id }}">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse{{ $user->id }}" aria-expanded="true" aria-controls="collapse{{ $user->id }}">
                                    <h2>{{ @$user->ppdb->name }}<small class="text-secondary"> ({{ $user->username }})</small></h2>
                                    <div>
                                        <label class="text-body-title">Nomor Registrasi: <span class="text-primary-green">{{ @$user->ppdb->register_number }}</span></label>
                                    </div>
                                    <div>
                                        <label class="text-body-title">Unit: <span class="text-primary-green">{{ @$user->ppdb->unit->name }}</label>
                                    </div>
                                </button>
                            </h2>
                        </div>

                        <div id="collapse{{ $user->id }}" class="collapse" aria-labelledby="heading{{ $user->id }}" data-parent="#accordionAccountSelect">
                            <div class="card-body text-center">
                                <form action="{{ route('ppdb.login.submit') }}" method="POST">
                                    @csrf
                                    @method('POST')
                                    <input type="hidden" name="username" value="{{ $user->username }}">
                                    <div class="form-group row" id="show_hide_password">
                                        <input type="password" class="form-control mb-2" id="password" name="password" placeholder="Password">
                                        <div class="input-group-addon">
                                            <i class="fa fa-eye-slash"></i>
                                        </div>
                                    </div>
                                    <input type="submit" class="btn btn-login" name="login" value="Login">
                                </form>
                                <div class="form-group mt-3">
                                    <a href="{{ route('ppdb.forgot-password', ['username' => $user->username]) }}" class="">Lupa Password? Klik di sini</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                {{-- <div class="list-group text-dark">
                    @foreach ($users as $user)
                    <a href="#" class="list-group-item list-group-item-action">
                        <h2>{{ @$user->ppdb->name }}</h2>
                        <div>
                            <label class="text-body-title">Nomor Registrasi: <span class="text-primary-green">{{ @$user->ppdb->register_number }}</span></label>
                        </div>
                        <div>
                            <label class="text-body-title">Unit: <span class="text-primary-green">{{ @$user->ppdb->unit->name }}</label>
                        </div>
                    </a>
                    @endforeach
                </div> --}}
            </div>
            <!-- /Wizard container -->
        </div>
        <!-- /content-right-->
    </div>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">
@endpush
@push('scripts')
    <!-- Wizard script -->
    <script src="{{asset('frontend-ppdb-online/js/registration_func.js')}}"></script>
    <script>
        $(document).ready(function() {
            $("#show_hide_password i").on('click', function(event) {
                event.preventDefault();
                if($('#show_hide_password input').attr("type") == "text"){
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass( "fa-eye-slash" );
                    $('#show_hide_password i').removeClass( "fa-eye" );
                }else if($('#show_hide_password input').attr("type") == "password"){
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass( "fa-eye-slash" );
                    $('#show_hide_password i').addClass( "fa-eye" );
                }
            });
        });
    </script>
@endpush
