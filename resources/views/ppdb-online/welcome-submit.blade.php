@extends('layouts.ppdb-online.main')
@section('content')
    <div class="row row-height">
        <div class="col-lg-5 content-left">
            <div class="content-left-wrapper">
                <div>
                    <figure>
                        <img src="{{asset('frontend-ppdb-online/img/welcome-image.svg')}}" alt="" class="img-fluid img-welcome">
                    </figure>
                </div>
            </div>
            <!-- /content-left-wrapper -->
        </div>
        <!-- /content-left -->

        <div class="col-lg-7 content-top" id="start">
            <div id="wizard_container">
                <div id="top-wizard"></div>
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="header-form">
                    <img src="{{asset('frontend-ppdb-online/img/logo-serviam.png')}}" class="logo-serviam-top">
                </div>
                <a href="{{ route('ppdb.logout') }}"><button class="btn btn-outline-success" type="button">LOGOUT</button></a>
                <br>
                <form id="wrapped" method="POST" enctype="multipart/form-data"
                      action="{{route('ppdb.welcome.submit')}}">
                    <div>
                        <p>Selamat Datang,<br><span class="span-name">{{@$user['name']}}</span></p>

                        <div class="icon-step">
                            <img src="{{asset('frontend-ppdb-online/img/icon-step-4.png')}}">
                        </div>

                        <p class="text-welcome">
                            @if ($user->isAccepted)
                                Selamat, calon siswa atas nama<br/>
                                <b>{!! $user->name !!}</b> telah berhasil diterima<br/>
                                di sekolah <b>{!! @$user->unit->name !!}</b>

                            @elseif ($user->isRejected)

                            @else
                                Anda telah selesai melengkapi data yang dibutuhkan.<br/>
                                Status pendaftaran anda masih menunggu validasi dari admin.<br/>
                                <b>Anda akan mendapatkan email konfirmasi jika pendaftaran anda berhasil diterima</b>
                            @endif
                        </p>

                        <a href="{{ route('ppdb.download-proof-registration') }}" target="_blank" class="btn btn-register" style="width: 300px; padding-top: 0.8rem;">Unduh Bukti Pendaftaran</a>
                    </div>
                    @csrf
                </form>
                <div class="clear-50"></div>
            </div>
            <!-- /Wizard container -->
        </div>
        <!-- /content-right-->
    </div>
@endsection
@push('scripts')
    <!-- Wizard script -->
    <script src="{{asset('js/sweet-alert/sweet-alert.min.js')}}"></script>
@endpush
@push('styles')
    <style>
        p.text-welcome {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: bold;
            font-size: 25px;
            line-height: 29px;
            color: #89998B;
        }

        p.text-welcome b {
            color: #000000;
        }

        .btn-outline-success {
            position: fixed;
            right: 10px;
            top: 10px;
        }
    </style>
@endpush
