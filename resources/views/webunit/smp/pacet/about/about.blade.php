@extends('layouts.webunit.smp.pacet.main')
@section('content')
    <div class="navbar-bg"></div>
    <div class="container">
        <div class="about-nav">
            <a class="body-text-20 grey" href="{{ route('webunit.about.history', ['webunit' => $webUnit]) }}">
                HISTORY
            </a>
            <a class="body-text-20 dark-purple active" href="{{ route('webunit.about.about', ['webunit' => $webUnit]) }}">
                ABOUT
            </a>
            <a class="body-text-20 grey" href="{{ route('webunit.about.welcome', ['webunit' => $webUnit]) }}">
                A WARM WELCOME
            </a>
        </div>

        <div id="about">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h4 class="title-3 orange mb-4">
                            Since 1951
                        </h4>
                        {!! $campusUnit->about !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <h4 class="title-3 orange mb-4">
                            Keunggulan
                        </h4>
                        <div class="body-text-16 grey">
                        {!! $campusUnit->keunggulan !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-6">
                        <div id="card-keunggulan">
                            <div class="card-keunggulan-item">
                                <div class="card-keunggulan-line"></div>
                                <p class="title-2">01</p>
                                <p class="body-text-16">Fasilitas Lengkap</p>
                                <p class="body-text-16 grey">Fasilitas penunjang proses belajar mengajar yang lengkap dan
                                    berkualitas
                                    premium akan memudahkan siswa dalam belajar</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-6">
                        <div id="card-keunggulan">
                            <div class="card-keunggulan-item">
                                <div class="card-keunggulan-line"></div>
                                <p class="title-2">02</p>
                                <p class="body-text-16">Pengajar Kompeten</p>
                                <p class="body-text-16 grey">Pengajar yang kompeten dan mampu memberikan kalian kompetensi
                                    sesuai perkembangan dan permintaan universitas</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-6">
                        <div id="card-keunggulan">
                            <div class="card-keunggulan-item">
                                <div class="card-keunggulan-line"></div>
                                <p class="title-2">03</p>
                                <p class="body-text-16">Kerjasama Universitas</p>
                                <p class="body-text-16 grey">Banyak kerjasama dengan universitas dalam dan luar negeri sehingga
                                    memperluas kesempatan peserta didik untuk melanjutkan studi ke jenjang lebih tinggi</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-6">
                        <div id="card-keunggulan">
                            <div class="card-keunggulan-item">
                                <div class="card-keunggulan-line"></div>
                                <p class="title-2">04</p>
                                <p class="body-text-16">Lingkungan Nyaman</p>
                                <p class="body-text-16 grey">Berdiri di lingkungan yang nyaman dan asri sehingga membuat suasana
                                    belajar siswa menjadi lebih menyenangkan</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-6">
                        <div id="card-keunggulan">
                            <div class="card-keunggulan-item">
                                <div class="card-keunggulan-line"></div>
                                <p class="title-2">05</p>
                                <p class="body-text-16">Ikatan Alumni</p>
                                <p class="body-text-16 grey">Komunitas alumni yang solid mempermudah kalian untuk sharing dan
                                    menemukan informasi pekerjaan atau yang lainnya</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-6">
                        <div id="card-keunggulan">
                            <div class="card-keunggulan-item">
                                <div class="card-keunggulan-line"></div>
                                <p class="title-2">06</p>
                                <p class="body-text-16">Pembelajaran 4C</p>
                                <p class="body-text-16 grey">Menerapkan pembelajaran 4C (critical thinking, creativity,
                                    collaboration, and communication) yang didukung layanan Google Apps for Education</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
