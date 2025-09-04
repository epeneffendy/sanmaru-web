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
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- BASE CSS -->
    <link href="{{asset('web-sma/sby/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('web-sma/sby/css/bootnavbar.css')}}" rel="stylesheet">
    <!-- Add the slick-theme.css if you want default styling -->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <!-- Add the slick-theme.css if you want default styling -->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/> 

    <!-- YOUR CUSTOM CSS -->
    <link href="{{asset('web-sma/sby/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('web-sma/sby/css/responsive.css')}}" rel="stylesheet">



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

  <body>
    @if (\App\Helpers\Helper::isProduction())
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W22293V"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    @endif
    @include('layouts.webunit.sma.sby.navbar')

    <div>
    @yield('content')
    </div>


    @include('layouts.webunit.sma.sby.footer')

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js"></script>
    <script src="{{asset('web-sma/sby/js/bootnavbar.js')}}"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script>
      $('#navbar').bootnavbar();
      $('.carousel').slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        dots: true,
        centerMode: true,
        centerPadding: '0px',
      });
      $('.mobile-carousel').slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: true,
        prevArrow: false,
        nextArrow: false
      });
      $('#CarouselPopup').slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
      });
      $('.hero-carousel').slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        prevArrow: false,
        nextArrow: false,
        autoplay: true,
        autoplaySpeed: 3000,
      });

      $(function () {
        $(document).scroll(function () {
          var $nav = $(".navbar");
          $nav.toggleClass('scrolled', $(this).scrollTop() > $nav.height());
        });
      });

      $(function () {
        const navToggle = document.querySelector(".navbar-toggler");
        const sidebar = document.querySelector("#sidebar");
        const backToggle = document.querySelector(".close-toggler");

        navToggle.addEventListener("click", () => {
          sidebarShow();
        });
        backToggle.addEventListener("click", () => {
          sidebarHide();
        });

        function sidebarShow() {
          sidebar.style.transition = "all 0.3 ease 0.5s";
          sidebar.classList.add("open");
        }

        function sidebarHide() {
          sidebar.style.transition = "all 0.3 ease";
          sidebar.classList.remove("open");
        }
      });



    </script>


    @stack('scripts')
  </body>

</html>