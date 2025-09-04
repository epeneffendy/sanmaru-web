@extends('layouts.webunit.sma.sby.main')
@section('content')

    {{-- MODAL SECTION --}}
    @if (!$popups->isEmpty())
    <div id="popup-modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="CarouselPopup">
                        @foreach($popups as $key => $popup)
                        <div class="carousel-inner">
                            <div class="row">
                                <div class="col-12">
                                    <h1 class="text-center green-salad">{{ $popup->title }}</h1>
                                    <div class="popup-content">
                                        {!! $popup->html_content !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    {{-- END OF MODAL SECTION --}}

    <div id="home">
        <section id="hero-section">
            <div class="hero-carousel">
            @if($headlines->isEmpty())
                <div class="carousel-inner">
                    <div class="jumbotron jumbotron-fluid illustration">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-7 col-md-7 left">
                                    <h3 class="yellow-submarine">SMA Santa Maria</h3>
                                    <h1 class="title-2 white">Pemimpin yang humanis dan berwawasan global</h1>
                                    <p class="body-text-16 white">We aim to educate, engage and empower youth by using an innovative educational methods</p>
                                </div>
                                <div class="col-lg-5 col-md-5 right">
                                    <img src="{{ asset('web-sma/sby/images/hero-illustration.png') }}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="carousel-inner">
                    <div class="jumbotron jumbotron-fluid illustration">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-7 col-md-7 left">
                                    <h3 class="yellow-submarine">SMA Santa Maria</h3>
                                    <h1 class="title-2 white">Pemimpin yang humanis dan berwawasan global</h1>
                                    <p class="body-text-16 white">We aim to educate, engage and empower youth by using an innovative educational methods</p>
                                </div>
                                <div class="col-lg-5 col-md-5 right">
                                    <img src="{{ asset('web-sma/sby/images/hero-illustration.png') }}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @foreach($headlines as $headline)
                <div class="carousel-inner">
                    @if($headline->type === 'video')
                    <div class="jumbotron jumbotron-fluid video" style="background: linear-gradient(to right, {{$headline->color_overlay}}, rgba(0, 0, 0, 0))">
                        <iframe width="640" height="360" src="{{$headline->getUrl()}}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="1" frameborder="0"></iframe>
                    </div>
                    @else
                    <div class="jumbotron jumbotron-fluid" style="background: linear-gradient(to right, {{$headline->color_overlay}}, rgba(0, 0, 0, 0)), url('{{$headline->getUrl()}}') no-repeat; background-size: cover; background-position: 70% center;">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-7 col-md-7 left">
                                    <h3 class="white">SMA Santa Maria</h3>
                                    <h1 class="title-2 white">Pemimpin yang humanis dan berwawasan global</h1>
                                    <p class="body-text-16 white">We aim to educate, engage and empower youth by using an innovative educational methods</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
            @endif
            </div>
        </section>
        <section id="home-section-1">
            <div class="container ">
                <div class="row flex-column">
                    <div class="line-title"></div>
                    <h4 class="title-3 green-salad">LATEST BLOGS AND ARTICLES</h4>
                </div>
                <p class="body-text-16 mt-3 grey">Our alumni share their experiences of studying with us and discuss <br>
                    how our community have supported them in their careers</p>
                <div class="row desktop-version">
                    @forelse($blogs as $blog)
                        <div class="col-md-4">
                            <div class="card">
                                <a href="{{route('webunit.news.show', ['webunit' => $webUnit, 'slug' => $blog->slug])}}">
                                    <div class="card-img-top">
                                        <img src="{{ $blog->getFeaturedImageUrl() }}" alt="">
                                        <div class="middle">
                                        <div class="text body-text-14">Read now</div>
                                    </div>
                                </a>
                                    </div>
                                    <div class="card-body text-left">
                                        <p class="body-text-14 black-panther m-0">{{ \App\Helpers\Helper::tanggal($blog->created_at) }}</p>
                                        <p class="body-text-18 black-panther m-0"> <strong>{{ $blog->title }}</strong></p>
                                    </div>
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>
                <div class="row mobile-version mobile-carousel">
                    @forelse($blogs as $blog)
                    <div class="news-carousel-inner">
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-img-top">
                                    <img src="{{ $blog->getFeaturedImageUrl() }}" alt="">
                                    <div class="middle">
                                        <div class="text body-text-14">Read now</div>
                                    </div>
                                </div>
                                <a href="{{route('webunit.news.show', ['webunit' => $webUnit, 'slug' => $blog->slug])}}">
                                    <div class="card-body text-left">
                                        <p class="body-text-14 black-panther m-0">{{ \App\Helpers\Helper::tanggal($blog->created_at) }}</p>
                                        <p class="body-text-18 black-panther m-0"> <strong>{{ $blog->title }}</strong></p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    @endforelse
                </div>
            </div>
        </section>
        <section id="home-section-2">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <div class="line-title"></div>
                        <h4 class="title-3 green-salad">OUR CORE VALUES</h4>
                        <p class="body-text-16 mt-3 grey">Our school promotes a learning community in which our core values
                            represent the guidelines of our behavior</p>
                    </div>
                    <div class="col-md-4">

                        <a href="{{ route('webunit.about.core-values', ['webunit' => $webUnit]) . '#CintaBelasKasih' }}" class="core-values-item">
                            <img src="{{ asset('web-sma/sby/images/icon-cinta.png') }}" alt="" srcset="">
                            <p class="body-text-16 m-0 ml-4 black-panther">Cinta dan Belas Kasih</p>
                        </a>

                        <a href="{{ route('webunit.about.core-values', ['webunit' => $webUnit]) . '#Integritas' }}" class="core-values-item">
                            <img src="{{ asset('web-sma/sby/images/icon-integritas.png') }}" alt="" srcset="">
                            <p class="body-text-16 m-0 ml-4 black-panther">Integritas</p>
                        </a>

                        <a href="{{ route('webunit.about.core-values', ['webunit' => $webUnit]) . '#Pelayanan' }}" class="core-values-item">
                            <img src="{{ asset('web-sma/sby/images/icon-keberanian.png') }}" alt="" srcset="">
                            <p class="body-text-16 m-0 ml-4 black-panther">Semangat Pelayanan</p>
                        </a>

                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('webunit.about.core-values', ['webunit' => $webUnit]) . '#Persatuan' }}" class="core-values-item">
                            <img src="{{ asset('web-sma/sby/images/icon-persatuan.png') }}" alt="" srcset="">
                            <p class="body-text-16 m-0 ml-4 black-panther">Semangat Persatuan</p>
                        </a>

                        <a href="{{ route('webunit.about.core-values', ['webunit' => $webUnit]) . '#TotalitasKesungguhan' }}" class="core-values-item">
                            <img src="{{ asset('web-sma/sby/images/icon-kesungguhan.png') }}" alt="" srcset="">
                            <p class="body-text-16 m-0 ml-4 black-panther">Kesungguhan (Totalitas)</p>
                        </a>

                        <a href="{{ route('webunit.about.core-values', ['webunit' => $webUnit]) . '#KeberanianKetangguhan' }}" class="core-values-item">
                            <img src="{{ asset('web-sma/sby/images/icon-pelayanan.png') }}" alt="" srcset="">
                            <p class="body-text-16 m-0 ml-4 black-panther">Keberanian dan Ketangguhan</p>
                        </a>

                    </div>
                </div>
            </div>
        </section>
        <section id="home-section-3">
            <div class="container desktop-version">
                <div class="banner-achievement">
                    <div class="col-md-5">
                        <h4 class="title-3 text-white">OUR KEY ACHIEVEMENT</h4>
                        <p class="body-text-16 mt-3 text-white mb-0">For more than 60 years, we have been providing high
                            quality
                            learning programs</p>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-5">
                        <div class="d-flex justify-content-around">
                            <div class="achievement-item">
                                <h2 class="text-white">69</h2>
                                <p class="body-text-16 text-white mb-0">years</p>
                            </div>
                            <div class="achievement-item">
                                <h2 class="text-white">120k</h2>
                                <p class="body-text-16 text-white mb-0">students</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container mobile-version">
                <div class="row">
                    <div class="col-md-5" >
                        <div class="d-inline-block">
                            <h4 class="title-3 green-salad">OUR KEY ACHIEVEMENT</h4>
                        </div>
                        <p class="body-text-16 mt-3 grey">For more than 60 years, we have been <br> providing high quality learning programs</p>
                    </div>
                    <div class="col-md-7 d-flex">
                        <div class="row">
                            <div class="col-6">
                                <div class="banner-achievement">
                                    <div class="achievement-item">
                                        <h2 class="text-white">69</h2>
                                        <p class="body-text-16 text-white mb-0">years</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="banner-achievement">
                                    <div class="achievement-item">
                                        <h2 class="text-white">120k</h2>
                                        <p class="body-text-16 text-white mb-0">students</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="home-section-4">
            <div class="container ">
                <div class="row flex-column">
                    <div class="line-title"></div>
                    <h4 class="title-3 green-salad">TESTIMONIALS</h4>
                </div>
                <p class="body-text-16 mt-3 grey">Our alumni share their experiences of studying with us and discuss how our
                    <br>
                    community have supported them in their careers
                </p>

                <div class="row carousel desktop-version">
                    @foreach($testimonials as $testimonial)
                    <div class="carousel-inner">
                        <div class="card">
                            <div class="card-body">
                                <p class="body-text-16 grey text-left">{{ $testimonial->content }}</p>
                            </div>
                            <div class="card-footer d-flex align-items-center">
                                <img src="{{ $testimonial->getPhotoPathUrl() }}" alt="">
                                <div class="text-left">
                                    <p class="body-text-14 grey m-0"><strong>{{ $testimonial->subject }}</strong></p>
                                    <p class="body-text-14 grey m-0">{{ @$testimonial->unit->name }}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                    @endforeach
                </div>
                <div class="row mobile-version">
                    @foreach($testimonials as $testimonial)
                    <div class="col-12 mt-2">
                        <div class="card">
                            <div class="card-footer d-flex align-items-center">
                                <img src="{{ $testimonial->getPhotoPathUrl() }}" alt="">
                                <div class="text-left">
                                    <p class="body-text-14 grey m-0"><strong>{{ $testimonial->subject }}</strong></p>
                                    <p class="body-text-14 grey m-0">{{ @$testimonial->unit->name }}</p>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="body-text-16 grey text-left">{{ $testimonial->content }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        <section id="home-section-5">
            <div class="container ">
                <div class="row flex-column">
                    <div class="line-title"></div>
                    <h4 class="title-3 green-salad">GALLERY</h4>
                </div>
                <p class="body-text-16 mt-3 grey">Have a look at our activities and learn more about us</p>
                <div class="row desktop-version">
                    @forelse($galleries as $gallery)
                    <div class="col-md-4">
                            <div class="card-img-top" data-bs-toggle="modal" data-bs-target="#gallery-modal-{{ $gallery->id }}">
                                <img src="{{$gallery->getContentImageUrl()}}" alt="Gallery Image">
                            </div>
                        </div>
                        <div id="gallery-modal-{{ $gallery->id }}" class="modal gallery-modal" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <img src="{{$gallery->getContentImageUrl()}}" alt="Gallery Image">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>
                <div class="row mobile-version mobile-carousel">
                    @forelse($galleries as $gallery)
                    <div class="gallery-carousel-inner">
                        <div class="col-lg-4">
                            <img src="{{$gallery->getContentImageUrl()}}" alt="Gallery Image">
                        </div>
                    </div>
                    @empty
                    @endforelse
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function(){
        $('#popup-modal').modal('show');
    });
    $("button[data-dismiss=modal]").click(function()
    {
        $(".modal").modal('hide');
    });
</script>
@endpush
