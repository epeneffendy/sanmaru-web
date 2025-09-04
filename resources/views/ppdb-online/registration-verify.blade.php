<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SANMARU - Aplikasi Sekolah Santa Maria Ursulin</title>
    <link rel="shortcut icon" type="image/png" href="/img/Logo Sanmaru Icon Apps.png"/>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background: linear-gradient(90deg, #9ebd13 0%, #008552 100%);
            color: #fff;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
            font-weight: 500;
        }

        .links {
            color: #fff;
            padding: 0 25px;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }

        .logo {
            text-align: center;
        }

        .logo img {
            width: 200px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">

    <div class="content">
        <div class="title m-b-md">
            <a href="{{route('ppdb.verify').'?v='.$register_token}}">{{$register_token}}</a>
        </div>

        <div class="links">
            Aplikasi Sekolah Santa Maria Ursulin
        </div>
    </div>
</div>
</body>
</html>
