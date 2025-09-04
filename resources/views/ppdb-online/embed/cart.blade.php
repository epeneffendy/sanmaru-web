@extends('layouts.ppdb-online.main')
@section('content')
    <div class="container container-desktop">
        {{-- <div class="row nav"> --}}
        <div class="container-button-back">
            <h2>Daftar Keranjang</h2>
            <div class="row mb-3 pl-3 justify-content-start" id="mobile">
                <a href="{{ route('ppdb.embed-product.index') }}"
                   class="d-flex align-items-center justify-content-start"><img class="head-left"
                                                                                src="{{asset('frontend-ppdb-online/img/Icon/Icon-Arrow.png')}}"
                                                                                alt=""><span
                            class="text-body-title text-primary-green ml-2">Kembali belanja</span></a>
            </div>
        </div>
        {{-- <a href="{{ route('ppdb.embed-product.index') }}" class="arrow-back"></a>
        <span>Keranjang</span> --}}
        {{-- </div> --}}

        @if ($cart)
            @forelse ($cart->details as $detail)
                <script>
                    if (typeof products[{{ $detail->id }}] === 'undefined') {
                        products[{{ $detail->id }}] = {};
                    }
                    products[{{ $detail->id }}].id = {{ $detail->id }};
                    products[{{ $detail->id }}].product_detail_id = {{ $detail->product_detail_id }};
                    products[{{ $detail->id }}].qty = {{ $detail->quantity }};
                    products[{{ $detail->id }}].price = '{{ $detail->product_detail->price_ppdb }}';
                    products[{{ $detail->id }}].size = '{{ $detail->product_detail->size }}';
                    products[{{ $detail->id }}].product_id = {{ $detail->product_detail->product_id }};
                    products[{{ $detail->id }}].include = true;
                    products[{{ $detail->id }}].payment_type = '08';
                    products[{{ $detail->id }}].note = '{{ $detail->note }}';
                </script>
                <div class="row item">
                    <div class="item-upper">
                        <div class="checkbox">
                            <span data-cart-detail-id="{{ $detail->id }}"></span>
                        </div>
                        <div class="image">
                            <img src="{{ $detail->product->image }}"/>
                        </div>
                        <div class="info">
                            <div class="info-title">{{ $detail->product->name }}</div>
                            @if ($cartVoucherProducts && in_array($detail->product_id, $cartVoucherProducts->pluck('id')->all()))
                                <div class="info-harga">{{ \App\Helpers\PriceHelper::rupiah(0) }}</div>
                            @else
                                <div class="info-harga">{{ \App\Helpers\PriceHelper::rupiah($detail->product_detail->price_ppdb) }}</div>
                            @endif
                            <div class="info-delete"><span data-cart-id="{{ $detail->cart_id }}"
                                                           data-cart-detail-id="{{ $detail->id }}"></span></div>
                        </div>
                    </div>
                    <div class="item-lower">
                        <div class="ukuran">
                            <div class="ukuran-title">Ukuran</div>
                            <div class="ukuran-content">
                                <ul>
                                    @forelse ($detail->product->details as $product_detail)
                                        <li {!! $product_detail->id === $detail->product_detail->id ? 'class="active"' : 'class="disabled"' !!}>{{ $product_detail->size }}</li>
                                    @empty
                                        tidak ada ukuran
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                        <div class="jumlah">
                            <div class="jumlah-title">Jumlah</div>
                            <div class="jumlah-content">
                                <span class="minus minus-active" data-type="minus"
                                      data-cart-detail-id="{{ $detail->id }}"
                                      data-cart-detail-stock="{{ $detail->product_detail->stock }}"></span>
                                <span class="jumlah-counter">{{ $detail->quantity }}</span>
                                <span class="plus plus-active" data-type="plus" data-cart-detail-id="{{ $detail->id }}"
                                      data-cart-detail-stock="{{ $detail->product_detail->stock }}"></span>
                            </div>
                        </div>
                    </div>
                    <div class="item-lower">
                        <div class="note">
                            <div class="note-title">Note</div>
                            <div class="note-content">
                                <span class="form-label">{{ $detail->note }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center">Cart kosong...</p>
            @endforelse
        @endif

        <div class="container-btn-tambah">
            <a href="{{ route('ppdb.embed-product.index') }}">
                <span>
                </span>
                Tambah Item
            </a>
        </div>

        @php ($voucher = @json_decode($cart->voucher, TRUE))
        <div class="row">
            @if (count($vouchers) > 0)
                @foreach($vouchers as $item)
                    <span class="btn btn-green btn-voucher-detail voucher-button {{ $voucher ? 'hide' : NULL }}"
                          id="btn-voucher-detail" data-id="{{$item->id}}"
                          data-code="{{$item->code}}" {{ $voucher ? 'hide' : NULL }}>{{$item->code}}</span>
                @endforeach
            @endif
        </div>
        <br>
        <div class="container-footer">
            @php ($voucher = @json_decode($cart->voucher, TRUE))
            <div class="additional additional-voucher voucher-input {{ $voucher ? 'hide' : NULL }}">
                <p>Punya voucher ? masukkan disini </p>
                <div class="row py-2">
                    <div class="col-sm-8 col-sm-offset-2 col-md-offset-2 col-md-8">
                        <input class="form-control" type="text" name="voucher"/>

                    </div>
                    <div class="col-sm-2 col-md-2">
                        <span class="btn btn-green btn-check-voucher"><i class="fa fa-gift"></i>Gunakan</span>
                    </div>
                </div>
            </div>

            @if ($voucher)
                <div class="additional additional-voucher voucher-result">
                    <p><i class="fa fa-check-o"></i> {{ $voucher['code'] }}</p>
                    <p>{{ nl2br($voucher['note']) }}</p>
                    <button class="btn btn-xs btn-delete-voucher">Reset voucher</button>
                </div>
            @endif

            @if ($cart)
                <div class="container-subtotal">
                    {{-- <hr class="my-5"> --}}
                    <p class="text-body text-black">Subtotal</p>
                    <div class="subtotal">
                        <h3 id="grand_total" class="text-green"
                            style="text-decoration: line-through black; {{ $cart->discount_total > 0 && $cart->grand_total > 0 ? 'NULL' : 'display: none' }}">{{ \App\Helpers\PriceHelper::rupiah($cart->grand_total) }}</h3>
                        <h3 id="grand_total_with_discount"
                            class="text-primary-green">{{ \App\Helpers\PriceHelper::rupiah($cart->grand_total_after_discount) }}</h3>
                    </div>
                    <button type="button" class="btn btn-green" id="button-beli">Beli Sekarang</button>

                </div>
            @endif
        </div>
    </div>

    <div id="modal-detail"></div>

    @include('ppdb-online/embed/_modal_fitting', ['user_fittings' => $user_fittings, 'fittings' => $fittings])
@endsection
@push('styles')
<style>
    .swal-text {
        text-align: center;
        color: red;
    }
</style>
@endpush
@push('scripts')
<script src="{{asset('js/sweet-alert/sweet-alert.min.js')}}"></script>
<script>
    $(document).on('click', '.info-delete span', function (e) {
        var parent = this;
        swal({
            title: 'Perhatian !',
            text: "Apakah Anda yakin akan menghapus item ini !",
            icon: "error",
            buttons: true,
            dangerMode: true,
        }).then((result) => {
            if (result) {
                $.post('{{ route('ppdb.embed-product.delete-cart') }}', {
                    _token: '{{ csrf_token() }}',
                    cart_id: $(parent).data('cart-id'),
                    cart_detail_id: $(parent).data('cart-detail-id'),
                }, function (data, status) {
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

    // AUTOCOMPLETE

    $(function () {
        // Data autocomplete (voucher)
        var myList = {!! json_encode(\App\Models\Voucher::eligible(request()->session()->get('user'))->pluck('code')->all()) !!};
        $("input[name='voucher']").autocomplete({
            minLength: 0,
            source: function (request, response) {
                var data = $.grep(myList, function (value) {
                    return value.substring(0, request.term.length).toLowerCase() == request.term.toLowerCase();
                });

                response(data);
            }
        }).focus(function () {
            $(this).autocomplete("search", "");
        });

    });

    $(document).on('click', '.btn-check-voucher', function (e) {
        e.preventDefault();
        $.post('{{ route('ppdb.embed-product.post-voucher') }}', {
            _token: '{{ csrf_token() }}',
            voucher: $('input[name=voucher]').val()
        }, function (data, status) {
            if (data.status === 'success') {
                window.location.reload();
            } else {
                swal(
                    'Gagal!',
                    'Voucher tidak ditemukan. Silahkan coba kembali',
                    'error'
                );
            }
        }).fail(function () {
            swal(
                'Gagal!',
                'Voucher tidak ditemukan. Silahkan coba kembali',
                'error'
            );
        });
    });

    $(document).on('click', '.btn-delete-voucher', function (e) {
        e.preventDefault();
        $.post('{{ route('ppdb.embed-product.delete-voucher') }}', {
            _token: '{{ csrf_token() }}',
        }, function (data, status) {
            if (data.status === 'success') {
                voucher = {};
                window.location.reload();
                $('.voucher-button').fadeIn();
                $('.voucher-input').fadeIn();
                $('.voucher-result').fadeOut();
                calculateGrandTotal();
            } else {
                swal(
                    'Gagal!',
                    'Voucher tidak bisa dihapus. Silahkan coba kembali',
                    'error'
                );
            }
        });
    });

    $(document).on('click', '.minus-active, .plus-active', function (e) {
        let cart_detail_id = $(this).data('cart-detail-id');
        let product_stock = $(this).data('cart-detail-stock');
        let sibling = $(this).data('type') === 'minus' ? 'plus' : 'minus';

        if (($(this).data('type') === 'minus' && products[cart_detail_id].qty === 1)
            || ($(this).data('type') === 'plus' && (products[cart_detail_id].qty + 1) >= product_stock)) {
            $(this).removeClass('minus-active');
            $(this).removeClass('plus-active');
        }

        $(this).siblings('.minus, .plus').addClass(sibling + '-active');

        if (sibling === 'minus') {
            products[cart_detail_id].qty++;
        } else {
            products[cart_detail_id].qty--;
        }

        $(this).siblings('.jumlah-counter').html(products[cart_detail_id].qty);
        calculateGrandTotal();
    });

    $(document).on('click', '.checkbox span', function (e) {
        if ($(this).hasClass('disabled')) {
            $(this).removeClass('disabled');
            products[$(this).data('cart-detail-id')].include = true;
        } else {
            $(this).addClass('disabled');
            products[$(this).data('cart-detail-id')].include = false;
        }

        calculateGrandTotal();
    });

    $(document).on('click', '#button-beli', function (e) {
        e.preventDefault();
        let productsFinalized = {};
        $.each(products, function (index, element) {
            if (element.qty > 0 && element.include) {
                productsFinalized[index] = element;
            }
        });

        let cartVoucherProducts = {!! json_encode($cartVoucherProducts) !!};
        let isCartVoucherFulfilled = {{ $isCartVoucherFulfilled ? 1 : 0 }};

        if ((isCartVoucherFulfilled <= 0) && (cartVoucherProducts.length > 0)) {
            var _s_prdcts = cartVoucherProducts.map(function (item) {
                return "- " + item.name;
            }).join("\n");

            swal(
                'Gagal!',
                'Anda harus menambahkan produk di bawah ini ke keranjang Anda:\n\n' + _s_prdcts + '\n\nSilahkan tekan tombol "Tambah item" untuk menambahkan produk tersebut.',
                'warning'
            );

            return;
        }

        @if($orders > 0)
            swal({
                title: 'Anda masih memiliki tagihan aktif!',
                text: 'Tagihan No Invoice {{$no_invoice}}, Silahkan Lunasi atau Batalkan Pembayaran!',
                icon: "warning",
                }).then((result)=>{
                    window.location.href = '{{ route('ppdb.embed-product.order-list') }}';
            });
            return;
        @endif

        @if ((count($vouchers) > 0) && (!$voucher))
               swal({
                title: 'Andah memiliki voucher yang belum digunakan!',
                text: "Mohon pilih voucher yang tersedia pada kolom voucher",
                icon: "warning",
            });
            return;
        @endif

        //pengecekan pembelian
        if ($.isEmptyObject(productsFinalized)) {
            alert('cart kosong, harap menambahkan produk atau cek jumlah produk !');
            return;
        }

        var parent = this;
        swal({
            title: 'Apakah Anda yakin akan melanjutkan?',
            text: "Pastikan kembali ukuran dan jumlah produk sudah sesuai. Barang yang sudah dibeli tidak dapat ditukar/dikembalikan",
            icon: "success",
            buttons: true,
            dangerMode: false,
        }).then((result) => {
            if (result) {
                $("#button-beli").attr('disabled', true);
                $.post('{{ route('ppdb.embed-product.post-cart') }}', {
                    _token: '{{ csrf_token() }}',
                    products: productsFinalized
                }, function (data, status) {
                    if (data.status === 'success') {
                        window.location.href = '{{ route('ppdb.embed-product.order') }}/' + data.order;
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

    function calculateGrandTotal() {
        let gt = 0;
        let dt = 0;
        var rule = voucher.rule ? JSON.parse(voucher.rule) : [];

        $.each(products, function (index, element) {
            if (element.include && element.qty > 0) {
                gt += (element.qty * element.price);

                if (voucher.type === 'free_product' && rule.includes(String(element.product_id))) {
                    dt += parseInt(element.price);

                    var index = rule.indexOf(String(element.product_id));
                    if (index > -1) {
                        rule.splice(index, 1);
                    }
                }
            }
        });

        if (voucher && voucher.type && voucher.type === 'discount_percent') {
            dt = parseInt(Math.round(gt * (voucher.rule / 100)));
        }

        if (voucher && voucher.type && voucher.type === 'discount_fixed') {
            dt = parseInt(voucher.rule);
        }

        let rupiah = formatter(gt);

        if (dt > 0 && gt > 0) {
            let total = gt - dt > 0 ? gt - dt : 0;
            let discount = formatter(total);
            $('#grand_total').html(rupiah).show();
            $('#grand_total_with_discount').html(discount);
        } else {
            $('#grand_total').html(rupiah).hide();
            $('#grand_total_with_discount').html(rupiah);
        }
    }

    function formatter(number) {
        let format = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
        });

        return format.format(number);
    }

    $(document).on('click', '.btn-voucher-detail', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var code = $(this).data('code');
        $.post('{{ route('ppdb.embed-product.detail-voucher') }}', {
            _token: '{{ csrf_token() }}',
            id: id,
            code: code
        }, function (data, status) {
            console.log(data);
            $("#modal-detail").html(data);
            $('#modal-detail-voucher').modal('show');
        })
    });

    $(document).on('click', '.btn-get-voucher', function (e) {
        e.preventDefault();
        $.post('{{ route('ppdb.embed-product.post-voucher') }}', {
            _token: '{{ csrf_token() }}',
            voucher: $(this).data('code')
        }, function (data, status) {
            if (data.status === 'success') {
                window.location.reload();
            } else {
                swal(
                    'Gagal!',
                    'Voucher tidak ditemukan. Silahkan coba kembali',
                    'error'
                );
            }
        }).fail(function () {
            swal(
                'Gagal!',
                'Voucher tidak ditemukan. Silahkan coba kembali',
                'error'
            );
        });
    });
</script>
@endpush
@push('styles')
<link rel="stylesheet" href="{{asset("frontend-ppdb-online/css/jquery-ui.css")}}">
<script>var products = {};</script>
<script>var voucher = {!! isset($cart->voucher) && $cart->voucher ? $cart->voucher : '{}' !!};</script>
<link href="{{asset('css/plugin/sweet-alert/sweet-alert.css')}}" rel="stylesheet"/>
<style>
    .full-height {
        padding-bottom: 80px;
    }

    .hide {
        display: none;
    }

    .additional-voucher p {
        margin: 0px;
    }

    .additional-voucher .row {
        padding: 0 15px 15px 15px;
    }

    .additional p {
        padding-bottom: 0px;
    }

    .nav {
        display: block;
        text-align: center;
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12);
    }

    .item {
        background: #FFFFFF;
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12);
        border-radius: 6px;
        margin: 20px 0;
    }

    .item-upper {
        display: flex;
        padding: 15px 17px;
        width: 100%;
        border-bottom: 1px solid rgba(6, 39, 10, 0.1);
    }

    .item-lower {
        display: flex;
        width: 100%;
        padding: 11px 40px;
    }

    .item-lower .ukuran {
        flex: 1;
    }

    .item-lower .ukuran, .item-lower .jumlah {
    / / font-family: Roboto;
        font-style: normal;
        font-weight: normal;
        font-size: 12px;
        line-height: 16px;
        color: #06270A;
    }

    .item-lower .ukuran-content,
    .item-lower .jumlah-content {
        margin-top: 10px;
    }

    .item-lower .ukuran ul {
        list-style: none;
        display: inline;
    }

    .item-lower .ukuran ul > li {
        float: left;
        margin-left: 10px;
        border: 1px solid #E6EAE7;
        box-sizing: border-box;
        border-radius: 6px;
        padding: 7px;
    / / font-family: Roboto;
        font-style: normal;
        font-weight: normal;
        font-size: 10px;
        line-height: 12px;
        text-align: center;
        color: #06270A;
    }

    .item-lower .ukuran ul > li.active {
        background: linear-gradient(225deg, #489F59 0%, #266C34 100%);
        color: #FFFFFF;
    }

    .item-lower .ukuran ul > li.disabled {
        background-color: #CECECE;
        cursor: auto;
    }

    .item-lower .ukuran ul > li:first-child {
        margin-left: 0;
    }

    .item-lower .jumlah .minus, .item-lower .jumlah .plus {
        width: 20px;
        height: 20px;
        display: inline-block;
        cursor: pointer;
    }

    .item-lower .jumlah-content {
    / / font-family: Roboto;
        font-style: normal;
        font-weight: 500;
        font-size: 14px;
        line-height: 16px;
        text-align: center;
        color: #06270A;
        display: inline-block;
        vertical-align: super;
    }

    .item-lower .jumlah-counter {
    / / font-family: Roboto;
        font-style: normal;
        font-weight: 500;
        font-size: 14px;
        line-height: 16px;
        text-align: center;
        color: #06270A;
        margin: 0 10px;
        vertical-align: super;
    }

    .item-lower .minus {
        background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Crect width='20' height='20' rx='6' fill='%23F6F6F6'/%3E%3Crect x='5' y='9' width='10' height='2' rx='1' fill='%23C4C9C4'/%3E%3C/svg%3E%0A");
    }

    .item-lower .minus-active {
        background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Crect width='20' height='20' rx='6' fill='%23EEF7EE'/%3E%3Crect x='5' y='9' width='10' height='2' rx='1' fill='%2342B549'/%3E%3C/svg%3E%0A");
    }

    .item-lower .plus {
        background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Crect width='20' height='20' rx='6' fill='%23F6F6F6'/%3E%3Cpath fill-rule='evenodd' clip-rule='evenodd' d='M10 5C10.5523 5 11 5.44772 11 6V9H14C14.5523 9 15 9.44772 15 10C15 10.5523 14.5523 11 14 11H11V14C11 14.5523 10.5523 15 10 15C9.44772 15 9 14.5523 9 14V11H6C5.44772 11 5 10.5523 5 10C5 9.44772 5.44772 9 6 9H9V6C9 5.44772 9.44772 5 10 5Z' fill='%23C4C9C4'/%3E%3C/svg%3E%0A");
    }

    .item-lower .plus-active {
        background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Crect width='20' height='20' rx='6' fill='%23EEF7EE'/%3E%3Cpath fill-rule='evenodd' clip-rule='evenodd' d='M10 5C10.5523 5 11 5.44772 11 6V9H14C14.5523 9 15 9.44772 15 10C15 10.5523 14.5523 11 14 11H11V14C11 14.5523 10.5523 15 10 15C9.44772 15 9 14.5523 9 14V11H6C5.44772 11 5 10.5523 5 10C5 9.44772 5.44772 9 6 9H9V6C9 5.44772 9.44772 5 10 5Z' fill='%2342B549'/%3E%3C/svg%3E%0A");
    }

    .item-upper .checkbox {
        align-self: center;
    }

    .item-upper .checkbox span,
    .fa-check-o {
        background-image: url("data:image/svg+xml,%3Csvg width='18' height='18' viewBox='0 0 18 18' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M16 0H2C0.9 0 0 0.9 0 2V16C0 17.1 0.9 18 2 18H16C17.1 18 18 17.1 18 16V2C18 0.9 17.1 0 16 0Z' fill='url(%23paint0_linear)'/%3E%3Cpath d='M7.70997 13.2901C7.31997 13.6801 6.68997 13.6801 6.29997 13.2901L2.70997 9.70006C2.52271 9.51323 2.41748 9.25958 2.41748 8.99506C2.41748 8.73054 2.52271 8.47689 2.70997 8.29006C3.09997 7.90006 3.72997 7.90006 4.11997 8.29006L6.99997 11.1701L13.88 4.29006C14.27 3.90006 14.9 3.90006 15.29 4.29006C15.68 4.68006 15.68 5.31006 15.29 5.70006L7.70997 13.2901Z' fill='white'/%3E%3Cdefs%3E%3ClinearGradient id='paint0_linear' x1='9' y1='-9' x2='-9' y2='9' gradientUnits='userSpaceOnUse'%3E%3Cstop stop-color='%23489F59'/%3E%3Cstop offset='1' stop-color='%23266C34'/%3E%3C/linearGradient%3E%3C/defs%3E%3C/svg%3E%0A");
        width: 18px;
        height: 18px;
        display: block;
        cursor: pointer;
    }

    .fa-check-o {
        display: inline-block;
    }

    .item-upper .checkbox span.disabled {
        background-image: url("data:image/svg+xml,%3Csvg width='18' height='18' viewBox='0 0 18 18' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' clip-rule='evenodd' d='M2 0H16C17.1 0 18 0.9 18 2V16C18 17.1 17.1 18 16 18H2C0.9 18 0 17.1 0 16V2C0 0.9 0.9 0 2 0ZM6.3 13.29C6.69 13.68 7.32 13.68 7.71 13.29L15.29 5.7C15.68 5.31 15.68 4.68 15.29 4.29C14.9 3.9 14.27 3.9 13.88 4.29L7 11.17L4.12 8.29C3.73 7.9 3.1 7.9 2.71 8.29C2.52275 8.47683 2.41751 8.73048 2.41751 8.995C2.41751 9.25952 2.52275 9.51317 2.71 9.7L6.3 13.29Z' fill='%23C4C9C4'/%3E%3C/svg%3E%0A");
    }

    .item-upper .image {
        margin-left: 10px;
    }

    .item-upper .image img {
        width: 100px;
        height: auto;
        border-radius: 8px;
    }

    @media only screen and (max-width: 425px) {
        .item-upper .image img {
            width: 50px;
        }

        .item-lower {
            padding: 11px 5px;
        }

        .item-lower .ukuran ul {
            padding-inline-start: 0;
        }
    }

    .item-upper .info {
        margin-left: 13px;
        display: block;
        flex: 1;
    }

    .item-upper .info .info-title {
    / / font-family: Roboto;
        font-style: normal;
        font-weight: bold;
        font-size: 14px;
        line-height: 21px;
        color: #06270A;
    }

    .item-upper .info .info-harga {
    / / font-family: AcuminPro;
        font-size: 14px;
        line-height: 24px;
        color: #42B549;
    }

    .item-upper .info .info-delete {
        float: right;
    }

    .btn-delete-voucher{
        font-size: 14px;
        margin-top: 10px;
        margin-left: 14px;
    }

    .item-upper .info .info-delete span {
        cursor: pointer;
        background-image: url("data:image/svg+xml,%3Csvg width='18' height='18' viewBox='0 0 18 18' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' clip-rule='evenodd' d='M11.625 3H13.5C13.9125 3 14.25 3.3375 14.25 3.75C14.25 4.1625 13.9125 4.5 13.5 4.5H4.5C4.0875 4.5 3.75 4.1625 3.75 3.75C3.75 3.3375 4.0875 3 4.5 3H6.375L6.9075 2.4675C7.0425 2.3325 7.2375 2.25 7.4325 2.25H10.5675C10.7625 2.25 10.9575 2.3325 11.0925 2.4675L11.625 3ZM6 15.75C5.175 15.75 4.5 15.075 4.5 14.25V6.75C4.5 5.925 5.175 5.25 6 5.25H12C12.825 5.25 13.5 5.925 13.5 6.75V14.25C13.5 15.075 12.825 15.75 12 15.75H6Z' fill='%23C4C9C4'/%3E%3C/svg%3E%0A");
        width: 18px;
        height: 18px;
        display: block;
    }

    .footer {
        background-color: #FFFFFF;
        position: fixed;
        width: 100%;
        padding: 10px 15px;
        display: block;
        bottom: 0;
        box-shadow: 0px -1px 1px rgba(0, 0, 0, 0.04);
        border-radius: 2px;
    }

    .footer .subtotal {
    / / font-family: Roboto;
        font-style: normal;
        font-size: 12px;
        line-height: 14px;
        color: #89998B;
        text-align: left;
        float: left;
        display: inline-block;
    }

    .footer .subtotal span {
        /* display: block; */
    / / font-family: Acumin Pro;
        font-style: italic;
        font-weight: 700;
        font-size: 16px;
        line-height: 24px;
        color: #42B549;
    }

    .footer button {
        display: inline-block;
        float: right;
        background: linear-gradient(225deg, #489F59 0%, #266C34 100%);
        border-radius: 20px;
    / / font-family: Roboto;
        font-style: normal;
        font-weight: bold;
        font-size: 12px;
        line-height: 14px;
        text-align: center;
        padding: 13px 30px;
        margin: 0 3px;
    }

    .nav span {
    / / font-family: Roboto;
        padding: 14px 0;
        font-style: normal;
        font-weight: 700;
        font-size: 18px;
        line-height: 21px;
        text-align: center;
        color: #06270A;
        display: block;
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

    .ui-menu {
        border-radius: .25rem;
        background: white;
        border: 1px solid #ced4da;
    }

    li.ui-menu-item:hover {
    / / padding: 0.25 rem 1 rem;
        background-color: rgba(0, 0, 0, 0.05);
    }

    li.ui-menu-item {
        padding: .4rem .8rem;
    }

    li.ui-menu-item a {
        color: black;
        display: block;
    }

    li.ui-menu-item a:hover {
        text-decoration: none;
    }
</style>
@endpush
