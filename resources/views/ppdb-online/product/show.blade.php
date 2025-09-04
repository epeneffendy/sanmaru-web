@extends('layouts.ppdb-online.main')

@section('content')
    <div class="product-show">
        <div class="col">
            <div class="row mb-3 d-flex justify-content-between">
                <a href="#" class="d-flex align-items-center justify-content-around"><img class="head-left" src="{{asset('frontend-ppdb-online/img/Icon/Icon-Arrow.png')}}" alt=""><span class="text-body-title text-primary-green ml-2">Kembali</span></a>
                <a href="#"><img src="{{asset('frontend-ppdb-online/img/Icon/Cart-Active.png')}}" alt=""></a>
            </div>
            <div class="row py-2">
                <img src="{{asset('frontend-ppdb-online/img/thumbnail-product.png')}}" alt="" class="w-100">
            </div>
            <div class="row my-3">
                <div class="col">
                    <div class="d-flex flex-column">
                        <span class="text-body-title text-black">Satu Set Kemeja SMP</span>
                        <span class="text-body text-primary-green">Rp. 150.000,-</span>
                    </div>
    
                    <hr>
                    <a href="#" class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <span class="text-primary-green">Pilih Ukuran</span>
                            <span class="text-grey pl-1">(contoh: S, M, L, XL)</span>
                        </div>
                        <img src="{{asset('frontend-ppdb-online/img/Icon/Icon-Arrow.png')}}" alt="" style="transform: rotate(-90deg)">
                    </a>
                    <hr>
    
                    <span class="text-body-title text-black">Informasi Produk</span>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="text-description text-black">Stok</span>
                        <span class="text-description text-grey">>200</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="text-description text-black">Kategori</span>
                        <span class="text-description text-grey">Pakaian Pria</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="text-description text-black">Berat</span>
                        <span class="text-description text-grey">300gr</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="text-description text-black">Tipe</span>
                        <span class="text-description text-grey">Kemeja SMP</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="text-description text-black">Merk</span>
                        <span class="text-description text-grey">Javara</span>
                    </div>
                    <hr>
                    
                    <div class="d-flex justify-content-center">
                        <a href="#" class="btn btn-green align-self-center">Tambah ke keranjang</a>
                    </div>
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