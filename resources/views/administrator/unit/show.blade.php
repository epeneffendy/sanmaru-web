@extends('layouts.admin.main')
@section('content')
    @php($status="Show")
    @php($status_header="Show")

    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data Master Unit</h1>
        <ol class="breadcrumb">
            <li>Master</li>
            <li><a href="{{route('admin.unit.index')}}">Unit</a></li>
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
                        <h3>{{$status_header}} Unit</h3>
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
                        <form role="form" method="POST" class="form-horizontal" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="{{@$unit->id}}" />
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="unit_code">Unit Code:</label>
                                <div class="col-sm-10">
                                    <div class="form-control"> {{@$unit['unit_code']}} </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Unit Name:</label>
                                <div class="col-sm-10">
                                    <div class="form-control"> {{@$unit['name']}} </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="address">Address:</label>
                                <div class="col-sm-10">
                                    <div class="form-control"> {{@$unit['address']}} </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="city">City:</label>
                                <div class="col-sm-10">
                                    <div class="form-control"> {{@$unit['city']}} </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="email">Email:</label>
                                <div class="col-sm-10">
                                    <div class="form-control"> {{@$unit['email']}} </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="phone">Phone:</label>
                                <div class="col-sm-10">
                                    <div class="form-control"> {{@$unit['phone']}} </div>
                                </div>
                            </div>

                            <hr>

                            <div class="form-group">
                                <div class="control-label col-sm-2"><h3>Profile Unit</h3></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="about">About:</label>
                                <div class="col-sm-10">
                                    <div class="col-sm-10">
                                        <div class="form-control" style="height: auto"> {!!@$unit['about']!!}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="keunggulan">Keunggulan:</label>
                                <div class="col-sm-10">
                                    <div class="col-sm-10">
                                        <div class="form-control" style="height: auto"> {!!@$unit['keunggulan']!!}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="image_path">Image:</label>
                                <div class="col-sm-10">
                                    <div class="preview-image {{ @$unit->image_path !== null ? NULL : 'hide' }}">
                                        <img class="responsive" src="{{ @$unit->image }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="banner_path">Banner:</label>
                                <div class="col-sm-10">
                                    <div class="preview-banner {{ @$unit->banner_path !== null ? NULL : 'hide' }}">
                                        <img class="responsive" src="{{ @$unit->banner }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="keunggulan_path">Keunggulan:</label>
                                <div class="col-sm-10">
                                    <div class="preview-keunggulan {{ @$unit->keunggulan_path !== null ? NULL : 'hide' }}">
                                        <img class="responsive" src="{{ @$unit->keunggulan_image_path }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="phone">Phone:</label>
                                <div class="col-sm-10">
                                    <div class="form-control"> {{@$unit['phone']}} </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="header_info">Header Info (untuk bukti pendaftaran):</label>
                                <div class="col-sm-10">
                                    <div class="form-control"> {{@$unit['header_info']}} </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="procedure">Prosedur:</label>
                                <div class="col-sm-10">
                                    <div class="col-sm-10">
                                        <div class="form-control" style="height: auto"> {!!@$unit['procedure']!!}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="costs">Costs:</label>
                                <div class="col-sm-10">
                                    <div class="col-sm-10">
                                        <table class="table table-bordered table-condensed">
                                            <tr>
                                                <td>Title</td>
                                                <td>Description</td>
                                            </tr>
                                            @forelse($unit->costs as $cost)
                                                <tr>
                                                    <td>{{ $cost->title }}</td>
                                                    <td>{{ $cost->description }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="2">Cost is not exists.</td>
                                                </tr>
                                            @endforelse
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="form-group">
                                <div class="control-label col-sm-2"><h3>Testimoni</h3></div>
                            </div>
                            <div class="form-group" id="testimoni_form">
                            @if (@$unit->testimonies)
                                @foreach ($unit->testimonies as $testimony)
                                    <div class="col-sm-12" id="testimoni{{ $loop->index }}">
                                    <div class="box">
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="subject">Subject:</label>
                                            <div class="col-sm-10">
                                                <div class="form-control"> {{ @$testimony->subject }} </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="job">Job:</label>
                                            <div class="col-sm-10">
                                                <div class="form-control"> {{ @$testimony->job }} </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="content">Content</label>
                                            <div class="col-sm-10">
                                                <div class="form-control" style="height: auto"> {{@$testimony['content']}}</div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                           <label class="control-label col-sm-2" for="photo_path">Photo:</label>
                                            <div class="col-sm-10">
                                                <div class="preview-photo {{ @$testimony->photo_path !== null ? NULL : 'hide' }}">
                                                    <img class="responsive" src="{{ \App\Helpers\ImageHelper::imageUrl($testimony->photo_path) }}" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                @endforeach
                            @endif
                            </div>

                            <fieldset>
                                <legend>Peraturan Pendaftaran</legend>
                                <div class="form-group">
                                    <label class="control-label col-sm-2">Pembayaran:</label>
                                    <div class="col-sm-10">
                                        <div>
                                            Bank: {{ \App\Helpers\PriceHelper::paymentInfo($unit)['bank'] }}<br/>
                                            Kode Biller: {{ \App\Helpers\PriceHelper::paymentInfo($unit)['kode_biller'] }}<br/>
                                            Kode Bank: {{ \App\Helpers\PriceHelper::paymentInfo($unit)['kode_bank'] }}<br/>
                                        </div>
                                    </div>
                                </div>


                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <a href="{{ route('admin.unit.index')}}" class="btn btn-danger">Back</a>
                                </div>
                            </div>
                            <!-- /bottom-wizard -->
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

@push('styles')
    @parent
    <link rel="stylesheet" href="{{asset('css/plugin/bootstrap-select/bootstrap-select.css')}}">
    <style>
        .preview-image, .preview-banner, .preview-photo, .preview-keunggulan {
            margin-top: 5px;
            border: 1px solid #333333;
            padding: 10px;
            width: 200px;
        }

        .preview-image img,
        .preview-banner img,
        .preview-photo img,
        .preview-keunggulan img {
            width: 100%;
            height: auto;
        }

        .box{
            border:1px solid #ccc;
            padding-top: 20px;
            margin-top: 20px;
        }

        #delete_form {
            margin-right: 20px;
        }

    </style>
@endpush
@push('scripts')
    @parent
    <script src="{{asset('js/bootstrap-select/bootstrap-select.js')}}"></script>
@endpush
