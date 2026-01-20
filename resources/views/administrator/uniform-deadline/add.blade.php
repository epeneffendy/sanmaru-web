@extends('layouts.admin.main')
@section('content')
    @if(@$status=="edit")
        @php($action=route('admin.uniform-deadline.update',array($deadline['id'])))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.uniform-deadline.insert'))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data Deadline Seragam</h1>
        <ol class="breadcrumb">
            <li>Shop</li>
            <li><a href="{{route('admin.uniform-deadline.index')}}">Deadline Seragam</a></li>
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
                        <h3>{{$status_header}} Data</h3>
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
                            <input type="hidden" value="{{@deadline['id']}}" name="id" />

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Unit:</label>
                                <div class="col-sm-10">
                                    <select name="unit_id" id="unit_id" class="form-control input-sm">
                                        <option value="0">== SEMUA ==</option>
                                        @foreach (@$units as $unit)
                                            <option
                                                value="{{ $unit->id }}" {{ $unit->id == @$deadline['unit_id'] ? 'selected' : NULL }}>{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Tahun Pelajaran:</label>
                                <div class="col-sm-10">
                                    <select name="school_year" id="school_year" class="form-control input-sm">
                                        @php($y = date('Y') + 1)
                                        <option
                                            value="all" {{ (@$deadline['school_year'] == 'all') ? 'selected' : NULL }}>
                                            == SEMUA ==
                                        </option>
                                        @for($i = 2020; $i <= $y; $i++)
                                            <option value="{{ $i }}" {{empty($deadline) ?  ($i == $y) ? 'selected' : '' : (($deadline['school_year'] == $i) ? 'selected' : '') }}>{{ $i }} - {{ $i + 1 }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Deadline:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="uniform_payment_deadline" id="uniform_payment_deadline"
                                           value="{{old('uniform_payment_deadline') ?? @$deadline['uniform_payment_deadline']}}" placeholder="Enter Deadline" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-default">{{$status}}</button>
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
