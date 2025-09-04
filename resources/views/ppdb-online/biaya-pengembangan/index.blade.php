@extends('layouts.ppdb-online.main')
@section('content')
@include('layouts.ppdb-online.tab-bar')
    <div class="container container-biaya">
        <div class="col">
            <div class="nav-back">
                <div class="row mb-3">
                    <a href="{{URL::previous()}}" class="d-flex align-items-center justify-content-around"><img
                            class="head-left" src="{{ asset('frontend-ppdb-online/img/Icon/Icon-Arrow.png') }}"
                            alt=""><span class="text-body-title text-primary-green ml-2">Kembali</span></a>
                </div>
            </div>

            <div class="row">
                <div class="card-green">
                    <span>Nominal uang pengembangan Anda {{ \App\Helpers\PriceHelper::development($ppdb, true) }}</span>
                </div>
            </div>
            <div class="row mt-4">
                <p class="text-body-title text-primary-green">Pilih cara pembayaran biaya pengembangan</p>
            </div>
            <div class="row-button-biaya">
                <a href="{{ route('ppdb.biaya-pengembangan.lunas') }}" class="btn-biaya" id="lunas">
                    <div class="d-flex justify-content-center align-items-center">
                        <img src="{{ asset('frontend-ppdb-online/img/Icon/ic-lunas.png') }}" alt="">
                        <span class="text-body px-2">Pembayaran Lunas</span>
                    </div>
                </a>
                <a href="{{ route('ppdb.biaya-pengembangan.cicilan') }}" class="btn-biaya" id="cicilan">
                    <div class="d-flex justify-content-center align-items-center">
                        <img src="{{ asset('frontend-ppdb-online/img/Icon/ic-cicilan.png') }}" alt="">
                        <span class="text-body px-2">Pembayaran Cicilan</span>
                    </div>
                </a>
            </div>
            <div class="row">
                <p class="text-body">Tidak menemukan pilihan pembayaran? <a
                        href="{{ route('ppdb.biaya-pengembangan.lainnya') }}" id="lainnya">Klik
                        disini</a></p>
{{--                <p class="text-body">Silahkan lakukan upload form surat pernyataan sebelum tanggal <b>{{$deadline}}</b>, jika anda tidak melakukan upload form surat pernyataan sampai batas waktu yang ditentukan otomatis calon siswa dinyatakan <b>TIDAK LOLOS</b></p>--}}
            </div>
        </div>
    </div>
@endsection
