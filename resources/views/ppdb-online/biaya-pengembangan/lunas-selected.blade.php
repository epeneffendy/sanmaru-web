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

            <div class="row">
                <div class="col">
                    <div class="p-4 mt-3 mb-4 rounded" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                        <p class="text-body font-weight-bold mb-4" style="font-size: 1.1rem;">Langkah-langkah pengajuan
                            Pembayaran Lunas:</p>

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
                                    memilih pembayaran biaya pengembangan Lunas.</p>
                            </div>
                        </div>

                        <div class="d-flex mb-3 align-items-start">
                            <div class="mr-3 mt-1">
                                <span
                                    class="d-inline-flex justify-content-center align-items-center rounded-circle text-white font-weight-bold"
                                    style="width: 32px; height: 32px; background-color: #a3dd82; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">2</span>
                            </div>
                            <div>
                                <h6 class="font-weight-bold mb-1 text-primary-green" style="font-size: 1.05rem;">Download
                                    Surat Pernyataan </h6>
                                <p class="text-body mb-0" style="font-size: 0.95rem; color: #475569;">Silahkan download
                                    surat pernyataan dan isi dengan data yang benar.</p>
                            </div>
                        </div>

                        <div class="d-flex mb-3 align-items-start">
                            <div class="mr-3 mt-1">
                                <span
                                    class="d-inline-flex justify-content-center align-items-center rounded-circle text-white font-weight-bold"
                                    style="width: 32px; height: 32px; background-color: #a3dd82; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">3</span>
                            </div>
                            <div>
                                <h6 class="font-weight-bold mb-1 text-primary-green" style="font-size: 1.05rem;">Upload
                                    Surat Pernyataan</h6>
                                <p class="text-body mb-0" style="font-size: 0.95rem; color: #475569;">Upload surat
                                    pernyataan dan lakukan pembayaran secara lunas di menu Keuangan, anda akan mendapatkan
                                    Nomor Virtual Account BCA.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $isPaid = isset($dispensation) && ($dispensation->status == 'paid' || $dispensation->status_payment == 'paid');
                $hasDispensation = isset($dispensation) && !empty($dispensation);
            @endphp

            @if ($isPaid)
                {{-- TAMPILAN SETELAH LUNAS: DOWNLOAD & UPLOAD SURAT PERNYATAAN --}}
                <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                    <i class="fas fa-check-circle fa-2x me-3 mr-3"></i>
                    <div>
                        <h6 class="fw-bold mb-1">Pembayaran Lunas Berhasil!</h6>
                        <p class="mb-0">Silahkan unduh dan unggah Surat Pernyataan yang telah ditandatangani untuk menyelesaikan proses.</p>
                    </div>
                </div>

                <form id="form-development">
                    <div class="row">
                        <div class="col">
                            <input type="hidden" name="development_fee_option" value="lunas" />
                            <p class="text-body-title text-primary-green">Upload Surat Pernyataan</p>
                            <p class="text-body">Silahkan download form surat pernyataan terlebih dahulu <a
                                    href="{{ route('ppdb.download-biaya-pengembangan', ['type' => 'lunas']) }}"
                                    target="_blank" class="font-weight-bold text-success">disini</a></p>

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

                            <div class="upload-image-box mt-3 mb-3">
                                <div class="btn-upload p-4 text-center" style="border: 2px dashed #a7f3d0; border-radius: 12px; background-color: #f0fdf4;">
                                    <div class="row justify-content-center align-items-center flex-column">
                                        <i class="fas fa-cloud-upload-alt fa-3x mb-3" style="color: #166534;"></i>
                                        <span class="d-block font-weight-bold mb-2" style="color: #166534;">Pilih file dari perangkat Anda</span>
                                        <span class="text-muted d-block mb-3">Support: PDF</span>
                                        <span class="btn btn-dark-green text-white position-relative">
                                            Browse
                                            <input type="file" name="development_statement" accept="application/pdf"
                                                class="position-absolute w-100 h-100" id="development_statement"
                                                style="left: 0; top: 0; opacity: 0; cursor: pointer;" />
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-row mt-3">
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
                                @else
                                    <div class="status-tab status-tab-red" id="message_development_statement">
                                        <img class="red" src="{{ asset('frontend-ppdb-online/img/Icon/Tab/cross.png') }}"
                                            alt="">
                                        <img class="grey" src="{{ asset('frontend-ppdb-online/img/Icon/Tab/folder.png') }}"
                                            alt="">
                                        <img class="green" src="{{ asset('frontend-ppdb-online/img/Icon/Tab/check.png') }}"
                                            alt="">
                                        <span>Belum Terupload</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            @else
                {{-- TAMPILAN SEBELUM LUNAS: PEMBUATAN VIRTUAL ACCOUNT --}}
                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 12px; background-color: #f8fafc;">
                    <h5 class="font-weight-bold text-dark mb-2">Instruksi Pembayaran Lunas</h5>
                    <p class="text-muted mb-4">
                        Untuk melakukan pembayaran lunas, klik tombol di bawah untuk mendapatkan nomor Virtual Account. 
                        Surat Pernyataan baru dapat diunduh dan diunggah setelah pembayaran lunas terverifikasi.
                    </p>

                    @if ($hasDispensation)
                        <div class="alert alert-info d-flex align-items-center mb-3" role="alert">
                            <i class="fas fa-info-circle fa-2x mr-3 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Tagihan Pembayaran Lunas Telah Diterbitkan</h6>
                                <p class="mb-0">Silakan selesaikan pembayaran tagihan lunas Anda melalui Virtual Account.</p>
                            </div>
                        </div>
                        <a href="{{ route('ppdb.bills.payment-now', ['id' => $dispensation->id, 'type' => \App\Models\PaymentVirtualAccounts::VIRTUAL_ACCOUNT_FULL_STATEMENT, 'payment_type' => 'lunas', 'dispensation_type' => 'development']) }}"
                            class="btn btn-green font-weight-bold text-white px-4 py-3">
                            <i class="fas fa-credit-card mr-2 me-2"></i>Lihat Virtual Account Pembayaran
                        </a>
                    @else
                        <form action="{{ route('ppdb.bills.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="ppdb_user_id" value="{{ $ppdb->id }}">
                            <input type="hidden" name="paymentType" value="lunas">
                            <input type="hidden" name="type" value="development">
                            <input type="hidden" name="total_bill" value="{{ \App\Helpers\PriceHelper::development($ppdb) }}">
                            <button type="submit" class="btn btn-green font-weight-bold text-white px-4 py-3">
                                <i class="fas fa-file-invoice-dollar mr-2 me-2"></i>Bayar Lunas Sekarang (Dapatkan Virtual Account)
                            </button>
                        </form>
                    @endif
                </div>
            @endif
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
