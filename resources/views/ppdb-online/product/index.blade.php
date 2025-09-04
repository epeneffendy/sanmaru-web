@extends('layouts.ppdb-online.main')

@section('content')
    <div class="product-index">
        <div class="col">
            <div class="row mb-3 d-flex justify-content-between">
                <a href="#" class="d-flex align-items-center justify-content-around"><img class="head-left" src="{{asset('frontend-ppdb-online/img/Icon/Icon-Arrow.png')}}" alt=""><span class="text-body-title text-primary-green ml-2">Kembali</span></a>
                <a href="#"><img src="{{asset('frontend-ppdb-online/img/Icon/Cart-Active.png')}}" alt=""></a>
            </div>
            <div class="row">
                {{-- <form action=""> --}}
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
                {{-- </form> --}}
            </div>
            <div class="row py-2 justify-content-center">
                <div class="col-6">
                    <a href="#">
                        <div class="card-product">
                            <div class="product-thumbnail" style="background-image: url('{{asset('frontend-ppdb-online/img/product.jpg')}}')"></div>
                            <div class="product-caption d-flex flex-column">
                                <span class="caption-title">Seragam SMP</span>
                                <span class="caption-price">Rp. 123</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-6">
                    <a href="#">
                        <div class="card-product">
                            <div class="product-thumbnail" style="background-image: url('{{asset('frontend-ppdb-online/img/product.jpg')}}')"></div>
                            <div class="product-caption d-flex flex-column">
                                <span class="caption-title">Seragam SMP</span>
                                <span class="caption-price">Rp. 123</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $('#sort').click(function(){
        var dropdownItem = $(this).children()[1]
        $(dropdownItem).toggle()
    })
    $('#filter').click(function(){
        var dropdownItem = $(this).children()[1]
        $(dropdownItem).toggle()
    })
</script>
    
@endpush