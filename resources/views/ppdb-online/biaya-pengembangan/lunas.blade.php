@extends('layouts.ppdb-online.main')
@section('content')
    @include('layouts.ppdb-online.tab-bar')
    <div class="container container-biaya">
        <div class="col">
            <div class="biaya-header col">
                <div class="row">
                    <div class="card-green">
                        @if (\App\Helpers\PriceHelper::getDevelopmentDiscountStatus($ppdb) && $discount > 0)
                            <span>Nominal uang pengembangan Anda
                                <del>{{ \App\Helpers\PriceHelper::development($ppdb, true) }}</del>
                                {{ \App\Helpers\PriceHelper::rupiah(((100 - $discount) / 100) * \App\Helpers\PriceHelper::development($ppdb)) }}</span>
                        @else
                            <span>Nominal uang pengembangan Anda
                                {{ \App\Helpers\PriceHelper::development($ppdb, true) }}</span>
                        @endif
                    </div>
                </div>
                <div class="row mt-4">
                    <p class="text-body-title text-primary-green">Pilih cara pembayaran biaya pengembangan</p>
                </div>
                <div class="row-button-biaya">
                    <a href="{{ route('ppdb.biaya-pengembangan.lunas') }}" class="btn-biaya active" id="lunas">
                        <img src="{{ asset('frontend-ppdb-online/img/Icon/ic-lunas.png') }}" class="image-passive"
                            alt="">
                        <img src="{{ asset('frontend-ppdb-online/img/Icon/ic-lunas-active.png') }}" class="image-active"
                            alt="">
                        <span class="text-body px-2">Pembayaran Lunas</span>
                    </a>
                    <a href="{{ route('ppdb.biaya-pengembangan.cicilan') }}" class="btn-biaya" id="cicilan">
                        <img src="{{ asset('frontend-ppdb-online/img/Icon/ic-cicilan.png') }}" class="image=passive"
                            alt="">
                        <img src="{{ asset('frontend-ppdb-online/img/Icon/ic-cicilam-active.png') }}" class="image-active"
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

            @php
                $discountStatus = \App\Helpers\PriceHelper::getDevelopmentDiscountStatus($ppdb);
                $voucherStatus = \App\Helpers\PriceHelper::getFreeVouchersOlahRagaProductStatus($ppdb, 'lunas');
            @endphp
            <div class="row">
                <div class="col">
                    @if ($discountStatus || $voucherStatus)
                        <p class="text-body-title text-primary-green">Pembayaran Lunas</p>
                        <p class="text-body">Bayar 100% langsung lunas akan mendapatkan keuntungan:</p>
                    @endif
                    <ol class="ol-number">
                        @if ($discountStatus && $discount > 0)
                            <li>Voucher {{ $discount }}% dari nominal uang pengembangan yang sudah ditentukan</li>
                        @endif
                        @if ($voucherStatus)
                            <li>Mendapat voucher free seragam olahraga siswa</li>
                        @endif
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <p class="text-body-title text-primary-green">Keterangan</p>
                    <p class="text-body">{{ \App\Helpers\PriceHelper::getDescriptionFinance($ppdb, 'development') }}</p>
                </div>
            </div>

            <form id="form-development">
                <div class="row">
                    <div class="col">
                        <input type="hidden" name="development_fee_option" value="lunas" />
                        <p class="text-body-title text-primary-green">Upload Surat Pernyataan</p>
                        <p class="text-body">Silahkan download form surat pernyataan terlebih dahulu <a
                                href="{{ route('ppdb.download-biaya-pengembangan', ['type' => 'lunas']) }}"
                                target="_blank">disini</a></p>
                        {{--                        <p class="text-body">Silahkan lakukan upload form surat pernyataan sebelum tanggal <b>{{$deadline}}</b>, jika anda tidak melakukan upload form surat pernyataan sampai batas waktu yang ditentukan otomatis calon siswa dinyatakan <b>TIDAK LOLOS</b></p> --}}

                        <div class="row" style="margin-bottom: 1em">
                            @if (!empty(@$ppdb['development_statement']))
                                <div class="status-tab status-tab-green" style="margin-top: 15px;"
                                    id="message_development_statement" role="alert">
                                    <span class="d-flex align-items-center text-white">
                                        <img class="green"
                                            src="{{ asset('frontend-ppdb-online/img/Icon/Tab/check.png') }}"
                                            alt="">
                                        <img class="check-green"
                                            src="{{ asset('frontend-ppdb-online/img/Icon/Tab/check-green.png') }}"
                                            alt="">
                                        <span>Dokumen Sudah Terupload</span>
                                    </span>
                                </div>
                                <br>
                            @endif
                        </div>

                        <div class="upload-image-desktop">
                            <div class="btn-upload">
                                <div class="row justify-content-center align-items-center flex-column">
                                    <img src="{{ asset('frontend-ppdb-online/img/Icon/ic-upload.png') }}" class="mb-3"
                                        alt="">
                                    <span class="text-title-3 mb-2">Pilih file dari perangkat komputer Anda</span>
                                    <span class="text-description text-grey mb-3">Support: PDF</span>
                                    <span class="btn btn-grey" style="cursor: pointer;">
                                        <img src="{{ asset('frontend-ppdb-online/img/Icon/Tab/folder.png') }}"
                                            alt="">Browse
                                    </span>
                                    <input type="file" name="development_statement" accept="application/pdf"
                                        class="btn btn-grey" id="development_statement"
                                        style="width: 135px; z-index: 9999; margin-top: -40px; opacity: 0; cursor: pointer;" />
                                </div>
                            </div>

                        </div>


                        <div class="upload-image-mobile">
                            <div class="d-flex justify-content-center">
                                <span class="btn btn-green upload-image" style="padding: 0.5rem 2rem">Upload Surat
                                    Pernyataan</span>
                            </div>
                        </div>


                        <div class="d-flex flex-row">
                            @if (!empty(@$ppdb['development_statement']))
                                <div class="status-tab status-tab-green" style="margin-top: 15px;"
                                    id="message_development_statement" role="alert">
                                    <a target="_blank" class="d-flex align-items-center text-white"
                                        href="{{ route('ppdb.download-development-statement-letter') }}">
                                        <img class="green"
                                            src="{{ asset('frontend-ppdb-online/img/Icon/Tab/check.png') }}"
                                            alt="">
                                        <img class="check-green"
                                            src="{{ asset('frontend-ppdb-online/img/Icon/Tab/check-green.png') }}"
                                            alt="">
                                        <span>Lihat File</span>
                                    </a>
                                </div>
                                <div style="margin-top: 15px;">
                                    <a target="_blank" href="{{ route('ppdb.reset-development-fee.post') }}"
                                        class="btn btn-red" style="margin-top: 5px;"
                                        id="reset_development_fee_button">Ganti Pembayaran</a>
                                </div>
                            @else
                                <div class="status-tab status-tab-red" id="message_development_statement">
                                    <img class="red" src="{{ asset('frontend-ppdb-online/img/Icon/Tab/cross.png') }}"
                                        alt="">
                                    <img class="grey" src="{{ asset('frontend-ppdb-online/img/Icon/Tab/folder.png') }}"
                                        alt="">
                                    <img class="green" src="{{ asset('frontend-ppdb-online/img/Icon/Tab/check.png') }}"
                                        alt="">
                                    <span>Belum</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>


            </form>
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
    <script>
        $(document).on('click', '.upload-image', function() {
            $('input[name=development_statement]').trigger('click');
        });

        $(document).on('change', "#development_statement", function() {
            if ($(this).val()) {
                var self = $(this);
                var formData = new FormData($('#form-development')[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    type: "POST",
                    url: "{{ route('ppdb.upload-development-fee') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#message_development_statement').removeClass("status-tab-green");
                        $('#message_development_statement').removeClass("status-tab-red");
                        $('#message_development_statement').addClass("status-tab-yellow");
                        $('#message_development_statement').text("Uploading...");
                    },
                    error: function(data) {
                        $('#message_development_statement').removeClass("status-tab-green");
                        $('#message_development_statement').addClass("status-tab-red");
                        $('#message_development_statement').removeClass("status-tab-yellow");
                        $('#message_development_statement').text("Belum Lengkap");
                    },
                    success: function(data) {
                        $('#message_development_statement').addClass("status-tab-green");
                        $('#message_development_statement').removeClass("status-tab-red");
                        $('#message_development_statement').removeClass("status-tab-yellow");
                        var html =
                            '<a target="_blank" class="d-flex align-items-center text-white" href=' +
                            data.preview +
                            '><img class="green" src="{{ asset('frontend-ppdb-online/img/Icon/Tab/check.png') }}" alt=""><img class="check-green" src="{{ asset('frontend-ppdb-online/img/Icon/Tab/check-green.png') }}" alt=""><span>Lihat File</span></a>';
                        $('#message_development_statement').html(html);
                        swal({
                            icon: 'success',
                            title: "Sukses!",
                            text: 'Upload Dokumen Berhasil!',
                        });
                        setTimeout(function() {
                            location.reload()
                        }, 2000);
                    }
                });
                return false;
            }
        });

        $(document).on('click', '#reset_development_fee_button', function(e) {
            e.preventDefault();

            swal({
                    title: 'Apakah Anda yakin akan merubah cara pembayaran Anda?',
                    text: 'Data sebelumnya akan tergantikan dengan pilihan pembayaran Anda yang baru',
                    buttons: [
                        'Tidak',
                        'Ya'
                    ],
                    icon: "warning"
                })
                .then((value) => {
                    switch (value) {
                        case true:
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                },
                                type: "POST",
                                contentType: "JSON",
                                url: "{{ route('ppdb.reset-development-fee.post') }}",
                                cache: false,
                                contentType: false,
                                processData: false,
                                success: function(data) {
                                    if (data.status === 'success') {
                                        window.location.href = "{{ route('ppdb.welcome') }}";
                                    }
                                }
                            });
                            break;
                        default:
                    }
                });
        });
    </script>
@endpush
