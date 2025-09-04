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
                {{-- end of searchbar --}}
                @if($latest && !$latest->isEmpty())
                <div class="row row-card-mobile">
                    <div class="col-9 align-content-center justify-items-center col-full-mobile">
                        <div class="img-headline mb-4" style="background-image: url('{{$latest[0]->getFeaturedImageUrl()}}')"></div>
                        <div class="row justify-content-center my-2">
                            <time class="news-date text-center">{{date('F j, Y', strtotime($latest[0]->created_at))}}</time>
                        </div>
                        <a href="{{route('web.news.show', $latest[0]->slug)}}"><h2 class="news-title text-center my-3">{{$latest[0]->title}}</h2></a>
                        <p class="news-overview text-center my-4">{{$latest[0]->short_desc}}</p>
                    </div>
                    <div class="col-3">
                        <h4 class="news-section-title mb-5">TERBARU</h4>
                        @for($i=1;$i<=count($latest)-1;$i++)
                        <a href="{{route('web.news.show', $latest[$i]->slug)}}"><h4 class="news-mini-title">{{$latest[$i]->title}}</h4></a>
                        <hr>
                        @endfor
                        <a href="{{ route('web.news.all') }}"><h5 class="news-mini-title" style="font-size: 14px; border-bottom: 5px solid #ffce00;">View More News</h5></a>
                    </div>
                </div>
                @endif
                <hr>
                @if($popular && !$popular->isEmpty())
                <div class="row mb-5">
                    <div class="col">
                        <h4 class="news-section-title mb-4">TERPOPULER</h4>
                        <div class="row row-card-mobile">
                            @foreach($popular as $item)
                            <div class="col-4 py-4 col-mobile">
                                <div class="news-mini-img mb-4" style="background-image: url('{{$item->getFeaturedImageUrl()}}')"></div>
                                <a href="{{route('web.news.show', $item->slug)}}"><h4 class="news-mini-title">{{$item->title}}</h4></a>
                                <h5 class="news-category">{{$item->category->name}}</h5>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
                @if($results && !$results->isEmpty())
                <div class="row mb-5">
                    <div class="col">
                        <h4 class="news-section-title mb-4">{{count($results)}} results:</h4>
                        <div class="row">
                            @foreach($results as $item)
                            <div class="col-4 py-4">
                                <div class="news-mini-img mb-4" style="background-image: url('{{$item->getFeaturedImageUrl()}}')"></div>
                                <a href="{{route('web.news.show', $item->slug)}}"><h4 class="news-mini-title">{{$item->title}}</h4></a>
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
