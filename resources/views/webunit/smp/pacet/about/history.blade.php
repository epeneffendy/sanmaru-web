@extends('layouts.webunit.smp.pacet.main')
@section('content')
    <div class="navbar-bg"></div>
    <div class="container">
        <div class="about-nav">
            <a class="body-text-20 dark-purple active" href="{{ route('webunit.about.history', ['webunit' => $webUnit]) }}">
                HISTORY
            </a>
            <a class="body-text-20 grey" href="{{ route('webunit.about.about', ['webunit' => $webUnit]) }}">
                ABOUT
            </a>
            <a class="body-text-20 grey" href="{{ route('webunit.about.welcome', ['webunit' => $webUnit]) }}">
                A WARM WELCOME
            </a>
        </div>
        <div id="history">
            <div class="timeline">
                @forelse($abouts as $about)
                <div class="timeline-body row ">
                    <div class="timeline-item desktop-version"></div>
                    @if($about->featured_image)
                    <div class="col-12 mobile-version">
                        <div class="history-image ">
                            <img class="picture" src="{{ $about->getFeaturedImageUrl() }}" alt="">
                        </div>
                    </div>
                    @endif
                    <div class="col-lg-7">
                        <div class="timeline-date body-text-20 mint-green desktop-version">{{ $about->title }}</div>
                        <div class="title-3 mint-green mobile-version">{{ $about->title }}</div>
                        <div class="timeline-content body-text-16 black-panther">
                            {!! $about->content !!}
                        </div>
                    </div>
                    <div class="col-lg-5 desktop-version">
                    @if($about->featured_image)
                    <div class="history-image">
                        <div class="mask">
                        <svg width="303" height="260" viewBox="0 0 303 260" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                            <clipPath id="svgPath-1">
                                <path d="M246.025 28.9292C274.128 47.7993 298.086 72.1317 301.689 98.616C305.112 125.1 287.998 153.902 273.947 180.221C259.896 206.539 248.547 230.375 228.912 241.797C209.096 253.052 180.814 251.728 150.37 254.542C119.746 257.356 87.1405 264.308 63.0016 254.377C39.0427 244.28 23.7307 217.133 12.5619 188.166C1.39313 159.364 -5.81253 128.576 6.25696 106.23C18.3264 83.8841 49.4909 70.1454 77.2328 51.1098C104.794 31.9087 128.573 7.57625 156.855 1.94834C185.138 -3.51404 217.743 10.0591 246.025 28.9292Z" fill="#FF0066"/>
                            </clipPath>
                            </defs>
                        </svg>
                        </div>
                        <img class="picture" src="{{ $about->getFeaturedImageUrl() }}" alt="">
                        <img class="vector-1" src="{{asset('web-smp/pacet/images/image-vector-1.png')}}" alt="">
                        <img class="vector-2" src="{{asset('web-smp/pacet/images/image-vector-2.png')}}" alt="">
                    </div>
                    @endif
                    </div>
                </div>
                @empty
                @endforelse
            </div>
        </div>

    </div>
@endsection
