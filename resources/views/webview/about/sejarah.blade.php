@extends('layouts.webview.main')
@section('title', 'Sejarah - SANMARU')
@push('styles')

@endpush

@section('content')

    <div class="container-fluid" style="background-color: #F9F9F9">
        <div class="container">
            <div class="col">
                <div class="row justify-content-center py-4 ">
                    <a href="#" class="btn btn-yellow nav-about mx-2">SEJARAH</a>
                    <a href="#" class="btn btn-yellow nav-about mx-2">SERVIAM</a>
                    <a href="#" class="btn btn-yellow nav-about mx-2">VISI DAN MISI</a>
                    <a href="#" class="btn btn-yellow nav-about mx-2">SAMBUTAN KETUA YAYASAN</a>
                    <a href="#" class="btn btn-yellow nav-about mx-2">SPIRITUALITAS SANTA ANGELA</a>

                </div>
                <h1 class="text-center about-title mb-4 ">Sejarah Kampus Santa Maria Surabaya</h1>
                <div class="row">
                    <img class="w-100" src="{{ asset('front/images/img-sejarah.png') }}"  alt="">
                </div>
                {{-- <div class="img-header my-4" style="background-image: url('{{ asset('front/images/img-sejarah.png') }}')"> --}}
                </div>
                <div class="row my-4 justify-content-start">
                    <div class="col-3">
                        <div class="row align-items-center justify-content-between pl-3 pr-5">
                            <span class="about-span">Share: </span>
                            <a href="#" class="share-link"><img src="{{ asset('front/images/About/facebook.png') }}" alt=""></a>
                            <a href="#" class="share-link"><img src="{{ asset('front/images/About/twitter.png') }}" alt=""></a>
                            <a href="#" class="share-link"><img src="{{ asset('front/images/About/linkedin.png') }}" alt=""></a>
                            <a href="#" class="share-link"><img src="{{ asset('front/images/About/whatsapp.png') }}" alt=""></a>
                        </div>
                    </div>
                    <div class="col-9 pr-5 col-mobile">
                        <p class="about-content text-justify">
                            Surabaya merupakan salah satu kota terbesar di Indonesia. Kota yang dijuluki ‘Kota Pahlawan’ ini
                            menyimpan banyak sekali cerita perjuangan bangsa pada saat memukul mundur penjajah di masa lalu.
                            Tak heran jika di setiap sudut kota Surabaya banyak berdiri bangunan maupun pemukiman peninggalan
                            sejarah yang bentuk arsitekturnya cukup unik. Salah satu bangunan yang merupakan cagar budaya
                            adalah bangunan peninggalan karya besar para Suster Ursulin.
                            <br>
                            <br>
                            Melalui perjalanan panjang dan perjuangan yang tiada hentinya, Kampus Santa Maria Surabaya
                            dimulai dari kedatangan 5 Suster Ursulin ke Surabaya tanggal 14 Oktober 1863. Suster-suster Ursulin pada
                            awalnya membangun komunitas di Kepanjen, kemudian berpindah di kawasan Jl. Raya Kupang, yang
                            sekarang dikenal dengan Jl. Raya Darmo.
                            <br>
                            <br>
                            Santa Maria Tahun 1951.
                            <br>
                            <br>
                            Atas permintaan Pastoor Van den Elsen, SJ maka 5 suster Ursulin dari Batavia tiba di Kepanjen
                            tgl. 14 Oktober 1863 untuk menangani karya pendidikan dan panti asuhan.
                            <br>
                            <br>
                            Sekolah yang didirikan di Kepanjen (Krembangan) ini adalah:
                            <br>
                            <br>
                            Sekolah Dasar pada th. 1863
                            <br>
                            Sekolah Ketrampilan Putri pada th. 1874
                            <br>
                            Sekolah TK (Froobel) pada th. 1877
                            <br>
                            Sekolah Pendidikan Guru (Kweekschool) pada th. 1880.
                            <br>
                            <br>
                            Sekolah ini didirikan hanya untuk murid-murid berkebangsaan Belanda.
                            <br>
                            <br>
                            Seiring dengan terjadinya perluasan kota (menuju ke arah Selatan Surabaya), maka biara Ursulin
                            Kepanjen mendirikan fillialnya di Jl. Kupang (sekarang bernama Jl. Raya Darmo). Pembangunan
                            gedung dimulai tgl. 27 Februari 1920 dan berakhir pada th. 1924. Selain biara, didirikan pula
                            sekolah-sekolah secara bertahap untuk menyediakan sekolah menengah dan kejuruan yang bermutu
                            (dengan Kurikulum Belanda). Pembangunan sekolah dimulai dari HBS kelas 1 pada bulan Juli 1920, menyusul
                            sekolah-sekolah lainnya pada tgl. 26 Juni 1922. Di sekolah ini anak perempuan Eropa, anak
                            perempuan pribumi kalangan atas dan anak-anak perempuan Indo dididik.
                            <br>
                            <br>
                            Sekolah yang didirikan di Kupang (Darmo) adalah:
                            <br>
                            <br>
                            Frobeel (TK)
                            <br>
                            Lagere School (SD)
                            <br>
                            HBS jenjang 3 tahun
                            <br>
                            HBS jenjang 5 tahun
                            <br>
                            Kweek School (SPG)
                            <br>
                            <br>
                            Pada tanggal 10 Maret 1942 Surabaya diduduki tentara Jepang. Sebagai perintah dari pembesar
                            Jepang, maka pada permulaan tahun 1943 semua sekolah harus ditutup (termasuk sekolah di Jl. Kupang).
                            Para suster Belanda ditahan di kamp tahanan, sedangkan yang lainnya hanya 4 suster diungsikan di
                            pastoran, Rumah Sakit dan Panti Asuhan sambil bertugas mengawasi dan merawat sebisanya biara Jl.
                            Kupang.
                            <br>
                            <br>
                            Pada tgl. 17 Agustus 1945 (Indonesia merdeka), para suster yang ditahan dibebaskan, mereka
                            kembali ke Jl. Kupang pada tanggal 3 Oktober 1945.
                            <br>
                            <br>
                            Namun karena situasi belum aman (menghadapi tentara Inggris yang datang ke Surabaya), maka tidak
                            berapa lama kemudian para suster diungsikan kembali ke Singapura. Duta besar dari Swiss dan
                            beberapa pemuda meminta agar suster-suster Ursulin Kupang secara sementara meminjamkan tempatnya untuk
                            Tentara Indonesia yang akan digunakan sebagai basis para pemuda sekaligus tempat peristirahatan
                            anggota-anggota pasukan BKR (Badan Keamanan Rakyat) Pelajar Staf I. Juga sebagai tempat
                            pembentukan BKR pelajar di bawah pimpinan mas Iswan (TRIP).
                            <br>
                            <br>
                            Para biarawati baru kembali lagi dari pengungsian pada tanggal 6 April 1946 ke Biara Kupang.
                            Bangunan benar-benar dalam kondisi memprihatinkan, rusak berat dan tidak terawat! Renovasi
                            dilakukan tahap demi tahap. Sekolah-sekolah yang sudah ditutup dibuka kembali. Largere School (SD) dibuka
                            kembali pada akhir April 1946 dengan jumlah 143 murid. Menyusul kemudian pada tgl. 1 Agustus
                            1948 SMP Santa Maria dibuka, kemudian HBS dan AMS pada tahun 1949.
                            <br>
                            <br>
                            Sesudah kemerdekaan, maka sekolah di Kupang (Darmo) bisa diperuntukkan bagi seluruh masyarakat
                            Indonesia.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
