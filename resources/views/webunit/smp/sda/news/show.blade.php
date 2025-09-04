@extends('layouts.webunit.smp.sda.main')
@section('content')
    <div class="news-bg desktop-version">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2 class="heading-2 green-salad"> News & <br> Updates </h2>
                    <!-- <div class="headline-news-category body-text-14 vanilla-cream">Pengumuman</div> -->
                </div>
            </div>
        </div>
    </div>

    <section id="news-detail">
            <div class="container">
                @if($news)
                @if($news->featured_image)
                <div class="row">
                <div class="col-lg-12">
                    <img class="news-image" src="{{ $news->getFeaturedImageUrl() }}" alt="News Image">
                </div>
                </div>
                @endif
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                        <div class="col-md-1 desktop-version">
                            <div class="d-flex flex-column align-items-center mt-4 mr-4">
                            <p class="body-text-12 grey"><strong>Share</strong></p>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ Request::url() }}" target="_blank">
                                <i class="icon icon-share-facebook mt-3"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?text={{ Request::url() }}">
                                <i class="icon icon-share-twitter mt-3"></i>
                            </a>
                            <a href="https://wa.me/?text={{ Request::url() }}">
                                <i class="icon icon-share-whatsapp mt-3"></i>
                            </a>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="news-detail">
                            <div class="title-2 black-panther mt-3">{{ $news->title }}</div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="news-date body-text-16 grey mt-2">{{ \App\Helpers\Helper::tanggal($news->created_at) }}</div>
                                <div class="mobile-version">
                                <div class="d-flex">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ Request::url() }}" target="_blank">
                                    <i class="icon icon-share-facebook mt-2 mr-1"></i>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?text={{ Request::url() }}">
                                    <i class="icon icon-share-twitter mt-2 mr-1"></i>
                                    </a>
                                    <a href="https://wa.me/?text={{ Request::url() }}">
                                    <i class="icon icon-share-whatsapp mt-2"></i>
                                    </a>
                                </div>
                                </div>
                            </div>
                            <div class="mt-4 ">
                                {!! $news->html_content !!}
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    @endif
                    <div class="col-md-4 desktop-version">
                        <div class="body-text-18 black-panther mt-2 mb-4"><strong>Related News</strong></div>
                        @forelse($related as $blog)
                        <a href="{{route('webunit.news.show', ['webunit' => $webUnit, 'slug' => $blog->slug])}}">
                        <div class="news-card mb-4">
                            <img class="card-img-top" src="{{ $blog->getFeaturedImageUrl() }}" alt="Card image cap">
                            <div class="title-4 black-panther mt-2 mb-2"><strong> {{ $blog->title }}</strong> </div>
                            <div class="card-footer d-flex align-items-center">
                            <div class="news-category body-text-14 black-panther">{{ @$blog->category->name }}</div>
                            <i class="icon icon-clock ml-3 mr-2"></i>
                            <div class="news-date body-text-16 grey">{{ \App\Helpers\Helper::tanggal($blog->created_at) }}</div>
                            </div>
                
                        </div>
                        </a>
                        @empty 
                        @endforelse 
                    </div>
                </div>

                <div class="row mobile-version">
                <div class="col-12 body-text-18 grey my-2"><strong>Related News</strong></div>
                @forelse($related as $blog)
                <div class="col-12 news-card">
                    <a href="{{route('webunit.news.show', ['webunit' => $webUnit, 'slug' => $blog->slug])}}">
                    <div class="row mt-4 mb-4">
                        <div class="col-5">
                        <img class="card-img-top" src="{{ $blog->getFeaturedImageUrl() }}" alt="Card image cap">
                        </div>
                        <div class="col-7">
                        <div class="title-2 black-panther mb-2">{{ $blog->title }} </div>
                        <div class="card-footer d-flex align-items-center">
                            <i class="icon icon-clock mr-1"></i>
                            <div class="news-date body-text-16 grey">{{ \App\Helpers\Helper::tanggal($blog->created_at) }}</div>
                            <div class="mx-2 black-panther">•</div>
                            <div class="news-category body-text-14 black-panther">{{ @$blog->category->name }}</div>
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
