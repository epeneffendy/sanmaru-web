@extends('layouts.admin.main')
@section('content')
    @php($action=route('admin.ppdb.show',array($data->id)))
    @php($status="Show")
    @php($status_header="Show")
    @php($uploadsForm = \App\Helpers\InputCollectionHelper::uploads($data->unit, null, $data))
    @php($additionalData = \App\Helpers\InputCollectionHelper::additionalData($data->unit))
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data Master Peserta PPDB</h1>
        <ol class="breadcrumb">
            <li>Master</li>
            <li><a href="{{route('admin.ppdb.index')}}">PPDB</a></li>
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
                    </div> <!-- /widget-header -->
                    <div class="widget-content">
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default panel-collapse">
                                <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
                                           aria-expanded="true" aria-controls="collapseOne">
                                            Data Diri
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel"
                                     aria-labelledby="headingOne" aria-expanded="true">
                                    <div class="panel-body">
                                        <div class="form-horizontal">
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="name">Nama:</label>
                                                <div class="col-sm-10">
                                                    <div class="form-control"> {{ @$data->name }} </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="nik_siswa">NIK Siswa:</label>
                                                <div class="col-sm-10">
                                                    <div class="form-control"> {{ @$data->nik_siswa }} </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="nik_ortu">NIK Orang
                                                    Tua:</label>
                                                <div class="col-sm-10">
                                                    <div class="form-control"> {{ @$data->nik_ortu }} </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="name">Email:</label>
                                                <div class="col-sm-10">
                                                    <div class="form-control">{{ @$data->email }} </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="name">Nomor Telepon:</label>
                                                <div class="col-sm-10">
                                                    <div class="form-control"> {{ @$data->user->mobile_phone }} </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="name">Asal Sekolah:</label>
                                                <div class="col-sm-10">
                                                    <div class="form-control"> {{ @$data->origin_school }} </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="gender">Jenis
                                                    Kelamin:</label>
                                                <div class="col-sm-10">
                                                    <div class="form-control">{{ @$data->gender }}</div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="place_of_birth">Tempat
                                                    kelahiran:</label>
                                                <div class="col-sm-10">
                                                    <div class="form-control">{{ @$data->place_of_birth }}</div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="date_of_birth">Tanggal
                                                    Lahir:</label>
                                                <div class="col-sm-10">
                                                    <div class="form-control">{{ @$data['date_of_birth'] }}</div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="address">Alamat:</label>
                                                <div class="col-sm-10">
                                                    <div class="form-control">{{ @$data['address'] }}</div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="city">Kota:</label>
                                                <div class="col-sm-10">
                                                    <div class="form-control"> {{ @$data['city'] }}</div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="region">Provinsi:</label>
                                                <div class="col-sm-10">
                                                    <div class="form-control"> {{ @$data['region'] }}</div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="country">Negara:</label>
                                                <div class="col-sm-10">
                                                    <div class="form-control"> {{ @$data['country']}}</div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="religion">Agama:</label>
                                                <div class="col-sm-10">
                                                    <div class="form-control">{{ @$data['religion']}}</div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="school_year">Tahun
                                                    masuk:</label>
                                                <div class="col-sm-10">
                                                    <div class="form-control">{{ @$data['school_year']}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default panel-collapse">
                                <div class="panel-heading" role="tab" id="headingSix">
                                    <h4 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion"
                                           href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                            Data Tambahan
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseSix" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="headingSix" aria-expanded="false">
                                    <div class="panel-body">
                                        <div class="form-horizontal">
                                            @foreach ($additionalData as $k => $v)
                                                <div class="form-group">
                                                    <label class="control-label col-sm-4">
                                                        @if ($k == 'npwp')
                                                            {{ strtoupper($k) }} Orangua/Wali
                                                        @else
                                                            {{ ucwords(str_replace('_', ' ', $k)) }}
                                                        @endif
                                                    </label>
                                                    <div class="col-sm-6">
                                                        <div class="form-control"> {{ @$data[$k] }}</div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if (!$data->isWalIRequired)
                                <div class="panel panel-default panel-collapse">
                                    <div class="panel-heading" role="tab" id="headingTwo">
                                        <h4 class="panel-title">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion"
                                               href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                Data Ayah
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel"
                                         aria-labelledby="headingTwo" aria-expanded="false" style="height: 0px;">
                                        <div class="panel-body">
                                            <div class="form-horizontal">
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="father_name">Nama
                                                        Ayah</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control">{{ @$dad->name }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="f_place_of_birth">Tempat
                                                        Lahir Ayah</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control"> {{ @$dad['place_of_birth'] }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="f_date_of_birth">Tanggal
                                                        Lahir Ayah</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control"> {{ @$dad->date_of_birth }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="f_addresss">Alamat
                                                        Ayah</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control"> {{ @$dad['address'] }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="f_city">Kota Ayah</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control"> {{ @$dad['city'] }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="f_region">Provinsi
                                                        Ayah</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control"> {{ @$dad['region'] }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="f_country">Kewarganegaraan
                                                        Ayah</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control"> {{ @$dad['country'] }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="f_religion">Agama
                                                        Ayah</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control"> {{ @$dad['religion'] }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="f_job">Pekerjaan
                                                        Ayah</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control">{{ @$dad['job'] }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="f_salary">Penghasilan
                                                        Ayah</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control">{{ @$dad['salary'] }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="f_education">Pendidikan
                                                        Ayah</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control">{{ @$dad['education'] }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="f_phone">Nomor Telepon
                                                        Ayah</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control"> {{ @$dad['phone'] }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default panel-collapse">
                                    <div class="panel-heading" role="tab" id="headingThree">
                                        <h4 class="panel-title">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion"
                                               href="#collapseThree" aria-expanded="false"
                                               aria-controls="collapseThree">
                                                Data Ibu
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel"
                                         aria-labelledby="headingThree" aria-expanded="false">
                                        <div class="panel-body">
                                            <div class="form-horizontal">
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="mother_name">Nama
                                                        Ibu</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control"> {{ @$mom['name'] }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="m_place_of_birth">Tempat
                                                        Lahir Ibu</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control"> {{ @$mom['place_of_birth'] }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="m_date_of_birth">Tanggal
                                                        Lahir Ibu</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control"> {{ @$mom->date_of_birth }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="m_address">Alamat
                                                        Ibu</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control"> {{ @$mom['address'] }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="m_city">Kota Ibu</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control"> {{ @$mom['city'] }} </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="m_region">Propinsi
                                                        Ibu</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control"> {{ @$mom['region'] }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="m_country">Kewarganegaraan
                                                        Ibu</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control"> {{ @$mom['country'] }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="m_religion">Agama
                                                        Ibu</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control"> {{ @$mom['religion'] }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="m_job">Pekerjaan
                                                        Ibu</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control"> {{ @$mom['job'] }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="m_salary">Penghasilan
                                                        Ibu</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control">{{ @$mom['salary'] }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="w_education">Pendidikan
                                                        Ibu</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control">{{ @$mom['education'] }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="m_phone">Nomor Telepon
                                                        Ibu</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control"> {{ @$mom['phone'] }} </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="panel panel-default panel-collapse">
                                    <div class="panel-heading" role="tab" id="headingSeven">
                                        <h4 class="panel-title">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion"
                                               href="#collapseSeven" aria-expanded="false"
                                               aria-controls="collapseSeven">
                                                Data Wali
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseSeven" class="panel-collapse collapse" role="tabpanel"
                                         aria-labelledby="headingSeven" aria-expanded="false" style="height: 0px;">
                                        <div class="panel-body">
                                            <div class="form-horizontal">
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="wali_name">Nama
                                                        Wali</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control">{{ @$wali->name }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="w_place_of_birth">Tempat
                                                        Lahir Wali</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control"> {{ @$wali['place_of_birth'] }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="w_date_of_birth">Tanggal
                                                        Lahir Wali</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control"> {{ @$wali->date_of_birth }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="w_addresss">Alamat
                                                        Wali</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control"> {{ @$wali['address'] }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="w_city">Kota Wali</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control"> {{ @$wali['city'] }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="w_region">Provinsi
                                                        Wali</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control"> {{ @$wali['region'] }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="w_country">Kewarganegaraan
                                                        Wali</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control"> {{ @$wali['country'] }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="w_religion">Agama
                                                        Wali</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control"> {{ @$wali['religion'] }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="w_job">Pekerjaan
                                                        Wali</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control">{{ @$wali['job'] }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="w_salary">Penghasilan
                                                        Wali</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control">{{ @$wali['salary'] }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="w_education">Pendidikan
                                                        Wali</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control">{{ @$wali['education'] }}</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="w_phone">Nomor Telepon
                                                        Wali</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control"> {{ @$wali['phone'] }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="panel panel-default panel-collapse">
                                <div class="panel-heading" role="tab" id="headingFour">
                                    <h4 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion"
                                           href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                            Data Image
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseFour" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="headingFour" aria-expanded="false">
                                    <div class="panel-body">
                                        <div class="form-horizontal">
                                            @if ($uploadsForm->get('payment_form'))
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="image">Bukti
                                                        Bayar</label>
                                                    <div class="col-sm-10">
                                                        @if($data->payment_option == 'BCA')
                                                            @if($data->payment_date != '')
                                                                @include('administrator.ppdb.partial.kwitansi', $data)
                                                            @else
                                                                @include('administrator.ppdb.partial.kwitansi_empty', $data)
                                                            @endif
                                                        @else
                                                            @if ($data->payment_form !== null)
                                                                <a href="{{ $data->getPaymentFormImageUrl() }}"
                                                                   target="_blank">
                                                                    <img src="{{ $data->getPaymentFormImageUrl() }}"
                                                                         alt="Bukti bayar"
                                                                         style="max-width: 300px; height: auto;">
                                                                </a>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                                <hr/>
                                            @endif

                                            @if ($uploadsForm->get('birth_certificate'))
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="image">Akta
                                                        Kelahiran</label>
                                                    <div class="col-sm-10">
                                                        @if ($data->birth_certificate !== null)
                                                            <a href="{{ $data->getBirtCertificateImageUrl() }}"
                                                               target="_blank">
                                                                <img src="{{ $data->getBirtCertificateImageUrl() }}"
                                                                     alt="Akta Kelahiran"
                                                                     style="max-width: 300px; height: auto;">
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <hr/>
                                            @endif

                                            @if ($uploadsForm->get('photo'))
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="image">Foto 3x4</label>
                                                    <div class="col-sm-10">
                                                        @if ($data->photo !== null)
                                                            <a href="{{ $data->getPhotoImageUrl() }}" target="_blank">
                                                                <img src="{{ $data->getPhotoImageUrl() }}"
                                                                     alt="Foto 3x4"
                                                                     style="max-width: 300px; height: auto;">
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <hr/>
                                            @endif

                                            @if ($uploadsForm->get('parent_identity_card'))
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="image">KTP Orang
                                                        Tua</label>
                                                    <div class="col-sm-10">
                                                        @if ($data->parent_identity_card !== null)
                                                            <a href="{{ $data->getParentIdentityCardImageUrl() }}"
                                                               target="_blank">
                                                                <img src="{{ $data->getParentIdentityCardImageUrl() }}"
                                                                     alt="KTP Orang Tua"
                                                                     style="max-width: 300px; height: auto;">
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <hr/>
                                            @endif

                                            @if ($uploadsForm->get('marriage_certificate'))
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="image">Akta Pernikahan
                                                        Orangtua</label>
                                                    <div class="col-sm-10">
                                                        @if ($data->marriage_certificate !== null)
                                                            <a href="{{ $data->getMarriageCertificateImageUrl() }}"
                                                               target="_blank">
                                                                <img src="{{ $data->getMarriageCertificateImageUrl() }}"
                                                                     alt="Akta Pernikahan Orangtua"
                                                                     style="max-width: 300px; height: auto;">
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <hr/>
                                            @endif

                                            @if ($uploadsForm->get('family_card'))
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="image">Kartu
                                                        Keluarga</label>
                                                    <div class="col-sm-10">
                                                        @if ($data->family_card !== null)
                                                            <a href="{{ $data->getFamilyCardImageUrl() }}"
                                                               target="_blank">
                                                                <img src="{{ $data->getFamilyCardImageUrl() }}"
                                                                     alt="Kartu Keluarga"
                                                                     style="max-width: 300px; height: auto;">
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <hr/>
                                            @endif

                                            @if ($uploadsForm->get('report_cards'))
                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="control-label col-sm-2" for="image">Raport</label>
                                                        @forelse (@$data->report_cards as $report)
                                                            @if ($loop->index % 2 === 0)
                                                    </div>
                                                    <div class="row" style="margin-top: 5px;">
                                                        <div class="col-sm-4 col-sm-offset-2">
                                                            @else
                                                                <div class="col-sm-4">
                                                                    @endif
                                                                    <a href="{{ $data->getRaportImageUrl($report) }}"
                                                                       target="_blank">
                                                                        <img
                                                                            src="{{ $data->getRaportImageUrl($report) }}"
                                                                            alt="Raport"
                                                                            style="max-width: 300px; height: auto;">
                                                                    </a>
                                                                </div>
                                                                @empty

                                                                @endforelse
                                                        </div>
                                                    </div>
                                                    <hr/>
                                                    @endif

                                                    @if ($uploadsForm->get('award_photo'))
                                                        <div class="form-group">
                                                            <label class="control-label col-sm-2" for="image">Piagam
                                                                Penghargaan</label>
                                                            <div class="col-sm-10">
                                                                @if ($data->award_photo !== null)
                                                                    <a href="{{ $data->getAwardPhotoImageUrl() }}"
                                                                       target="_blank">
                                                                        <img src="{{ $data->getAwardPhotoImageUrl() }}"
                                                                             alt="Piagam Penghargaan"
                                                                             style="max-width: 300px; height: auto;">
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <hr/>
                                                    @endif

                                                    @if ($uploadsForm->get('baptismal_certificate'))
                                                        <div class="form-group">
                                                            <label class="control-label col-sm-2" for="image">Kartu
                                                                Baptismal</label>
                                                            <div class="col-sm-10">
                                                                @if ($data->baptismal_certificate !== null)
                                                                    <a href="{{ $data->getBaptismalCertificateImageUrl() }}"
                                                                       target="_blank">
                                                                        <img
                                                                            src="{{ $data->getBaptismalCertificateImageUrl() }}"
                                                                            alt="Kartu Baptismal"
                                                                            style="max-width: 300px; height: auto;">
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <hr/>
                                                    @endif

                                                    @if ($uploadsForm->get('angket_peminatan'))
                                                        <div class="form-group">
                                                            <label class="control-label col-sm-2" for="image">Angket
                                                                Peminatan</label>
                                                            <div class="col-sm-10">
                                                                @if ($data->angket_peminatan !== null)
                                                                    <a href="{{ $data->getAngketPeminatanFileUrl() }}"
                                                                       target="_blank">
                                                                        <img
                                                                            src="{{ $data->getAngketPeminatanFileUrl() }}"
                                                                            alt="Angket Peminatan"
                                                                            style="max-width: 300px; height: auto;">
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <hr/>
                                                    @endif

                                                    @if ($uploadsForm->get('rekomendasi_bk'))
                                                        <div class="form-group">
                                                            <label class="control-label col-sm-2" for="image">Rekomendasi
                                                                BK</label>
                                                            <div class="col-sm-10">
                                                                @if ($data->rekomendasi_bk !== null)
                                                                    <a href="{{ $data->getRekomendasiBkImageUrl() }}"
                                                                       target="_blank">
                                                                        <img
                                                                            src="{{ $data->getRekomendasiBkImageUrl() }}"
                                                                            alt="Rekomendasi BK"
                                                                            style="max-width: 300px; height: auto;">
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <hr/>
                                                    @endif

                                                    @if ($uploadsForm->get('statement_letter'))
                                                        <div class="form-group">
                                                            <label class="control-label col-sm-2" for="image">Surat
                                                                Pernyataan</label>
                                                            <div class="col-sm-10">
                                                                @if ($data->statement_letter !== null)
                                                                    <a href="{{ $data->getStatementLetterFileUrl() }}"
                                                                       target="_blank">
                                                                        <img
                                                                            src="{{ $data->getStatementLetterFileUrl() }}"
                                                                            alt="Surat Pernyataan"
                                                                            style="max-width: 300px; height: auto;">
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <hr/>
                                                    @endif

                                                    @if ($data->potensi_kecerdasan_image !== null)
                                                        <div class="form-group">
                                                            <label class="control-label col-sm-2" for="image">Potensi
                                                                Kecerdasan</label>
                                                            <div class="col-sm-10">
                                                                <a href="{{ $data->getPotensiKecerdasanImageUrl() }}"
                                                                   target="_blank">
                                                                    <img
                                                                        src="{{ $data->getPotensiKecerdasanImageUrl() }}"
                                                                        alt="Potensi Kecerdasan"
                                                                        style="max-width: 300px; height: auto;">
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <hr/>
                                                    @endif

                                                    @if ($data->bakat_istimewa_image !== null)
                                                        <div class="form-group">
                                                            <label class="control-label col-sm-2" for="image">Bakat
                                                                Istimewa</label>
                                                            <div class="col-sm-10">
                                                                <a href="{{ $data->getBakatIstimewaImageUrl() }}"
                                                                   target="_blank">
                                                                    <img src="{{ $data->getBakatIstimewaImageUrl() }}"
                                                                         alt="Bakat Istimewa"
                                                                         style="max-width: 300px; height: auto;">
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <hr/>
                                                    @endif

                                                    @if ($data->kesiapan_psikis_image !== null)
                                                        <div class="form-group">
                                                            <label class="control-label col-sm-2" for="image">Kesiapan
                                                                Psikis</label>
                                                            <div class="col-sm-10">
                                                                <a href="{{ $data->getKesiapanPsikisImageUrl() }}"
                                                                   target="_blank">
                                                                    <img src="{{ $data->getKesiapanPsikisImageUrl() }}"
                                                                         alt="Kesiapan Psikis"
                                                                         style="max-width: 300px; height: auto;">
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <hr/>
                                                    @endif

                                                    @if ($data->kartu_golongan_darah !== null)
                                                        <div class="form-group">
                                                            <label class="control-label col-sm-2" for="image">Kartu
                                                                Golongan darah</label>
                                                            <div class="col-sm-10">
                                                                <a href="{{ $data->getKartuGolonganDarahImageUrl() }}"
                                                                   target="_blank">
                                                                    <img
                                                                        src="{{ $data->getKartuGolonganDarahImageUrl() }}"
                                                                        alt="Kartu Golongan Darah"
                                                                        style="max-width: 300px; height: auto;">
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <hr/>
                                                    @endif

                                                    @if ($data->kms !== null)
                                                        <div class="form-group">
                                                            <label class="control-label col-sm-2" for="image">Kartu
                                                                Menuju sehat (KMS)</label>
                                                            <div class="col-sm-10">
                                                                <a href="{{ $data->getKmsImageUrl() }}" target="_blank">
                                                                    <img src="{{ $data->getKmsImageUrl() }}"
                                                                         alt="Kartu Menuju Sehat"
                                                                         style="max-width: 300px; height: auto;">
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <hr/>
                                                    @endif
                                                </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end -->
                                <div class="panel panel-default panel-collapse">
                                    <div class="panel-heading" role="tab" id="headingFive">
                                        <h4 class="panel-title">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion"
                                               href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                                Data PPDB
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseFive" class="panel-collapse collapse" role="tabpanel"
                                         aria-labelledby="headingFive" aria-expanded="false">
                                        <div class="panel-body">
                                            <div class="form-horizontal">
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="register_number">Nomor
                                                        Registrasi</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control"> {{ @$data['register_number'] }}</div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="origin_school">Sekolah
                                                        Asal</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control"> {{ @$data['origin_school'] }}</div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="unit_name">Daftar pada
                                                        Unit</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control"> {{ @$data->unit->name }}</div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2"
                                                           for="period_name">Periode</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control"> {{ @$data->period->name }}</div>
                                                    </div>
                                                </div>

                                                @if ($data->class_option !== null)
                                                    <div class="form-group">
                                                        <label class="control-label col-sm-2" for="class_option">Pilihan
                                                            Kelas</label>
                                                        <div class="col-sm-10">
                                                            <div class="form-control"> {{ @$data->class_option }}</div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="created_at">Tanggal
                                                        Daftar</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-control"> {{ @$data->created_at }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end -->
                            </div>
                        </div>
                        <a href="{{route('admin.ppdb.index')}}" class="btn btn-warning">Back</a>
                    </div> <!-- /widget-content -->
                </div>
                <!-- End Panel -->
            </div>
            <!-- End Row -->
        </div>
        <!-- END CONTAINER -->
@endsection
