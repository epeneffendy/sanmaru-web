@extends('layouts.admin.main')
@section('content')
    @if(@$method=="edit")
        @php($action=route('admin.teacher.update',array($teacher->id)))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.teacher.insert'))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data Master Teacher</h1>
        <ol class="breadcrumb">
            <li>Master</li>
            <li><a href="{{route('admin.teacher.index')}}">Teacher</a></li>
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
                        <h3>{{$status_header}} Teacher</h3>
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
                        <form role="form" method="POST" action="{{$action}}" class="form-horizontal"
                              enctype="multipart/form-data">
                            <input type="hidden" value="{{@$teacher->id}}" name="id" />
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="nik">NIK:</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="nik" id="nik"
                                           value="{{@$teacher['nik']}}" placeholder="Enter NIK"
                                           required {{($status=="Update")?"readonly":""}}>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Nama:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name"
                                           value="{{@$teacher['name']}}" placeholder="Enter name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="email">Email:</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" name="email" id="email"
                                           value="{{@$teacher['email']}}" placeholder="Enter email" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="mobile_phone">Mobile Phone:</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="mobile_phone" id="mobile_phone"
                                           value="{{@$teacher['mobile_phone']}}"
                                           placeholder="Enter mobile phone number">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="address">Alamat:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="address" id="address"
                                              placeholder="Enter address" required>{{@$teacher['address']}}</textarea>
                                </div>
                            </div>
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
