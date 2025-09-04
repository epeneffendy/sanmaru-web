@extends('layouts.webview.main')
@section('title', $aboutCategory->name .' - SANMARU')
@section('content')

    <div class="container-fluid" style="background-color: #F9F9F9">
        <div class="container">
            <div class="col">
                <div class="row justify-content-center py-4 ">
                    @foreach($nav['aboutCategories'] as $category)
                    <a href="{{ route('web.about.category.show',$category->slug) }}" class="btn btn-yellow nav-about mx-2 {{($nav['child'] == $category->slug) ? 'active' : ''}}">{{$category->name}}</a>
                    @endforeach
                </div>
            </div>
        </div>
        @if(! $abouts->isEmpty())
        @foreach($abouts as $about)
        <div class="container">
            <div class="about-ck-content">
                <h1 class="text-center about-title mb-4 ">{{$about->title}}</h1>
                @if($about->featured_image)
                <div class="img-header my-4" style="background-image: url('{{ $about->getFeaturedImageUrl() }}')"></div>
                @endif
                <div class="row my-4">
                    <!-- <div class="col-3">
                        <div class="row align-items-center justify-content-between pl-3 pr-5">
                            <span class="about-span">Share: </span>
                            <a href="#"><img src="{{ asset('front/images/About/facebook.png') }}" alt=""></a>
                            <a href="#"><img src="{{ asset('front/images/About/twitter.png') }}" alt=""></a>
                            <a href="#"><img src="{{ asset('front/images/About/linkedin.png') }}" alt=""></a>
                            <a href="#"><img src="{{ asset('front/images/About/whatsapp.png') }}" alt=""></a>
                        </div>
                    </div> -->
                    <div class="col-12">
                        <div class="ck-content">
                            {!! $about->html_content !!}
                        </div>
                    </div>
                </div>
                @endforeach
                @else 
                {{'No Post Found'}}
                @endif
            </div>

        </div>
        
    </div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/content-styles.css') }}">
@endpush
