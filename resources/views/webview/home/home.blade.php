@extends('layouts.webview.main')

@push('styles')
<style>
  .youtube-player {
    position: relative;
    padding-bottom: 56.25%;
    height: 0;
    overflow: hidden;
    width: 330px;
    height: 200px;
    background: #000;
    margin: 5px;
  }

  .youtube-player iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 100;
    background: transparent;
  }

  .youtube-player img {
    object-fit: cover;
    display: block;
    left: 0;
    bottom: 0;
    margin: auto;
    max-width: 100%;
    width: 100%;
    position: absolute;
    right: 0;
    top: 0;
    border: none;
    height: auto;
    cursor: pointer;
    -webkit-transition: 0.4s all;
    -moz-transition: 0.4s all;
    transition: 0.4s all;
  }

  .youtube-player img:hover {
    -webkit-filter: brightness(75%);
  }

  .youtube-player .play {
    height: 64px;
    width: 64px;
    left: 50%;
    top: 50%;
    margin-left: -30px;
    margin-top: -30px;
    position: absolute;
    background: url("{{ asset('front/images/youtube-play.png') }}") no-repeat;
    cursor: pointer;
  }
</style>
@endpush

@section('content')

{{-- MODAL SECTION --}}
@if (!$popups->isEmpty())
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-centered">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="col px-5 popup-mobile">
          <div id="CarouselPopup" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
              @foreach($popups as $key => $popup)
              <div class="carousel-item {{ $key == 0 ? 'active' : ''}}">
                <div class="col">
                  <div class="row justify-content-center py-3"><h1 class="text-center text-green">{{ $popup->title }}</h1></div>
                  <div class="row justify-content-center">
                    {!! $popup->html_content !!}
                  </div>
                </div>
              </div>
              @endforeach
            </div>
            <a class="carousel-control-prev" href="#CarouselPopup" role="button" data-slide="prev">
              <img src="{{asset('front/images/icon-arrow-left.png')}}" alt="">
              <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#CarouselPopup" role="button" data-slide="next">
              <img src="{{asset('front/images/icon-arrow-right.png')}}" alt="">
              <span class="sr-only">Next</span>
            </a>
          </div>

          {{-- <div class="col">
            <div class="row justify-content-center py-3"><img src="{{asset('front/images/blog.png')}}" alt=""></div>
            <div class="row justify-content-center py-3"><h1 class="text-center text-green">Lorem Ipsum</h1></div>
            <div class="row justify-content-center">
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus nibh leo, molestie eu ullamcorper eget, pretium et ligula. Praesent lorem est, placerat a accumsan laoreet, suscipit vitae lacus. Proin placerat, ipsum consequat hendrerit malesuada, lectus arcu scelerisque tellus, dictum hendrerit velit lacus vitae metus. Duis aliquam molestie lacinia. Praesent dignissim eu risus quis iaculis. Suspendisse potenti. Fusce nunc leo, cursus fringilla metus vitae, efficitur dapibus tortor. Duis lorem tortor, molestie ut condimentum ut, ullamcorper a velit.</p>
            </div>
          </div> --}}
        </div>
      </div>
      <!--<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>-->
    </div>

  </div>
</div>
@endif
{{-- END OF MODAL SECTION --}}


  <nav id="navbar" class="navbar-scroll d-flex flex-column justify-content-sm-center">
    <ul id="menu">
      <li>
        <a data-menuanchor="UnitAnchor" href="#UnitAnchor" class="navbar-scroll-link scrollto">
          <img class="white" src="{{asset('front/images/Icons/Unit-white.png')}}" alt="">
          <img class="green" src="{{asset('front/images/Icons/Unit-green.png')}}" alt="">
          <span>Unit</span>
        </a>
      </li>
      <li>
        <a data-menuanchor="StAngela" href="#StAngela" class="navbar-scroll-link scrollto">
          <img class="white" src="{{asset('front/images/Icons/Quotes-white.png')}}" alt="">
          <img class="green" src="{{asset('front/images/Icons/Quotes-green.png')}}" alt="">
          <span>QUOTES</span>
        </a>
      </li>
      <li>
        <a data-menuanchor="NewsAnchor" href="#NewsAnchor" class="navbar-scroll-link scrollto">
          <img class="white" src="{{asset('front/images/Icons/News-white.png')}}" alt="">
          <img class="green" src="{{asset('front/images/Icons/News-green.png')}}" alt="">
          <span>NEWS</span>
        </a>
      </li>
      <li>
        <a data-menuanchor="TestimonialsAnchor" href="#TestimonialsAnchor" class="navbar-scroll-link scrollto">
          <img class="white" src="{{asset('front/images/Icons/Testimonial-white.png')}}" alt="">
          <img class="green" src="{{asset('front/images/Icons/Testimonial-green.png')}}" alt="">
          <span>Testimonials</span>
        </a>
      </li>
      <li>
        <a data-menuanchor="GalleryAnchor" href="#GalleryAnchor" class="navbar-scroll-link scrollto">
          <img class="white" src="{{asset('front/images/Icons/Gallery-white.png')}}" alt="">
          <img class="green" src="{{asset('front/images/Icons/Gallery-green.png')}}" alt="">
          <span>Gallery</span>
        </a>
      </li>
      <li>
        <a data-menuanchor="VideoAnchor" href="#VideoAnchor" class="navbar-scroll-link scrollto">
          <img class="white" src="{{asset('front/images/Icons/Video-white.png')}}" alt="">
          <img class="green" src="{{asset('front/images/Icons/Video-green.png')}}" alt="">
          <span>Video</span>
        </a>
      </li>
      <li>
        <a data-menuanchor="PPDBAnchor" href="#PPDBAnchor" class="navbar-scroll-link scrollto">
          <img class="white" src="{{asset('front/images/Icons/PPDB-white.png')}}" alt="">
          <img class="green" src="{{asset('front/images/Icons/PPDB-green.png')}}" alt="">
          <span>PPDB</span>
        </a>
      </li>
      <li>
        <a data-menuanchor="MapsAnchor" href="#MapsAnchor" class="navbar-scroll-link scrollto">
          <img class="white" src="{{asset('front/images/Icons/Maps-white.png')}}" alt="">
          <img class="green" src="{{asset('front/images/Icons/Maps-green.png')}}" alt="">
          <span>Maps</span>
        </a>
      </li>
      <li>
        <a data-menuanchor="ContactAnchor" href="#ContactAnchor" class="navbar-scroll-link scrollto">
          <img class="white" src="{{asset('front/images/Icons/Contact-white.png')}}" alt="">
          <img class="green" src="{{asset('front/images/Icons/Contact-green.png')}}" alt="">
          <span>Contact</span>
        </a>
      </li>
    </ul>
  </nav>

  <section class="section-hero">
    <div class="hero-carousel" class="carousel slide" data-ride="carousel">
      @if($headlines->isEmpty())
        <div class="carousel-inner">
          <div class="section-home" style="background-image: url('{{asset('front/images/home-section.png')}}');">
            <h1 class="header-text">Welcome to Campus Santa Maria</h1>
            <h1 class="sub-header">Built in 1922 by Ursuline</h1>
          </div>
        </div>
      @else
        @foreach($headlines as $headline)
          <div class="carousel-inner">
            @if($headline->type === 'video')
              <div class="section-home">
                <iframe width="640" height="360" src="{{$headline->getUrl()}}?&autoplay=1&loop=1&mute=1&rel=0&showinfo=0&fs=0&modestbranding=1&autohide=1&playlist={{$headline->content_url}}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="1" frameborder="0"></iframe>
                <div class="container-fluid hero-header">
                  <h1 class="header-text">Welcome to Campus Santa Maria</h1>
                  <h1 class="sub-header">Built in 1922 by Ursuline</h1>
                </div>
              </div>
            @else
              <div class="section-home" style="background-image: url('{{$headline->getUrl()}}');">
                <div class="container-fluid hero-header">
                  <h1 class="header-text">Welcome to Campus Santa Maria</h1>
                  <h1 class="sub-header">Built in 1922 by Ursuline</h1>
                </div>
              </div>
            @endif
          </div>
        @endforeach
      @endif
    </div>
  </section>


  <section class="section-sekolah" id="UnitAnchor">
    <div class="container h-100">
      <div class="row justify-content-center">
        <div class="col">
          <h4 class="school-title title-section">- UNIT SEKOLAH -</h4>
        </div>
      </div>

      <div class="carousel-mobile">
        <div class="row h-75 justify-content-center align-items-center">
          @if (!$units->isEmpty())
          <div id="Carousel-sekolah-mobile" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
              @foreach ($units->chunk(1)->all() as $key => $chunked)
              <div class="carousel-item {{ $key == 0 ? 'active' : NULL }}">
                <div class="row equal">
                  @foreach ($chunked as $unit)
                  <div class="col">
                    <div class="container h-100 card-school d-flex flex-column justify-content-between">
                      <div class="center-horizontally">
                        <div class="container img-circle img-school my-3"
                          style="background: url('{{ $unit->getImagePathUrl() }}') no-repeat center/cover;"></div>
                        <h5 class="school-name m-2">{{ $unit->unit->name }}</h5>
                        <p class="text-address m-3">{{ $unit->unit->address }}</p>
                        {{-- <a href="{{ route('webunit.home', ['webunit' => $webUnit]) }}" class="btn button-outline-green m-2">VISIT SCHOOL ></a> --}}
                      </div>
                      <a href="{{ $unit->permalink }}" class="btn button-outline-green m-2">VISIT SCHOOL ></a>
                    </div>
                  </div>
                  @endforeach
                </div>
              </div>
              @endforeach
            </div>

            <div class="container">
              <div class="row justify-content-center">
                <div class="col-3">
                  <ol class="carousel-indicators">
                    @foreach ($units->chunk(1)->all() as $key => $chunked)
                    <li data-target="#Carousel-sekolah-mobile" data-slide-to="{{ $key }}" {!! $key == 0 ? 'class="active"' : NULL !!}}></li>
                    @endforeach
                    <a class="carousel-control-prev" data-target="#Carousel-sekolah-mobile" role="button" data-slide="prev">
                      <img src="{{asset('front/images/icon-arrow-left.png')}}" alt="">
                    </a>
                    <a class="carousel-control-next" data-target="#Carousel-sekolah-mobile" role="button" data-slide="next">
                      <img src="{{asset('front/images/icon-arrow-right.png')}}" alt="">
                    </a>
                  </ol>
                </div>
              </div>
            </div>
          </div>
          @endif
        </div>
      </div>

      <div class="row h-75 align-items-center">
        <div class="container">
          <div class="carousel-desktop">
            @if (!$units->isEmpty())
            <div id="Carousel-sekolah" class="carousel slide" data-ride="carousel">
              <div class="carousel-inner">
                @foreach ($units->chunk(4)->all() as $key => $chunked)
                <div class="row equal carousel-item {{ $key == 0 ? 'active' : NULL }}">
                  <div class="row equal justify-content-center">
                    @foreach ($chunked as $unit)
                     @if(strpos($unit->unit->name, 'KB') === false)
                      <div class="col-3">
                        <div class="container h-100 card-school d-flex flex-column justify-content-between">
                          <div class="center-horizontally">
                            <div class="container img-circle img-school my-3"
                              style="background: url('{{ $unit->getImagePathUrl() }}') no-repeat center/cover;"></div>
                            @if(strpos($unit->unit->name, 'TK') !== false)
                              <h5 class="school-name m-2">{{ str_replace("TK", "KBTK", $unit->unit->name) }}</h5>
                            @else
                              <h5 class="school-name m-2">{{ $unit->unit->name }}</h5>
                            @endif
                            <p class="text-address m-3">{{ $unit->unit->address }}</p>
                          </div>
                          <a href="{{ $unit->permalink }}" class="btn button-outline-green m-2">VISIT SCHOOL ></a>
                        </div>
                      </div>
                     @endif
                    @endforeach
                  </div>
                </div>
                @endforeach
              </div>

              <div class="container">
                <div class="row justify-content-center">
                  <div class="col-3">
                    <ol class="carousel-indicators">
                      @foreach ($units->chunk(4)->all() as $key => $chunked)
                      <li data-target="#Carousel-sekolah" data-slide-to="{{ $key }}" {!! $key == 0 ? 'class="active"' : NULL !!}}></li>
                      @endforeach
                      <a class="carousel-control-prev" data-target="#Carousel-sekolah" role="button" data-slide="prev">
                        <img src="{{asset('front/images/icon-arrow-left.png')}}" alt="">
                      </a>
                      <a class="carousel-control-next" data-target="#Carousel-sekolah" role="button" data-slide="next">
                        <img src="{{asset('front/images/icon-arrow-right.png')}}" alt="">
                      </a>
                    </ol>
                  </div>
                </div>
              </div>
            </div>

            @endif
          </div>
        </div>
      </div>


    </div>
  </section>

  <section class="section-stangela" id="StAngela">
    <div class="container h-100">
      <div class="d-flex flex-column justify-content-between h-100">
        <div class="row justify-content-center">
          <h4 class="text-center text-green title-section">- The Words of Santa Angela Merici -</h1>
        </div>
        <div class="row justify-content-center">
          <h5 class="text-center font-italic">“Hiduplah dalam keserasian, bersatu, sehati sekehendak, terikat satu sama lain dengan cinta kasih, saling menghargai, saling membantu, saling bersabar dalam Yesus Kristus”
            <br>(Nas.T. 2)</h4>
        </div>

        <div class="row justify-content-center pt-3">
          <a href="{{route('web.about.category.show', 'spritualitas-santa-angela')}}" class="btn button-outline-green">Spiritualitas Santa Angela</a>
        </div>
      </div>
    </div>
  </section>

  <section class="section section-blog" id="NewsAnchor">
    <div class="container h-100">
      <div class="row justify-content-center">
        <div class="col">
          <h4 class="blog-title title-section">- LATEST BLOGS AND ARTICLES -</h4>
        </div>
      </div>

      <div class="carousel-mobile">
        @if (!$blogs->isEmpty())
        <div id="Carousel-mobile" class="carousel slide" data-ride="carousel">

          <div class="carousel-inner">
            @foreach ($blogs->chunk(1)->all() as $key => $chunked)
              <div class="carousel-item {{ $key == 0 ? 'active' : NULL }}">
                <div class="row align-self-center justify-content-center">
                  @foreach ($chunked as $blog)
                    <div class="col">
                      <div class="d-flex justify-content-center align-items-center flex-column">
                        <!-- <img src="{{ $blog->getFeaturedImageUrl() }}" class="mb-4" alt="" width="100%"> -->
                        <div class="mb-4" style="width: 100%; height: 213px; background: url({{ $blog->getFeaturedImageUrl() }}) no-repeat center/cover"></div>
                        <div class="blog-title mb-4">
                          <h4 class="blog-article-title">{{ $blog->title }}</h4>
                        </div>
                        <div class="blog-date mb-4">
                          <span> {{ \Carbon\Carbon::parse($blog->created_at)->format('F j, Y') }}</span>
                        </div>
                        <div class="blog-content mb-4">
                          <p>{{ $blog->short_desc }}</p>
                        </div>
                        <a href="{{ route('web.news.show', $blog->slug) }}" class="btn button-readmore">READ MORE...</a>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            @endforeach
          </div>

          <div class="container">
            <div class="row justify-content-center">
              <div class="col-3">
                <ol class="carousel-indicators">
                @foreach ($blogs->chunk(1)->all() as $key => $chunked)
                  <li data-target="#Carousel-mobile" data-slide-to="{{ $key }}" {!! $key == 0 ? 'class="active"' : NULL !!}></li>
                @endforeach
                  <a class="carousel-control-prev" data-target="#Carousel-mobile" role="button" data-slide="prev">
                    <img src="{{asset('front/images/icon-arrow-left.png')}}" alt="">
                  </a>
                  <a class="carousel-control-next" data-target="#Carousel-mobile" role="button" data-slide="next">
                    <img src="{{asset('front/images/icon-arrow-right.png')}}" alt="">
                  </a>
                </ol>
              </div>
            </div>
          </div>
        </div>
        @endif
      </div>

      <div class="carousel-desktop">
        @if (!$blogs->isEmpty())
        <div id="Carousel" class="carousel slide" data-ride="carousel">

          <div class="carousel-inner">
            @foreach ($blogs->chunk(3)->all() as $key => $chunked)
              <div class="carousel-item {{ $key == 0 ? 'active' : NULL }}">
                <div class="row align-self-center justify-content-center">
                  @foreach ($chunked as $blog)
                    <div class="col-4 my-4">
                      <div class="d-flex justify-content-center align-items-center flex-column card-blog">
                        <!-- <img src="{{ $blog->getFeaturedImageUrl() }}" class="mb-4" alt="" width="100%"> -->
                        <div class="mb-4" style="width: 100%; height: 213px; background: url({{ $blog->getFeaturedImageUrl() }}) no-repeat center/cover"></div>
                        <div class="blog-title mb-4" style="height: 102px">
                          <h4 class="blog-article-title">{{ $blog->title }}</h4>
                        </div>
                        <div class="blog-date mb-4" style="height: 20px">
                          <span> {{ \Carbon\Carbon::parse($blog->created_at)->format('F j, Y') }}</span>
                        </div>
                        <div class="blog-content mb-4" style="height: 69px">
                          <p>{{ $blog->short_desc }}</p>
                        </div>

                        <a href="{{ route('web.news.show', $blog->slug) }}" class="btn button-readmore" style="margin-top: auto">READ MORE...</a>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            @endforeach
          </div>

          <div class="container">
            <div class="row justify-content-center">
              <div class="col-3">
                <ol class="carousel-indicators">
                @foreach ($blogs->chunk(2)->all() as $key => $chunked)
                  <li data-target="#Carousel" data-slide-to="{{ $key }}" {!! $key == 0 ? 'class="active"' : NULL !!}></li>
                @endforeach
                  <a class="carousel-control-prev" data-target="#Carousel" role="button" data-slide="prev">
                    <img src="{{asset('front/images/icon-arrow-left.png')}}" alt="">
                  </a>
                  <a class="carousel-control-next" data-target="#Carousel" role="button" data-slide="next">
                    <img src="{{asset('front/images/icon-arrow-right.png')}}" alt="">
                  </a>
                </ol>
              </div>
            </div>
          </div>
        </div>
        @endif
      </div>
    </div>
  </section>

  <section class="section section-testimonials" id="TestimonialsAnchor" style="background-image: url('{{asset('front/images/bg-home.png')}}');">
    <div class="container-fluid h-100">
      <div class="row justify-content-center">
        <div class="col">
          <h4 class="testi-title title-section">- TESTIMONIALS OF STUDENTS AND ALUMNI -</h4>
        </div>
      </div>

      <div class="carousel-mobile">
        @if (!$testimonials->isEmpty())
        <div id="Carousel-testi-mobile" class="carousel slide" data-ride="carousel">
          <div class="carousel-inner">
            @foreach ($testimonials->chunk(1)->all() as $key => $chunked)
            <div class="carousel-item {{ $key == 0 ? 'active' : NULL }}">
              <div class="row align-self-center justify-content-center">
                @foreach ($chunked as $testimony)
                <div class="row m-3">
                  <div class="container card-testi d-flex flex-column justify-content-between align-items-center">
                    <div class="container img-circle" style="background: url('{{ $testimony->getPhotoPathUrl(asset('front/images/pp.png')) }}') no-repeat center/cover; "></div>
                    <h5 class="alum-name">{{ $testimony->subject }}</h5>
                    <img src="{{asset('front/images/line.png')}}" alt="">
                    <div class="container-fluid d-flex justify-content-start">
                      <img src="{{asset('front/images/quote.png')}}" alt="">
                    </div>
                    <p class="text-testi">{{ $testimony->content }}</p>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
            @endforeach
          </div>

          <div class="container">
            <div class="row justify-content-center">
              <div class="col-3">
                <ol class="carousel-indicators">

                @foreach ($testimonials->chunk(1)->all() as $key => $chunked)
                  <li data-target="#Carousel-testi-mobile" data-slide-to="{{ $key }}" {!! $key == 0 ? 'class="active"' : NULL !!}></li>
                @endforeach
                  <a class="carousel-control-prev" data-target="#Carousel-testi-mobile" role="button" data-slide="prev">
                    <img src="{{asset('front/images/icon-arrow-left.png')}}" alt="">
                  </a>
                  <a class="carousel-control-next" data-target="#Carousel-testi-mobile" role="button" data-slide="next">
                    <img src="{{asset('front/images/icon-arrow-right.png')}}" alt="">
                  </a>
                </ol>
              </div>
            </div>
          </div>
        </div>
        @endif
      </div>

      <div class="carousel-desktop">
        @if (!$testimonials->isEmpty())
        <div id="Carousel-testi" class="carousel slide" data-ride="carousel">
          <div class="carousel-inner">
            @foreach ($testimonials->chunk(3)->all() as $key => $chunked)
            <div class="carousel-item {{ $key == 0 ? 'active' : NULL }}">
              <div class="row align-self-center justify-content-center">
                @foreach ($chunked as $testimony)
                <div class="row m-3">
                  <div class="container card-testi d-flex flex-column justify-content-between align-items-center">
                    <div class="container img-circle" style="background: url('{{ $testimony->getPhotoPathUrl(asset('front/images/pp.png')) }}') no-repeat center/cover; "></div>
                    <h5 class="alum-name">{{ $testimony->subject }}</h5>
                    <img src="{{asset('front/images/line.png')}}" alt="">
                    <div class="container-fluid d-flex justify-content-start">
                      <img src="{{asset('front/images/quote.png')}}" alt="">
                    </div>
                    <p class="text-testi">{{ $testimony->content }}</p>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
            @endforeach
          </div>

          <div class="container">
            <div class="row justify-content-center">
              <div class="col-3">
                <ol class="carousel-indicators">

                @foreach ($testimonials->chunk(3)->all() as $key => $chunked)
                  <li data-target="#Carousel-testi" data-slide-to="{{ $key }}" {!! $key == 0 ? 'class="active"' : NULL !!}></li>
                @endforeach
                  <a class="carousel-control-prev" data-target="#Carousel-testi" role="button" data-slide="prev">
                    <img src="{{asset('front/images/icon-arrow-left.png')}}" alt="">
                  </a>
                  <a class="carousel-control-next" data-target="#Carousel-testi" role="button" data-slide="next">
                    <img src="{{asset('front/images/icon-arrow-right.png')}}" alt="">
                  </a>
                </ol>
              </div>
            </div>
          </div>
        </div>
        @endif
      </div>
    </div>
  </section>

  <section class="section section-gallery" id="GalleryAnchor">
    <div class="container-fluid h-100">
      <div class="row justify-content-center">
        <div class="col">
          <h4 class="gallery-title title-section">- GALLERY -</h4>
        </div>
      </div>

      <div class="carousel-mobile">
        @if (!$galleries->isEmpty())
        <div id="Carousel-gallery-mobile" class="carousel slide" data-ride="carousel">
          <div class="carousel-inner">
            @foreach ($galleries->chunk(2)->all() as $key => $chunked)
            <div class="carousel-item {{ $key == 0 ? 'active' : NULL }}">
              <div class="col align-self-center justify-content-sm-around">
                @foreach (collect($chunked)->chunk(1)->all() as $chunkedGalleries)
                <div class="row justify-content-center">
                  @foreach ($chunkedGalleries as $gallery)
                  <div class="card-gallery m-4" style="background: url('{{ $gallery->getContentImageUrl() }}') no-repeat center/cover; "></div>
                  @endforeach
                </div>
                @endforeach
              </div>
            </div>
            @endforeach
          </div>

          <div class="container">
            <div class="row justify-content-center">
              <div class="col-3">
                <ol class="carousel-indicators">
                  @foreach ($galleries->chunk(2)->all() as $key => $chunked)
                  <li data-target="#Carousel-gallery-mobile" data-slide-to="{{ $key }}" {!! $key == 0 ? 'class="active"' : NULL !!}}></li>
                  @endforeach
                  <a class="carousel-control-prev" data-target="#Carousel-gallery-mobile" role="button" data-slide="prev">
                    <img src="{{asset('front/images/icon-arrow-left.png')}}" alt="">
                  </a>
                  <a class="carousel-control-next" data-target="#Carousel-gallery-mobile" role="button" data-slide="next">
                    <img src="{{asset('front/images/icon-arrow-right.png')}}" alt="">
                  </a>
                </ol>
              </div>
            </div>
          </div>
        </div>
        @endif
      </div>

      <div class="carousel-desktop">
        @if (!$galleries->isEmpty())
        <div id="Carousel-gallery" class="carousel slide" data-ride="carousel">
          <div class="carousel-inner">
            @foreach ($galleries->chunk(6)->all() as $key => $chunked)
            <div class="carousel-item {{ $key == 0 ? 'active' : NULL }}">
              <div class="col align-self-center justify-content-sm-around">
                @foreach (collect($chunked)->chunk(6)->all() as $chunkedGalleries)
                <div class="row justify-content-center">
                  @foreach ($chunkedGalleries as $gallery)
                  <div class="card-gallery m-4" style="background: url('{{ $gallery->getContentImageUrl() }}') no-repeat center/cover; "></div>
                  @endforeach
                </div>
                @endforeach
              </div>
            </div>
            @endforeach
          </div>

          <div class="container">
            <div class="row justify-content-center">
              <div class="col-3">
                <ol class="carousel-indicators">
                  @foreach ($galleries->chunk(3)->all() as $key => $chunked)
                  <li data-target="#Carousel-gallery" data-slide-to="{{ $key }}" {!! $key == 0 ? 'class="active"' : NULL !!}}></li>
                  @endforeach
                  <a class="carousel-control-prev" data-target="#Carousel-gallery" role="button" data-slide="prev">
                    <img src="{{asset('front/images/icon-arrow-left.png')}}" alt="">
                  </a>
                  <a class="carousel-control-next" data-target="#Carousel-gallery" role="button" data-slide="next">
                    <img src="{{asset('front/images/icon-arrow-right.png')}}" alt="">
                  </a>
                </ol>
              </div>
            </div>
          </div>
        </div>
        @endif
      </div>

    </div>
  </section>

  <section class="section section-voice" id="VideoAnchor">
    <div class="container h-100">
      <div class="row justify-content-center">
        <div class="col">
          <h4 class="gallery-title title-section">- VOICE OF SANMAR -</h4>
        </div>
      </div>

      <div class="carousel-mobile">
        @if (!$voices->isEmpty())
        <div class="row justify-content-center align-content-center h-75">
          <div class="col align-self-center justify-content-center">
          @foreach ($voices->chunk(1)->all() as $chunked)
            <div class="row justify-content-center">
              @foreach ($chunked as $voice)
              @php
                  preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/",$voice->embed_url, $video_id);
              @endphp
                <div class="yt-embed">
                  <div class="youtube-player" data-id="{{$video_id[1]}}"></div>
                  {{-- <iframe class="m-3 yt-hidden" width="330" height="200" src="{{ $voice->embed_url }}"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe> --}}
                </div>
              @endforeach
            </div>
          @endforeach
          </div>
        </div>
        @endif
      </div>

      <div class="carousel-desktop h-100">
        <div class="row justify-content-center align-items-center h-75">
          @if (!$voices->isEmpty())
          <div class="row justify-content-center align-content-center h-75">
            <div class="col align-self-center justify-content-center">
            @foreach ($voices->chunk(3)->all() as $chunked)
              <div class="row justify-content-center">
                @foreach ($chunked as $voice)
                @php
                    preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/",$voice->embed_url, $video_id);
                @endphp
                <div class="yt-embed">
                  <div class="youtube-player" data-id="{{$video_id[1]}}"></div>
                  {{-- <iframe class="m-3 yt-hidden" width="330" height="200" src="{{ $voice->embed_url }}"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe> --}}
                </div>
                @endforeach
              </div>
              @endforeach
            </div>
          </div>
          @endif
        </div>
      </div>

    </div>
  </section>

  <section class="section section-ppdb" id="PPDBAnchor">
    <div class="container-fluid d-flex h-100 justify-content-center align-content-center">
      <div class="row justify-content-center align-content-center">
        <div class="col-6 ppdb-background">
          <img src="{{asset('front/images/ppfb-illustration.png')}}" alt="">
        </div>
        <div class="col-4 ml-3 d-flex flex-column justify-content-center align-content-center ppdb-text">
          <h5 class="title-ppdb m-3">PPDB SANMARU</h5>
          <div class="row m-3 justify-content-center">
            <img class="mx-3" src="{{asset('front/images/serviam-green.png')}}" alt="">
            <img class="mx-3" src="{{asset('front/images/logo-2.png')}}" alt="">
          </div>
          <p class="detail-ppdb m-3">Sanmaru adalah adalah sistem manajemen kampus Santa Maria Ursulin yang dapat diakses oleh siswa, orang tua siswa, guru dan Yayasan Paratha Bhakti. Semua informasi yang diperlukan berada pada satu tempat dan juga terdapat informasi seputar prosedur dan persyaratan PPDB Kampus Santa Maria.</p>
          <div class="row justify-content-center">
            <a href="{{ route('ppdb.index') }}" class="btn btn-green">READ MORE...</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section section-map" id="MapsAnchor">
    <div class="container-fluid h-100">
      <div class="row justify-content-center">
        <div class="col">
          <h4 class="blog-title title-section">- MAP -</h4>
        </div>
      </div>

      <div class="row justify-content-center align-content-center h-75 ">
        <iframe class="embed-map"
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d27403.6875783123!2d112.72956532626417!3d-7.284505719003641!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fb955f0f1109%3A0x18f7d27449bd3151!2sSanta%20Maria%20High%20School%20Surabaya!5e0!3m2!1sen!2sid!4v1614872530128!5m2!1sen!2sid"
          style="border:0;" allowfullscreen="" loading="lazy"></iframe>
      </div>

    </div>
  </section>

  <section class="section section-contact" id="ContactAnchor">
    <div class="container-fluid d-flex h-100 justify-content-center align-content-center">
      <div class="row align-self-center">
        <div class="col align-items-center d-flex flex-column">
          <img class="img-contactlogo mb-5" src="{{asset('front/images/serviam-white.png')}}" alt="">
          <h5 class="text-contact  mb-3">- YAYASAN PARATHA BHAKTI -</h5>
          <p class="text-contact-address mb-4">Jl. Raya Darmo No.49, Keputran, Kec. Tegalsari,
            <br>Kota SBY, Jawa Timur 60264, Indonesia.
            <br>Telp.: +62315661996
          </p>
          <div class="row align-items-center">
            <a href="https://www.instagram.com/yayasanparathabhakti/" class="btn"><img src="{{asset('front/images/icon-ig.png')}}" alt=""></a>
            <a href="https://www.facebook.com/yayasanparatha.bhakti" class="btn"><img src="{{asset('front/images/icon-fb.png')}}" alt=""></a>
            <a href="https://api.whatsapp.com/send/?phone=6282143880624&text=Halo%2C+Selamat+Pagi&app_absent=0 " class="btn"><img src="{{asset('front/images/icon-wa.png')}}" alt=""></a>
            <a href=" https://www.tiktok.com/@kampussanmar_sby?lang=id-ID" class="btn"><img src="{{asset('front/images/icon-tiktok.png')}}" alt=""></a>
          </div>

        </div>
      </div>
    </div>
  </section>


@endsection

@push('scripts')
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script>
  $('.hero-carousel').slick({
    infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    prevArrow: false,
    nextArrow: false,
    autoplay: true,
    autoplaySpeed: 3000,
  });


  // $( document ).ready(function() {
  //   if (document.cookie.indexOf('visited=true') == -1){
  //     // load the overlay
      $('#myModal').modal({show:true});

  //     var year = 1000*60*60*24*365;
  //     var expires = new Date((new Date()).valueOf() + year);
  //     document.cookie = "visited=true;expires=" + expires.toUTCString();

  //   }
  // });

    jQuery(document).ready(function () {
      jQuery("a").on('click', function (event) {
        if (this.hash !== "") {
          event.preventDefault();
          var hash = this.hash;
          jQuery('html, body').animate({ scrollTop: jQuery(hash).offset().top }, 800);
        }
      });
    });

    var navlinks = $('.scrollto');
    $(window).scroll(function () {
      var currentPosition = $(this).scrollTop() + 200;

      for (const element of navlinks) {
        let section = $(element.hash)
        var top = section.offset().top,
          bottom = top + section.height();
        if (currentPosition >= top && currentPosition <= bottom) {
          $(element).addClass('active');
        }else{
          $(element).removeClass('active');
        }
      };
    });

    // YOUTUBE EMBED
    // $('.yt-embed').each(function(){
    //   var rx = /^.*(?:(?:youtu\.be\/|v\/|vi\/|u\/\w\/|embed\/)|(?:(?:watch)?\?v(?:i)?=|\&v(?:i)?=))([^#\&\?]*).*/;
    //   var url = $($(this).children()[1]).attr('src')
    //   var id = url.match(rx);
    //   var ytEmbed = $(this).children()[0]

    //   $(ytEmbed).attr('data-id',id[1])

    //   console.log("those",id[1])
    //   console.log(ytEmbed)

    // })

    // var rx = /^.*(?:(?:youtu\.be\/|v\/|vi\/|u\/\w\/|embed\/)|(?:(?:watch)?\?v(?:i)?=|\&v(?:i)?=))([^#\&\?]*).*/;
    // var url = $('.yt-embed').has('iframe').attr("src");
    // var iframe = $('.yt-embed').has('iframe')
    // console.log("this",iframe.children()[0]);
    // console.log(url);
    // var id = url.match(rx);
    // console.log(id[1]);

    function labnolIframe(div) {
    var iframe = document.createElement('iframe');
    iframe.setAttribute(
      'src',
      'https://www.youtube.com/embed/' + div.dataset.id + '?autoplay=1&rel=0'
    );
    iframe.setAttribute('frameborder', '0');
    iframe.setAttribute('allowfullscreen', '1');
    iframe.setAttribute(
      'allow',
      'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture'
    );
    div.parentNode.replaceChild(iframe, div);
  }

  function initYouTubeVideos() {
    var playerElements = document.getElementsByClassName('youtube-player');
    for (var n = 0; n < playerElements.length; n++) {
      var videoId = playerElements[n].dataset.id;
      var div = document.createElement('div');
      div.setAttribute('data-id', videoId);
      var thumbNode = document.createElement('img');
      thumbNode.src = '//i.ytimg.com/vi/ID/hqdefault.jpg'.replace(
        'ID',
        videoId
      );
      div.appendChild(thumbNode);
      var playButton = document.createElement('div');
      playButton.setAttribute('class', 'play');
      div.appendChild(playButton);
      div.onclick = function () {
        labnolIframe(this);
      };
      playerElements[n].appendChild(div);
    }
  }

  document.addEventListener('DOMContentLoaded', initYouTubeVideos);

  </script>

@endpush

