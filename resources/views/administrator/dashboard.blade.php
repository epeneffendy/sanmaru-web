@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Dasbor</h1>
        <ol class="breadcrumb">
            <li class="active">Berikut adalah gambaran umum singkat dari statistik pendaftar PPDB dan beberapa fitur lainnya.</li>
        </ol>
    </div>

    <div class="container-default">
        <div class="card h-100"></div>
        @forelse ($data as $unit)
        <div class="col-md-12">
            <ul class="topstats clearfix">
                <li class="col-xs-6 col-lg-2">
                  <h3>{{ $unit->unit_code }}</h3>
                  <span class="diff"><b class="title">Unit<br>{{ $unit->name }}</span>
                </li>
                <li class="col-xs-6 col-lg-2">
                  <h3 class="color-up">{{ $unit->ppdbUsers->count('id') }}</h3>
                  <span class="diff"><b class="title">Total<br>Pendaftar</span>
                </li>
                <li class="col-xs-6 col-lg-2">
                  <h3 class="color-down">{{ $unit->totalUsersEmailNotVerified() }}</h3>
                  <span class="diff"><b class="title">Belum Verifikasi<br>Email</span>
                </li>
                <li class="col-xs-6 col-lg-2">
                  <h3 class="color-down">{{ $unit->totalUsersPaymentNotYetSubmitted() }}</h3>
                  <span class="diff"><b class="title">Belum Upload<br>Bukti Bayar Formulir</span>
                </li>
                <li class="col-xs-6 col-lg-2">
                  <h3 class="color-down">{{ $unit->totalUsersPaymentNotYetVerified() }}</h3>
                  <span class="diff"><b class="title">Pembayaran Belum<br>Terverifikasi Admin</span>
                </li>
                <li class="col-xs-6 col-lg-2">
                  <h3 class="color-up">{{ $unit->totalUsersSubmittedRegistration() }}</h3>
                  <span class="diff"><b class="title">Submit<br>Registrasi</span>
                </li>
            </ul>
        </div>
        @empty
          No data
        @endforelse
    </div>
@endsection