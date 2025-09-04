@extends('layouts.webunit.kbtk.sby.main')
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
								<h3 class="black-panther">KB-TK Santa Maria</h3>
								<h1 class="title-2 green-salad">Pemimpin yang Humanis dan Berwawasan Global</h1>
								<p class="body-text-16 grey">We aim to educate, engage and empower youth by using an innovative educational methods</p>
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
								<h3 class="black-panther">KB-TK Santa Maria</h3>
								<h1 class="title-2 green-salad">Pemimpin yang Humanis dan Berwawasan Global</h1>
								<p class="body-text-16 grey">We aim to educate, engage and empower youth by using an innovative educational methods</p>
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
                                <h3 class="white">KB-TK Santa Maria</h3>
								<h1 class="title-2 white">Pemimpin yang Humanis dan Berwawasan Global</h1>
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
		<div class="container">
			<div class="d-inline-block">
				<h4 class="title-5 airplane black-panther">Latest Blogs and Articles</h4>
			</div>
			<p class="body-text-16 mt-3 grey">Our alumni share their experiences of studying with us and discuss <br> how our community have supported them in their careers</p>
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
			<div class="d-inline-block">
				<h4 class="title-5 airplane black-panther">Our Core Values</h4>
			</div>
			<p class="body-text-16 mt-3 grey">Our school promotes a learning community in which our core values <br> represent the guidelines of our behavior</p>
			<div class="row">
				<div class="col-md-4 col-sm-6 col-6 d-flex align-items-stretch">
					<div class="card core-value">
						<div class="subtitle-2">Cinta dan Belas Kasih</div>
						<img class="card-img-top bear-1" src="{{asset('web-kbtk/sby/icons/illustration-bear-1.svg')}}" alt="Card image cap">
					</div>
				</div>
				<div class="col-md-4 col-sm-6 col-6 d-flex align-items-stretch">
					<div class="card core-value">
						<div class="subtitle-2">Integritas</div>
						<img class="card-img-top bear-2" src="{{asset('web-kbtk/sby/icons/illustration-bear-2.svg')}}" alt="Card image cap">
					</div>
				</div>
				<div class="col-md-4 col-sm-6 col-6 d-flex align-items-stretch">
					<div class="card core-value">
						<div class="subtitle-2">Keberanian dan Ketangguhan</div>
						<img class="card-img-top bear-3" src="{{asset('web-kbtk/sby/icons/illustration-bear-3.svg')}}" alt="Card image cap">
					</div>
				</div>
				<div class="col-md-4 col-sm-6 col-6 d-flex align-items-stretch">
					<div class="card core-value">
						<div class="subtitle-2">Semangat Persatuan</div>
						<img class="card-img-top bear-4" src="{{asset('web-kbtk/sby/icons/illustration-bear-4.svg')}}" alt="Card image cap">
					</div>
				</div>
				<div class="col-md-4 col-sm-6 col-6 d-flex align-items-stretch">
					<div class="card core-value">
						<div class="subtitle-2">Kesungguhan (Totalitas)</div>
						<img class="card-img-top bear-5" src="{{asset('web-kbtk/sby/icons/illustration-bear-5.svg')}}" alt="Card image cap">
					</div>
				</div>
				<div class="col-md-4 col-sm-6 col-6 d-flex align-items-stretch">
					<div class="card core-value">
						<div class="subtitle-2">Semangat Pelayanan</div>
						<img class="card-img-top bear-6" src="{{asset('web-kbtk/sby/icons/illustration-bear-6.svg')}}" alt="Card image cap">
					</div>
				</div>
			</div>
		</div>
	</section>
	<section id="home-section-3">
		<div class="container">
			<div class="row">
				<div class="col-md-5" >
					<div class="d-inline-block">
						<h4 class="title-5 airplane black-panther">Our Key Achievement</h4>
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
    <img class="butterfly-bg" src="{{asset('web-kbtk/sby/images/butterfly-bg.png')}}"  alt="">
		<div class="container">
			<div class="d-inline-block">
				<h4 class="title-5 airplane black-panther">Testimonials</h4>
			</div>
			<p class="body-text-16 mt-3 grey">Our alumni share their experiences of studying with us and discuss <br> how our community have supported them in their careers</p>
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
		<div class="container">
			<div class="d-inline-block">
				<h4 class="title-5 airplane black-panther">Gallery</h4>
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
