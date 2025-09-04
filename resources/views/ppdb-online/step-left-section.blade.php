<div class="col-lg-6 content-left">
    <div class="row content-left-wrapper ">
        <div class="col-12">
            <h2 class="title-white main-title">
                Alur Proses Sistem Penerimaan Murid Baru (SPMB) Online
                <br />
                YAYASAN PARATHA BHAKTI - Kampus Santa Maria
            </h2>
            <div class="slider-container">
                <div class="text-center">
                    <div>
                        <img src="{{asset('frontend-ppdb-online/img/slide-1.png')}}" class="img-fluid">
                    </div>
                    <h2 class="title-white">Registrasi</h2>
                    <p>
                        Calon peserta didik mendaftar akun dengan mengisi alamat email, username dan
                        password.
                    </p>
                </div>
                <div class="text-center">
                    <div>
                        <img src="{{asset('frontend-ppdb-online/img/slide-2.png')}}" class="img-fluid">
                    </div>
                    <h2 class="title-white">Email Verifikasi</h2>
                    <p>Calon peserta didik akan mendapatkan email verifikasi resmi dari panitia.</p>
                </div>
                <div class="text-center">
                    <div>
                        <img src="{{asset('frontend-ppdb-online/img/slide-3.png')}}" class="img-fluid">
                    </div>
                    <h2 class="title-white">Login</h2>
                    <p>
                        Setelah mendapat email verifikasi, akun peserta didik telah aktif, dan dapat digunakan
                        untuk login website SPMB.
                    </p>
                </div>
                <div class="text-center">
                    <div>
                        <img src="{{asset('frontend-ppdb-online/img/slide-4.png')}}" class="img-fluid">
                    </div>
                    <h2 class="title-white">Melengkapi data Siswa dan Orang Tua</h2>
                    <p>
                        Setelah sukses masuk website, calon peserta didik dapat memulai untuk melengkapi data
                        pribadi Siswa dan Orang tua.
                    </p>
                </div>
                <div class="text-center">
                    <div>
                        <img src="{{asset('frontend-ppdb-online/img/slide-5.png')}}" class="img-fluid">
                    </div>
                    <h2 class="title-white">Upload dokumen kelengkapan</h2>
                    <p>
                        Selanjutnya calon peserta didik melengkapi dokumen dengan cara mengunggah / upload
                        dokumen yang telah di scan terlebih dahulu. Data dan dokumen yang telah dikirim akan
                        diverifikasi oleh panitia SPMB.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- /content-left-wrapper -->
</div>
@push('styles')
    <link rel="stylesheet" href="{{asset('js/slick-1.8.1/slick/slick.css')}}">
    <link rel="stylesheet" href="{{asset('js/slick-1.8.1/slick/slick-theme.css')}}">
@endpush
@push('scripts')
    <!-- Wizard script -->
    <script src="{{asset('frontend-ppdb-online/js/registration_func.js')}}"></script>
    <script src="{{asset('js/slick-1.8.1/slick/slick.min.js')}}"></script>
    <script>
        $('.slider-container').slick({
            nextArrow: '<button class="slick-custom-next"><img src="{{asset('frontend-ppdb-online/img/slider/next.png')}}" alt=""></button>',
            prevArrow: '<button class="slick-custom-prev"><img src="{{asset('frontend-ppdb-online/img/slider/prev.png')}}" alt=""></button>',
            dots: true,
            infinite: false,
        });
    </script>
@endpush
