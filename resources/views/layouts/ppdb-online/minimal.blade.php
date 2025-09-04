<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicons-->
    <link rel="icon" type="image/png" href="{{asset('img/favicon.png')}}" />

    <!-- GOOGLE WEB FONT -->
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:400,500,600" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,600;0,800;1,400&display=swap" rel="stylesheet">

    <!-- BASE CSS -->
    <link href="{{asset('frontend-ppdb-online/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- YOUR CUSTOM CSS -->
    <link href="{{asset('css/app.css')}}" rel="stylesheet">

    <!-- MODERNIZR MENU -->
    <script src="{{asset('frontend-ppdb-online/js/modernizr.js')}}"></script>

    @stack('styles')

    @if (App::environment('production'))
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-136148327-4"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-136148327-4');
    </script>
    @endif

</head>
<body id="ppdb">

<!-- Start Content -->
<div class="container-fluid full-height">
    @yield('content')
</div>
<!-- End Content -->

<!-- COMMON SCRIPTS -->
<script src="{{asset('frontend-ppdb-online/js/jquery-3.2.1.min.js')}}"></script>
<script src="{{asset('frontend-ppdb-online/js/common_scripts.min.js')}}"></script>
<script src="{{asset('frontend-ppdb-online/js/velocity.min.js')}}"></script>
<script src="{{asset('frontend-ppdb-online/js/functions.js')}}"></script>
<script src="{{asset('frontend-ppdb-online/js/pw_strenght.js')}}"></script>

@stack('scripts')
</body>
</html>
