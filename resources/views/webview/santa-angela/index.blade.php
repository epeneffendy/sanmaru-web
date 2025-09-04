@extends('layouts.webview.main')
@section('title', 'St. Angela - SANMARU')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="header-admission w-100"
        style="background-image: url('{{ asset('front/images/headline-santa-angela.png') }}')">
        <div class="container h-100 d-flex flex-column justify-content-center">
                    <h2 class="text-green my-4">Kata-kata<br>St. Angela Merici</h2>
                    <img src="{{ asset('front/images/headline-line.png') }}" width="75" height="7" alt="">
                </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card-angela text-center">
                    <img src="{{ asset('front/images/st-angela-regula.png') }}" alt="">
                    <div class="card-body">
                        <h4 class="card-title text-green">REGULA <br> ST. ANGELA MERICI</h4>
                        <img src="{{ asset('front/images/headline-line.png') }}" width="39" height="7" alt="">
                        <p class="card-text">Kumpulan regula St. Angela Merici</p>
                        <a href="{{route('web.santa-angela.regula')}}" class="btn btn-outline-green">Learn more</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-angela text-center">
                    <img src="{{ asset('front/images/st-angela-nasehat.png') }}" alt="">
                    <div class="card-body">
                        <h4 class="card-title text-green">NASIHAT<br> ST. ANGELA MERICI</h4>
                        <img src="{{ asset('front/images/headline-line.png') }}" width="39" height="7" alt="">
                        <p class="card-text">Kumpulan nasehat St. Angela Merici</p>
                        <a href="{{route('web.santa-angela.nasehat')}}" class="btn btn-outline-green">Learn more</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-angela text-center">
                    <img src="{{ asset('front/images/st-angela-warisan.png') }}" alt="">
                    <div class="card-body">
                        <h4 class="card-title text-green">WARISAN <br> ST. ANGELA MERICI</h4>
                        <img src="{{ asset('front/images/headline-line.png') }}" width="39" height="7" alt="">
                        <p class="card-text">Kumpulan warisan St. Angela Merici</p>
                        <a href="{{route('web.santa-angela.warisan')}}" class="btn btn-outline-green">Learn more</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection