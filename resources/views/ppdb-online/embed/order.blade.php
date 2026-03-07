@extends('layouts.ppdb-online.main')
@section('content')

    <div class="wrapper-content-desktop">
        <div class="container" style="padding: 3rem">
            <h2>Pembayaran Seragam</h2>

            <div class="row">
                <div class="col">
                    <div class="">
                        <h3 class="text-black" style="margin-top: 25px">Daftar Pesanan</h3>
                        @forelse ($order->productOrderDetails as $detail)
                            <div class="pemesanan-item">
                                <img src="{{ $detail->product->image }}"/>
                                <div class="pemesanan-item-info">
                                    <h3 class="text-black">{{ $detail->product->name }}</h3>
                                    <div
                                        class="pemesanan-item-info__price">{{ \App\Helpers\PriceHelper::rupiah($detail->total_price) }}</div>
                                    <p class="text-title-3 font-italic">Detail Pemesanan</p>
                                    <p class="text-title-3 font-italic">Ukuran: {{ $detail->productDetail->size }}</p>
                                    <p class="text-title-3 font-italic">Jumlah: {{ $detail->quantity }}</p>
                                    <p class="text-title-3 font-italic">Note: {{ $detail->note }}</p>
                                    {{-- <div class="pemesanan-item-info__detail">Size '{{ $detail->productDetail->size }}' - {{ $detail->quantity }} Barang</div> --}}
                                </div>
                            </div>
                        @empty
                            Tidak ada data
                        @endforelse
                    </div>
                </div>

                <div class="col">
                    @if ($order->voucher)
                        @php ($voucher = json_decode($order->voucher, TRUE))
                        <div class="voucher">
                            <div class="title">Voucher</div>
                            <div class="voucher-item">
                                <b>{{ $voucher['code'] }} -
                                    ({{ \App\Helpers\PriceHelper::rupiah($order->discount_total) }} off)</b>
                                <p>{{ nl2br($voucher['note']) }}</p>
                            </div>
                            <div class="text-danger" style="margin-top: 10px">
                                <b>**Notes:</b>
                                Pastikan ukuran/pesanan yang anda pilih sudah sesuai.
                                <b>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan</b>
                            </div>
                        </div>
                    @endif

                    <div class="total">
                        <h2 class="text-black">Total yang harus dibayarkan</h2>
                        <div class="total-item">{{ \App\Helpers\PriceHelper::rupiah($order->grand_total) }}</div>
                    </div>

                    <div class="pembayaran">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="text-black">Metode Pembayaran</h3>
                            <a href="{{ route('ppdb.embed-product.detail-payment', ['id' => $order->id]) }}"
                               class="text-subtitle-3 text-grey">lihat detail</a>
                        </div>

                        <div class="pembayaran-item">
                            <div class="pembayaran-item__title">
                                @if(!empty($order->payment_option))
                                    Bank {{ $order->payment_option }} <span class="{{ $order->payment_option }}"></span>
                                @else
                                    Bank {{ \App\Helpers\PriceHelper::paymentInfo($user->unit, \App\Helpers\Helper::isVaBcaEnable() ? 'BCA' : NULL)['bank'] }}
                                    <span
                                        class="{{ \App\Helpers\PriceHelper::paymentInfo($user->unit, \App\Helpers\Helper::isVaBcaEnable() ? 'BCA' : NULL)['bank'] }}"></span>
                                @endif
                            </div>
                            <div class="pembayaran-item__content">No. VA:
                                <span id="virtual_account_number">
                                @if(!empty($order->virtual_account_number))
                                        {{ $order->virtual_account_number }}
                                    @else
                                        {{ \App\Helpers\PriceHelper::virtualAccountNumber($user, true, \App\Helpers\Helper::isVaBcaEnable() ? 'BCA' : NULL) }}
                                    @endif
                                </span>
                                <img class="icon-normal"
                                     onclick="CopyToClipboard('virtual_account_number')"
                                     id="copy-va"
                                     src="{{asset('frontend-ppdb-online/img/Icon/Data-Active.png')}}"
                                     alt="Copy" title="Copy">
                                </div>
                        </div>
                    </div>

                    <div class="info">
                        Anda bisa melihat detail pemesanan lewat email.
                    </div>

                    @if ($order->pickup_date_schedule)
                            @if(empty($order->pickup_date))
                                <div class="pembayaran" id="qr-code">
                            <h3 class="text-black">Pengambilan Seragam</h3>
                            <div class="pembayaran-item">
                                <div class="pembayaran-item__content">
                                    <div style="color: black">Silahkan unduh detail transaksi berikut ini sebagai
                                        persyaratan pengambilan seragam
                                    </div>
                                    <a class="btn btn-green" style="margin-top: 10px"
                                       href="{{ route('ppdb.embed-product.order.pdf', $order->id) }}">
                                        <img class="icon-active"
                                             src="{{asset('frontend-ppdb-online/img/Icon/Data-Normal.png')}}" alt=""
                                             style="margin-right: 10px">
                                        Download
                                    </a>
                                    <div style="color: black; margin-top: 20px">QR code ditunjukkan kepada petugas saat
                                        pengambilan seragam
                                    </div>
                                    <button class="btn" style="margin-top: 10px" data-toggle="modal"
                                            data-target="#qrCodeModal">
                                        <img class="icon-active"
                                             src="{{asset('frontend-ppdb-online/img/Icon/qr-code-icon.png')}}" alt=""
                                             width="30px">
                                    </button>
                                </div>
                            </div>
                        </div>
                            @endif
                    @endif

                        @if ($order->pickup_status == 'pickup')
                            @if($is_complaint)
                                <div class="pembayaran" id="qr-code">
                                    <h3 class="text-black">Komplain Order</h3>
                                    <div class="pembayaran-item">
                                        <div class="pembayaran-item__content">
                                            <div style="color: black">Jika seragam tidak lengkap atau tidak sesuai dapat
                                                dapat mengisi form berikut
                                            </div>
                                            <a class="btn btn-green" style="margin-top: 10px"
                                               href="{{ route('ppdb.embed-product.complaint', ['product_order' => $order->id]) }}"
                                               target="_blank">
                                                Komplain
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="pembayaran" id="qr-code">
                                    <div class="pembayaran-item">
                                        <div class="pembayaran-item__content">
                                            @if((\Carbon\Carbon::now()->format('Y-m-d')) < $periodComplaint->date_start)
                                                <div style="color: black; font-size: 17px">Periode komplain akan di buka
                                                    pada {{ \Carbon\Carbon::parse($periodComplaint->date_start)->format('d-m-Y') }}</div>
                                            @else
                                                <h3 class="text-black">Periode komplain masih belum tersedia!</h3>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                        @if((ENV("APP_ENV") == "local") || (ENV("APP_ENV") == "production"))
                            <div class="cancel-order" id="cancel-order">
                                @if ($order->status === \App\Models\ProductOrder::STATUS_NEW_ORDER)
                                    <h3 class="text-black">Cancel Order</h3>
                                @else
                                    <h3 class="text-black">Status Order</h3>
                                @endif
                                <div class="pembayaran-item">
                                    <div class="pembayaran-item__content">
                                        @if ($order->status === \App\Models\ProductOrder::STATUS_NEW_ORDER)
                                            <div style="display: inline-block">
                                                Status: {!! $order->label_konfirmasi_pembayaran !!}
                                            </div>
                                            <div style="display: flex; align-items:center; margin:1rem 0">

                                                <div class="cancel-reason" style="width: 100%">
                                                    <input type="hidden" name="id" id="id" value={{ $order->id }} />
                                                    <div class="label-note">Keterangan</div>
                                                    <div class="text-note" style="width: 100%; padding-left: 3em">
                                        <textarea class="form-control" name="reason" id="reason"
                                                  placeholder="" required></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end" style="padding-top: 1em">
                                                <button type="button" class="btn btn-green post-cancel-order"
                                                        id="post-cancel-order">Cancel Order
                                                </button>
                                            </div>
                                        @endif
                                        @if ($order->status === \App\Models\ProductOrder::STATUS_CANCEL)
                                            <div style="display: inline-block">
                                                Status: {!! $order->label_konfirmasi_pembayaran !!}
                                            </div>
                                            @if(!empty($order->payment_cancel_reason))
                                                <div class="info">
                                                    Anda telah melakukan pembatalan order pada <label class="label"
                                                                                                      style="color: #dc3545">
                                                        {{ \App\Helpers\Helper::hariTanggalJam($order->payment_cancel_date) }}</label>
                                                </div>
                                            @endif
                                            <div class="info">
                                                Keterangan Pembatalan :
                                                @if(!empty($order->payment_cancel_reason))
                                                    <label class="label"
                                                           style="color: #dc3545">{{$order->payment_cancel_reason}}</label>
                                                @else
                                                    <label class="label" style="color: #dc3545">Melebihi Batas Waktu
                                                        Pembayaran</label>
                                                @endif
                                            </div>
                                        @endif

                                        @if ($order->status === \App\Models\ProductOrder::STATUS_CONFIRMED)
                                            <div style="display: inline-block">
                                                Status: {!! $order->label_konfirmasi_pembayaran !!}
                                            </div>
                                            <div class="info">
                                                Pembayaran anda telah terkonfirmasi pada <label class="label"
                                                                                                style="color: #28a745">
                                                    {{ \App\Helpers\Helper::hariTanggalJam($order->payment_confirmed_date) }}</label>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="pembayaran" id="konfirmasi-pembayaran">
                                <h3 class="text-black">Status Pembayaran</h3>
                                <div class="pembayaran-item">
                                    <div class="pembayaran-item__content">
                                        <div style="display: inline-block">
                                            Status: {!! $order->label_konfirmasi_pembayaran !!}
                                        </div>

                                        @if ($order->status === \App\Models\ProductOrder::STATUS_NEW_ORDER)
                                            <div style="display: flex; align-items:center; margin:1rem 0">
                                                <button class="btn btn-sm btn-green upload-file-button">
                                                    <img src="{{asset('frontend-ppdb-online/img/Icon/upload.png')}}" alt=""><span
                                                        class="text-white">Upload</span></button>
                                            </div>
                                            <form id="wrapped" method="POST" enctype="multipart/form-data">
                                                <input type="file" name="payment_image" id="upload_file"
                                                       accept="image/x-png,image/jpeg,application/pdf"/>
                                                <input type="hidden" name="id" value={{ $order->id }} />
                                            </form>
                                        @endif

                                        <div
                                            class="preview-konfirmasi-pembayaran {{ $order->getPaymentImageUrl() ? NULL : "hide" }}">
                                            <img src="{{ $order->getPaymentImageUrl() }}"
                                                 style="display: block; width: 100%; height: auto;"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                </div>
            </div>
        </div>
    </div>

    <div class="wrapper-content-mobile">
        <div class="row pl-3">
            <a href="{{ route('ppdb.embed-product.order-list') }}"
               class="d-flex align-items-center justify-content-around"><img class="head-left"
                                                                             src="{{asset('frontend-ppdb-online/img/Icon/Icon-Arrow.png')}}"
                                                                             alt=""><span
                    class="text-body-title text-primary-green ml-2">Kembali</span></a>
            {{-- <a href="{{ route('ppdb.embed-product.order-list') }}" class="arrow-back"></a> --}}
            {{-- <span>Detail Pesanan</span> --}}
        </div>
        <div class="pemesan">
            <div class="title">Pemesan</div>
            <div class="pemesan-content">
                <img src="/img/profile.png"/>
                <div class="pemesan-info">
                    {{ $user['name'] }}
                    <span>ID {{ $user['register_number'] }}</span>
                </div>
            </div>
        </div>

        <div class="pemesanan">
            <div class="title">Pemesan</div>
            @forelse ($order->productOrderDetails as $detail)
                <div class="pemesanan-item">
                    <img src="{{ $detail->product->image }}"/>
                    <div class="pemesanan-item-info">
                        <div class="pemesanan-item-info__title">{{ $detail->product->name }}</div>
                        <div class="pemesanan-item-info__detail">Size '{{ $detail->productDetail->size }}'
                            - {{ $detail->quantity }} Barang
                        </div>
                        <div
                            class="pemesanan-item-info__price">{{ \App\Helpers\PriceHelper::rupiah($detail->total_price) }}</div>
                    </div>
                </div>
            @empty
                Tidak ada data
            @endforelse
        </div>

        @if ($order->voucher)
            @php ($voucher = json_decode($order->voucher, TRUE))
            <div class="voucher">
                <div class="title">Voucher</div>
                <div class="voucher-item">
                    <b>{{ $voucher['code'] }} - ({{ \App\Helpers\PriceHelper::rupiah($order->discount_total) }} off)</b>
                    <p>{{ nl2br($voucher['note']) }}</p>
                </div>
            </div>
        @endif

        <div class="total">
            <div class="title">Total</div>
            <div class="total-item">{{ \App\Helpers\PriceHelper::rupiah($order->grand_total) }}</div>
        </div>

        <div class="pembayaran">
            <div class="d-flex justify-content-between align-items-center">
                <div class="title">Pembayaran</div>
                <a href="{{ route('ppdb.embed-product.detail-payment', ['id' => $order->id]) }}"
                   class="title text-grey">lihat detail</a>
            </div>
            <div class="pembayaran-item">
                @if(!empty($order->payment_option))
                    <div class="pembayaran-item__title">Bank {{ $order->payment_option }} <span
                            class="{{ $order->payment_option }}"></span></div>
                    <div class="pembayaran-item__content">No. VA: <span id="virtual_account_number_mobile">{{ $order->virtual_account_number  }}</span>
                    </div>
                    <img class="icon-normal"
                         onclick="CopyToClipboardMobile('virtual_account_number_mobile')"
                         id="copy-va"
                         src="{{asset('frontend-ppdb-online/img/Icon/Data-Active.png')}}"
                         alt="Copy" title="Copy">
                @else
                    <div class="pembayaran-item__title">
                        Bank {{ \App\Helpers\PriceHelper::paymentInfo($user->unit, \App\Helpers\Helper::isVaBcaEnable() ? 'BCA' : NULL)['bank'] }}
                        <span
                            class="{{ \App\Helpers\PriceHelper::paymentInfo($user->unit, \App\Helpers\Helper::isVaBcaEnable() ? 'BCA' : NULL)['bank'] }}"></span>
                    </div>
                    <div class="pembayaran-item__content">No. VA:
                        <span>{{ \App\Helpers\PriceHelper::virtualAccountNumber($user, true, \App\Helpers\Helper::isVaBcaEnable() ? 'BCA' : NULL) }}</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="info">
            Anda bisa melihat detail pemesanan lewat email.
        </div>

        @if((ENV("APP_ENV") == "local") || (ENV("APP_ENV") == "staging"))
            <div class="cancel-order" id="cancel-order">
                @if ($order->status === \App\Models\ProductOrder::STATUS_NEW_ORDER)
                    <h3 class="text-black">Cancel Order</h3>
                @else
                    <h3 class="text-black">Status Order</h3>
                @endif
                <div class="pembayaran-item">
                    <div class="pembayaran-item__content">
                        @if ($order->status === \App\Models\ProductOrder::STATUS_NEW_ORDER)
                            <div style="display: inline-block">
                                Status: {!! $order->label_konfirmasi_pembayaran !!}
                            </div>
                            <div style="display: flex; align-items:center; margin:1rem 0">

                                <div class="cancel-reason" style="width: 100%">
                                    <input type="hidden" name="id" id="id" value={{ $order->id }} />
                                    <div class="label-note">Keterangan</div>
                                    <div class="text-note" style="width: 100%; padding-left: 3em">
                                        <textarea class="form-control" name="reason" id="reason"
                                                  placeholder="" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end" style="padding-top: 1em">
                                <button type="button" class="btn btn-green post-cancel-order" id="post-cancel-order">
                                    Cancel Order
                                </button>
                            </div>
                        @endif
                        @if ($order->status === \App\Models\ProductOrder::STATUS_CANCEL)
                            <div style="display: inline-block">
                                Status: {!! $order->label_konfirmasi_pembayaran !!}
                            </div>
                            @if(!empty($order->payment_cancel_reason))
                                <div class="info">
                                    Anda telah melakukan pembatalan order pada <label class="label"
                                                                                      style="color: #dc3545">
                                        {{ \App\Helpers\Helper::hariTanggalJam($order->payment_cancel_date) }}</label>
                                </div>
                            @endif
                            <div class="info">
                                Keterangan Pembatalan :
                                @if(!empty($order->payment_cancel_reason))
                                    <label class="label"
                                           style="color: #dc3545">{{$order->payment_cancel_reason}}</label>
                                @else
                                    <label class="label" style="color: #dc3545">Melebihi Batas Waktu Pembayaran</label>
                                @endif
                            </div>
                        @endif

                        @if ($order->status === \App\Models\ProductOrder::STATUS_CONFIRMED)
                            <div style="display: inline-block">
                                Status: {!! $order->label_konfirmasi_pembayaran !!}
                            </div>
                            <div class="info">
                                Pembayaran anda telah terkonfirmasi pada <label class="label" style="color: #28a745">
                                    {{ \App\Helpers\Helper::hariTanggalJam($order->payment_confirmed_date) }}</label>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="pembayaran" id="konfirmasi-pembayaran">
                <h3 class="text-black">Status Pembayaran</h3>
                <div class="pembayaran-item">
                    <div class="pembayaran-item__content">
                        <div style="display: inline-block">
                            Status: {!! $order->label_konfirmasi_pembayaran !!}
                        </div>

                        @if ($order->status === \App\Models\ProductOrder::STATUS_NEW_ORDER)
                            <div style="display: flex; align-items:center; margin:1rem 0">
                                <button class="btn btn-sm btn-green upload-file-button">
                                    <img src="{{asset('frontend-ppdb-online/img/Icon/upload.png')}}" alt=""><span
                                        class="text-white">Upload</span></button>
                            </div>
                            <form id="wrapped" method="POST" enctype="multipart/form-data">
                                <input type="file" name="payment_image" id="upload_file"
                                       accept="image/x-png,image/jpeg,application/pdf"/>
                                <input type="hidden" name="id" value={{ $order->id }} />
                            </form>
                        @endif

                        <div class="preview-konfirmasi-pembayaran {{ $order->getPaymentImageUrl() ? NULL : "hide" }}">
                            <img src="{{ $order->getPaymentImageUrl() }}"
                                 style="display: block; width: 100%; height: auto;"/>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if ($order->pickup_date_schedule)
            <div class="pembayaran" id="qr-code-mobile">
                <h3 class="text-black">Pengambilan Seragam</h3>
                <div class="pembayaran-item">
                    <div class="pembayaran-item__content">
                        <div style="color: black">Silahkan unduh detail transaksi berikut ini sebagai persyaratan
                            pengambilan seragam
                        </div>
                        <a class="btn btn-green" style="margin-top: 10px"
                           href="{{ route('ppdb.embed-product.order.pdf', $order->id) }}">
                            <img class="icon-active" src="{{asset('frontend-ppdb-online/img/Icon/Data-Normal.png')}}"
                                 alt="" style="margin-right: 10px">
                            Download
                        </a>
                        <div style="color: black; margin-top: 20px">QR code ditunjukkan kepada petugas saat pengambilan
                            seragam
                        </div>
                        <button class="btn" style="margin-top: 10px;" data-toggle="modal" data-target="#qrCodeModal">
                            <img class="icon-active" src="{{asset('frontend-ppdb-online/img/Icon/qr-code-icon.png')}}"
                                 alt="" width="30px">
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="modal fade" id="qrCodeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">QR Code</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        {!! QrCode::size(200)->generate(route('admin.product-order-pickup.qr-result', $order->id )) !!}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('js/sweet-alert/sweet-alert.min.js')}}"></script>
    <script>
        $(document).on('click', '.upload-file-button', function () {
            $("#upload_file").click();
        });

        $(document).on('change', '#upload_file', function () {
            if ($(this).val()) {
                var self = $(this);
                var formData = new FormData($('#wrapped')[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    type: "POST",
                    url: "{{route('ppdb.embed-product.upload-order-confirmation')}}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('.upload-file-button').hide();
                        $('.preview-konfirmasi-pembayaran').hide();
                    },
                    error: function (data) {
                        $('.upload-file-button').show();
                        // $('.preview-konfirmasi-pembayaran').show();
                    },
                    success: function (data) {
                        $('.upload-file-button').show();
                        $('.preview-konfirmasi-pembayaran img').attr('src', data.path);
                        $('.preview-konfirmasi-pembayaran').show();
                    }
                });
                return false;
            }
        })

        $(document).on('click', '.post-cancel-order', function (e) {
            e.preventDefault();
            var id = $('#id').val();
            var reason = $('#reason').val();

            var success = true;
            var message = '';

            console.log(reason);
            if (reason == '' || reason == null) {
                success = false;
                message = 'Keterangan Harap Diisi!'
            }

            if (success) {
                swal({
                    title: 'Perhatian !',
                    text: "Apakah Anda yakin membatalkan order ini?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((result) => {
                    if (result) {
                        $.post('{{ route('ppdb.embed-product.post-cancel-order') }}', {
                            _token: '{{ csrf_token() }}',
                            product_order_id: id,
                            payment_cancel_reason: reason
                        }, function (data, status) {
                            if (data.status === 'success') {
                                window.location.reload();
                            } else {
                                swal(
                                    'Gagal!',
                                    'Cancel Order Gagal!. Silahkan coba kembali',
                                    'error'
                                );
                            }

                        })
                    }
                });
            } else {
                swal(
                    'Gagal!',
                    message,
                    'error'
                );
            }

        });

        function CopyToClipboard(id) {
            var r = document.createRange();
            r.selectNode(document.getElementById(id));
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(r);
            try {
                document.execCommand('copy');
                window.getSelection().removeAllRanges();
                console.log('Successfully copy text: hello world ' + r);
                swal({
                    text: "Virtual Account berhasil disalin!",
                });
            } catch (err) {
                console.log('Unable to copy!');
            }
        }

        function CopyToClipboardMobile(id) {
            var r = document.createRange();
            r.selectNode(document.getElementById(id));
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(r);
            try {
                document.execCommand('copy');
                window.getSelection().removeAllRanges();
                console.log('Successfully copy text: hello world ' + r);
                swal({

                    text: "Virtual Account berhasil salin!",
                });
            } catch (err) {
                console.log('Unable to copy!');
            }
        }

    </script>
@endpush
@push('styles')
    <style>
        #upload_file {
            display: none;
            position: absolute;
            left: -100px;
            top: -100px;
        }

        .hide {
            display: none;
        }

        .preview-konfirmasi-pembayaran {
            margin-top: 25px;
        }

        .nav {
            display: block;
            text-align: center;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12);
        }

        .nav span {
            /* font-family: Roboto; */
            padding: 14px 0;
            font-style: normal;
            font-weight: 700;
            font-size: 18px;
            line-height: 21px;
            text-align: center;
            color: #06270A;
            display: block;
        }

        .title {
            /* font-family: Roboto; */
            font-style: normal;
            font-weight: normal;
            font-size: 16px;
            line-height: 19px;
            color: #06270A;
            margin-bottom: 10px;
        }

        .pemesan, .pemesanan, .pembayaran, .total, .info, .voucher {
            margin: 25px;
        }

        .pemesan-content {
            margin0todd
            background: #FFFFFF;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12);
            border-radius: 6px;
            padding: 10px;
            display: flex;
            width: 100%;
        }

        .pemesan-content img {
            width: 120px;
            height: auto;
            border-radius: 50%;
        }

        .pemesan-info {
            margin-left: 10px;
            flex: 1;
            /* font-family: Roboto; */
            font-style: normal;
            font-weight: 500;
            font-size: 16px;
            line-height: 19px;
            color: #06270A;
            align-self: center;
        }

        .pemesan-info span {
            /* font-family: Roboto; */
            display: block;
            font-style: normal;
            font-weight: normal;
            font-size: 12px;
            line-height: 14px;
            color: #89998B;
        }

        .pemesanan-item {
            background: #FFFFFF;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12);
            border-radius: 6px;
            display: flex;
            margin-top: 15px;
        }

        .pemesanan-item img {
            width: 200px;
            height: auto;
            border-radius: 8px;
        }

        .pemesanan-item-info {
            flex: 1;
            align-self: center;
            margin-left: 13px;
        }

        .pemesanan-item-info__title {
            /* font-family: Roboto; */
            font-style: normal;
            font-weight: bold;
            font-size: 18px;
            line-height: 27px;
            color: #06270A;
        }

        .pemesanan-item-info__detail {
            /* font-family: AcuminPro; */
            font-size: 14px;
            line-height: 24px;
            color: #89998B;
        }

        .pemesanan-item-info__price {
            /* font-family: AcuminPro; */
            font-size: 16px;
            line-height: 24px;
            color: #42B549;
        }

        .pembayaran-item {
            background: #FFFFFF;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12);
            border-radius: 6px;
            padding: 14px;
        }

        .pembayaran-item__title {
            /* font-family: Roboto; */
            font-style: normal;
            font-weight: bold;
            font-size: 14px;
            line-height: 16px;
            color: #06270A;
            margin-bottom: 10px;
            position: relative;
        }

        .pembayaran-item__title span {
            display: inline;
            background-image: url("data:image/svg+xml,%3Csvg width='19' height='18' viewBox='0 0 19 18' fill='none' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'%3E%3Crect width='18.4186' height='18' fill='url(%23pattern0)'/%3E%3Cdefs%3E%3Cpattern id='pattern0' patternContentUnits='objectBoundingBox' width='1' height='1'%3E%3Cuse xlink:href='%23image0' transform='scale(0.0227273 0.0232558)'/%3E%3C/pattern%3E%3Cimage id='image0' width='44' height='43' xlink:href='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACwAAAArCAYAAAADgWq5AAAAAXNSR0IArs4c6QAAAERlWElmTU0AKgAAAAgAAYdpAAQAAAABAAAAGgAAAAAAA6ABAAMAAAABAAEAAKACAAQAAAABAAAALKADAAQAAAABAAAAKwAAAABOTjuzAAAEQUlEQVRYCe2YXWgUVxTH753PndlJrIZqfIgf0URpTNQggiFWVBSFon0R3wqlT30ShD4UpS9+PQk+2EJBBX2zUERL+mBAqWJf2hrygRKalFqSiG2UmmRnd77u9ZyNg7g7Ozt3M5EEvDDM3jtnzv3Nf8459+7QH/du4ZQs/KZKlEz+PbZVIRwaIRSOBd04yBow35eQcqHDhowe/CgC48Biae+B5/tNLTqFlUhFoG74IwOET0dendMg/YAQZW0H1KXaimk5MBY5e9I71PffNGdMY5zNCfCtmwGy8HKK9ba01ml7OoBYHLocGJwyztXfr3xv7PzqhPHWhCl0rA9XkK4Hv8z8unuXpXVvFvYYGcOStZyMnz9pPO75aQY94tKS5rG662Nr1bmzNnsxnA4welHbNpOHnxyynv81kqM1xlscTdfxrzVlRbvLvUKcWdm1SIVDq8zudnJ7XUvGte1CmtD4tiB6lQM/9LLC/WEGry+csuo5FphQiajdLfLPR/dL4NJPG9qoX5LZ9cdveffuQFXQ0CAeGKyoZhJvvF+7f+YbF7rJpQhniDmj0k2d27LN313IBf/2x1i+uVQVGE2lpevIxMVT5uOeW3aaKocY2788lsls2O9wJxcOVTwnAsa71Y8wCQ9nJ0dHUoV+Hc/ywWs3uPtgBOI5vu4nBkboYhKub9G9fPpJqJpmZs/QgO3cHcSpKjYhYExCDZKw58i+eUnCxrZ2a+PVSzPBROV4FgOG5y4m4cSAdu/0PCQh+O/87Asju+OIw/NTkSoLA6MXTMKn354yH/XcLC4qmIipHLOI8oHL19FhQFhQBl2+lygziR7AJHx0/FNt2Z3Pp2CDJKVZ8DTD5PVNrWT62Xhd6ew1A3ucsO3NG5k32FsPO41SvzX3ZbhzzKX2/3SpKUvlAVATsA8La5vm2rpMrECyaoYrvRE3mwEnznCg65XAyh+h1EtJH6vkSsnPNSjMAuepNhmK8KCrclAZhY5sQsDIZ1BeWK97GT9tWJB33FfyOS5n4rb1YsCc+x2aSwNOKyoQKUuVQQQsMFIY9RQDVI61TgzswZeXTbrnQh7o8S5j54u8KFMeDDkaVWn17ySJgBkk2RrFm1kic5OlTCuDvE9cteASSY98mpLBqsDIV0eDfJPqZ9OOW5w855H8P75sJi3kVYHhK6EHoSCDynG5UKJDsi4l3B/ydFmBdS3ZHVW+rcHiwDt0xwdnWsqRQACS/OmqDqNESwqLdhUVDuChW1U/Z8rEiN+hikw3awufesnLgOaeMSWbWNrX00QuKKhmA/XzjaoPi4PA+0rAXgwsTtwhV4XVTPy9RQJj3K7Wg7wdSGkvZriEcai3GLZCoRBqEQkM0aD2Ofoy8ecP3cafsSKIhkLoMRoYriYtM6Gjd3WumHTvCkB0nvfAooqJ2i9OhWvNWFF15mKPjCocCv7dxc5Ch0ZIWVKUVxwjnVMY5LJiAAAAAElFTkSuQmCC'/%3E%3C/defs%3E%3C/svg%3E%0A");
            width: 19px;
            height: 18px;
            float: right;
        }

        .pembayaran-item__title span.Mandiri {
            background-image: url('data:image/png;base64,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');
            background-size: cover;
            width: 60px;
            height: 60px;
            position: absolute;
            right: 0;
            top: -25px;
        }

        .pembayaran-item__content {
            /* font-family: Roboto; */
            font-style: normal;
            font-weight: normal;
            font-size: 14px;
            line-height: 16px;
            color: #06270A;
            padding-top: 15px;
            border-top: 1px solid #E6EAE7;
            margin-bottom: 10px;
        }

        .pembayaran-item__content span {
            color: #189D49;
        }

        .total-item {
            /* font-family: Acumin Pro; */
            font-style: italic;
            font-weight: bold;
            font-size: 24px;
            line-height: 24px;
            color: #42B549;
        }

        .info {
            /* font-family: Roboto; */
            font-style: normal;
            font-weight: normal;
            font-size: 16px;
            line-height: 19px;
            color: #89998B;
            padding: 10px 0;
            border-bottom: 1px solid #E6EAE7;
        }

        @media only screen and (max-width: 425px) {
            .pemesan-content img {
                width: 80px;
            }

            .pemesanan-item img {
                width: 120px;
            }

            .pemesan, .pemesanan, .pembayaran, .total, .info, .voucher {
                margin: 25px 5px;
            }
        }

        .arrow-back {
            background-image: url("data:image/svg+xml,%3Csvg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' clip-rule='evenodd' d='M16.1371 1.35743C16.6137 0.880857 17.3863 0.880857 17.8629 1.35743C18.3395 1.834 18.3395 2.60668 17.8629 3.08325L8.94616 12L17.8629 20.9167C18.3395 21.3933 18.3395 22.166 17.8629 22.6426C17.3863 23.1191 16.6137 23.1191 16.1371 22.6426L6.35743 12.8629C5.88086 12.3863 5.88086 11.6137 6.35743 11.1371L16.1371 1.35743Z' fill='%2389998B'/%3E%3C/svg%3E%0A");
            width: 24px;
            height: 24px;
            position: absolute;
            top: 12px;
            left: 15px;
            background-size: cover;
        }


        .cancel-reason {
            display: block;
            background: #FFFFFF;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12);
            border-radius: 8px;
            margin-top: 20px;
            padding: 13px;
            /* font-family: Roboto;
            font-style: normal; */
            font-size: 16px;
            line-height: 19px;
            color: #06270A;
            display: flex;
            align-items: center
        }

        .label-note {
            width: 55px;
        }

        .swal-text {
            text-align: center;
            color: red;
        }
    </style>
@endpush
