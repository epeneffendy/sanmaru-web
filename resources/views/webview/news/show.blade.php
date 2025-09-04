@extends('layouts.webview.main')
@section('title', $blog->title .' - SANMARU')
@section('content')
    <div class="container">
        <div class="mt-150">
            @if(isset($blog))
            <h1 class="text-center text-green">{{ $blog->title }}</h1>
            <div class="row py-4">
                <img class="w-100" src="{{ $blog->getFeaturedImageUrl() }}" alt="">
            </div>
            <div class="row py-4">
                <div class="col-3">
                    <div class="row align-items-center justify-content-between pl-3 pr-5">
                        <span class="about-span">Share: </span>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ Request::url() }}" target="_blank" class="share-link"><img src="{{ asset('front/images/About/facebook.png') }}" alt=""></a>
                        <a href="https://twitter.com/intent/tweet?text={{ Request::url() }}" class="share-link"><img src="{{ asset('front/images/About/twitter.png') }}" alt=""></a>
                        <a href="whatsapp://send?text={{ Request::url() }}" class="share-link"><img src="{{ asset('front/images/About/whatsapp.png') }}" alt=""></a>
                    </div>
                </div>
                <div class="col-9 ">
                    <div class="ck-content">
                        {!! $blog->html_content !!}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
@push('styles')
<link rel="stylesheet" href="{{ asset('css/content-styles.css') }}">
@endpush
