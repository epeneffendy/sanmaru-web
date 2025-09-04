@extends('layouts.webunit.sma.sby.main')
@section('content')
    <div class="navbar-bg mobile-version"></div>
    <div class="news-bg desktop-version">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2 class="heading-2 yellow-submarine">News & <br> Updates</h2>
                    <!-- <div class="headline-news-category body-text-14 black-panther">Pengumuman</div> -->
                </div>
            </div>
        </div>
    </div>
    <section id="all-news">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
            <h4 class="title-1 green-salad d-inline-block desktop-version">All News</h4>
            <h4 class="title-3 airplane black-panther mb-4 mobile-version">All News</h4>
            <div class="d-flex mb-4 desktop-version">
                <button class="btn btn-outline-green body-text-14 oak-green mr-2">
                <div class="d-flex justify-content-center align-items-center">
                    Search
                    <i class="icon icon-search ml-2"></i>
                </div>
                </button>
                <div class="dropdown">
                <button class="btn btn-outline-green dropdown-toggle body-text-14 oak-green" type="button" id="dropdownSortBy" data-bs-toggle="dropdown" aria-expanded="false">
                Sort by
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownSortBy">
                <li><a class="dropdown-item body-text-14 white" href="#">Newest</a></li>
                <li><a class="dropdown-item body-text-14 white" href="#">Oldest</a></li>
                <li><a class="dropdown-item body-text-14 white" href="#">Most Popular</a></li>
                </ul>
                </div>
            </div>
            </div>

            <div class="mobile-version">
            <form action="">
                <div class="input-group search-input">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                    <div class="icon icon-search"></div>
                    </span>
                </div>
                <input type="text" class="form-control" placeholder="Search" required>
                <div class="input-group-append">
                    <span class="input-group-text">
                        <div class="icon icon-sort"></div>
                    </span>
                </div>
                </div>
            </form>
            </div>

            <div class="desktop-version">
            @forelse($news as $blog)
            <div class="row news-list mt-4 mb-4 desktop-version">
                <div class="col-md-4">
                <a href="{{route('webunit.news.show', ['webunit' => $webUnit, 'slug' => $blog->slug])}}">
                    <img class="card-img-top" src="{{ $blog->getFeaturedImageUrl() }}" alt="Card image cap">
                </a>
                </div>
                <div class="col-md-8">
                <a href="{{route('webunit.news.show', ['webunit' => $webUnit, 'slug' => $blog->slug])}}">
                    <div class="title-2 black-panther mb-3">{{ $blog->title }}</div>
                    <p class="body-text-16 grey">{{ $blog->short_desc }}</p>
                </a>
                <div class="card-footer d-flex align-items-center">
                    <div class="news-category body-text-14 black-panther">{{ @$blog->category->name }}</div>
                    <i class="icon icon-clock ml-3 mr-2"></i>
                    <div class="news-date body-text-16 grey">{{ \App\Helpers\Helper::tanggal($blog->created_at) }}</div>
                </div>
                </div>
            </div>
            @empty
            @endforelse
            </div>

            <div class="row mobile-version">
            @forelse($news as $blog)
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
