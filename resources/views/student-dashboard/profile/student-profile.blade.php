@extends('layouts.welcome-page.main')
@section('content')

<div class="wrapper-content-desktop">
    <div class="container" style="padding: 3rem">
        <div class="row">
            <h2 class="">Identitas Siswa</h2>
        </div>
        <div class="row">
            @if (session('message'))
                <label class="label label-success">{{ session('message') }}</label>
            @endif
            <div class="table container">
                <div class="row tbl-row">
                    <div class="col tbl-col text-subtitle-1 text-grey">Nama Lengkap</div>
                    <div class="col tbl-col text-subtitle-1">{{ $student['name'] }}</div>
                </div>
                <div class="row tbl-row">
                    <div class="col tbl-col text-subtitle-1 text-grey">Tempat, Tanggal Lahir</div>
                    <div class="col tbl-col text-subtitle-1">{{ $student['place_of_birth'] && $student['date_of_birth'] ? $student['place_of_birth'] . ', ' . date('j F Y', strtotime($student['date_of_birth'])) : '-'}}</div>
                </div>
                <div class="row tbl-row">
                    <div class="col tbl-col text-subtitle-1 text-grey">Alamat</div>
                    <div class="col tbl-col text-subtitle-1">{{ $student['address'] }}</div>
                </div>
                <div class="row tbl-row">
                    <div class="col tbl-col text-subtitle-1 text-grey">Jenis Kelamin</div>
                    <div class="col tbl-col text-subtitle-1">{{ $student['gender'] == 'male' ? 'Laki-laki' : 'Perempuan' }}</div>
                </div>
                <div class="row tbl-row">
                    <div class="col tbl-col text-subtitle-1 text-grey">Asal Sekolah</div>
                    <div class="col tbl-col text-subtitle-1">{{ $student['origin_school'] }}</div>
                </div>
                <div class="row tbl-row">
                    <div class="col tbl-col text-subtitle-1 text-grey">No. HP Siswa</div>
                    <div class="col tbl-col text-subtitle-1">{{ $student['mobile_phone'] }}</div>
                </div>
                <div class="row tbl-row">
                    <div class="col tbl-col text-subtitle-1 text-grey">No. HP Orang tua</div>
                    <div class="col tbl-col text-subtitle-1">{{ $student['dad_mobile_phone'] }}</div>
                </div>
                <div class="row tbl-row">
                    <div class="col tbl-col text-subtitle-1 text-grey">Agama</div>
                    <div class="col tbl-col text-subtitle-1">{{ @$student['religion'] }}</div>
                </div>
            </div>
        </div>
        <div class="row justify-content-end">
            <a href="{{ route('profile.change-password.form') }}" class="btn btn-outline-green">Ubah Password</a>
        </div>
    </div>
</div>

<div class="wrapper-content-mobile">
    <div class="profile-siswa-ppdb">
        <div class="container">
            @if (session('message'))
                <label class="label label-success">{{ session('message') }}</label>
            @endif
            <div class="row justify-content-center">
                <div class="profile-picture-ppdb"
                    style="background-image: url({{ isset($student['photo']) && $student['photo'] ? \App\Helpers\ImageHelper::imageUrl($student['photo'])  : asset('frontend-ppdb-online/img/profile.png') }}); border-radius: 50%; "></div>
            </div>
            <div class="row justify-content-center" style="display:block;">
                <b style="display: block; text-align: center">{{ $student['register_number'] }}</b>
                <h2 class="text-extra-bold text-primar-green" style="text-align: center;">{{ $student['unit'] }}</h2>
            </div>
            <div class="col">
                <div class="card-outline">
                    <div class="row justify-content-center align-items-center mb-4">
                        <span class="text-body-title text-primary-green">Identitas Siswa</span>
                        <img src="{{asset('frontend-ppdb-online/img/Icon/Icon-Arrow.png')}}" alt="" style="transform: rotate(-180deg); position: absolute;right:10%">
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <p class="text-description text-grey">Nama Lengkap</p>
                        </div>
                        <div class="col-6">
                            <p class="text-description text-right">{{ $student['name'] }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <p class="text-description text-grey">Tempat, Tanggal Lahir</p>
                        </div>
                        <div class="col-6">
                            <p class="text-description text-right">{{ $student['place_of_birth'] }}, {{ date('j F Y', strtotime($student['date_of_birth'])) }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <p class="text-description text-grey">Alamat</p>
                        </div>
                        <div class="col-6">
                            <p class="text-description text-right">{{ $student['address'] }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <p class="text-description text-grey">Jenis Kelamin</p>
                        </div>
                        <div class="col-6">
                            <p class="text-description text-right">{{ $student['gender'] == 'male' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <p class="text-description text-grey">Asal Sekolah</p>
                        </div>
                        <div class="col-6">
                            <p class="text-description text-right">{{ $student['origin_school'] }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <p class="text-description text-grey">No. HP Siswa</p>
                        </div>
                        <div class="col-6">
                            <p class="text-description text-right">{{ $student['mobile_phone'] }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <p class="text-description text-grey">No. HP Orang Tua</p>
                        </div>
                        <div class="col-6">
                            <p class="text-description text-right">{{ $student['dad_mobile_phone'] }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <p class="text-description text-grey">Agama</p>
                        </div>
                        <div class="col-6">
                            <p class="text-description text-right">{{ @$student['religion'] }}</p>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
            <div class="col my-3">
                <div class="card-outline">
                    <div class="row justify-content-center align-items-center">
                        <a href="{{ route('profile.change-password.form') }}"><span class="text-body-title text-primary-green">Ubah Password</span></a>
                        <img src="{{asset('frontend-ppdb-online/img/Icon/Icon-Arrow.png')}}" alt="" style="transform: rotate(-90deg); position: absolute;right:10%">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
