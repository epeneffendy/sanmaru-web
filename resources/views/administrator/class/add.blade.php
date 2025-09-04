@extends('layouts.admin.main')
@section('content')
    @if (@$method == "edit")
        @php($action=route('admin.class.update', array($class->id) ))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.class.insert'))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data Master Class</h1>
        <ol class="breadcrumb">
            <li>Master</li>
            <li><a href="{{ route('admin.class.index') }}">Class</a></li>
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
                        <h3>{{ $status_header }} Class</h3>
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
                                    <input type="text" class="form-control" name="name" id="name" value="{{ old('name', @$class->name) }}" placeholder="Enter name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="unit_class">Unit Class:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="unit_class" id="unit_class" value="{{ old('unit_class', @$class->unit_class) }}" placeholder="Enter Unit Class" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="unit_id">Unit:</label>
                                <div class="col-sm-10">
                                    <select name="unit_id" class="form-control" id="unit_id">
                                        @foreach ($unitList as $key => $unit)
                                            <option value="{{ $key }}" {{ (old('unit_id', @$class->unit_id) === $key) ? 'selected' : NULL }}>{{ $unit }}</option>
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
