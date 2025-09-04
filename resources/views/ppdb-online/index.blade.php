@extends('layouts.ppdb-landing-page.main')
@section('content')

<div class="section-home">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-6 mobile">
                <img src="{{asset('frontend-ppdb-online/img/logo-serviam.png')}}" class="ppdb-logo-serviam">
                <img src="{{asset('img/Sanmaru Logo.png')}}" class="ppdb-logo-sanmaru">
                <img src="{{asset('frontend-ppdb-online/img/ppdb-main-image.png')}}" class="ppdb-main-image">
            </div>
            <div class="col-sm-5 offset-sm-1">
				<div class="home-headline">
					<div class="logo desktop">
						<img src="{{asset('frontend-ppdb-online/img/logo-serviam.png')}}" class="ppdb-logo-serviam">
						<img src="{{asset('img/Sanmaru Logo.png')}}" class="ppdb-logo-sanmaru">
					</div>
					<div class="headline">
						<h4>SELAMAT DATANG DI</h4>
						<h1>SPMB</h1>
						<h1>ONLINE</h1>
						<h4>KAMPUS SANTA MARIA</h4>
					</div>
					<div class="headline-button">
						<button type="button" name="register" class="btn btn-register desktop">Daftar</button>&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="{{ route('ppdb.login') }}"><button type="button" name="login" class="btn btn-login desktop">Login</button></a>&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="#section-info" class="link-info info desktop"><u>Informasi</u></a>
						<a href="#section-info" class="link-info info mobile"><br><u>Informasi</u></a>
					</div>
				</div>
			</div>
			<div class="col-sm-6 desktop">
				<img src="{{asset('frontend-ppdb-online/img/ppdb-main-image.png')}}" class="ppdb-main-image">
			</div>
		</div>
	</div>
</div>

<div class="section-registration" id="section-registration">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-11 offset-sm-1">
				<div class="headline">
					<h1>REGISTRASI</h1>
					<h4>Berikut SPMB Online Unit Kampus Santa Maria</h4>
				</div>
			</div>
		</div>
		<div class="row">
            @forelse ($units as $key=>$unit)
                <div class="col-sm-2 {{ (($key+1)%5==1) ? 'offset-sm-1' : NULL }}">
                    <div class="card-unit">
                        <a href="{{ route('ppdb.profile', $unit->name) }}">
                            <img src="{{ !empty($unit->image_path) ? \App\Helpers\ImageHelper::imageUrl($unit->image_path) : '/frontend-ppdb-online/img/unit-banner.jpg' }}" class="card-unit-image" style=" height: 150px">
                            <div class="card-unit-title">{{ str_replace('-', ' ', $unit->name) }}</div>
                        </a>
                    </div>
                </div>
            @empty
            @endforelse
		</div>
	</div>
</div>

<div class="section-info" id="section-info">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-11 offset-sm-1">
				<div class="headline">
					<h1>Informasi</h1>
					<h4>Informasi seputar prosedur dan persyaratan SPMB Kampus Santa Maria.</h4>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4">
                <div class="card-info text-center">
                    <div>
                        <img src="{{asset('frontend-ppdb-online/img/slide-1.png')}}" class="img-fluid">
                    </div>
                    <h2 class="title">Registrasi</h2>
                    <p>
                        Calon peserta didik mendaftar akun dengan mengisi alamat email, username dan
                        password.
                    </p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card-info text-center">
                    <div>
                        <img src="{{asset('frontend-ppdb-online/img/slide-2.png')}}" class="img-fluid">
                    </div>
                    <h2 class="title">Email Verifikasi</h2>
                    <p>Calon peserta didik akan mendapatkan email verifikasi resmi dari panitia.</p>
                </div>
            </div>
	        <div class="col-sm-4">
            	<div class="card-info text-center">
                    <div>
                        <img src="{{asset('frontend-ppdb-online/img/slide-3.png')}}" class="img-fluid">
                    </div>
                    <h2 class="title">Login</h2>
                    <p>
                        Setelah mendapat email verifikasi, akun peserta didik telah aktif, dan dapat digunakan
                        untuk login website SPMB.
                    </p>
                </div>
            </div>
	        <div class="col-sm-4 offset-sm-2">
                <div class="card-info text-center">
                    <div>
                        <img src="{{asset('frontend-ppdb-online/img/slide-4.png')}}" class="img-fluid">
                    </div>
                    <h2 class="title">Melengkapi data Siswa dan Orang Tua</h2>
                    <p>
                        Setelah sukses masuk website, calon peserta didik dapat memulai untuk melengkapi data
                        pribadi Siswa dan Orang tua.
                    </p>
                </div>
            </div>
	        <div class="col-sm-4">
                <div class="card-info text-center">
                    <div>
                        <img src="{{asset('frontend-ppdb-online/img/slide-5.png')}}" class="img-fluid">
                    </div>
                    <h2 class="title">Upload Dokumen Kelengkapan</h2>
                    <p>
                        Selanjutnya calon peserta didik melengkapi dokumen dengan cara mengunggah / upload
                        dokumen yang telah di scan terlebih dahulu. Data dan dokumen yang telah dikirim akan
                        diverifikasi oleh panitia SPMB.
                    </p>
                </div>
			</div>
		</div>
	</div>
    <div class="menu-bottom mobile">
        <div class="button-register-mobile"><a href="#section-registration">DAFTAR</a></div>
        <div class="button-login-mobile"><a href="{{ route('ppdb.login') }}">MASUK</a></div>
    </div>
</div>

@include('ppdb-online.footer')
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        scroll($('.btn-register'), document.querySelector('#section-registration'));
        scroll($('.link-info'), document.querySelector('#section-info'));
    });

    function scroll(element, target) {
        $(element).click(function(e) {
            e.preventDefault();
            target.scrollIntoView({behavior: 'smooth'});
        });
    }
</script>
@endpush
