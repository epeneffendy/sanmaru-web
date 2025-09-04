@extends('layouts.webkantin.main')

{{-- @push('styles')
<style>
    button.html5-qrcode-element {
        color: 'red';
        border: '1px solid red';
    }
</style>
@endpush --}}

@section('content')
<script>var products = {};</script>

<div class="mobile-header">
    <a href="{{ route('kantin.index') }}">
        <i class="icon icon-back"></i>
    </a>
    <h5 class="text-lg medium mt-2 me-2">Keranjang</h5>
    <div></div>
</div>

<div id="cart">
    <section class="home-section-1">
        <div class="container">
            <div class="menu-wrapper">
                <div class="row desktop">
                    <!-- Search Bar -->
                    <div class="col">
                        <div class="input-group search-bar">
                            <span class="input-group-text" id="search-form"><i class="icon icon-search"></i></span>
                            <input type="text" class="form-control text-sm reguler grey" placeholder="Search" aria-label="Search" aria-describedby="search-form">
                        </div>
                    </div>
                    <div class="col-2 col-lg-1">
                        <a class="btn btn-primary d-flex align-items-center justify-content-center" href="{{ route('kantin.history') }}"><i class="icon icon-receipt"></i></a>
                    </div>
                    <div class="col-2 col-lg-1">
                        {{-- <button type="button" data-bs-toggle="modal" data-bs-target="#createOrderModal" class="btn btn-primary text-sm bold d-flex align-items-center justify-content-center"> <i class="icon icon-cart me-2"></i> Buat order</button> --}}
                        <a href="{{ route('kantin.cart.index') }}" class="btn btn-primary text-sm bold d-flex align-items-center justify-content-center"> <i class="icon icon-cart"></i></a>
                    </div>
                    <!-- <div class="col-lg-2 col-md-3">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#createOrderModal" class="btn btn-primary text-sm bold d-flex align-items-center justify-content-center"> <i class="icon icon-cart me-2"></i> Buat order</button>
                    </div> -->

                    <!-- Create Order Modal -->
                    <div class="modal fade" id="createOrderModal" tabindex="-1" aria-labelledby="createOrderModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form>
                                    <div class="modal-body">
                                        <div class="container">
                                            <h4 class="form-header">Masukkan identitas pemesan</h4>
                                            <input type="text" class="form-control text-sm reguler grey" placeholder="Nama pemesan" aria-describedby="orderName">
                                            <input type="text" class="form-control text-sm reguler grey" placeholder="Unit sekolah"  aria-describedby="orderSchoolUnit">
                                            <input type="tel" class="form-control text-sm reguler grey" placeholder="Nomor handphone"  aria-describedby="orderPhone">
                                            <div class="input-group">
                                                <span class="input-group-text" id="orderDay"><i class="icon icon-calendar"></i></span>
                                                <select class="form-select text-sm reguler grey" aria-label="orderDay">
                                                    <option selected>Pilih Hari</option>
                                                    <option value="Senin">Senin</option>
                                                    <option value="Selasa">Selasa</option>
                                                    <option value="Rabu">Rabu</option>
                                                    <option value="Kamis">Kamis</option>
                                                    <option value="Jumat">Jumat</option>
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary text-md bold">Selesai</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-48">
                    <div class="col-md-4">
                        <h5 class="text-lg bold black-solid mb-4 desktop">Keranjang</h5>
                        <div class="cart-container">
                            @php
                                $grandTotal = 0;
                            @endphp
                            @if ($cart)
                            @forelse($details as $detail)
                            <script>
                                if (typeof products[{{ $detail->id }}] === 'undefined') {
                                    products[{{ $detail->id }}] = {};
                                }
                                products[{{ $detail->id }}].id = {{ $detail->id }};
                                products[{{ $detail->id }}].product_detail_id = {{ $detail->product_detail_id }};
                                products[{{ $detail->id }}].qty = {{ $detail->quantity }};
                                products[{{ $detail->id }}].price = '{{ $detail->product_detail->price_siswa }}';
                                products[{{ $detail->id }}].size = '{{ $detail->product_detail->size }}';
                                products[{{ $detail->id }}].product_id = {{ $detail->product_detail->product_id }};
                                products[{{ $detail->id }}].include = true;
                                products[{{ $detail->id }}].payment_type = '12';
                                products[{{ $detail->id }}].note = '{{ $detail->note }}';
                            </script>
                            <div class="row mb-4">
                                <div class="col-4">
                                    <div class="image-wrapper">
                                        <img src="{{ $detail->product->image }}" alt="{{ $detail->product->name }}">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="cart-detail-wrapper">
                                        <div>
                                            <h5 class="text-md bold black mt-1">{{ $detail->product->name }}</h5>
                                            <h6 class="text-md medium secondary-green">{{ $detail->product_detail->size }} - Rp.<span class="total-price">{{ intval($detail->total_price) }}</span>
                                        </div>
                                        <div class="input-number">
                                            <span class="minus"><i class="icon icon-minus"></i></span>
                                            <input type="text" class="text-md bold input-counter" value="{{ $detail->quantity }}" data-detail-id="{{ $detail->id }}" data-price="{{ $detail->product_detail->price_siswa }}" autocomplete="off"/>
                                            <span class="plus"><i class="icon icon-plus"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-1">
                                    <i class="icon icon-delete delete-item" style="cursor: pointer" data-detail-id="{{ $detail->id }}"></i>
                                </div>
                            </div>
                            @php
                                $grandTotal += intval($detail->total_price);
                            @endphp
                            @empty
                            <div class="row mb-4">
                                <span>Keranjang Kosong</span>
                            </div>
                            @endforelse
                            @else
                                <div class="row mb-4">
                                    <span>Keranjang Kosong</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col"></div>
                    <div class="col-md-5">
                        <div class="d-flex justify-content-between">
                            <h5 class="text-lg bold black-solid mb-4">Detail Pemesan</h5>
                            {{-- <p class="text-sm bold secondary-green">Edit</p> --}}
                        </div>
                        {{-- <div class="d-flex">
                            <i class="icon icon-location me-4"></i>
                            <p class="text-md medium black-solid">16 Desember 2021</p>
                        </div> --}}
                        <div class="line mb-3"></div>
                        <p class="text-md regular ms-5">{{ $cart ? $cart->user->student->name : '' }}</p>
                        <p class="text-md regular ms-5">{{ $cart ? $cart->user->student->mobile_phone : '' }}</p>

                        <h5 class="text-lg bold black-solid mt-5 mb-3">Jenis Order</h5>
                        <div class="d-flex align-items-center">
                            <img src="{{asset('webkantin/images/pickup.png')}}" alt="Pick Up Order">
                            <p class="text-md bold black-solid ms-4">Ambil Sendiri</p>
                        </div>

                        <h5 class="text-lg bold black-solid mt-5 mb-3">Metode Pembayaran</h5>
                        <div class="d-flex align-items-center">
                            <img src="{{asset('webkantin/images/barcode.png')}}" alt="QRIS">
                            <p class="text-md bold black-solid mt-2 ms-3">QRIS</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-5 offset-md-7 text-center text-md-end order-wrapper">
                        <button id="toggle-order-btn" data-toggle="collapse" data-target="#order-container"></button>
                        <div id="order-container" class="collapse in show">
                            <div class="d-flex justify-content-between mt-0 mt-md-5" id="order-container" >
                                <h5 class="text-lg bold black-solid">Total</h5>
                                <h5 class="text-lg bold black-solid">Rp. <span id="grandTotalPrice">{{ $grandTotal }}</span></h5>
                            </div>
                            <button type="button" class="btn btn-primary text-md bold order-button mt-4" id="orderNowButton">Order Sekarang</button>
                            {{-- <button type="button" data-bs-toggle="modal" data-bs-target="#scanQrisModal" class="btn btn-primary text-md bold order-button">Order Sekarang</button> --}}
                        </div>
                    </div>

                    <!-- Scan QRIS Modal -->
                    <div class="modal fade" id="scanQrisModal" tabindex="-1" aria-labelledby="scanQrisModal" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="container">
                                        <div class="d-flex justify-content-center">
                                            <div class="image-wrapper d-none d-sm-block">
                                                <img style="width: 100%" src="{{asset('webkantin/images/QRIS-460074990.jpg')}}" alt="QRIS Santa Maria">
                                            </div>
                                            <div class="image-wrapper d-block d-sm-none">
                                                <img style="width: 100%" src="{{asset('webkantin/images/QRIS-460074990-mobile.jpg')}}" alt="QRIS Santa Maria">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="{{ asset('webkantin/images/QRIS-460074990.jpg') }}" download="QRIS Kantin Sanmar.jpg" class="btn btn-primary text-md bold">Download QRIS</a>
                                    <button class="btn btn-light text-md bold black" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
                                    {{-- <button type="button" data-bs-toggle="modal" data-bs-target="#qrScannerModal" class="btn btn-primary text-md bold">Scan QRIS</button> --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SUCCESSS PAYMENT Modal -->
                    <div class="modal fade" id="successPaymentModal" tabindex="-1" aria-labelledby="successPaymentModal" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <div class="container">
                                        <div class="d-flex justify-content-center my-4">
                                            <div class="image-wrapper">
                                                <img src="{{asset('webkantin/images/payment-success.png')}}" alt="Kantin Santa Maria">
                                            </div>
                                        </div>
                                        <h5 class="display-xs bold black-solid">Pembayaran Sukses</h5>
                                        <p class="text-md reguler grey">Tunggu pesananmu selesai diproses ya!</p>
                                    </div>
                                </div>
                                <div class="modal-footer mb-5">
                                    <a href="{{ route('kantin.index') }}" class="btn btn-primary text-md bold">Kembali ke halaman utama</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Failed PAYMENT Modal -->
                    <div class="modal fade" id="failedPaymentModal" tabindex="-1" aria-labelledby="failedPaymentModal" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <div class="container">
                                        <div class="d-flex justify-content-center my-4">
                                            <div class="image-wrapper">
                                                <img src="{{asset('webkantin/images/payment-failed.png')}}" alt="Kantin Santa Maria">
                                            </div>
                                        </div>
                                        <h5 class="display-xs bold black-solid">Pembayaran Gagal</h5>
                                        <p class="text-md reguler grey">Uuppsss, coba cek saldomu atau ulangi proses pembayaranmu ya</p>
                                    </div>
                                </div>
                                <div class="modal-footer mb-5">
                                    <a href="{{ route('kantin.cart.index', ['type' => $type]) }}" class="btn btn-primary text-md bold">Kembali ke keranjang</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- QR Scanner Modal -->
                    <div class="modal fade" id="qrScannerModal" tabindex="-1" aria-labelledby="failedPaymentModal" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <div class="container">
                                        <div class="d-flex justify-content-center my-4">
                                            <div class="image-wrapper">
                                                <div id="qr-reader" style="width:400px"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer mb-5">
                                    <button class="btn btn-dark text-white text-md bold black" data-bs-dismiss="modal" aria-label="Close">Tutup</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        $('.minus').click(function () {
            let input = $(this).parent().find('.input-counter');
            let count = parseInt(input.val()) - 1;
            let detailId = parseInt(input.data('detail-id'))
            let price = parseInt(input.data('price'));
            let totalPriceField = input.parent().parent().find('.total-price');
            if (count > 0) {
                totalPriceField.html(parseInt(totalPriceField.html()) - price);
                $('#grandTotalPrice').html(parseInt($('#grandTotalPrice').html()) - price);
                products[detailId].qty -= 1;
            }
            count = count < 1 ? 1 : count;
            input.val(count);
            input.change();
            return false;
        });
        $('.plus').click(function () {
            var input = $(this).parent().find('.input-counter');
            let detailId = parseInt(input.data('detail-id'))
            let price = parseInt(input.data('price'));
            let totalPriceField = input.parent().parent().find('.total-price');

            totalPriceField.html(parseInt(totalPriceField.html()) + price);
            $('#grandTotalPrice').html(parseInt($('#grandTotalPrice').html()) + price);
            products[detailId].qty += 1;

            input.val(parseInt(input.val()) + 1);
            input.change();
            return false;
        });
        $('.delete-item').on('click', function() {
            let detailId = $(this).data('detail-id');
            Swal.fire({
                title: 'Konfirmasi Penghapusan',
                text: 'Apakah anda yakin menghapus item ini?',
                icon: 'error',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('{{ route('kantin.cart.delete') }}', {
                        _token: '{{ csrf_token() }}',
                        cart_id: {{ $cart ? $cart->id : '0' }},
                        cart_detail_id: detailId
                    }, function (data, status) {
                        if (data.status === 'success') {
                            window.location.href = '';
                        } else {
                            Swal.fire(
                                'Gagal!',
                                'Item anda gagal dihapus, silahkan coba kembali.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
        $('#orderNowButton').on('click', function(e) {
            e.preventDefault();
            if ($.isEmptyObject(products)) {
                Swal.fire(
                    'Perhatian!',
                    'Cart kosong, silahkan tambahkan produk untuk melakukan pesanan',
                    'warning'
                );
                return;
            }
            Swal.fire({
                title: 'Apakah Anda yakin akan melanjutkan?',
                text: "Pastikan kembali ukuran dan jumlah produk sudah sesuai. Barang yang sudah dibeli tidak dapat ditukar/dikembalikan",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: 'Ya, lanjut pesan',
                cancelButtonText: 'Tidak',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Mohon Tunggu',
                        html: 'Pesanan anda sedang kami proses.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading()
                        },
                    });
                    $.post('{{ route('kantin.cart.checkout') }}', {
                        _token: '{{ csrf_token() }}',
                        products: products,
                    }, function (data, status) {
                        Swal.close();
                        if (data.status === 'success') {
                            $('#scanQrisModal').modal('toggle');
                            function onScanSuccess(qrCodeMessage) {
                                window.open("{{ route('kantin.history') }}","_self");
                            }
                            let html5QrcodeScanner = new Html5QrcodeScanner(
                                "qr-reader", { fps: 10, qrbox: 250 }
                            );
                            html5QrcodeScanner.render(onScanSuccess);
                            $('#scanQrisModal').on('hidden.bs.modal', function (e) {
                                if (!$('#qrScannerModal').hasClass('show')) {
                                    window.open("{{ route('kantin.history') }}","_self");
                                }
                            });
                            $('#qrScannerModal').on('hidden.bs.modal', function (e) {
                                window.open("{{ route('kantin.history') }}","_self");
                            });
                        } else {
                            Swal.fire(
                                'Gagal!',
                                'Pesanan anda gagal diproses, silahkan coba kembali.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
        @if (session()->has('alert'))
        @php ($alert = session()->pull('alert'))
        Swal.fire({
            title: "{{ $alert['title'] }}",
            width: 800,
            icon: "{{ $alert['icon'] }}",
            text: "{{ $alert['text'] }}",
        });
        @endif
    });
</script>
@endpush
