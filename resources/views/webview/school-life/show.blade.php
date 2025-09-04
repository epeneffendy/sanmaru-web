@extends('layouts.webview.main')
@section('title', $schoolLifeCategory->name .' - SANMARU')
@section('content')
    <div class="container-fluid" style="background-image: url('{{ asset('bg-dot-page.png') }}')">
        <div class="container">
            <div class="school-link-mobile">
                <div class="row justify-content-center">
                    @foreach($nav['schoolLifeCategories'] as $category)
                    <a href="{{ route('web.school-life.category.show',$category->slug) }}" class="school-link {{($nav['child'] == $category->slug) ? 'active' : ''}}">{{$category->name}}<div class="line-yellow inside-position"></div></a>
                    @endforeach
                </div>
            </div>

            <div class="row">
                <div class="col-3 d-flex flex-column align-items-end school-life-link mt-5">
                    @foreach($nav['schoolLifeCategories'] as $category)
                    <a href="{{ route('web.school-life.category.show',$category->slug) }}" class="school-link {{($nav['child'] == $category->slug) ? 'active' : ''}}">{{$category->name}}<div class="line-yellow inside-position"></div></a>
                    @endforeach
                </div>
                <div class="col-9 col-full-mobile">
                    @if(! $schoolLifes->isEmpty())
                    @foreach($schoolLifes as $schoolLife)
                    <h1 class=" text-green">{{ $schoolLife->title }}</h1>
                    @if($schoolLife->featured_image)
                    <img class="cover-img" src="{{ $schoolLife->getFeaturedImageUrl() }}" alt="">
                    @endif
                    <div class="ck-content py-5">
                        {!! $schoolLife->html_content !!}
                    </div>
                    @endforeach
                    @else 
                    {{'No Post Found'}}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
<link rel="stylesheet" href="{{ asset('css/content-styles.css') }}">
@endpush

