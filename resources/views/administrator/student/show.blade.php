@extends('layouts.admin.main')
@section('content')
@php($action=route('admin.student.show',array($student['id'])))
@php($status="Show")
@php($status_header="Show")
@php($uploadsForm = \App\Helpers\InputCollectionHelper::uploads(null, $student->class))
@php($additionalData = \App\Helpers\InputCollectionHelper::additionalData(null, $student->class))
@php($additionalData = $additionalData->merge(['gender' => 0,'place_of_birth' => 0,'date_of_birth' => 0,'city' => 0,'region' => 0,'country' => 0,'religion' => 0]))
@php($data = $student->additionalData);
<!-- Start Page Header -->
<div class="page-header">
    <h1 class="title">Data Master Student</h1>
    <ol class="breadcrumb">
        <li>Master</li>
        <li><a href="{{route('admin.student.index')}}">Student</a></li>
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
                    <h3>{{$status_header}} Student</h3>
                </div> <!-- /widget-header -->
                <div class="widget-content">
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-default panel-collapse">
                          <div class="panel-heading" role="tab" id="headingOne">
                            <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Data Diri
                              </a>
                            </h4>
                          </div>
                          <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne" aria-expanded="true">
                            <div class="panel-body">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="nis">Status:</label>
                                        <div class="col-sm-10" style="margin-top: 10px;">
                                            {!! $student->status_label !!}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="nis">NIS:</label>
                                        <div class="col-sm-10">
                                            {{ @$student->nis }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="name">Nama:</label>
                                        <div class="col-sm-10">
                                            {{ @$student->name }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="email">Email:</label>
                                        <div class="col-sm-10">
                                            {{ @$student->email }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="mobile_phone">Mobile Phone:</label>
                                        <div class="col-sm-10">
                                            {{ @$student->mobile_phone }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="address">Alamat:</label>
                                        <div class="col-sm-10">
                                            {{ @$student->address }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="unit_id">Class:</label>
                                        <div class="col-sm-10">
                                            {{ @$student->class->name }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="unit_id">Unit:</label>
                                        <div class="col-sm-10">
                                            {{ @$student->class->unit->name }} [{{ @$student->class->unit->unit_code }}]
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="school_year">Tahun</label>
                                        <div class="col-sm-10">
                                            {{ @$student->school_year }}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="school_year">No Registrasi PPDB</label>
                                        <div class="col-sm-10">
                                            {{ @$student->register_number }}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="image">Foto</label>
                                        <div class="col-sm-10">
                                            @if ($student->image_path !== null)
                                                <img src="{{ \App\Helpers\ImageHelper::imageUrl($student->image_path) }}" alt="{{@$student->name}}" height="200">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>

                        <div class="panel panel-default panel-collapse">
                          <div class="panel-heading" role="tab" id="headingSix">
                            <h4 class="panel-title">
                              <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                Data Tambahan
                              </a>
                            </h4>
                          </div>
                          <div id="collapseSix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSix" aria-expanded="false">
                            <div class="panel-body">
                                <div class="form-horizontal">
                                    @foreach ($additionalData as $k => $v)
                                    <div class="form-group">
                                        <label class="control-label col-sm-4">
                                            @if ($k == 'npwp')
                                                {{ strtoupper($k) }}
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

                        @if (@$data->tinggal_dengan != 'wali')
                        @php($dad = @$student->parents->filter(function($query) {return $query->type == 'father';})->first())
                        <div class="panel panel-default panel-collapse">
                          <div class="panel-heading" role="tab" id="headingTwo">
                            <h4 class="panel-title">
                              <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Data Ayah
                              </a>
                            </h4>
                          </div>
                          <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo" aria-expanded="false" style="height: 0px;">
                            <div class="panel-body">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="father_name">Nama Ayah</label>
                                        <div class="col-sm-10">
                                            <div class="form-control">{{ @$dad->name }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="f_place_of_birth">Tempat Lahir Ayah</label>
                                        <div class="col-sm-10">
                                            <div class="form-control"> {{ @$dad['place_of_birth'] }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="f_date_of_birth">Tanggal Lahir Ayah</label>
                                        <div class="col-sm-10">
                                            <div class="form-control"> {{ @$dad->date_of_birth }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="f_addresss">Alamat Ayah</label>
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
                                        <label class="control-label col-sm-2" for="f_region">Provinsi Ayah</label>
                                        <div class="col-sm-10">
                                            <div class="form-control"> {{ @$dad['region'] }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="f_country">Kewarganegaraan Ayah</label>
                                        <div class="col-sm-10">
                                            <div class="form-control"> {{ @$dad['country'] }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="f_religion">Agama Ayah</label>
                                        <div class="col-sm-10">
                                            <div class="form-control"> {{ @$dad['religion'] }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="f_job">Pekerjaan Ayah</label>
                                        <div class="col-sm-10">
                                            <div class="form-control">{{ @$dad['job'] }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="f_salary">Penghasilan Ayah</label>
                                        <div class="col-sm-10">
                                            <div class="form-control">{{ @$dad['salary'] }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="f_education">Pendidikan Ayah</label>
                                        <div class="col-sm-10">
                                            <div class="form-control">{{ @$dad['education'] }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="f_phone">Nomor Telepon Ayah</label>
                                        <div class="col-sm-10">
                                            <div class="form-control"> {{ @$dad['phone'] }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          </div>
                        </div>
                        @php($mom = @$student->parents->filter(function($query) {return $query->type == 'mother';})->first())
                        <div class="panel panel-default panel-collapse">
                          <div class="panel-heading" role="tab" id="headingThree">
                            <h4 class="panel-title">
                              <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Data Ibu
                              </a>
                            </h4>
                          </div>
                          <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree" aria-expanded="false">
                            <div class="panel-body">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="mother_name">Nama Ibu</label>
                                        <div class="col-sm-10">
                                            <div class="form-control"> {{ @$mom['name'] }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="m_place_of_birth">Tempat Lahir Ibu</label>
                                        <div class="col-sm-10">
                                            <div class="form-control"> {{ @$mom['place_of_birth'] }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="m_date_of_birth">Tanggal Lahir Ibu</label>
                                        <div class="col-sm-10">
                                            <div class="form-control"> {{ @$mom->date_of_birth }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="m_address">Alamat Ibu</label>
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
                                        <label class="control-label col-sm-2" for="m_region">Propinsi Ibu</label>
                                        <div class="col-sm-10">
                                            <div class="form-control"> {{ @$mom['region'] }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="m_country">Kewarganegaraan Ibu</label>
                                        <div class="col-sm-10">
                                            <div class="form-control"> {{ @$mom['country'] }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="m_religion">Agama Ibu</label>
                                        <div class="col-sm-10">
                                            <div class="form-control"> {{ @$mom['religion'] }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="m_job">Pekerjaan Ibu</label>
                                        <div class="col-sm-10">
                                            <div class="form-control"> {{ @$mom['job'] }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="m_salary">Penghasilan Ibu</label>
                                        <div class="col-sm-10">
                                            <div class="form-control">{{ @$mom['salary'] }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="w_education">Pendidikan Ibu</label>
                                        <div class="col-sm-10">
                                            <div class="form-control">{{ @$mom['education'] }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="m_phone">Nomor Telepon Ibu</label>
                                        <div class="col-sm-10">
                                            <div class="form-control"> {{ @$mom['phone'] }} </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          </div>
                        </div>
                        @else
                        @php($wali = @$student->parents->filter(function($query) {return $query->type == 'wali';})->first())
                        <div class="panel panel-default panel-collapse">
                          <div class="panel-heading" role="tab" id="headingSeven">
                            <h4 class="panel-title">
                              <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                Data Wali
                              </a>
                            </h4>
                          </div>
                          <div id="collapseSeven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSeven" aria-expanded="false" style="height: 0px;">
                            <div class="panel-body">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="wali_name">Nama Wali</label>
                                        <div class="col-sm-10">
                                            <div class="form-control">{{ @$wali->name }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="w_place_of_birth">Tempat Lahir Wali</label>
                                        <div class="col-sm-10">
                                            <div class="form-control"> {{ @$wali['place_of_birth'] }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="w_date_of_birth">Tanggal Lahir Wali</label>
                                        <div class="col-sm-10">
                                            <div class="form-control"> {{ @$wali->date_of_birth }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="w_addresss">Alamat Wali</label>
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
                                        <label class="control-label col-sm-2" for="w_region">Provinsi Wali</label>
                                        <div class="col-sm-10">
                                            <div class="form-control"> {{ @$wali['region'] }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="w_country">Kewarganegaraan Wali</label>
                                        <div class="col-sm-10">
                                            <div class="form-control"> {{ @$wali['country'] }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="w_religion">Agama Wali</label>
                                        <div class="col-sm-10">
                                            <div class="form-control"> {{ @$wali['religion'] }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="w_job">Pekerjaan Wali</label>
                                        <div class="col-sm-10">
                                            <div class="form-control">{{ @$wali['job'] }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="w_salary">Penghasilan Wali</label>
                                        <div class="col-sm-10">
                                            <div class="form-control">{{ @$wali['salary'] }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="w_education">Pendidikan Wali</label>
                                        <div class="col-sm-10">
                                            <div class="form-control">{{ @$wali['education'] }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="w_phone">Nomor Telepon Wali</label>
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
                          <div class="panel-heading" role="tab" id="headingImage">
                            <h4 class="panel-title">
                              <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseImage" aria-expanded="false" aria-controls="collapseImage">
                                Data Image
                              </a>
                            </h4>
                          </div>
                          <div id="collapseImage" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingImage" aria-expanded="false">
                            <div class="panel-body">
                                <div class="form-horizontal">
                                    @foreach ($uploadsForm as $k => $v)
                                    <div class="form-group">
                                        <label class="control-label col-sm-2">{{ ucwords(str_replace('_', ' ', $k)) }}</label>
                                        <div class="col-sm-10">
                                            @if ($data[$k])
                                            <a href="{{ \App\Helpers\ImageHelper::imageUrl($data[$k]) }}" target="_blank">
                                                <img src="{{ \App\Helpers\ImageHelper::imageUrl($data[$k]) }}" alt="{{ ucwords(str_replace('_', ' ', $k)) }}" style="max-width: 300px; height: auto;">
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                          </div>
                        </div>

                    </div>
                </div>
                <a href="{{route('admin.student.index')}}" class="btn btn-warning">Back</a>
            </div> <!-- /widget-content -->
        </div>
        <!-- End Panel -->

    </div>
    <!-- End Row -->

</div>
<!-- END CONTAINER -->
@endsection
