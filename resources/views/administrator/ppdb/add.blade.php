@extends('layouts.admin.main')
@section('content')
@if(@$method=="edit")
    @php($action=route('admin.ppdb.update',array($data->id)))
    @php($status="Update")
    @php($status_header="Edit")
@else
    @php($action=route('admin.ppdb.insert'))
    @php($status="Save")
    @php($status_header="Tambah")
@endif
<!-- Start Page Header -->
<div class="page-header">
    <h1 class="title">Data Master PPDB User</h1>
    <ol class="breadcrumb">
        <li>Master</li>
        <li><a href="{{route('admin.ppdb.index')}}">PPDB User</a></li>
        <li class="active">{{$status_header}}</li>
    </ol>
</div>
<!-- End Page Header -->

<!-- START CONTAINER -->
<div class="container-padding">

    <!-- Start Row -->
    <div class="row">
        <!-- Start Panel -->
        <div class="col-md-12">
            <div class="widget ">
                <div class="widget-header">
                    <h3>
                        {{$status_header}} PPDB User
                    </h3>
                    <p class="text-danger">* Setiap data wajib di isi</p>
                </div> <!-- /widget-header -->
                <div class="widget-content">
                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form role="form" method="POST" action="{{$action}}" class="form-horizontal" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="name">Nama:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="name" id="name" value="{{ old('name') ?? @$data->name }}" placeholder="Enter name" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="nik">NIK:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="nik" id="nik" value="{{ old('nik') ?? @$data->name }}"
                                    placeholder="NIK Siswa" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="name">Email:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="email" id="email" value="{{ old('email') ?? @$data->email }}" placeholder="Enter email" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="name">Nomor Telepon:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="mobile_phone" id="mobile_phone" value="{{ old('mobile_phone') ?? @$data->mobilePhone }}" placeholder="Enter Mobile Phone" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="gender">Jenis Kelamin:</label>
                            <div class="col-sm-10">
                                <select class="form-control{{ $errors->has('gender') ? ' is-invalid' : '' }}" name="gender" id="gender" required>
                                    <option value="male" {{ @$data->gender === 'male' ? 'selected' : ''}}>Laki-laki</option>
                                    <option value="Female" {{ @$data->gender === 'female' ? 'selected' : ''}}>Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="place_of_birth">Tempat kelahiran:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="place_of_birth" id="place_of_birth" value="{{ old('place_of_birth') ?? @$data->place_of_birth }}" placeholder="Enter place of birth" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="date_of_birth">Tanggal Lahir:</label>
                            <div class="col-sm-10">
                                <input class="form-control" type='date' name="date_of_birth" value="{{ old('date_of_birth') ?? @$data['date_of_birth'] }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="address">Alamat:</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="address" id="address" placeholder="Enter address" required>{{ old('address') ?? @$data['address'] }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="city">Kota:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="city" id="city" value="{{ old('city') ?? @$data['city'] }}" placeholder="Enter city" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="region">Provinsi:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="region" id="region" value="{{ old('region') ?? @$data['region'] }}" placeholder="Enter region">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="country">Negara:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="country" id="country" value="{{ old('country') ?? @$data['country']}}" placeholder="Enter country">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="religion">Agama:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="religion" id="religion" value="{{ old('religion') ?? @$data['religion']}}" placeholder="Enter religion">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="school_year">Tahun masuk:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="school_year" id="school_year" value="{{ old('school_year') ?? @$data['school_year']}}" placeholder="Enter school year">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="origin_school">Asal Sekolah:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="origin_school" id="origin_school" value="{{ old('origin_school') ?? @$data['origin_school']}}" placeholder="Enter school year">
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                            <div class="control-label col-sm-2"><label>Data Ayah</label></div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="father_name">Nama Ayah</label>
                            <div class="col-sm-10">
                                <input type="text" name="father_name" value="{{ old('father_name') ?? @$dad->name }}" class="form-control required" placeholder="Nama Ayah">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="f_place_of_birth">Tempat Lahir Ayah</label>
                            <div class="col-sm-10">
                                <input type="text" name="f_place_of_birth" value="{{ old('f_place_of_birth') ?? @$dad['place_of_birth'] }}" class="form-control" placeholder="Tempat Lahir">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="f_date_of_birth">Tanggal Lahir Ayah</label>
                            <div class="col-sm-10">
                                <input class="form-control" type='date' name="f_date_of_birth" value="{{ old('f_date_of_birth') ?? @$dad->date_of_birth }}" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="f_addresss">Alamat Ayah</label>
                            <div class="col-sm-10">
                                <textarea class="form-control required" rows="3" name="f_address" placeholder="Alamat">{{ old('f_address') ?? @$dad['address'] }}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="f_city">Kota Ayah</label>
                            <div class="col-sm-10">
                                <input type="text" name="f_city" value="{{ old('f_city') ?? @$dad['city'] }}" class="form-control required" placeholder="Kota">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="f_region">Provinsi Ayah</label>
                            <div class="col-sm-10">
                                <input type="text" name="f_region" value="{{ old('f_region') ?? @$dad['region'] }}" class="form-control required" placeholder="Provinsi">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="f_country">Kewarganegaraan Ayah</label>
                            <div class="col-sm-10">
                                <input type="text" name="f_country" value="{{ old('f_country') ?? @$dad['country'] }}" class="form-control required" placeholder="Kewarganegaraan">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="f_religion">Agama Ayah</label>
                            <div class="col-sm-10">
                                <input type="text" name="f_religion" value="{{ old('f_religion') ?? @$dad['religion'] }}" class="form-control required" placeholder="Agama">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="f_job">Pekerjaan Ayah</label>
                            <div class="col-sm-10">
                                <input type="text" name="f_job" value="{{ old('f_job') ?? @$dad['job'] }}" class="form-control required" placeholder="Pekerjaan">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="f_phone">Nomor Telepon Ayah</label>
                            <div class="col-sm-10">
                                <input type="number" name="f_phone" value="{{ old('f_phone') ?? @$dad['phone'] }}" class="form-control required" placeholder="Telepon">
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                            <div class="control-label col-sm-2"><label>Data Ibu</label></div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="mother_name">Nama Ibu</label>
                            <div class="col-sm-10">
                                <input type="text" name="mother_name" value="{{ old('mother_name') ?? @$mom['name'] }}" class="form-control required" placeholder="Nama Ibu">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="m_place_of_birth">Tempat Lahir Ibu</label>
                            <div class="col-sm-10">
                                <input type="text" name="m_place_of_birth" value="{{ old('m_place_of_birth') ?? @$mom['place_of_birth'] }}" class="form-control" placeholder="Tempat Lahir">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="m_date_of_birth">Tanggal Lahir Ibu</label>
                            <div class="col-sm-10">
                                <input class="form-control" type='date' name="m_date_of_birth" value="{{ old('m_date_of_birth') ?? @$mom->date_of_birth }}" id="datepicker-mother" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="m_address">Alamat Ibu</label>
                            <div class="col-sm-10">
                                <textarea class="form-control required" rows="3" name="m_address" placeholder="Alamat">{{ old('m_address') ?? @$mom['address'] }}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="m_city">Kota Ibu</label>
                            <div class="col-sm-10">
                                <input type="text" name="m_city" value="{{ old('m_city') ?? @$mom['city'] }}" class="form-control required" placeholder="Kota">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="m_region">Propinsi Ibu</label>
                            <div class="col-sm-10">
                                <input type="text" name="m_region" value="{{ old('m_region') ?? @$mom['region'] }}" class="form-control required" placeholder="Provinsi">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="m_country">Kewarganegaraan Ibu</label>
                            <div class="col-sm-10">
                                <input type="text" name="m_country" value="{{  old('m_country') ?? @$mom['country'] }}" class="form-control required" placeholder="Kewarganegaraan">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="m_religion">Agama Ibu</label>
                            <div class="col-sm-10">
                                <input type="text" name="m_religion" value="{{ old('m_religion') ?? @$mom['religion'] }}" class="form-control required" placeholder="Agama">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="m_job">Pekerjaan Ibu</label>
                            <div class="col-sm-10">
                                <input type="text" name="m_job" value="{{ old('m_job') ?? @$mom['job'] }}" class="form-control required" placeholder="Pekerjaan">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="m_phone">Nomor Telepon Ibu</label>
                            <div class="col-sm-10">
                                <input type="number" name="m_phone" value="{{ old('m_phone') ?? @$mom['phone'] }}" class="form-control required" placeholder="Telepon">
                            </div>
                        </div>
                        <div class="clear-50"></div>

                        <hr/>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-default">{{$status}}</button>
                            </div>
                        </div>

                        @csrf
                    </form>
                </div>
            </div> <!-- /widget-content -->
        </div>
        <!-- End Panel -->
    </div>
    <!-- End Row -->
</div>
<!-- END CONTAINER -->
@endsection

@push('scripts')
@endpush