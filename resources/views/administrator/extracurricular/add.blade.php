@extends('layouts.admin.main')
@section('content')
    @if (@$method == "edit")
        @php($action=route('admin.extracurricular.update', array($extracurricular->id) ))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.extracurricular.insert'))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data Master Extracurricular</h1>
        <ol class="breadcrumb">
            <li>Master</li>
            <li><a href="{{ route('admin.extracurricular.index') }}">Extracurricular</a></li>
            <li class="active">{{ $status_header }}</li>
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
                        <h3>{{ $status_header }} Extracurricular</h3>
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
                        <form role="form" method="POST" action="{{$action}}"  class="form-horizontal" enctype="multipart/form-data">
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Nama:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name" value="{{ old('name', @$extracurricular->name) }}" placeholder="Enter name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="code">Code:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="code" id="code" value="{{ old('code', @$extracurricular->code) }}" placeholder="Enter Extracurricular Code" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="class_id">Class:</label>
                                <div class="col-sm-10">
                                    <select name="class_id" class="form-control" id="class_id">
                                        @foreach ($classList as $key => $class)
                                            <option value="{{ $key }}" {{ (old('class_id', @$subject->class_id) === $key) ? 'selected' : NULL }}>{{ $class }}</option>
                                        @endforeach
                                    </select>
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
