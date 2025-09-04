@extends('layouts.ppdb-landing-page.main')
@section('content')
    <div class="row mt-5">
        <div class="col-5 card-forgot-password">
            <div class="card card-header-background pb-4">
                <div class="card-body text-center">
                    <figure>
                        <img class="image" src="{{asset('img/email.png')}}" alt="">
                    </figure>
                    <h5 class="text-green">Kami mengirimkan pesan untuk konfirmasi di email kamu :</h5>
                    <h4 class="font-weight-bold">{{@$user->email}}</h4>
                    <p>Silakan cek inbox atau folder spam email Anda</p>
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
