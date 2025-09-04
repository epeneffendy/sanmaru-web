@extends('layouts.admin.main')
@section('content')
    @if (@$method == "edit")
        @php($action=route('admin.course.update', array($course->id) ))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.course.insert'))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data Master Course</h1>
        <ol class="breadcrumb">
            <li>Master</li>
            <li><a href="{{ route('admin.course.index') }}">Course</a></li>
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
                        <h3>{{ $status_header }} Course</h3>
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
                        <form role="form" method="POST" action="{{ $action }}" class="form-horizontal" enctype="multipart/form-data">
                            @if (@$course)
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="status">Status:</label>
                                <div class="col-sm-10">
                                    <strong class="badge" style="margin-top: 10px; background-color: {{ $course->isActive() ? '#5cc45e' : '#b94a48' }}">{{ $course->isActive() ? 'Active' : 'Inactive' }}</strong>
                                </div>
                            </div>
                            @endif
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="code">Unit:</label>
                                <div class="col-sm-10">
                                    <select name="unit_id" id="unit" class="form-control">
                                        <option value="">--Select Unit--</option>
                                        @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}"{{ @$course->unit_id == $unit->id ? ' selected' : '' }}>{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="code">Course Code:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="code" id="code"
                                           value="{{ @$course->code }}" placeholder="Enter Course Code"
                                           required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Course Name:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name"
                                           value="{{ @$course->name }}" placeholder="Enter name" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-default">{{ $status }}</button>
                                    <a href="{{ route('admin.course.index')}}" class="btn btn-danger">Cancel</a>
                                    @if (@$course)
                                    <a href="{{ route('admin.course.toggle',$course) }}" class="btn {{ $course->isActive() ? 'btn-warning' : 'btn-info'}}">{{ $course->isActive() ? 'Deaktivasi' : 'Aktivasi' }}</a>
                                    @endif
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
