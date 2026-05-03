@extends('layouts.ppdb-online.main')
@section('content')
    @include('layouts.ppdb-online.tab-bar')
    <div class="container container-biaya">
        @if (\App\Helpers\PriceHelper::development($ppdb, false) == 0)
            <div class="form-group">
                <div class="alert border-0 shadow-sm" role="alert"
                    style="background-color: #f8f9fa; border-left: 5px solid #17a2b8 !important; border-radius: 8px; padding: 20px;">
                    <div class="d-flex align-items-center">
                        <div style="margin-right: 20px;">
                            <i class="fa fa-info-circle text-info" style="font-size: 2.5rem;"></i>
                        </div>
                        <div>
                            <h5 class="font-weight-bold text-dark mb-2" style="font-size: 16px; margin-top: 0;">Informasi
                                Pembayaran</h5>
                            <span class="text-muted" style="font-size: 14px;">
                                Pilihan pembayaran uang pengembangan saat ini belum tersedia untuk akun Anda. <br>
                                Silakan hubungi <strong>Admin</strong> atau <strong>Tata Usaha</strong> sekolah untuk
                                mendapatkan informasi lebih lanjut.
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="col">
                <div class="nav-back">
                    <div class="row mb-3">
                        <a href="{{ URL::previous() }}" class="d-flex align-items-center justify-content-around"><img
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
                    {{--                <p class="text-body">Silahkan lakukan upload form surat pernyataan sebelum tanggal <b>{{$deadline}}</b>, jika anda tidak melakukan upload form surat pernyataan sampai batas waktu yang ditentukan otomatis calon siswa dinyatakan <b>TIDAK LOLOS</b></p> --}}
                </div>
            </div>
        @endif

    </div>
@endsection
