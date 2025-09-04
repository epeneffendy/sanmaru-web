@extends('layouts.webkantin.main')
@section('content')

@include('layouts.webkantin.mobile-welcome-header')

<div id="home">
    <div class="floating-cart">
        <a href="{{ route('kantin.cart.index') }}" class="btn btn-primary"> <i class="icon icon-cart"></i></a>
    </div>
    
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

                    <!-- Create Order Modal -->
                    {{-- <div class="modal fade" id="createOrderModal" tabindex="-1" aria-labelledby="createOrderModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
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
                            </div>
                        </div>
                    </div> --}}
                </div>

                {{-- <!-- Ads Section -->
                <div class="row mt-48">
                    <div class="col-12">
                        <div class="ads-container">
                            <img class="bg-image"src="{{asset('webkantin/images/bg-ads.png')}}" alt="Ads">
                            <div class="ads-detail">
                                <div class="ads-image">
                                    <img src="{{asset('webkantin/images/burger.png')}}" alt="Burger">
                                </div>
                                <div class="ads-text">
                                    <h5 class="inter text-lg semi-bold white"> Special Menu <br> Burger BUZZ <br> Rp 5.000 </h5>
                                    <p class="inter text-sm reguler white mt-4">*Hanya tersedia selama bulan Desember</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

                 <!-- Categories Section -->
                <div class="row mt-48">
                    <h5 class="text-lg bold black-solid mb-3">Categories</h5>
                    <div class="categories-wrapper">
                        <a href="{{ route('kantin.index') }}" style="text-decoration: none;">
                            <div class="categories-card">
                                <div class="card-img-top">
                                    <img src="{{asset('webkantin/images/categories/all.png')}}" alt="All Menu">
                                </div>
                                <div class="card-title text-sm medium {{ !@$params['day'] ? 'text-dark' : null }}">
                                    All Menu
                                </div>
                            </div>
                        </a>
                        @foreach ($days as $hari => $day)
                        <a href="{{ route('kantin.index', ['day' => $day]) }}" style="text-decoration: none;">
                            <div class="categories-card">
                                <div class="card-img-top">
                                    <img src="{{asset('webkantin/images/categories/' . $day . '.png')}}" alt="{{ $hari }}">
                                </div>
                                <div class="card-title text-sm medium {{ @$params['day'] == $day ? 'text-dark' : null }}">
                                    {{ ucwords(strtolower($hari)) }}
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Menu Section -->
                <div class="row mt-48 mt-sm-24">
                    @foreach ($readyProducts as $product)
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="menu-card ready-menu mb-3" type="button" data-product-id="{{ $product->id }}">
                            <div class="menu-image">
                                <img src="{{ $product->image }}" alt="Kantin Santa Maria">
                                <div class="darken-pseudo">
                                </div>
                                <div class="bottom-left">
                                    <h6 class="text-md bold white mb-0 mb-md-1">{{ $product->name }}</h6>
                                    <h6 class="text-sm medium white">{{ $product->price_siswa_range }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <!-- Add to Cart Modal -->
                <div class="modal fade" id="addToCartModal" tabindex="-1" aria-labelledby="addToCartModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="container" id="productDetail">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="submit" class="btn btn-primary text-md bold" value="Tambah ke keranjang" form="addToCartForm">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Menu Section -->
                <div class="row mt-48">
                    <h5 class="text-lg bold black-solid mb-3">Pre Order</h5>
                    @foreach ($preorderProducts as $product)
                    @if ($product->schedule->is_available_today)
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="menu-card preorder-menu" type="button" data-product-id="{{ $product->id }}">
                            <div class="menu-image">
                                <img src="{{ $product->image }}" alt="Kantin Santa Maria">
                                <div class="darken-pseudo">
                                </div>
                                <div class="bottom-left">
                                    <h6 class="text-md bold white">{{ $product->name }}</h6>
                                    <h6 class="text-sm medium white">{{ $product->price_siswa_range }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
                <!-- Preorder Modal -->
                <div class="modal fade" id="preorderModal" tabindex="-1" aria-labelledby="addToCartModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="container" id="preorderProductDetail">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="submit" class="btn btn-primary text-md bold" value="Pesan" form="preorderForm">
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
<script>
    $(document).ready(function() {
        var fetchProductDetailUrl = "{{ route('kantin.fetch-product-detail') }}";

        $('.ready-menu').on('click', function() {
            $.get(fetchProductDetailUrl + "/" + $(this).data('product-id'), function( data ) {
                $('#productDetail').html(data.html);
                console.log(data.html);
                $('.minus').click(function () {
                    let $input = $(this).parent().find('.input-counter');
                    let count = parseInt($input.val()) - 1;
                    count = count < 1 ? 1 : count;
                    $input.val(count);
                    $input.change();
                    return false;
                });
                $('.plus').click(function () {
                    let $input = $(this).parent().find('.input-counter');
                    $input.val(parseInt($input.val()) + 1);
                    $input.change();
                    return false;
                });
            });
            $('#addToCartModal').modal('toggle');
        });
        $('.preorder-menu').on('click', function() {
            $.get(fetchProductDetailUrl + "/" + $(this).data('product-id'), function( data ) {
                $('#preorderProductDetail').html(data.html);
                console.log(data.html);
                $('.minus').click(function () {
                    let $input = $(this).parent().find('.input-counter');
                    let count = parseInt($input.val()) - 1;
                    count = count < 1 ? 1 : count;
                    $input.val(count);
                    $input.change();
                    return false;
                });
                $('.plus').click(function () {
                    let $input = $(this).parent().find('.input-counter');
                    $input.val(parseInt($input.val()) + 1);
                    $input.change();
                    return false;
                });
            });
            $('#preorderModal').modal('toggle');
        });
    });
</script>
@endpush
