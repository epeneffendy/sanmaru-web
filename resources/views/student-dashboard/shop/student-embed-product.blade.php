@extends('layouts.welcome-page.main')
@section('content')

<div class="wrapper-content-desktop">
    <div class="container" style="padding: 3rem">
        <div class="row align-items-center">
            <div class="col">
                <h2>Daftar Seragam</h2>
            </div>
            <form action="{{ route('embed-product') }}">
                <div class="col d-flex justify-content-end align-items-center">
                    <input type="text" name="search" id="search" class="input-search" placeholder="Search" value="{{ request('search') }}">
                    <img src="{{asset('frontend-ppdb-online/img/Icon/Tab/search.png')}}" alt="" class="input-icon-search">
                </div>
            </form>
        </div>
        <div class="row container-product">
            @forelse ($products as $product)
                <a href="{{ route('embed-product.detail', ['id' => $product->id]) }}">
                    <div class="card-product">
                        <div class="product-thumbnail" style="background-image: url('{{ $product->image }}')"></div>
                        <div class="product-caption d-flex flex-column">
                            <h3 class="">{{ $product->name }}</h3>
                            <span class="text-subtitle-2">{{ $product->price_range }}</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-md-12 text-center">Tidak ada produk...</div>
            @endforelse
        </div>
        <div class="row justify-content-end">
            <a href="{{ route('embed-product.order-list') }}" class="btn btn-outline-green" style="border-radius: 50px; margin-right: 1rem" ><img src="{{ asset('frontend-ppdb-online/img/Icon/Order-List.png') }}" alt=""><span>Lihat status pesanan</span></a>
            <a href="{{ route('embed-product.cart') }}" class="btn btn-outline-green" style="border-radius: 50px"><img src="{{ asset('frontend-ppdb-online/img/Icon/Cart-Active.png') }}" alt=""><span>Lihat Keranjang</span></a>
        </div>
    </div>
</div>

<div class="wrapper-content-mobile">
    <div class="product-index">
        <div class="row mb-3 px-3 d-flex justify-content-end">
            {{-- <a href="#" class="d-flex align-items-center justify-content-around"><img class="head-left" src="{{asset('frontend-ppdb-online/img/Icon/Icon-Arrow.png')}}" alt=""><span class="text-body-title text-primary-green ml-2">Kembali</span></a> --}}
            <div class="d-flex justify-content-end align-items-center">
                <a href="{{ route('embed-product.order-list') }}" class="px-2"><img src="{{asset('frontend-ppdb-online/img/Icon/Receipt-Active.png')}}" alt=""></a>
                <a href="{{ route('embed-product.cart') }}" class="px-2"><img src="{{asset('frontend-ppdb-online/img/Icon/Cart-Active.png')}}" alt=""></a>
            </div>
        </div>
        <div class="row">
            {{-- <form action="">
                <div class="col-6">
                    <div class="dropdown" id="sort">
                        <div class="form-control d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <img src="{{asset('frontend-ppdb-online/img/Icon/icon - sorting.png')}}" alt="">
                                <span id="value-dropdown" class="dropdown-placeholder">Sort</span>
                            </div>
                            <img src="{{asset('frontend-ppdb-online/img/Icon/Icon-Arrow.png')}}" alt="">
                        </div>
                        <div class="dropdown-children">
                            <a href="#" class="dropdown-item">Harga tertinggi</a>
                            <a href="#" class="dropdown-item">Harga Terendah</a>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="dropdown" id="filter">
                        <div class="form-control d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <img src="{{asset('frontend-ppdb-online/img/Icon/icon - filter.png')}}" alt="">
                                <span id="value-dropdown" class="dropdown-placeholder">Filter</span>
                            </div>
                            <img src="{{asset('frontend-ppdb-online/img/Icon/Icon-Arrow.png')}}" alt="">
                        </div>
                        <div class="dropdown-children">
                            <a href="#" class="dropdown-item">Pakaian</a>
                            <a href="#" class="dropdown-item">Topi</a>
                        </div>
                    </div>
                </div>
            </form> --}}
        </div>
        <div class="row py-2 justify-content-center">
            @forelse ($products as $product)
            <div class="col-6">
                <a href="{{ route('embed-product.detail', ['id' => $product->id]) }}">
                    <div class="card-product">
                        <div class="product-thumbnail" style="background-image: url('{{ $product->image }}')"></div>
                        <div class="product-caption d-flex flex-column">
                            <span class="caption-title">{{ $product->name }}</span>
                            <span class="caption-price">{{ $product->price_range }}</span>
                        </div>
                    </div>
                </a>
            </div>
            @empty
                <div class="col-md-12 text-center">Tidak ada produk...</div>
            @endforelse
        </div>
    </div>
</div>

@endsection
