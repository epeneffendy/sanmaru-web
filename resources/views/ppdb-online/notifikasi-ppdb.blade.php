@extends('layouts.ppdb-online.main')
@section('content')
    <div class="wrapper-content-desktop">
        @include('layouts.ppdb-online.tab-bar')
        <div class="container" style="padding: 3rem">
            <div class="row py-2">
                <div class="notifikasi-ppdb-item container unread">
                    <div class="col">
                        <div class="row notifikasi-title justify-content-between align-items-center">
                            <h3 class="text-body-title">Wawancara Online & Tes Akademik</h3>
                            <div class="notifikasi-circle"></div>
                        </div>
                        <div class="row">
                            <p class="text-subtitle-3 text-grey">12 Maret 2021</p>
                        </div>
                        <div class="row">
                            <p class="text-subtitle-3 text-navy">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur in pretium libero. Donec elementum sem eget auctor ullamcorper.</p>
                        </div>
                    </div>
                </div>
                <div class="notifikasi-ppdb-item container">
                    <div class="col">
                        <div class="row notifikasi-title justify-content-between align-items-center">
                            <h3 class="">Wawancara Online & Tes Akademik</h3>
                            <div class="notifikasi-circle"></div>
                        </div>
                        <div class="row">
                            <p class="text-subtitle-3 text-grey">12 Maret 2021</p>
                        </div>
                        <div class="row">
                            <p class="text-subtitle-3 text-navy">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur in pretium libero. Donec elementum sem eget auctor ullamcorper.</p>
                        </div>
                    </div>
                </div>
                <div class="notifikasi-ppdb-item container">
                    <div class="col">
                        <div class="row notifikasi-title justify-content-between align-items-center">
                            <h3 class="">Wawancara Online & Tes Akademik</h3>
                            <div class="notifikasi-circle"></div>
                        </div>
                        <div class="row">
                            <p class="text-subtitle-3 text-grey">12 Maret 2021</p>
                        </div>
                        <div class="row">
                            <p class="text-subtitle-3 text-navy">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur in pretium libero. Donec elementum sem eget auctor ullamcorper.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="wrapper-content-mobile">
        <div class="informasi-ppdb">
            <div class="col">
                <div class="row mb-3">
                    <a href="{{ route('ppdb.welcome') }}" class="d-flex align-items-center justify-content-around"><img class="head-left" src="{{asset('frontend-ppdb-online/img/Icon/Icon-Arrow.png')}}" alt=""><span class="text-body-title text-primary-green ml-2">Kembali</span></a>
                </div>
                <div class="row py-2">
                    <div class="notifikasi-ppdb-item container unread">
                        <div class="col">
                            <div class="row notifikasi-title justify-content-between align-items-center">
                                <h5 class="text-body-title text-grey">Wawancara Online & Tes Akademik</h5>
                                <div class="notifikasi-circle"></div>
                            </div>
                            <div class="row">
                                <p class="text-description text-grey">12 Maret 2021</p>
                            </div>
                            <div class="row">
                                <p class="text-description text-navy">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur in pretium libero. Donec elementum sem eget auctor ullamcorper.</p>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="notifikasi-ppdb-item container">
                        <div class="col">
                            <div class="row notifikasi-title justify-content-between align-items-center">
                                <h5 class="text-body-title text-grey">Wawancara Online & Tes Akademik</h5>
                                <div class="notifikasi-circle"></div>
                            </div>
                            <div class="row">
                                <p class="text-description text-grey">12 Maret 2021</p>
                            </div>
                            <div class="row">
                                <p class="text-description text-navy">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur in pretium libero. Donec elementum sem eget auctor ullamcorper.</p>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="notifikasi-ppdb-item container">
                        <div class="col">
                            <div class="row notifikasi-title justify-content-between align-items-center">
                                <h5 class="text-body-title text-grey">Wawancara Online & Tes Akademik</h5>
                                <div class="notifikasi-circle"></div>
                            </div>
                            <div class="row">
                                <p class="text-description text-grey">12 Maret 2021</p>
                            </div>
                            <div class="row">
                                <p class="text-description text-navy">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur in pretium libero. Donec elementum sem eget auctor ullamcorper.</p>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection