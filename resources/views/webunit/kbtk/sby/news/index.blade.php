@extends('layouts.webunit.kbtk.sby.main')
@section('content')
<div class="news-bg">
  <div class="container">
    <div class="row">
      <div class="col-lg-6">
        <h2 class="heading-2 green-salad">News & <br> Updates</h2>
        <!-- <div class="news-category body-text-14 black-panther">Pengumuman</div> -->
      </div>
    </div>
  </div>
</div>

<section id="popular-news">
  <div class="container">
  <h4 class="title-5 airplane black-panther">Popular News</h4>
  <div class="row desktop-version">
    @if($popular->isNotEmpty())
      @php($headline = $popular->first())
      @php($popularDesktop = $popular->slice(1))
    <div class="col-lg-8">
      <a href="{{route('webunit.news.show', ['webunit' => $webUnit, 'slug' => $headline->slug])}}">
        <div class="headline-news">
          <img class="img-top" src="{{ $headline->getFeaturedImageUrl() }}" alt="Card image cap">
          <div class="title-2 black-panther mt-3 mb-3">{{ $headline->title }}</div>
          <p class="body-text-16 grey">{{ $headline->short_desc }}</p>
          <div class="card-footer d-flex align-items-center">
            <div class="news-category body-text-14 black-panther">{{ @$headline->category->name }}</div>
            <i class="icon icon-clock ml-3 mr-2"></i>
            <div class="news-date body-text-16 grey">{{ \App\Helpers\Helper::tanggal($headline->created_at) }}</div>
          </div>
        </div>
      </a>
    </div>
  
    <div class="col-lg-4">
      @forelse($popularDesktop as $news)
      <a href="{{route('webunit.news.show', ['webunit' => $webUnit, 'slug' => $news->slug])}}">
        <div class="news-card mb-4">
          <img class="card-img-top" src="{{ $news->getFeaturedImageUrl() }}" alt="Card image cap">
          <div class="title-4 black-panther mt-2 mb-2"><strong> {{ $news->title }}</strong> </div>
          <div class="card-footer d-flex align-items-center">
            <div class="news-category body-text-14 black-panther">{{ @$news->category->name }}</div>
            <i class="icon icon-clock ml-3 mr-2"></i>
            <div class="news-date body-text-16 grey">{{ \App\Helpers\Helper::tanggal($news->created_at) }}</div>
          </div>
        </div>
      </a>
      @empty 
      @endforelse
    </div>
    @endif
  </div>
  <div class="mobile-version">
    <div class="row popular-news-wrapper">
      <div class="col-12 align-items-stretch">
        @forelse($popular as $news)
        <a href="{{route('webunit.news.show', ['webunit' => $webUnit, 'slug' => $news->slug])}}">
          <div class="news-card mb-4">
            <img class="card-img-top" src="{{ $news->getFeaturedImageUrl() }}" alt="Card image cap">
            <div class="title-4 black-panther mt-2 mb-2"><strong> {{ $news->title }}</strong> </div>
            <div class="news-category body-text-14 black-panther">{{ @$news->category->name }}</div>
          </div>
        </a>
        @empty 
        @endforelse
      </div>
    </div>
  </div>
    
  </div>
</section>

<section id="lastest-news">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <h4 class="title-5 airplane black-panther">Lastest News</h4>
      <a href="{{ route('webunit.news.all', ['webunit' => $webUnit]) }}" class="body-text-16 grey mr-3"><u>View More</u></a>
    </div>
    <div class="row desktop-version">
      @forelse($latest as $news)
      <div class="col-lg-4">
        <a href="{{route('webunit.news.show', ['webunit' => $webUnit, 'slug' => $news->slug])}}">
          <div class="news-card mt-4 mb-4">
            <img class="card-img-top" src="{{ $news->getFeaturedImageUrl() }}" alt="Card image cap">
            <div class="title-2 black-panther mt-3 mb-3">{{ $news->title }} </div>
            <div class="card-footer d-flex align-items-center">
              <div class="news-category body-text-14 black-panther">{{ @$news->category->name }}</div>
              <i class="icon icon-clock ml-3 mr-2"></i>
              <div class="news-date body-text-16 grey">{{ \App\Helpers\Helper::tanggal($news->created_at) }}</div>
            </div>
          </div>
        </a>
      </div>
      @empty 
      @endforelse

    </div>
    <div class="row mobile-version">
      @forelse($latest as $news)
      <div class="col-12 news-card">
        <a href="{{route('webunit.news.show', ['webunit' => $webUnit, 'slug' => $news->slug])}}">
          <div class="row mt-4 mb-4">
            <div class="col-5">
              <img class="card-img-top" src="{{ $news->getFeaturedImageUrl() }}" alt="Card image cap">
            </div>
            <div class="col-7">
              <div class="title-2 black-panther mb-2">{{ $news->title }} </div>
              <div class="card-footer d-flex align-items-center">
                <i class="icon icon-clock mr-1"></i>
                <div class="news-date body-text-16 grey">{{ \App\Helpers\Helper::tanggal($news->created_at) }}</div>
                <div class="mx-2 black-panther">•</div>
                <div class="news-category body-text-14 black-panther">{{ @$news->category->name }}</div>
              </div>
            </div>
          </div>
        </a>
      </div>
      @empty 
      @endforelse

    </div>
  </div>
</section>
@endsection