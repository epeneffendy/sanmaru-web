@extends('layouts.ppdb-online.main')
@section('content')
    @include('layouts.ppdb-online.tab-bar')
    <div class="container container-biaya">
        <div class="col">
            <div class="biaya-header col">
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
                        <img src="{{ asset('frontend-ppdb-online/img/Icon/ic-lunas.png') }}" class="image-passive"
                            alt="">
                        <img src="{{ asset('frontend-ppdb-online/img/Icon/ic-lunas-active.png') }}" class="image-active"
                            alt="">
                        <span class="text-body px-2">Pembayaran Lunas</span>
                    </a>
                    <a href="{{ route('ppdb.biaya-pengembangan.cicilan') }}" class="btn-biaya active" id="cicilan">
                        <img src="{{ asset('frontend-ppdb-online/img/Icon/ic-cicilan.png') }}" class="image-passive"
                            alt="">
                        <img src="{{ asset('frontend-ppdb-online/img/Icon/ic-cicilan-active.png') }}" class="image-active"
                            alt="">
                        <span class="text-body px-2">Pembayaran Cicilan</span>
                    </a>
                </div>
                <div class="row">
                    <p class="text-body">Tidak menemukan pilihan pembayaran? <a
                            href="{{ route('ppdb.biaya-pengembangan.lainnya') }}" id="lainnya">Klik
                            disini</a></p>
                </div>
            </div>

            <div class="nav-back">
                <div class="row mb-3">
                    <a href="{{ URL::previous() }}" class="d-flex align-items-center justify-content-around"><img
                            class="head-left" src="{{ asset('frontend-ppdb-online/img/Icon/Icon-Arrow.png') }}"
                            alt=""><span class="text-body-title text-primary-green ml-2">Kembali</span></a>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <p class="text-body-title text-primary-green">Pembayaran Cicilan</p>
                    <div class="p-4 mt-3 mb-4 rounded" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                        <p class="text-body font-weight-bold mb-4" style="font-size: 1.1rem;">Langkah-langkah pengajuan
                            cicilan:</p>

                        <div class="d-flex mb-3 align-items-start">
                            <div class="mr-3 mt-1">
                                <span
                                    class="d-inline-flex justify-content-center align-items-center rounded-circle text-white font-weight-bold"
                                    style="width: 32px; height: 32px; background-color: #a3dd82; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">1</span>
                            </div>
                            <div>
                                <h6 class="font-weight-bold mb-1 text-primary-green" style="font-size: 1.05rem;">Pilih cara
                                    pembayaran biaya pengembangan</h6>
                                <p class="text-body mb-0" style="font-size: 0.95rem; color: #475569;">Anda diwajibkan
                                    memilih pembayaran biaya pengembangan Cicilan.</p>
                            </div>
                        </div>

                        <div class="d-flex mb-3 align-items-start">
                            <div class="mr-3 mt-1">
                                <span
                                    class="d-inline-flex justify-content-center align-items-center rounded-circle text-white font-weight-bold"
                                    style="width: 32px; height: 32px; background-color: #a3dd82; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">2</span>
                            </div>
                            <div>
                                <h6 class="font-weight-bold mb-1 text-primary-green" style="font-size: 1.05rem;">Pilih Skema
                                    Pembayaran Cicilan Anda</h6>
                                <p class="text-body mb-0" style="font-size: 0.95rem; color: #475569;">Jika anda sudah
                                    menentukan pembayaran cicilan pada Tahap Finalisasi Penerimaan, Silahkan tentukan skema
                                    cicilan anda.</p>
                            </div>
                        </div>

                        <div class="d-flex mb-3 align-items-start">
                            <div class="mr-3 mt-1">
                                <span
                                    class="d-inline-flex justify-content-center align-items-center rounded-circle text-white font-weight-bold"
                                    style="width: 32px; height: 32px; background-color: #a3dd82; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">3</span>
                            </div>
                            <div>
                                <h6 class="font-weight-bold mb-1 text-primary-green" style="font-size: 1.05rem;">Cara
                                    Pembayaran</h6>
                                <p class="text-body mb-0" style="font-size: 0.95rem; color: #475569;">Jika skema cicilan
                                    sudah anda simpan, anda akan melakukan pembayaran dengan Virtual Account BCA dengan
                                    skema cicilan dan anda juga bisa melakukan pemabayran dengan skema pembayaran sebagian
                                    untuk mengurangi biaya tagihan anda.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row my-3">
                <div class="col">
                    <form action="" method="post">
                        <div id="alert">
                            @if ($errors->any())
                                <div class="alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        @csrf


                        <div class="d-flex justify-content-center mt-2">
                            <button type="submit" name="simpan-cicilan" id="simpan-cicilan" class="btn btn-green"
                                style="padding: 0.5rem 2rem; display: none">Simpan</button>
                            <button type="submit" name="simpan-cicilan-temp" id="simpan-cicilan-temp" class="btn btn-green"
                                style="padding: 0.5rem 2rem" onclick="clicked(event)">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
@push('styles')
    <style>
        .swal-title {
            font-size: 18px;
            margin-left: 4px;
            margin-right: 4px;
        }

        .swal-text {
            text-align: center;
            margin-left: 4px;
            margin-right: 4px;
        }

        .swal-footer {
            text-align: center;
            padding: 17px;
        }

        .swal-button--confirm {
            background-color: #a3dd82;
        }

        .swal-button--cancel {
            background-color: #efefef;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{ asset('js/sweet-alert/sweet-alert.min.js') }}"></script>
@endpush
