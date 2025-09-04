{{-- @extends('layouts.ppdb-online.main') --}}
@extends('layouts.ppdb-landing-page.main')
@section('content')
    <div class="row mt-5">
        <div class="col-5 card-forgot-password">
            <div class="card card-header-background pb-4">
                <div class="card-body text-center">
                    <h1 class="text-white font-weight-bold">Silakan Cek Email Kamu!</h1>
                    <figure>
                        <img class="image" src="{{asset('img/email.png')}}" alt="">
                    </figure>
                    <h5 class="text-green">Kami mengirimkan pesan untuk konfirmasi di email kamu :</h5>
                    <h4 class="font-weight-bold">{{ @$email }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12 text-center">
            <a href="{{route('ppdb.login')}}" class="btn btn-semi-green">Kembali ke Homepage</a>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .sidebar-container {
            display: none;
        }

        .content-container {
            width: 100%;
        }
    </style>
@endpush
