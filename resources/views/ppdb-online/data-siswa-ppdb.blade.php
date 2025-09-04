@extends('layouts.ppdb-online.main')

@section('content')

<div class="wrapper-content-desktop">
    <div class="container">
        <div class="col">
            <div class="row py-3">
                <h5 class="text-body text-center">Lengkapi data indentitas dan dokumen administrasi di bawah ini untuk memudahkan proses penerimaan siswa baru.</h5>
            </div>
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="container data-siswa-table">
                <div class="row table-header-row">
                    <div class="col-4"><p class="table-header">Kelengkapan</p></div>
                    <div class="col-4"><p class="table-header">Action</p></div>
                    <div class="col-4"><p class="table-header">Status</p></div>
                </div>
                <form action="{{route('ppdb.welcome.submit')}}" method="post" enctype="multipart/form-data"></form>
                {{-- table row --}}
                <div class="row table-row">
                    <div class="col-4"><p class="table-row-text">Identitas Calon Siswa</p></div>
                    <div class="col-4">
                        <a href="{{ route('ppdb.form-student')  }}" class="">
                            <div class="status-tab status-tab-grey">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Edit</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        @if(!@$user->isPersonalDataFilled)
                            <div class="status-tab status-tab-green">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Lengkap</span>
                            </div>
                        @else
                            <div class="status-tab status-tab-red">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Belum Lengkap</span>
                            </div>
                        @endif
                    </div>
                </div>
                {{-- end of table row --}}
                {{-- table row --}}
                <div class="row table-row">
                    <div class="col-4"><p class="table-row-text">Identitas Orang Tua</p></div>
                    <div class="col-4">
                        <a href="{{ route('ppdb.form-parent') }}" class="">
                            <div class="status-tab status-tab-grey">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Edit</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        @if(!@$user->isParentsComplete)
                            <div class="status-tab status-tab-green">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Lengkap</span>
                            </div>
                        @else
                            <div class="status-tab status-tab-red">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Belum Lengkap</span>
                            </div>
                        @endif
                    </div>
                </div>
                {{-- end of table row --}}
                {{-- table row --}}
                @if ($input = $uploadsForm->get('payment_form'))
                <div class="row table-row">
                    <div class="col-4"><p class="table-row-text">{!! $input['nama'] !!}</p></div>
                    <div class="col-4">
                        <a href="#" class="">
                            <div class="status-tab status-tab-grey">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Browse</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="#">
                            <div class="status-tab status-tab-red">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Belum</span>
                            </div>
                        </a>
                    </div>
                </div>
                @endif
                {{-- end of table row --}}
                {{-- table row --}}
                <div class="row table-row">
                    <div class="col-4"><p class="table-row-text">Akta Kelahiran</p></div>
                    <div class="col-4">
                        <a href="#" class="">
                            <div class="status-tab status-tab-grey">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Edit</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="#">
                            <div class="status-tab status-tab-red">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Belum</span>
                            </div>
                        </a>
                    </div>
                </div>
                {{-- end of table row --}}
                {{-- table row --}}
                <div class="row table-row">
                    <div class="col-4"><p class="table-row-text">Pas Foto 3x4 (Background merah)</p></div>
                    <div class="col-4">
                        <a href="#" class="">
                            <div class="status-tab status-tab-grey">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Edit</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="#">
                            <div class="status-tab status-tab-red">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Belum</span>
                            </div>
                        </a>
                    </div>
                </div>
                {{-- end of table row --}}
                {{-- table row --}}
                <div class="row table-row">
                    <div class="col-4"><p class="table-row-text">Kartu Keluarga</p></div>
                    <div class="col-4">
                        <a href="#" class="">
                            <div class="status-tab status-tab-grey">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Edit</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="#">
                            <div class="status-tab status-tab-red">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Belum</span>
                            </div>
                        </a>
                    </div>
                </div>
                {{-- end of table row --}}
                {{-- table row --}}
                <div class="row table-row">
                    <div class="col-4"><p class="table-row-text">Rapor SMP Semester 1-4 (Multiple upload)</p></div>
                    <div class="col-4">
                        <a href="#" class="">
                            <div class="status-tab status-tab-grey">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Edit</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="#">
                            <div class="status-tab status-tab-red">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Belum</span>
                            </div>
                        </a>
                    </div>
                </div>
                {{-- end of table row --}}
                {{-- table row --}}
                <div class="row table-row">
                    <div class="col-4"><p class="table-row-text">Surat Permandian (bagi agama Katolik/Kristen) (Optional)</p></div>
                    <div class="col-4">
                        <a href="#" class="">
                            <div class="status-tab status-tab-grey">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Edit</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="#">
                            <div class="status-tab status-tab-red">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Belum</span>
                            </div>
                        </a>
                    </div>
                </div>
                {{-- end of table row --}}
                {{-- table row --}}
                <div class="row table-row">
                    <div class="col-4"><p class="table-row-text">Rekomendasi BK (Optional)</p></div>
                    <div class="col-4">
                        <a href="#" class="">
                            <div class="status-tab status-tab-grey">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Edit</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="#">
                            <div class="status-tab status-tab-red">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Belum</span>
                            </div>
                        </a>
                    </div>
                </div>
                {{-- end of table row --}}
                {{-- table row --}}
                <div class="row table-row">
                    <div class="col-4"><p class="table-row-text">Input Nilai Raport</p></div>
                    <div class="col-4">
                        <a href="#" class="">
                            <div class="status-tab status-tab-grey">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Edit</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="#">
                            <div class="status-tab status-tab-red">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Belum</span>
                            </div>
                        </a>
                    </div>
                </div>
                {{-- end of table row --}}
                {{-- table row --}}
                <div class="row table-row">
                    <div class="col-4"><p class="table-row-text">Angkat Peminatan</p></div>
                    <div class="col-4">
                        <a href="#" class="">
                            <div class="status-tab status-tab-grey">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Edit</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="#">
                            <div class="status-tab status-tab-red">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Belum</span>
                            </div>
                        </a>
                    </div>
                </div>
                {{-- end of table row --}}
                {{-- table row --}}
                <div class="row table-row">
                    <div class="col-4"><p class="table-row-text">Surat Pernyataan</p></div>
                    <div class="col-4">
                        <a href="#" class="">
                            <div class="status-tab status-tab-grey">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Edit</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="#">
                            <div class="status-tab status-tab-red">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Belum</span>
                            </div>
                        </a>
                    </div>
                </div>
                {{-- end of table row --}}
            </div>
        </div>
    </div>
</div>

<div class="wrapper-content-mobile">
    <div class="data-siswa-ppdb">
        <div class="col">
            <div class="row py-3">
                <h5 class="text-body text-center">Lengkapi data indentitas dan dokumen administrasi di bawah ini untuk memudahkan proses penerimaan siswa baru.</h5>
            </div>
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="container data-siswa-table">
                <div class="row table-header-row">
                    <div class="col-4"><p class="table-header">Kelengkapan</p></div>
                    <div class="col-4"><p class="table-header">Action</p></div>
                    <div class="col-4"><p class="table-header">Status</p></div>
                </div>
                <form action="{{route('ppdb.welcome.submit')}}" method="post" enctype="multipart/form-data"></form>
                {{-- table row --}}
                <div class="row table-row">
                    <div class="col-4"><p class="table-row-text">Identitas Calon Siswa</p></div>
                    <div class="col-4">
                        <a href="{{ route('ppdb.form-student')  }}" class="">
                            <div class="status-tab status-tab-grey">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Edit</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        @if(!@$user->isPersonalDataFilled)
                            <div class="status-tab status-tab-green">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Lengkap</span>
                            </div>
                        @else
                            <div class="status-tab status-tab-red">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Belum Lengkap</span>
                            </div>
                        @endif
                    </div>
                </div>
                {{-- end of table row --}}
                {{-- table row --}}
                <div class="row table-row">
                    <div class="col-4"><p class="table-row-text">Identitas Orang Tua</p></div>
                    <div class="col-4">
                        <a href="{{ route('ppdb.form-parent') }}" class="">
                            <div class="status-tab status-tab-grey">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Edit</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        @if(!@$user->isParentsComplete)
                            <div class="status-tab status-tab-green">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Lengkap</span>
                            </div>
                        @else
                            <div class="status-tab status-tab-red">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Belum Lengkap</span>
                            </div>
                        @endif
                    </div>
                </div>
                {{-- end of table row --}}
                {{-- table row --}}
                @if ($input = $uploadsForm->get('payment_form'))
                <div class="row table-row">
                    <div class="col-4"><p class="table-row-text">{!! $input['nama'] !!}</p></div>
                    <div class="col-4">
                        <a href="#" class="">
                            <div class="status-tab status-tab-grey">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Browse</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="#">
                            <div class="status-tab status-tab-red">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Belum</span>
                            </div>
                        </a>
                    </div>
                </div>
                @endif
                {{-- end of table row --}}
                {{-- table row --}}
                <div class="row table-row">
                    <div class="col-4"><p class="table-row-text">Akta Kelahiran</p></div>
                    <div class="col-4">
                        <a href="#" class="">
                            <div class="status-tab status-tab-grey">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Edit</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="#">
                            <div class="status-tab status-tab-red">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Belum</span>
                            </div>
                        </a>
                    </div>
                </div>
                {{-- end of table row --}}
                {{-- table row --}}
                <div class="row table-row">
                    <div class="col-4"><p class="table-row-text">Pas Foto 3x4 (Background merah)</p></div>
                    <div class="col-4">
                        <a href="#" class="">
                            <div class="status-tab status-tab-grey">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Edit</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="#">
                            <div class="status-tab status-tab-red">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Belum</span>
                            </div>
                        </a>
                    </div>
                </div>
                {{-- end of table row --}}
                {{-- table row --}}
                <div class="row table-row">
                    <div class="col-4"><p class="table-row-text">Kartu Keluarga</p></div>
                    <div class="col-4">
                        <a href="#" class="">
                            <div class="status-tab status-tab-grey">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Edit</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="#">
                            <div class="status-tab status-tab-red">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Belum</span>
                            </div>
                        </a>
                    </div>
                </div>
                {{-- end of table row --}}
                {{-- table row --}}
                <div class="row table-row">
                    <div class="col-4"><p class="table-row-text">Rapor SMP Semester 1-4 (Multiple upload)</p></div>
                    <div class="col-4">
                        <a href="#" class="">
                            <div class="status-tab status-tab-grey">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Edit</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="#">
                            <div class="status-tab status-tab-red">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Belum</span>
                            </div>
                        </a>
                    </div>
                </div>
                {{-- end of table row --}}
                {{-- table row --}}
                <div class="row table-row">
                    <div class="col-4"><p class="table-row-text">Surat Permandian (bagi agama Katolik/Kristen) (Optional)</p></div>
                    <div class="col-4">
                        <a href="#" class="">
                            <div class="status-tab status-tab-grey">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Edit</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="#">
                            <div class="status-tab status-tab-red">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Belum</span>
                            </div>
                        </a>
                    </div>
                </div>
                {{-- end of table row --}}
                {{-- table row --}}
                <div class="row table-row">
                    <div class="col-4"><p class="table-row-text">Rekomendasi BK (Optional)</p></div>
                    <div class="col-4">
                        <a href="#" class="">
                            <div class="status-tab status-tab-grey">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Edit</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="#">
                            <div class="status-tab status-tab-red">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Belum</span>
                            </div>
                        </a>
                    </div>
                </div>
                {{-- end of table row --}}
                {{-- table row --}}
                <div class="row table-row">
                    <div class="col-4"><p class="table-row-text">Input Nilai Raport</p></div>
                    <div class="col-4">
                        <a href="#" class="">
                            <div class="status-tab status-tab-grey">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Edit</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="#">
                            <div class="status-tab status-tab-red">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Belum</span>
                            </div>
                        </a>
                    </div>
                </div>
                {{-- end of table row --}}
                {{-- table row --}}
                <div class="row table-row">
                    <div class="col-4"><p class="table-row-text">Angkat Peminatan</p></div>
                    <div class="col-4">
                        <a href="#" class="">
                            <div class="status-tab status-tab-grey">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Edit</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="#">
                            <div class="status-tab status-tab-red">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Belum</span>
                            </div>
                        </a>
                    </div>
                </div>
                {{-- end of table row --}}
                {{-- table row --}}
                <div class="row table-row">
                    <div class="col-4"><p class="table-row-text">Surat Pernyataan</p></div>
                    <div class="col-4">
                        <a href="#" class="">
                            <div class="status-tab status-tab-grey">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Edit</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="#">
                            <div class="status-tab status-tab-red">
                                <img class="green" src="{{asset('frontend-ppdb-online/img/Icon/Tab/check.png')}}" alt="">
                                <img class="red" src="{{asset('frontend-ppdb-online/img/Icon/Tab/cross.png')}}" alt="">
                                <img class="grey" src="{{asset('frontend-ppdb-online/img/Icon/Tab/edit.png')}}" alt="">
                                <span>Belum</span>
                            </div>
                        </a>
                    </div>
                </div>
                {{-- end of table row --}}
            </div>
        </div>
    </div>
</div>
    
@endsection