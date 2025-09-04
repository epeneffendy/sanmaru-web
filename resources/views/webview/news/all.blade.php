@extends('layouts.webview.main')
@section('title', 'Artikel dan Berita - SANMARU')
@section('content')
    <div class="container-fluid page-bg" style="background-image: url({{asset('front/images/bg-dot-page.png')}})">
        <div class="container">
            <div class="col">
                <h1 class="about-title text-center py-4">Artikel dan Berita</h1>
                {{-- search bar --}}
                <div class="row justify-content-center mb-5">
                    <form class="form-inline" action="{{ route('web.news.index') }}">
                        <input type="text" name="title" class="form-control search-bar" placeholder="Cari artikel dan berita..." value="{{ @$params['title'] }}">
                        <i class="search-icon"><img src="{{asset('front/images/icon-search.png')}}" height="15" alt=""></i>
                    </form>
                </div>
                @if($news && !$news->isEmpty())
                <div class="row mb-5">
                    <div class="col">
                        <h4 class="news-section-title mb-4">Berita</h4>
                        <div class="row row-card-mobile">
                            @foreach($news as $item)
                            <div class="col-4 py-4 col-mobile">
                                <div class="news-mini-img mb-4" style="background-image: url('{{$item->getFeaturedImageUrl()}}')"></div>
                                <a href="{{route('web.news.show', $item->slug)}}"><h4 class="news-mini-title">{{$item->title}}</h4></a>
                                {{-- <h5 class="news-category">{{$item->category->name}}</h5> --}}
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
