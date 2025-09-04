@extends('layouts.ppdb-online.main')

@section('content')
    <div class="product-show">
        <div class="col">
            <div class="row mb-3 d-flex justify-content-between">
                <a href="#" class="d-flex align-items-center justify-content-around"><img class="head-left" src="{{asset('frontend-ppdb-online/img/Icon/Icon-Arrow.png')}}" alt=""><span class="text-body-title text-primary-green ml-2">Kembali belanja</span></a>
            </div>
            <div class="row py-2">

            </div>
            <div class="row my-3">
                
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