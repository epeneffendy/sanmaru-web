@extends('layouts.ppdb-online.main')
@section('content')

    <div class="wrapper-content-desktop">
        <div class="container" style="padding: 3rem">
            <h2>List Pesanan</h2>
            <div class="row">
                @foreach ($orders as $order)
                <div class="pemesanan">
                    <div class="pemesanan-item">
                        <div class="container">
                            <div class="row">
                                <!-- <div class="pemesanan-thumbnail" style="background-image: url('{{asset('frontend-ppdb-online/img/thumbnail-product.png')}}')"></div> -->
                                <div class="pemesanan-thumbnail" style="background-image: url('{{ $order->getFirstImageThumbnail() }}')"></div>
                                <div class="col">
                                    <div class="pemesanan-item-info">
                                        <div class="pemesanan-item-info__title"><a href="{{ route('ppdb.embed-product.order', ['id' => $order->id]) }}"><h3>Nomor invoice: {{ $order->invoice_no }}</h3></a></div>
                                        <div class="pemesanan-item-info__price">{{ \App\Helpers\PriceHelper::rupiah($order->grand_total) }}</div>
                                        <div class="text-title-3 font-italic text-black mt-2">Detail Pemesanan</div>
                                        <div class="text-title-3 font-italic text-black">{{ $order->productOrderDetails->count() }} Barang</div>
                                        {{-- @if ($voucher = json_decode($order->voucher, TRUE))
                                        <div class="pemesanan-item-info__detail">{{ $voucher['code'] }} - {{ \App\Helpers\PriceHelper::rupiah($order->discount_total) }} off</div>
                                        @endif --}}

                                        {{-- @if ($order->status === \App\Models\ProductOrder::STATUS_NEW_ORDER)
                                            <div class="pemesanan-item-info__button-cancel">
                                                <a href="{{ route('ppdb.embed-product.order', ['id' => $order->id]) }}#konfirmasi-pembayaran"><button class="btn btn-green btn-sm-mobile">Konfirmasi bayar</button></a>
                                                <a href="#" class="btn-pemesanan-cancel" data-product-order-id={{ $order->id }}><button class="btn btn-red btn-sm-mobile">Cancel</button></a>
                                            </div>
                                        @endif --}}

                                        {{-- @if ($order->status === \App\Models\ProductOrder::STATUS_CANCEL)
                                            <span style="color: #E00000;">Dibatalkan</span>
                                        @endif --}}
                                    </div>
                                </div>
                                <div class="col d-flex justify-content-between align-items-end flex-column">
                                    @if ($order->status === \App\Models\ProductOrder::STATUS_CONFIRMED)
                                    <div class="pemesanan-status pemesanan-status-green">
                                        <img src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                        <span>Terkonfirmasi</span>
                                    </div>
                                    @endif
                                    @if ($order->status === \App\Models\ProductOrder::PAYMENT_STATUS_NOT_CONFIRMED)
                                    <div class="pemesanan-status pemesanan-status-yellow">
                                        <img src="{{asset('frontend-ppdb-online/img/Icon/Tab/wait.png')}}" alt="">
                                        <span>Menunggu</span>
                                    </div>
                                    @endif
                                    @if ($order->status === \App\Models\ProductOrder::STATUS_CANCEL)
                                    <div class="pemesanan-status pemesanan-status-red">
                                        <img src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                        <span>Batal</span>
                                    </div>
                                    @endif
                                    @if ($order->status === \App\Models\ProductOrder::STATUS_NEW_ORDER)
                                    <div class="pemesanan-status pemesanan-status-purple">
                                        <img src="{{asset('frontend-ppdb-online/img/Icon/Tab/exclamation.png')}}" alt="">
                                        <span>Belum Bayar</span>
                                    </div>
                                    <div class="pemesanan-item-info__button-cancel">
                                        <a href="#" class="btn-pemesanan-cancel" data-product-order-id={{ $order->id }}><button class="btn btn-red btn-sm-mobile">Cancel</button></a>
                                    </div>
                                    @endif

                                    <a href="{{ route('ppdb.embed-product.order', ['id' => $order->id]) }}" class="text-title-3 text-grey">Lihat detail</a>
                                </div>
                            </div>
                        </div>
                        {{-- <img src="{{ @$order->productOrderDetails->first()->product->image }}" /> --}}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="wrapper-content-mobile">
        <div class="row pl-3">
            <a href="{{ route('ppdb.embed-product.index') }}" class="d-flex align-items-center justify-content-around"><img class="head-left" src="{{asset('frontend-ppdb-online/img/Icon/Icon-Arrow.png')}}" alt=""><span class="text-body-title text-primary-green ml-2">Kembali</span></a>
            {{-- <a href="{{ route('ppdb.embed-product.index') }}" class="arrow-back"></a>
            <span>List Pesanan</span> --}}
        </div>

        @foreach ($orders as $order)
        <div class="pemesanan">
            <div class="pemesanan-item {{ $order->status === \App\Models\ProductOrder::STATUS_CANCEL ? 'cancel-order' : NULL }}">
                <div class="container">
                    <div class="row">
                        <div class="col-6 d-flex align-items-center">
                            <!-- <img src="{{asset('frontend-ppdb-online/img/thumbnail-product.png')}}" /> -->
                            <img src="{{ $order->getFirstImageThumbnail() }}" />
                        </div>
                        <div class="col-6">
                            <div class="pemesanan-item-info">
                                <div class="pemesanan-item-info__title"><a href="{{ route('ppdb.embed-product.order', ['id' => $order->id]) }}">{{ $order->invoice_no }}</a></div>
                                <div class="pemesanan-item-info__detail">{{ $order->productOrderDetails->count() }} Barang</div>
                                @if ($voucher = json_decode($order->voucher, TRUE))
                                <div class="pemesanan-item-info__detail"><b>{{ $voucher['code'] }} - {{ \App\Helpers\PriceHelper::rupiah($order->discount_total) }} off</div>
                                @endif
                                <div class="pemesanan-item-info__price">{{ \App\Helpers\PriceHelper::rupiah($order->grand_total) }}</div>

                                @if ($order->status === \App\Models\ProductOrder::STATUS_NEW_ORDER)
                                    <div class="pemesanan-item-info__button-cancel">
                                        <a href="{{ route('ppdb.embed-product.order', ['id' => $order->id]) }}#konfirmasi-pembayaran"><button class="btn btn-green btn-sm-mobile">Konfirmasi bayar</button></a>
                                        <a href="#" class="btn-pemesanan-cancel" data-product-order-id={{ $order->id }}><button class="btn btn-red btn-sm-mobile">Cancel</button></a>
                                    </div>
                                @endif

                                @if ($order->status === \App\Models\ProductOrder::STATUS_CANCEL)
                                    <span style="color: #E00000;">Dibatalkan</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <img src="{{ @$order->productOrderDetails->first()->product->image }}" /> --}}
            </div>
        </div>
        @endforeach
    </div>


@endsection

@push('scripts')
    <script src="{{asset('js/sweet-alert/sweet-alert.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('.btn-pemesanan-cancel').click(function(e) {
                e.preventDefault();
                var id = $(this).data('product-order-id');
                swal({
                    title: 'Apakah Anda yakin melakukan pembatalan pesanan ini?',
                    text: "",
                    icon: "error",
                    buttons: true,
                    dangerMode: true,
                }).then((result) => {
                    if (result) {
                        $.post('{{ route('ppdb.embed-product.cancel-order') }}', {
                            _token: '{{ csrf_token() }}',
                            product_order_id: id
                        }, function(data, status) {
                            if (data.status === 'success') {
                                window.location.reload();
                            } else {
                                swal(
                                    'Gagal!',
                                    'Menghapus item dari keranjang. Silahkan coba kembali',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush

@push('styles')
    <link href="{{asset('css/plugin/sweet-alert/sweet-alert.css')}}" rel="stylesheet" />
    <style>
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

        .pemesan, .pembayaran, .total, .info, .voucher {
            margin: 25px;
        }

        .pemesan-content {
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
            /* padding: .5rem; */
        }

        .pemesanan-item.cancel-order {
            box-shadow: 0px 2px 10px rgba(224, 0, 0, 1);
        }



        .pemesanan-item-info {
            flex: 1;
            align-self: center;
            /* margin-left: 13px; */
            position: relative;
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

        .pemesanan-item-info__button-cancel {
            /* position: absolute; */
            /* right: 5px; */
            /* bottom: 50%; */
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
                width: 100%;
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
    </style>
@endpush
