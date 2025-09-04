@extends('layouts.webview.main')
@section('title', 'Program Beasiswa Santa Maria - SANMARU')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="header-admission w-100"
                style="background-image: url('{{ asset('front/images/headline-beasiswa.png') }}')">
                <div class="container h-100 d-flex flex-column justify-content-center">
                    <h2 class="text-white my-4">Program<br>Beasiswa<br>Santa Maria</h2>
                    <img src="{{ asset('front/images/headline-line.png') }}" width="75" height="7" alt="">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="container-fluid bg-dot" style="background-image: url('{{ asset('front/images/bg-dot-page.png') }}')">
                <div class="container p-5">
                    <div class="row justify-content-center">
                        <h2 class="text-center text-green">Temukan program
                            yang cocok untuk Anda</h2>
                    </div>
                    <div class="row justify-content-center py-5 row-card-mobile">
                        <h3 class="text-center text-green font-italic">Coming Soon...</h3>
                        {{-- <div class="col-4 col-mobile">
                            <div class="card-beasiswa d-flex flex-column justify-content-center align-items-center">
                                <img class="align-self-center mb-2" src="{{ asset('front/images/icon-graduation.png') }}"
                                    alt="">
                                <h3 class="text-center text-green">Beasiswa satu</h3>
                                <hr class="hr-yellow">
                                <p class="text-center">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum
                                    ultricies quis sem et elementum.</p>
                                <a href="#" class="btn btn-green">SELENGKAPNYA ></a>
                            </div>
                        </div>
                        <div class="col-4 col-mobile">
                            <div class="card-beasiswa d-flex flex-column justify-content-center align-items-center">
                                <img class="align-self-center mb-2" src="{{ asset('front/images/icon-graduation.png') }}"
                                    alt="">
                                <h3 class="text-center text-green">Beasiswa satu</h3>
                                <hr class="hr-yellow">
                                <p class="text-center">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum
                                    ultricies quis sem et elementum.</p>
                                <a href="#" class="btn btn-green">SELENGKAPNYA ></a>
                            </div>
                        </div>
                        <div class="col-4 col-mobile">
                            <div class="card-beasiswa d-flex flex-column justify-content-center align-items-center">
                                <img class="align-self-center mb-2" src="{{ asset('front/images/icon-graduation.png') }}"
                                    alt="">
                                <h3 class="text-center text-green">Beasiswa satu</h3>
                                <hr class="hr-yellow">
                                <p class="text-center">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum
                                    ultricies quis sem et elementum.</p>
                                <a href="#" class="btn btn-green">SELENGKAPNYA ></a>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="container-fluid bottom-beasiswa p-5">
                <div class="col">
                    <h2 class="text-center text-green">Mengapa beasiswa?</h2>
                    <div class="row py-5 justify-content-center row-card-beasiswa row-card-mobile">
                        <div class="col-2 col-beasiswa col-mobile">
                            <div class="card-info d-flex flex-column justify-items-center align-items-center">
                                <div class="img-top mb-3"
                                    style="background-image: url('{{ asset('front/images/img-wallet.png') }}')"></div>
                                <span class="text-center">Uang saku terjamin</span>
                            </div>
                        </div>
                        <div class="col-2 col-beasiswa col-mobile">
                            <div class="card-info d-flex flex-column justify-items-center align-items-center">
                                <div class="img-top mb-3"
                                    style="background-image: url('{{ asset('front/images/img-sertifikat.png') }}')"></div>
                                <span class="text-center">Mendapat sertifikat</span>
                            </div>
                        </div>
                        <div class="col-2 col-beasiswa col-mobile">
                            <div class="card-info d-flex flex-column justify-items-center align-items-center">
                                <div class="img-top mb-3"
                                    style="background-image: url('{{ asset('front/images/img-graduate.png') }}')"></div>
                                <span class="text-center">Biaya pendidikan khusus</span>
                            </div>
                        </div>
                        <div class="col-2 col-beasiswa col-mobile">
                            <div class="card-info d-flex flex-column justify-items-center align-items-center">
                                <div class="img-top mb-3"
                                    style="background-image: url('{{ asset('front/images/img-books.png') }}')"></div>
                                <span class="text-center">Jaminan fasilitas pembelajaran</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
