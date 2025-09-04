@extends('layouts.ppdb-online.main')
@section('content')
    <div class="wrapper-content-desktop">
        @include('layouts.ppdb-online.tab-bar')
        <div class="container" style="padding: 3rem">
            <div class="col">

                <div class="row py-2">
                    <h4>PPDB</h4>
                    @foreach ($faqs as $faq)
                    <div class="informasi-ppdb-item container">
                        <div class="row py-2">
                            <div class="d-flex justify-content-between w-100">
                                <a href="#" onclick="showDescriptionDesktop({{ $faq->id }},this)" class="d-flex justify-content-between align-items-center w-100 "><h3 class="text-body-title text-primary-green">{{ $faq->content }}</h3><img src="{{asset('frontend-ppdb-online/img/Icon/Icon-Arrow.png')}}" alt="" ></a>
                            </div>
                        </div>
                        <div class="informasi-ppdb-description" id="descriptionDesktop{{$faq->id}}" style="display:none">
                            <p class="text-subtitle-3 text-grey text-justify"></p><br>
                            {!! $faq->html_answer !!}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="wrapper-content-mobile">
        <div class="informasi-ppdb">
            <div class="col">
                <div class="row mb-3">
                    <a href="{{ route('ppdb.welcome') }}" class="d-flex align-items-center justify-content-around"><img class="head-left" src="{{asset('frontend-ppdb-online/img/Icon/Icon-Arrow.png')}}" alt=""><span class="text-body-title text-primary-green ml-2">Kembali</span></a>
                </div>
                <div class="row py-2">
                    <h4>PPDB</h4>
                    @foreach ($faqs as $faq)
                    <div class="informasi-ppdb-item container">
                        <div class="row mb-3">
                            <div class="col d-flex justify-content-center">
                                <a href="#" onclick="showDescription({{ $faq->id }},this)" class="d-flex justify-content-between align-items-center w-100"><span class="text-body-title text-primary-green">{{ $faq->content }}</span><img src="{{asset('frontend-ppdb-online/img/Icon/Icon-Arrow.png')}}" alt="" ></a>
                            </div>
                        </div>
                        <div class="informasi-ppdb-description" id="description{{ $faq->id }}" style="display: none">
                            <div class="container">
                                <div class="row">
                                    <p class="text-description text-grey text-justify"></p>
                                    {!! $faq->html_answer !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/content-styles.css') }}">
    <style>
        .informasi-ppdb-description ul, ol { 
            list-style-type: style;
        }
        img{
            max-width: 100%;
            max-height: 100%;
            display: block;
        }
    </style>
@endpush
@push('scripts')
    <script>
        function showDescription(id,element) {
            img = $(element).children()[1]

            $("#description"+id).toggle()
            $(img).toggleClass('flip')
        }
        function showDescriptionDesktop(id,element) {
            img = $(element).children()[1]
            $(img).toggleClass('flip')

            if(document.getElementById("descriptionDesktop"+id).style.display == "none"){
                document.getElementById("descriptionDesktop"+id).style.display = "block"
            }else{
                document.getElementById("descriptionDesktop"+id).style.display = "none"
            }
        }
    </script>
@endpush