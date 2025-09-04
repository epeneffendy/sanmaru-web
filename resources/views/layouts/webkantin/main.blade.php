<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="keywords" content=""/>
    <title>@lang('pages.title') - @lang('pages.subtitle')</title>

    <link rel="icon" type="image/png" href="{{asset('img/favicon.png')}}" />

    <!-- GOOGLE WEB FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- BASE CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- YOUR CUSTOM CSS -->
    <link href="{{asset('webkantin/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('webkantin/css/responsive.css')}}" rel="stylesheet">
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

    @include('layouts.webkantin.navbar')

    <div>
        @include('layouts.webkantin.desktop-hero')
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
      $(function () {
        $(document).scroll(function () {
          var $nav = $(".navbar");
          $nav.toggleClass('scrolled', $(this).scrollTop() > $nav.height());

          var $mobileHeader = $(".mobile-header");
          $mobileHeader.toggleClass('scrolled', $(this).scrollTop() > $mobileHeader.height());

          var $mobileWelcomeHeader = $(".mobile-welcome-header");
          $mobileWelcomeHeader.toggleClass('scrolled', $(this).scrollTop() > $mobileWelcomeHeader.height());

          $("#toggle-order-btn").click(function(){
            $("#order-container").collapse('toggle'); // toggle collapse
          });
        });
      });
    </script>

    @stack('scripts')
  </body>

</html>
