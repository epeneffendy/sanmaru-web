@extends('layouts.webunit.smp.sby.main')
@section('content')
    <div class="navbar-bg"></div>
    <div class="container">
        <div class="about-nav">
            <a class="body-text-20 grey" href="{{ route('webunit.about.history', ['webunit' => $webUnit]) }}">
                HISTORY
            </a>
            <a class="body-text-20 grey" href="{{ route('webunit.about.about', ['webunit' => $webUnit]) }}">
                ABOUT
            </a>
            <a class="body-text-20 green-salad active" href="{{ route('webunit.about.welcome', ['webunit' => $webUnit]) }}">
                A WARM WELCOME
            </a>
        </div>

        <div id="welcome">
            <div class="row">
            <div class="col-lg-12">
                <h4 class="title-3 black-panther mb-4">
                Welcome to {{strtoupper($webunit_level)}} Santa Maria
                </h4>
                <p class="body-text-16 grey">
                    {!! $campusUnit->sambutan !!}
                </p>
            </div>
            </div>
            <!-- <div class="row">
                <div class="col-lg-4 col-4">
                    <div class="welcome-image desktop-version">
                    <div class="mask">
                        <svg width="310" height="277" viewBox="0 0 310 277" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <clipPath id="svgPath-2">
                            <path d="M249.353 11.2932C274.191 26.0058 288.726 58.1897 298.661 91.1093C308.596 124.213 313.748 158.052 303.997 186.741C294.245 215.431 269.407 238.787 241.993 254.603C214.579 270.236 184.589 278.328 156.071 276.489C127.553 274.466 100.507 262.328 72.7253 246.695C44.9433 231.063 16.4254 211.937 5.57015 185.27C-5.46905 158.604 0.970486 124.764 15.5054 97.546C30.0404 70.3276 52.6708 49.7299 77.141 34.8334C101.611 19.7529 128.105 10.3736 158.831 4.48856C189.557 -1.39649 224.698 -3.60338 249.353 11.2932Z" fill="#FF0066"/>
                            </clipPath>
                        </defs>
                        </svg>
                    </div>
                    <img class="picture" src="{{asset('web-kbtk/sda/images/welcome-image.png')}}" alt="">
                    <img class="vector-1" src="{{asset('web-kbtk/sda/images/welcome-image-vector-1.png')}}" alt="">
                    <img class="vector-2" src="{{asset('web-kbtk/sda/images/welcome-image-vector-2.png')}}" alt="">
                    </div>
                    <div class="welcome-image mobile-version">
                    <img class="picture" src="{{asset('web-kbtk/sda/images/welcome-image.png')}}" alt="">
                    </div>
                </div>
                <div class="col-lg-8 col-8">
                    <h4 class="title-3 black-panther mt-4 mb-4">
                    Drs. Samuel Sugiyono
                    </h4>
                    <p class="body-text-16 grey">
                    Adam was appointed as Headmaster of the Pointer School in early 2019 becoming the fourth Head of the school since its founding in 1950. He has previously taught in both the independent and state sector and is a governor at a local Grammar school
                    </p>
                </div>
            </div> -->
        </div>

    </div>
@endsection
