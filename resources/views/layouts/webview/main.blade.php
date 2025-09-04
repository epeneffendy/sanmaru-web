<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" href="{{asset('front/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">

    <script src="{{asset('front/js/jquery.min.js')}}"></script>
    <script src="{{asset('front/js/popper.min.js')}}"></script>
    <script src="{{asset('front/js/bootstrap.min.js')}}"></script>

    <link rel="icon" type="image/png" href="{{asset('front/images/Serviam-Favicon.png')}}" />
    <link rel="stylesheet" href="{{asset('front/css/style.css')}}" />

    <!-- Add the slick-theme.css if you want default styling -->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <!-- Add the slick-theme.css if you want default styling -->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>

    @stack('styles')
    <title>@yield('title', 'SANMARU')</title>
    @if (\App\Helpers\Helper::isProduction())
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-W22293V');</script>
    <!-- End Google Tag Manager -->
    @endif
</head>

<body>
    @if (\App\Helpers\Helper::isProduction())
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W22293V"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    @endif
    @include('layouts.webview.navbar')

    <div style="padding-top: 70px">
    @yield('content')
    </div>


    @include('layouts.webview.footer')

    <script>
        $(function () {
            $(document).scroll(function () {
            var $nav = $(".navbar");
            $nav.toggleClass('scrolled', $(this).scrollTop() > $nav.height());
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
