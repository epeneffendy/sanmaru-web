<header>
    <nav class="navbar fixed-top navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('web.home') }}">
                <img src="{{asset('front/images/Campus-Santa-Maria-Logo.svg')}}" alt="" height="55" />
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse flex-grow-0" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item mx-2">
                        <div class="d-flex flex-row align-items-center">
                            <div class="">
                                <a class="nav-link position-relative mx-2 d-flex align-items-center {{$nav['parent']=='about' || $nav['parent']=='santa-angela' ?'active':''}}" href="{{ route('web.about.index') }}">
                                    <span class="mr-2">ABOUT</span>
                                    <img src="{{asset('front/images/icon-arrow-down.png')}}" alt="" width="16" height="8" />
                                    <div class="line-yellow bottom-position"></div>
                                </a>
                                <div class="nav-content">
                                    @foreach($nav['aboutCategories'] as $aboutCategory)
                                    @if (@$aboutCategory->slug)
                                    <a href="{{ route('web.about.category.show', $aboutCategory->slug) }}" class="nav-children">{{ $aboutCategory->name }}</a>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item mx-2">
                        <div class="d-flex flex-row align-items-center">
                            <div class="">
                                <a class="nav-link position-relative mx-2 d-flex align-items-center {{$nav['parent']=='admission'?'active':''}}" href="#">
                                    <span class="mr-2">ADMISSION</span>
                                    <img src="{{asset('front/images/icon-arrow-down.png')}}" alt="" width="16" height="8" />
                                    <div class="line-yellow bottom-position"></div>
                                </a>
                                <div class="nav-content">
                                    {{-- <a href="{{route('web.admission.beasiswa')}}" class="nav-children">Program Beasiswa</a> --}}
                                    <a href="{{route('web.admission.faq')}}" class="nav-children">Pertanyaan Umum</a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item mx-2">
                        <div class="d-flex flex-row align-items-center">
                            <div class="">
                                <a class="nav-link position-relative mx-2 d-flex align-items-center {{$nav['parent']=='school-life'?'active':''}}" href="{{ route('web.school-life.index') }}">
                                    <span class="mr-2">SCHOOL LIFE</span>
                                    <img src="{{asset('front/images/icon-arrow-down.png')}}" alt="" width="16" height="8" />
                                    <div class="line-yellow bottom-position"></div>
                                </a>
                                <div class="nav-content">
                                    @foreach($nav['schoolLifeCategories'] as $schoolLifeCategory)
                                    <a href="{{ route('web.school-life.category.show', $schoolLifeCategory->slug) }}" class="nav-children">{{ $schoolLifeCategory->name }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item mx-2">
                        <div class="d-flex flex-row align-items-center">
                            <div class="">
                                <a class="nav-link position-relative mx-2 d-flex align-items-center {{$nav['parent']=='campuses'?'active':''}}" href="{{route('web.campuses.index')}}">
                                   <span class="mr-2">CAMPUSES</span>
                                   {{-- <img src="{{asset('front/images/icon-arrow-down.png')}}" alt="" width="16" height="8" /> --}}
                                   <div class="line-yellow bottom-position"></div>
                                </a>
                                {{-- <div class="nav-content">
                                    <div class="nav-children">
                                        <a href="#" class="d-flex align-items-center">
                                            <span>Kampus Santa Maria Surabaya</span>
                                            <img src="{{asset('front/images/icon-arrow-down.png')}}" alt="" width="16" height="8" style="transform: rotate(-90deg)" />
                                        </a>
                                    </div>
                                    <a href="#" class="nav-children">Kampus Santa Maria II Sidoarjo</a>
                                    <a href="#" class="nav-children">Kampus Santo Yusup Pacet</a>
                                </div> --}}
                            </div>
                        </div>
                    </li>
                    <li class="nav-item mx-2">
                        <div class="d-flex flex-row align-items-center">
                            <div class="">
                                <a class="nav-link position-relative mx-2 d-flex align-items-center {{$nav['parent']=='news'?'active':''}}" href="{{ route('web.news.index') }}">
                                   <span class="mr-2">NEWS</span>
                                   {{-- <img src="{{asset('front/images/icon-arrow-down.png')}}" alt="" width="16" height="8" /> --}}
                                   <div class="line-yellow bottom-position"></div>
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item mx-2">
                        <div class="d-flex flex-row align-items-center">
                            <div class="">
                                <a class="nav-link position-relative mx-2 d-flex align-items-center" href="https://160tahunursulin.cloud/educational-insights/">
                                   <span class="mr-2">Educational Insights</span>
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item mx-2">
                        <div class="d-flex flex-row align-items-center">
                            <div class="">
                                <a class="nav-link position-relative mx-2 d-flex align-items-center" href="https://160tahunursulin.cloud">
                                   <span class="mr-2">160 Tahun Ursulin</span>
                                </a>
                            </div>
                        </div>
                    </li>
                    {{-- <li class="nav-item mx-2">
                        <div class="d-flex flex-row align-items-center">
                            <div class="">
                                <a class="nav-link position-relative mx-2 d-flex align-items-center {{$nav['parent']=='community'?'active':''}}" href="#">
                                   <span class="mr-2">COMMUNITY</span>
                                   <img src="{{asset('front/images/icon-arrow-down.png')}}" alt="" width="16" height="8" />
                                   <div class="line-yellow bottom-position"></div>
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item mx-2">
                        <div class="d-flex flex-row align-items-center">
                            <div class="">
                                <a class="nav-link position-relative mx-2 d-flex align-items-center {{$nav['parent']=='event'?'active':''}}" href="#">
                                   <span class="mr-2">EVENT</span>
                                   <img src="{{asset('front/images/icon-arrow-down.png')}}" alt="" width="16" height="8" />
                                   <div class="line-yellow bottom-position"></div>
                                </a>
                            </div>
                        </div>
                    </li> --}}
                </ul>
            </div>
        </div>
    </nav>

    @if (\Request::route()->getName() == 'web.home')
    {{-- <nav id="navbar" class="navbar-scroll d-flex flex-column justify-content-sm-center">
        <ul id="menu">
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
                <a data-menuanchor="UnitAnchor" href="#UnitAnchor" class="navbar-scroll-link scrollto">
                    <img class="white" src="{{asset('front/images/Icons/Unit-white.png')}}" alt="">
                    <img class="green" src="{{asset('front/images/Icons/Unit-green.png')}}" alt="">
                    <span>Unit</span>
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
    </nav> --}}
    @endif
</header>

<script type="text/javascript">
    $(document).ready(function(){
        if($(window).width() <= 768){
            $('.nav-item').click(function(){
                $(this).find('.nav-content').toggle()

                console.log("clicked")
            })
        }
    })
</script>
