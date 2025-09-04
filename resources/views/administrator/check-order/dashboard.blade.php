@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Dasbor</h1>
        <ol class="breadcrumb">
            <li class="active">Berikut adalah gambaran umum singkat dari statistik pesanan pendaftar PPDB.</li>
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
                  <h3 class="color-up">{{ $unit->totalUsersOrder() }}</h3>
                  <span class="diff"><b class="title">Sudah Pesan<br></span>
                </li>
                <li class="col-xs-6 col-lg-2">
                  <h3 class="color-down">{{ $unit->totalUsersNotOrder() }}</h3>
                  <span class="diff"><b class="title">Belum Pesan<br></span>
                </li>
                <li class="col-xs-6 col-lg-2">
                  <h3 class="color-down">{{ $unit->totalUsersOrderPaymentNotConfirmed() }}</h3>
                  <span class="diff"><b class="title">Belum Bayar<br></span>
                </li>
                <li class="col-xs-6 col-lg-2">
                  <h3 class="color-down" >{{ $unit->totalUsersOrderPaymentUploaded() }}</h3>
                  <span class="diff"><b class="title">Sudah Bayar<br>(Belum Terkonfirmasi)</span>
                </li>
                <li class="col-xs-6 col-lg-2">
                  <h3 class="color-up">{{ $unit->totalUsersOrderPaymentConfirmed() }}</h3>
                  <span class="diff"><b class="title">Sudah Bayar<br>(Terkonfirmasi)</span>
                </li>
            </ul>
        </div>
        @empty
          No data
        @endforelse
    </div>
    <div class="button-collection">
      <a href="{{ route('admin.check-order.index') }}" class="btn btn-primary">Kembali</a>
    </div>
@endsection