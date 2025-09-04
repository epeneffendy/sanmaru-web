@extends('layouts.webview.main')
@section('title', 'Pembelajaraan Daring')
@section('content')
    <div class="container-fluid" style="background-image: url('{{ asset('bg-dot-page.png') }}')">
        <div class="container">
            <div class="school-link-mobile">
                <div class="row justify-content-center">
                    <a href="#" class="school-link">Habitus<div class="line-yellow inside-position"></div></a>
                    <a href="#" class="school-link">Retret<div class="line-yellow inside-position"></div></a>
                    <a href="#" class="school-link">Live in<div class="line-yellow inside-position"></div></a>
                    <a href="#" class="school-link">Servian Camp<div class="line-yellow inside-position"></div></a>
                    <a href="#" class="school-link">RSO<div class="line-yellow inside-position"></div></a>
                    <a href="#" class="school-link">Serviant Projects<div class="line-yellow inside-position"></div></a>
                    <a href="#" class="school-link">Atraksi Siswa<div class="line-yellow inside-position"></div></a>
                    <a href="#" class="school-link">Kegiatan OSIS<div class="line-yellow inside-position"></div></a>
                    <a href="#" class="school-link active">Pembelajaran Daring<div class="line-yellow inside-position"></div></a>
                    <a href="#" class="school-link">Bimbingan Konseling<div class="line-yellow inside-position"></div></a>
                    <a href="#" class="school-link">Perpustakaan Santa Maria<div class="line-yellow inside-position"></div></a>
                </div>
            </div>

            <div class="row">
                <div class="offset-3 col-9 offset-mobile col-full-mobile">
                    <h1 class=" text-green">Pembelajaran daring</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-3 d-flex flex-column align-items-end school-life-link">
                    <a href="#" class="school-link">Habitus<div class="line-yellow inside-position"></div></a>
                    <a href="#" class="school-link">Retret<div class="line-yellow inside-position"></div></a>
                    <a href="#" class="school-link">Live in<div class="line-yellow inside-position"></div></a>
                    <a href="#" class="school-link">Servian Camp<div class="line-yellow inside-position"></div></a>
                    <a href="#" class="school-link">RSO<div class="line-yellow inside-position"></div></a>
                    <a href="#" class="school-link">Serviant Projects<div class="line-yellow inside-position"></div></a>
                    <a href="#" class="school-link">Atraksi Siswa<div class="line-yellow inside-position"></div></a>
                    <a href="#" class="school-link">Kegiatan OSIS<div class="line-yellow inside-position"></div></a>
                    <a href="#" class="school-link active">Pembelajaran Daring<div class="line-yellow inside-position"></div></a>
                    <a href="#" class="school-link">Bimbingan Konseling<div class="line-yellow inside-position"></div></a>
                    <a href="#" class="school-link">Perpustakaan Santa Maria<div class="line-yellow inside-position"></div></a>
                </div>
                <div class="col-9 col-full-mobile">
                    <img class="cover-img" src="{{ asset('front/images/pembelajaran-daring.png') }}" alt="">
                    <p class="text-justify py-5">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ultricies quis sem et elementum.
                        Morbi ullamcorper sem a magna pellentesque consequat. In dapibus ipsum id tellus tempus, nec
                        accumsan lacus consequat. Praesent malesuada risus sit amet enim rhoncus convallis. Sed metus nisi,
                        tempus in lectus quis, aliquet finibus ante. Proin ac cursus erat. Phasellus nisi sem, suscipit eget
                        elementum at, vehicula in turpis. Ut vitae porttitor sem.
                        <br>
                        <br>
                        Phasellus enim leo, posuere vitae sodales non, iaculis vel sapien. Pellentesque ut imperdiet ex.
                        Nulla ut cursus odio. Sed placerat ligula nisl, non suscipit lacus porta et. Aliquam feugiat libero
                        vel risus molestie, nec rutrum ipsum aliquam. Nulla porttitor ultrices dui sed feugiat. Donec
                        suscipit, nibh sed consequat hendrerit, mi libero dignissim dui, ac pellentesque ipsum lacus quis
                        velit. Vestibulum sed luctus diam. Sed vulputate, ligula quis ullamcorper convallis, odio diam
                        dapibus risus, vitae condimentum nisi libero in leo. Duis vitae nibh sed turpis suscipit volutpat at
                        sed lacus. Pellentesque dictum sit amet ligula in auctor.
                        <br>
                        <br>
                        Phasellus rutrum, nunc id pretium euismod, libero est pulvinar nisi, eget pharetra nisl ligula
                        placerat lorem. Fusce semper enim magna, a placerat quam feugiat id. Vestibulum congue nisi nisl, id
                        rutrum sem posuere eu. Curabitur ullamcorper arcu ut arcu interdum laoreet. Vestibulum laoreet
                        rhoncus sapien, id gravida arcu sagittis sed. Etiam mollis, erat at luctus commodo, lectus purus
                        pulvinar urna, at pharetra metus nisi eu odio. Mauris semper maximus massa vitae condimentum. Ut
                        urna ex, aliquet vitae metus non, molestie aliquet ante. Quisque tincidunt lacinia iaculis. Sed
                        elementum dui quis est vestibulum lobortis. Duis sit amet velit quis odio ornare pharetra. Proin
                        lobortis nulla eu lorem porta suscipit. Maecenas sapien velit, laoreet a nisi non, mattis venenatis
                        quam.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
