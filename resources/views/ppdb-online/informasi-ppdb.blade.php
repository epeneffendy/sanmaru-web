@extends('layouts.ppdb-online.main')
@section('content')

    <div class="wrapper-content-desktop">
        @include('layouts.ppdb-online.tab-bar')

        <div class="container" style="padding: 2rem 2rem">
            <div class="col">
                <h2>{{ $stage->stage->name }}</h2>
                @if ($stage->note)
                    <p style="font-weight: 700; padding: 15px; background: rgba(0,200,0,0.1);">
                        {!! nl2br($stage->note) !!}
                    </p>
                @endif
                @if ($stage->stage->is_opening_shop_feature)
                <p class="text-subtitle-3">
                    <a href="{{ route('ppdb.embed-product.index') }}">klik disini</a> untuk membeli seragam
                </p>
                @endif
                <p class="text-subtitle-3">
                    {!! $stage->stage->information !!}
                </p>
            </div>
            <ul class="btn-below">
                <li>
                    <a href="{{ route('ppdb.welcome') }}">
                        <button type="button" name="back" class="btn-back">Kembali</button>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="wrapper-content-mobile">
        <div class="informasi-ppdb">
            <div class="col">
                <div class="row mb-3">
                    <a href="{{ route('ppdb.welcome') }}" class="d-flex align-items-center justify-content-around"><img class="head-left" src="{{asset('frontend-ppdb-online/img/Icon/Icon-Arrow.png')}}" alt=""><span class="text-body-title text-primary-green ml-2">Kembali</span></a>
                </div>
                <div class="row py-2">
                    <div class="informasi-ppdb-item container">
                        <div class="row mb-3">
                            <div class="col d-flex justify-content-center">
                                <a href="#" onclick="showDescription(1,this)" class="d-flex justify-content-between align-items-center w-100"><span class="text-body-title text-primary-green">{{ $stage->stage->name }}</span><img src="{{asset('frontend-ppdb-online/img/Icon/Icon-Arrow.png')}}" alt="" ></a>
                            </div>
                        </div>
                        <div class="informasi-ppdb-description" id="description1" style="display: none">
                            <div class="row">
                                <div class="col-12">
                                    @if ($stage->note)
                                        <p style="font-weight: 700; padding: 15px; background: rgba(0,200,0,0.1);">
                                            {!! nl2br($stage->note) !!}
                                        </p>
                                    @endif
                                    @if ($stage->stage->is_opening_shop_feature)
                                    <p class="text-description text-navy">
                                        <a href="{{ route('ppdb.embed-product.index') }}">klik disini</a> untuk membeli seragam
                                    </p>
                                    @endif
                                    <p class="text-description text-navy">{!! $stage->stage->information !!}</p>
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="col-3"><p class="text-description text-grey">Waktu</p></div>
                                <div class="col-9"><p class="text-description text-navy">08.00 -10.00</p></div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function showDescription(id,element) {
            img = $(element).children()[1]

            $("#description"+id).toggle()
            $(img).toggleClass('flip')
        }
    </script>
@endpush
