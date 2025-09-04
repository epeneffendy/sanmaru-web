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
                    <h5 class="text-md reguler black mb-3">Hasil pencarian</h5>
                    <div class="col-md-4 col-lg-2">
                        <div class="menu-card" type="button" data-bs-toggle="modal" data-bs-target="#addToCartModal">
                            <div class="menu-image">
                                <img src="{{asset('webkantin/images/menu-4.png')}}" alt="Kantin Santa Maria">
                                <div class="darken-pseudo">
                                </div>
                                <div class="bottom-left">
                                    <h6 class="text-md bold white">Roti Mama Suka</h6>
                                    <h6 class="text-sm medium white">Rp 15.000</h6>
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
