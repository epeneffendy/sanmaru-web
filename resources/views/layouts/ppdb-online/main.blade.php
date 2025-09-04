<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="keywords" content=""/>
    <title>@lang('pages.title') - @lang('pages.subtitle')</title>

    <!-- Favicons-->
    <link rel="icon" type="image/png" href="{{asset('img/favicon.png')}}" />

    <!-- GOOGLE WEB FONT -->
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:400,500,600" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,600;0,800;1,400&display=swap" rel="stylesheet">

    <!-- BASE CSS -->
    <link href="{{asset('frontend-ppdb-online/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('frontend-ppdb-online/css/menu.css')}}" rel="stylesheet">
    <link href="{{asset('frontend-ppdb-online/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('frontend-ppdb-online/css/vendors.css')}}" rel="stylesheet">
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css"/>

    <!-- YOUR CUSTOM CSS -->
    <link href="{{asset('css/app.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('frontend-ppdb-online/css/desktop.css')}}">
    <link rel="stylesheet" href="{{asset('frontend-ppdb-online/css/mobile.css')}}">

    <!-- MODERNIZR MENU -->
    <script src="{{asset('frontend-ppdb-online/js/modernizr.js')}}"></script>

    @stack('styles')

    @if (App::environment('production'))
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-W22293V');</script>
    <!-- End Google Tag Manager -->
    @endif

</head>
<body id="ppdb">
    @if (\App\Helpers\Helper::isProduction())
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W22293V"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    @endif
    {{-- <div id="preloader">
        <div data-loader="circle-side"></div>
    </div> --}}

    <div id="loader_form">
        <div data-loader="circle-side-2"></div>
    </div>

    <!-- Start Content -->

    {{-- <div class="container-fluid full-height">
        @yield('content')
    </div> --}}

    <main-wrapper>
        <template class="main__mobile">
            <div class="wrapper-mobile">
                @include('layouts.ppdb-online.navbar')
                <div class="container-fluid full-height body-mobile">
                    @yield('content')
                </div>
            </div>
        </template>

        <template class="main__desktop">
            <div class="wrapper-desktop">
                <div class="body-desktop">
                    @include('layouts.ppdb-online.sidebar')
                    <div class="content-container">
                        <div class="container d-flex justify-content-end">
                            <img src="{{asset('img/Sanmaru Logo.png')}}" alt="" width="94" id="sanmaru-logo">
                        </div>
                        @yield('content')
                    </div>
                </div>
            </div>
        </template>
    </main-wrapper>



<!-- End Content -->

<div class="cd-overlay-nav">
    <span></span>
</div>
<!-- /cd-overlay-nav -->

<div class="cd-overlay-content">
    <span></span>
</div>
<!-- /cd-overlay-content -->
<!-- COMMON SCRIPTS -->
<script src="{{asset('frontend-ppdb-online/js/jquery-3.2.1.min.js')}}"></script>
<script src="{{asset('frontend-ppdb-online/js/common_scripts.min.js')}}"></script>
<script src="{{asset('frontend-ppdb-online/js/velocity.min.js')}}"></script>
<script src="{{asset('frontend-ppdb-online/js/functions.js')}}"></script>
{{-- <script src="{{asset('frontend-ppdb-online/js/pw_strenght.js')}}"></script> --}}

@stack('scripts')

<script>

class MainComponent extends HTMLElement {
  connectedCallback() {
    const isMobile = matchMedia('(max-width: 768px)').matches;
    const ad = document.currentScript.closest('.main');
    const content = this
      .querySelector(isMobile ? '.main__mobile' : '.main__desktop')
      .content;

    this.appendChild(document.importNode(content, true));
  }
}

customElements.define('main-wrapper', MainComponent);

</script>
</body>
</html>
