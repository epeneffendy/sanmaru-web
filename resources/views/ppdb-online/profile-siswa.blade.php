@extends('layouts.ppdb-online.main')

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
                    <div class="col tbl-col text-subtitle-1">{{ $ppdb_user['name'] }}</div>
                </div>
                <div class="row tbl-row">
                    <div class="col tbl-col text-subtitle-1 text-grey">Tempat, Tanggal Lahir</div>
                    <div class="col tbl-col text-subtitle-1">{{ $ppdb_user['place_of_birth'] }}, {{ date('j F Y', strtotime($ppdb_user['date_of_birth'])) }}</div>
                </div>
                <div class="row tbl-row">
                    <div class="col tbl-col text-subtitle-1 text-grey">Alamat</div>
                    <div class="col tbl-col text-subtitle-1">{{ $ppdb_user['address'] }}</div>
                </div>
                <div class="row tbl-row">
                    <div class="col tbl-col text-subtitle-1 text-grey">Jenis Kelamin</div>
                    <div class="col tbl-col text-subtitle-1">{{ $ppdb_user['gender'] == 'male' ? 'Laki-laki' : 'Perempuan' }}</div>
                </div>
                <div class="row tbl-row">
                    <div class="col tbl-col text-subtitle-1 text-grey">Asal Sekolah</div>
                    <div class="col tbl-col text-subtitle-1">{{ $ppdb_user['origin_school'] }}</div>
                </div>
                <div class="row tbl-row">
                    <div class="col tbl-col text-subtitle-1 text-grey">No. HP Siswa</div>
                    <div class="col tbl-col text-subtitle-1">{{ $user['mobile_phone'] }}</div>
                </div>
                <div class="row tbl-row">
                    <div class="col tbl-col text-subtitle-1 text-grey">No. HP Orang tua</div>
                    <div class="col tbl-col text-subtitle-1">{{ count($ppdb_user->parents) ? $ppdb_user->parents->first()->phone : '-' }}</div>
                </div>
                <div class="row tbl-row">
                    <div class="col tbl-col text-subtitle-1 text-grey">Agama</div>
                    <div class="col tbl-col text-subtitle-1">{{ @$ppdb_user['religion'] }}</div>
                </div>
            </div>
        </div>
        <div class="row justify-content-end" style="gap: 10px;">
            <a href="#" class="btn btn-outline-danger" data-toggle="modal" data-target="#pengunduranDiriModal">Pengunduran Diri</a>
            <a href="{{ route('ppdb.change-password.form') }}" class="btn btn-outline-green">Ubah Password</a>
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
                    style="background-image: url({{ isset($ppdb_user['photo']) && $ppdb_user['photo'] ? \App\Helpers\ImageHelper::imageUrl($ppdb_user['photo'])  : asset('frontend-ppdb-online/img/profile.png') }}); border-radius: 50%; "></div>
            </div>
            <div class="row justify-content-center" style="display:block;">
                <b style="display: block; text-align: center">{{ $ppdb_user['register_number'] }}</b>
                <h2 class="text-extra-bold text-primar-green" style="text-align: center;">{{ $ppdb_user['name'] }}</h2>
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
                            <p class="text-description text-right">{{ $ppdb_user['name'] }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <p class="text-description text-grey">Tempat, Tanggal Lahir</p>
                        </div>
                        <div class="col-6">
                            <p class="text-description text-right">{{ $ppdb_user['place_of_birth'] }}, {{ date('j F Y', strtotime($ppdb_user['date_of_birth'])) }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <p class="text-description text-grey">Alamat</p>
                        </div>
                        <div class="col-6">
                            <p class="text-description text-right">{{ $ppdb_user['address'] }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <p class="text-description text-grey">Jenis Kelamin</p>
                        </div>
                        <div class="col-6">
                            <p class="text-description text-right">{{ $ppdb_user['gender'] == 'male' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <p class="text-description text-grey">Asal Sekolah</p>
                        </div>
                        <div class="col-6">
                            <p class="text-description text-right">{{ $ppdb_user['origin_school'] }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <p class="text-description text-grey">No. HP Siswa</p>
                        </div>
                        <div class="col-6">
                            <p class="text-description text-right">{{ $user['mobile_phone'] }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <p class="text-description text-grey">No. HP Orang Tua</p>
                        </div>
                        <div class="col-6">
                            <p class="text-description text-right">{{ count($ppdb_user->parents) ? $ppdb_user->parents->first()->phone : '-' }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <p class="text-description text-grey">Agama</p>
                        </div>
                        <div class="col-6">
                            <p class="text-description text-right">{{ @$ppdb_user['religion'] }}</p>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
            <div class="col my-3">
                <div class="card-outline mb-3" data-toggle="modal" data-target="#pengunduranDiriModal" style="cursor: pointer;">
                    <div class="row justify-content-center align-items-center">
                        <span class="text-body-title text-danger">Pengunduran Diri</span>
                        <img src="{{asset('frontend-ppdb-online/img/Icon/Icon-Arrow.png')}}" alt="" style="transform: rotate(-90deg); position: absolute;right:10%">
                    </div>
                </div>
                <div class="card-outline">
                    <div class="row justify-content-center align-items-center">
                        <a href="{{ route('ppdb.change-password.form') }}"><span class="text-body-title text-primary-green">Ubah Password</span></a>
                        <img src="{{asset('frontend-ppdb-online/img/Icon/Icon-Arrow.png')}}" alt="" style="transform: rotate(-90deg); position: absolute;right:10%">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Pengunduran Diri Dummy -->
<div class="modal fade" id="pengunduranDiriModal" tabindex="-1" role="dialog" aria-labelledby="pengunduranDiriModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="pengunduranDiriModalLabel">Form Pengunduran Diri</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="#" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Alasan Pengunduran Diri <span class="text-danger">*</span></label>
                <textarea class="form-control" name="reason" rows="3" required placeholder="Tuliskan alasan pengunduran diri..."></textarea>
            </div>
            <div class="form-group">
                <label>Upload Surat Pernyataan Pengunduran Diri <span class="text-danger">*</span></label>
                <br>
                <small class="text-muted">Format file: PDF, JPG, JPEG, PNG. Maksimal ukuran: 2MB.</small>
                <input type="file" class="form-control-file mt-2" name="surat_pengunduran_diri" accept=".pdf, .jpg, .jpeg, .png" required>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" onclick="alert('Ini adalah form dummy, fitur belum berfungsi.')">Submit Pengunduran Diri</button>
      </div>
    </div>
  </div>
</div>

@endsection
