@extends('layouts.welcome-page.main')
@section('content')

<div class="wrapper-content-desktop">
    <div class="container" style="padding: 3rem">
        <h2>Informasi Produk</h2>
        <div class="row">
            <div class="col">
                <img src="{{ $product->image }}" class="header-image" />
            </div>

            <div class="col">
                <div class="row content-detail">
                    {{-- <a href="{{ route('embed-product') }}" class="link-back"></a>
                    <a href="{{ route('embed-product.cart') }}" class="link-cart"></a> --}}

                    <h1 class="text-black">{{ $product->name }}</h1>
                    <span class="text-subtitle-2 text-light-green">{{ $product->price_siswa_range }}</span>

                    <div class="detail" style="margin: 1rem 0">
                        <h3 class="mb-3 text-black">Informasi Produk</h3>
                        <div class="text-subtitle-2 info my-3 text-grey">Stok <span class="pull-right text-black">{{ $product->total_stock }}</span></div>
                        <div class="text-subtitle-2 info my-3 text-grey">Kategori <span class="pull-right text-black">{{ $product->category_name }}</span></div>
                        <div class="text-subtitle-2 info my-3 text-grey">Berat <span class="pull-right text-black">{{ $product->weight }}gr</span></div>
                        <div class="text-subtitle-2 info my-3 text-grey">Tipe <span class="pull-right text-black">{{ $product->type_name }}</span></div>
                        <div class="text-subtitle-2 info my-3 text-grey">Merk <span class="pull-right text-black">{{ $product->merk }}</span></div>
                    </div>

                    <div class="description" style="margin: 1rem 0">
                        <h3 class="text-black mb-3">Deskripsi Produk</h3>
                        <p class="text-subtitle-2" id="description" style="">{!! $product->description !!}</p>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="#" class="btn btn-green" data-toggle="modal" data-target="#detailModal"><span>Tambahkan ke Keranjang</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="wrapper-content-mobile">
    <div class="row mb-3 d-flex justify-content-between px-4">
        <a href="{{ route('embed-product') }}" class="d-flex align-items-center justify-content-around"><img class="head-left" src="{{asset('frontend-ppdb-online/img/Icon/Icon-Arrow.png')}}" alt=""><span class="text-body-title text-primary-green ml-2">Kembali</span></a>
        <a href="{{ route('embed-product.cart') }}"><img src="{{asset('frontend-ppdb-online/img/Icon/Cart-Active.png')}}" alt=""></a>
    </div>
    <div class="row">
        <img src="{{ $product->image }}" class="header-image" />
    </div>
    <div class="row content-detail">
        {{-- <a href="{{ route('embed-product') }}" class="link-back"></a>
        <a href="{{ route('embed-product.cart') }}" class="link-cart"></a> --}}

        <div class="text-body-title text-black">{{ $product->name }}</div>
        <div class="text-body text-primary-green">{{ $product->price_siswa_range }}</div>

        <hr>
            <a href="#" class="d-flex align-items-center justify-content-between py-2" onclick="showDescription(this)">
                <div class="">
                    <h5 class="">Deskripsi Produk</h5>
                </div>
                {{-- <div class="d-flex align-items-center">
                    <span class="text-primary-green">Pilih Ukuran</span>
                    <span class="text-grey pl-1">(contoh: S, M, L, XL)</span>
                </div> --}}
                <img src="{{asset('frontend-ppdb-online/img/Icon/Icon-Arrow.png')}}" id="description-arrow" alt="" style="transform: rotate(-90deg)">
            </a>
            <p class="" id="description" style="display: none">{!! $product->description !!}</p>
        <hr>

        <div class="detail">
            <h5 class="mb-3">Informasi Produk</h5>
            <div class="text-description info">Stok <span class="pull-right text-grey">{{ $product->total_stock }}</span></div>
            <div class="text-description info">Kategori <span class="pull-right text-grey">{{ $product->category_name }}</span></div>
            <div class="text-description info">Berat <span class="pull-right text-grey">{{ $product->weight }}gr</span></div>
            <div class="text-description info">Tipe <span class="pull-right text-grey">{{ $product->type_name }}</span></div>
            <div class="text-description info">Merk <span class="pull-right text-grey">{{ $product->merk }}</span></div>
        </div>

        <div class="footer">
            <!-- <a href="#" class="button" data-toggle="modal" data-target="#fittingModal"><span>Jadwal Fitting</span></a> -->
            <a href="#" class="button btn-green" data-toggle="modal" data-target="#detailModal"><span>Tambahkan ke Keranjang</span></a>
        </div>
    </div>
</div>

    @include('ppdb-online/embed/_modal_fitting', ['user_fittings' => $user_fittings, 'fittings' => $fittings])

    <!-- Detail produk modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close pull-left" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2 class="modal-title" id="myModalLabel">Detail Produk</h2>
            </div>
            <div class="modal-body">
                <div class="modal-detail-content">
                    <div class="content-header">
                        <img src="{{ $product->image }}" />
                        <div class="content-header-right">
                            {{ $product->name }}
                            <span class="content-header-price" id="label-harga">{{ $product->price_siswa_range }}</span>
                        </div>
                    </div>
                </div>
                <div class="text-danger" style="margin-top: 10px">Pastikan ukuran/pesanan yang anda pilih sudah sesuai. <b>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan</b></div>
                <div class="modal-detail-ukuran">
                    <div class="label-ukuran">Ukuran</div>
                    <div class="pilihan-ukuran">
                        <ul>
                        @forelse ($product->details as $detail)
                            <li {!! $detail->stock == 0 ? 'class="disabled"' : NULL !!} data-product-id="{{ $product->id }}" data-id="{{ $detail->id }}" data-price="{{ $detail->price_siswa }}" data-stock="{{ $detail->stock }}">{{ $detail->size }}</li>
                        @empty
                            tidak ada ukuran
                        @endforelse
                        </ul>
                    </div>
                </div>
                <div class="modal-detail-note">
                    <div class="label-note">Note</div>
                    <div class="text-note">
                        <input class="form-control" type="text" id="note" name="note" />
                    </div>
                </div>
                <div class="modal-detail-jumlah">
                    <div class="label-jumlah">Jumlah</div>
                    <div class="pilihan-jumlah">
                        <span class="minus minus-active" data-type="minus"></span>
                        <span class="jumlah" id="jumlah">1</span>
                        <span class="plus plus-active" data-type="plus"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="subtotal">
                    Subtotal
                    <span id="label-subtotal">-</span>
                </div>
                <button type="button" class="btn btn-green" id="button-lanjutkan">Lanjutkan</button>
            </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('js/sweet-alert/sweet-alert.min.js')}}"></script>
    <script>
        var product_size = false;
        var product_id = false;
        var product_detail_id = false;
        var product_qty = false;
        var product_price = false;
        var product_stock = false;
        var product_note = false;

        $(document).ready(function() {
        });

        function formatter(number) {
            let format = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
            });

            return format.format(number);
        }

        $(document).on('click', '.pilihan-ukuran li:not(.disabled)', function(e) {
            e.preventDefault();
            $(this).parent().find('li').removeClass('active');
            $(this).addClass('active');

            product_detail_id = $(this).data('id');
            product_id = $(this).data('product-id');
            product_size = $(this).data('size');
            product_price = $(this).data('price');
            product_stock = $(this).data('stock');
            product_qty = 1;

            let rupiah = formatter(product_price);

            $('#jumlah').html(product_qty);
            $('#label-harga').html(rupiah);
            $('#label-subtotal').html(rupiah);
        });

        $(document).on('click', '#detailModal .minus-active, #detailModal .plus-active', function(e) {
            if (!$('.pilihan-ukuran li.active').length) {
                return;
            }

            let sibling = $(this).data('type') === 'minus' ? 'plus' : 'minus';

            if (($(this).data('type') === 'minus' && product_qty === 1)
                || ($(this).data('type') === 'plus' && (product_qty+1) >= product_stock)) {
                $(this).removeClass('minus-active');
                $(this).removeClass('plus-active');
            }

            $(this).siblings('.minus, .plus').addClass(sibling+'-active');

            if (sibling === 'minus') {
                product_qty++;
            } else {
                product_qty--;
            }

            let rupiah = formatter(product_price*product_qty);

            $('#jumlah').html(product_qty);
            $('#label-subtotal').html(rupiah);
        });

        $(document).on('click', '#button-lanjutkan', function(e) {
            e.preventDefault();
            if (product_qty === 0 || product_qty === false) {
                alert('harap memilih ukuran dan kuantiti produk terlebih dahulu');
                return;
            }

            $.post('{{ route('embed-product.post-product') }}', {
                _token: '{{ csrf_token() }}',
                id: product_id,
                detail_id: product_detail_id,
                qty: product_qty,
                note: $('#note').val(),
            }, function(data, status) {
                if (data.status === 'success') {
                    window.location.href = "{{ route('embed-product.cart') }}";
                } else {
                    swal(
                        'Gagal!',
                        'Menambahkan produk kedalam keranjang gagal. Silahkan coba kembali',
                        'error'
                    );
                }
            });
        });

        function showDescription(element) {
            event.preventDefault();
            $("#description").toggle()
            $("#description-arrow").toggleClass('flip')
        }

    </script>
@endpush
@push('styles')
    <link href="{{asset('css/plugin/sweet-alert/sweet-alert.css')}}" rel="stylesheet" />
    <style>
        .container-fluid {
            margin-bottom: 10px;
        }

        .header-image {
            width: 100%;
            height: auto;
            display: block;
            z-index: -1;
        }

        .content-detail {
            background-color: #FFFFFF;
            /* border-radius: 30px 30px 0px 0px; */
            /* margin-top: -40px; */
            min-height: 400px;
            padding: 21px 25px 21px 25px;
            /* font-family: Roboto; */
            /* font-style: normal; */
            /* font-weight: 700; */
            /* font-size: 18px; */
            line-height: 27px;
            color: #1D1D1D;
            display: block;
        }

        .price {
            /* font-family: Acumin Pro; */
            font-style: italic;
            font-size: 16px;
            line-height: 24px;
            color: #42B549;
            display: block;
            margin: 15px 0 17px 0;
        }

        .footer {
            text-align: center;
        }

        .button {
            position: relative;
            margin: 0 auto;
            background: linear-gradient(225deg, #489F59 0%, #266C34 100%);
            border-radius: 20px;
            width: 182px;
            height: 40px;
            display: inline-block;
        }

        .button span {
            position: absolute;
            font-size: 12px;
            line-height: 14px;
            text-align: center;
            color: #FFFFFF;
            height: 14px;
            left: 6.41%;
            right: 6.41%;
            top: calc(50% - 14px/2);
            text-decoration: none;
        }

        .detail:nth-child(5) {
            border-bottom: none;
            margn-bottom: 20px;
        }

        .detail p {
            /* font-family: Acumin Pro; */
            font-weight: bold;
            font-size: 14px;
            line-height: 17px;
            color: #89998B;
        }

        body#ppdb .content-detail h5 {
            font-size: 14px;
            line-height: 16px;
            color: #06270A
        }

        .info {
            /* font-family: Acumin Pro; */
            /* font-style: italic; */
            /* font-weight: bold; */
            /* font-size: 14px; */
            line-height: 17px;
            color: #1D1D1D;
            margin-bottom: 11px;
        }

        .info:last-child {
            margin-bottom: 0;
        }

        .info .pull-right {
            /* font-family: Acumin Pro; */
            /* font-style: italic;
            font-weight: bold; */
            /* font-size: 14px; */
            line-height: 17px;
            text-align: right;
            color: #89998B;
            float: right;
        }

        /* detail modal */
        body#ppdb #detailModal .modal-footer {
            display: block;
        }

        body#ppdb #detailModal .modal-footer .subtotal {
            /* font-family: Roboto; */
            font-style: normal;
            font-size: 12px;
            line-height: 14px;
            color: #89998B;
            text-align: left;
            float: left;
            display: inline-block;
        }

        body#ppdb #detailModal .modal-footer .subtotal span {
            display: block;
            /* font-family: Acumin Pro; */
            font-style: italic;
            font-weight: 700;
            font-size: 16px;
            line-height: 24px;
            color: #42B549;
        }

        body#ppdb #detailModal .modal-footer button {
            display: inline-block;
            float: right;
        }

        #detailModal .content-header {
            display: flex;
        }

        #detailModal .content-header img {
            width: 140px;
            height: 132px;
            object-fit: contain;
            border-radius: 8px;
            background-color: #ECECEC;
            margin-right: 13px;
        }

        #detailModal .content-header-right {
            /* font-family: Roboto;
            font-style: normal; */
            font-weight: bold;
            font-size: 18px;
            line-height: 27px;
            color: #06270A;
        }

        #detailModal .content-header-right span {
            /* font-family: AcuminPro; */
            font-size: 16px;
            line-height: 24px;
            color: #42B549;
            display: block;
            margin-top: 5px;
        }

        #detailModal .modal-detail-ukuran, #detailModal .modal-detail-jumlah, #detailModal .modal-detail-note {
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

        #detailModal .label-note {
            width: 55px;
        }

        #detailModal .label-jumlah {
            width: 55px;
        }

        #detailModal .pilihan-jumlah {
            right: 25px;
            position: absolute;
        }

        #detailModal .minus, #detailModal .plus {
            width: 20px;
            height: 20px;
            display: inline-block;
        }

        #detailModal .jumlah {
            /* font-family: Roboto; */
            font-style: normal;
            font-weight: 500;
            font-size: 16px;
            line-height: 19px;
            text-align: center;
            color: #06270A;
            display: inline-block;
            vertical-align: super;
            margin: 0 10px;
        }

        #detailModal .minus {
            background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Crect width='20' height='20' rx='6' fill='%23F6F6F6'/%3E%3Crect x='5' y='9' width='10' height='2' rx='1' fill='%23C4C9C4'/%3E%3C/svg%3E%0A");
        }

        #detailModal .minus-active {
            background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Crect width='20' height='20' rx='6' fill='%23EEF7EE'/%3E%3Crect x='5' y='9' width='10' height='2' rx='1' fill='%2342B549'/%3E%3C/svg%3E%0A");
            cursor: pointer;
        }

        #detailModal .plus {
            background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Crect width='20' height='20' rx='6' fill='%23F6F6F6'/%3E%3Cpath fill-rule='evenodd' clip-rule='evenodd' d='M10 5C10.5523 5 11 5.44772 11 6V9H14C14.5523 9 15 9.44772 15 10C15 10.5523 14.5523 11 14 11H11V14C11 14.5523 10.5523 15 10 15C9.44772 15 9 14.5523 9 14V11H6C5.44772 11 5 10.5523 5 10C5 9.44772 5.44772 9 6 9H9V6C9 5.44772 9.44772 5 10 5Z' fill='%23C4C9C4'/%3E%3C/svg%3E%0A");
        }

        #detailModal .plus-active {
            background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Crect width='20' height='20' rx='6' fill='%23EEF7EE'/%3E%3Cpath fill-rule='evenodd' clip-rule='evenodd' d='M10 5C10.5523 5 11 5.44772 11 6V9H14C14.5523 9 15 9.44772 15 10C15 10.5523 14.5523 11 14 11H11V14C11 14.5523 10.5523 15 10 15C9.44772 15 9 14.5523 9 14V11H6C5.44772 11 5 10.5523 5 10C5 9.44772 5.44772 9 6 9H9V6C9 5.44772 9.44772 5 10 5Z' fill='%2342B549'/%3E%3C/svg%3E%0A");
            cursor: pointer;
        }

        #detailModal ul {
            list-style: none;
        }

        #detailModal li {
            display: inline-block;
            float: left;
            margin-left: 5px;
            border: 1px solid #E6EAE7;
            border-radius: 6px;
            /* font-family: Roboto; */
            font-style: normal;
            font-weight: normal;
            font-size: 10px;
            line-height: 12px;
            text-align: center;
            color: #06270A;
            padding: 7px 6px;
            cursor: pointer;
        }

        #detailModal li.active, #detailModal li:not(.disabled):hover {
            background: linear-gradient(225deg, #489F59 0%, #266C34 100%);
            color: #FFFFFF;
        }

        #detailModal li.disabled {
            background-color: #CECECE;
            cursor: auto;
        }

        .link-back {
            width: 24px;
            height: 24px;
            background-image: url("data:image/svg+xml,%3Csvg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' clip-rule='evenodd' d='M16.1371 1.35743C16.6137 0.880857 17.3863 0.880857 17.8629 1.35743C18.3395 1.834 18.3395 2.60668 17.8629 3.08325L8.94616 12L17.8629 20.9167C18.3395 21.3933 18.3395 22.166 17.8629 22.6426C17.3863 23.1191 16.6137 23.1191 16.1371 22.6426L6.35743 12.8629C5.88086 12.3863 5.88086 11.6137 6.35743 11.1371L16.1371 1.35743Z' fill='white'/%3E%3C/svg%3E%0A");
            position: absolute;
            top: 20px;
            left: 20px;
        }

        .link-cart {
            width: 22px;
            height: 20px;
            position: absolute;
            top: 20px;
            background-image: url("data:image/svg+xml,%3Csvg width='22' height='20' viewBox='0 0 22 20' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' clip-rule='evenodd' d='M21.3856 2.07426C21.221 1.89534 21.0261 1.80599 20.8008 1.80599H5.18718C5.17838 1.74035 5.15895 1.62042 5.1286 1.44638C5.09824 1.27242 5.0745 1.13363 5.0572 1.02991C5.03965 0.92698 5.00741 0.802105 4.95969 0.656405C4.91203 0.510442 4.85563 0.395122 4.79061 0.310707C4.72589 0.225765 4.63907 0.152948 4.5307 0.0916634C4.42257 0.0306423 4.29902 0 4.16023 0H0.832191C0.606643 0 0.411694 0.0896206 0.247101 0.268269C0.0822659 0.446917 0 0.658712 0 0.903257C0 1.1478 0.0822659 1.3594 0.247101 1.53805C0.411937 1.71696 0.606886 1.80625 0.832191 1.80625H3.48388L5.78502 13.4194C5.76772 13.457 5.67878 13.6356 5.51849 13.9556C5.35815 14.2756 5.23029 14.5554 5.13497 14.7953C5.03989 15.0354 4.99223 15.2211 4.99223 15.3526C4.99223 15.5972 5.0745 15.809 5.23909 15.9879C5.40393 16.166 5.59888 16.2559 5.82418 16.2559H6.97959C6.59629 16.3179 6.26 16.4935 5.97084 16.7825C5.60283 17.1508 5.41862 17.5948 5.41862 18.1154C5.41862 18.6357 5.60256 19.0797 5.97084 19.4477C6.33906 19.8158 6.78304 20 7.30336 20C7.8236 20 8.26787 19.8158 8.63588 19.4477C9.00382 19.0797 9.1881 18.6357 9.1881 18.1154C9.1881 17.5948 9.00409 17.1508 8.63588 16.7825C8.34645 16.4935 8.01027 16.3179 7.62706 16.2559H19.1361C19.3614 16.2559 19.5566 16.1662 19.7212 15.9879C19.8861 15.809 19.9683 15.5972 19.9683 15.3526C19.9683 15.1081 19.8861 14.8962 19.7212 14.7178C19.5566 14.5392 19.3614 14.4493 19.1361 14.4493H7.17595C7.3842 13.9978 7.48808 13.6972 7.48808 13.5466C7.48808 13.4521 7.47739 13.3487 7.4556 13.2362C7.43398 13.1234 7.40794 12.9985 7.37758 12.8619C7.34722 12.7259 7.32774 12.6248 7.31924 12.5589L20.8921 10.8373C21.1084 10.809 21.2862 10.7081 21.425 10.5338C21.5635 10.3601 21.6327 10.1601 21.6327 9.93431V2.70951C21.6327 2.46496 21.5504 2.25317 21.3856 2.07426ZM16.2636 18.2838C16.2636 18.7559 16.4265 19.1589 16.7512 19.4929C17.0759 19.8269 17.4684 19.9941 17.928 19.9941C18.3871 19.9941 18.7794 19.8269 19.1043 19.4929C19.4292 19.1589 19.5919 18.7559 19.5919 18.2838C19.5919 17.8113 19.4294 17.4084 19.1043 17.0741C18.7794 16.7404 18.3871 16.5731 17.928 16.5731C17.4684 16.5731 17.0759 16.7404 16.7512 17.0741C16.4265 17.4084 16.2636 17.8113 16.2636 18.2838Z' fill='white'/%3E%3C/svg%3E%0A");
            right: 20px;
        }
    </style>
@endpush
