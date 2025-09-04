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
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- ========== Css Files ========== -->
    <link href="{{asset('css/root.css')}}" rel="stylesheet">
    <link href="{{asset('css/app.css')}}" rel="stylesheet">
    <link href="{{asset('css/app-new-style.css')}}" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">


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
<body id="admin">
    @if (\App\Helpers\Helper::isProduction())
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W22293V"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    @endif
<!-- Start Page Loading -->
<div class="loading"><img src="{{asset('img/loading.gif')}}" alt="loading-img"></div>
<!-- End Page Loading -->

@include('layouts.admin.sidebar-menu')

<!-- START CONTAINER -->
<div class="content" id="app">
  <div class="admin-menu-mobile mobile">
    <a href="#" class="sidebar-open-button"><i class="fa fa-bars"></i></a>
    <a href="#" class="sidebar-open-button-mobile"><i class="fa fa-bars"></i></a>
  </div>
  @yield('content')
</div>
<!-- END CONTAINER -->

<script type="text/javascript" src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('js/bootstrap/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/plugins.js')}}"></script>
@stack('scripts')
</body>
