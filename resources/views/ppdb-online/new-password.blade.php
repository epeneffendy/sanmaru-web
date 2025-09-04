@extends('layouts.ppdb-online.main')

@section('content')
<div class="wrapper-content-desktop">
    <div class="container" style="padding: 3rem">
        <h2>Ubah Kata Sandi</h2>
        <p class="text-subtitle-3">
            Masukkan kata sandi baru Anda dan ulangi sekali lagi
        </p>

        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('ppdb.change-password.update') }}" method="post" class="w-100">
        <div class="wrapper-input">
            <span class="text-title-2 text-grey">Kata sandi lama</span>
            <div class="input-container">
                <div class="input-icon"><img src="{{asset('frontend-ppdb-online/img/Icon/Lock.png')}}" alt=""></div>
                <input type="password" name="old_password" class="input-field">
                <label class="btn btn-eye-password">
                    <img src="{{asset('frontend-ppdb-online/img/Icon/Eye.png')}}" class="icon-eye" alt="">
                </label>
            </div>
        </div>
        <div class="wrapper-input">
            <span class="text-title-2 text-grey">Kata sandi baru</span>
            <div class="input-container">
                <div class="input-icon"><img src="{{asset('frontend-ppdb-online/img/Icon/Lock.png')}}" alt=""></div>
                <input type="password" name="password" class="input-field">
                <label class="btn btn-eye-password">
                    <img src="{{asset('frontend-ppdb-online/img/Icon/Eye.png')}}" class="icon-eye" alt="">
                </label>
            </div>
        </div>
        <div class="wrapper-input">
            <span class="text-title-2 text-grey">Ulangi kata sandi baru</span>
            <div class="input-container">
                <div class="input-icon"><img src="{{asset('frontend-ppdb-online/img/Icon/Lock.png')}}" alt=""></div>
                <input type="password" name="password_confirmation" class="input-field">
                <label class="btn btn-eye-password">
                    <img src="{{asset('frontend-ppdb-online/img/Icon/Eye.png')}}" class="icon-eye" alt="">
                </label>
                <button type="submit" class="btn btn-green align-self-center">Ubah Password</button>
            </div>
        </div>
        @csrf
        </form>
    </div>
</div>

<div class="wrapper-content-mobile">
    <div class="new-password">
        <div class="col">
            <div class="row mb-3">
                <a href="{{ route('ppdb.profile-siswa') }}" class="d-flex align-items-center justify-content-around"><img class="head-left" src="{{asset('frontend-ppdb-online/img/Icon/Icon-Arrow.png')}}" alt=""><span class="text-body-title text-primary-green ml-2">Kembali</span></a>
            </div>
            @if ($errors->any())
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            <div class="row py-2 justify-content-center">
                <form action="{{ route('ppdb.change-password.update') }}" method="post" class="w-100">
                    <div class="form-group">
                        <input type="password" name="old_password" placeholder="Password lama" class="form-control required form-password">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Ketik Password" class="form-control required form-password">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password_confirmation" placeholder="Ketik Ulang Password" class="form-control required form-password">
                    </div>
                    @csrf
                    <div class="form-group d-flex justify-content-center">
                        <button type="submit" class="btn btn-green align-self-center">Ubah Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.btn-eye-password').click(function(e) {
                e.preventDefault();
                if ($(this).parent().find('input').attr('type') === 'password') {
                    $(this).parent().find('input').attr('type', 'text');
                } else {
                    $(this).parent().find('input').attr('type', 'password');
                }
            });
        });
    </script>
@endpush
