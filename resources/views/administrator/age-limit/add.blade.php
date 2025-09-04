@extends('layouts.admin.main')
@section('content')
    @if(@$status=="edit")
        @php($action=route('admin.age-limit.update',array($ageLimit['id'])))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.age-limit.insert'))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Setting Age Limit</h1>
        <ol class="breadcrumb">
            <li>PPDB</li>
            <li><a href="{{route('admin.age-limit.index')}}">Setting Age Limit</a></li>
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
                        <h3>{{$status_header}} Setting Age Limit</h3>
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
                            @if (@$status == 'Update')
                                @method('PATCH')
                            @endif
                            <input type="hidden" name="id" value="{{@$ageLimit->id}}" />
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Name:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name" value="{{old('name', @$ageLimit['name'])}}" placeholder="Input a name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="description">Description:</label>
                                <div class="col-sm-10">
                                    <textarea name="description" rows="5" class="form-control" placeholder="Description">{{old('description', @$ageLimit['description'])}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="year">Year:</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="year" id="year" value="{{old('year', @$ageLimit['year'])}}" placeholder="Input Year (example: 12)" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="month">Month:</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="month" id="month" value="{{old('month', @$ageLimit['month'])}}" placeholder="Input Month (example: 10)" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="max_year">Max Year:</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="max_year" id="max_year" value="{{old('max_year', @$ageLimit['max_year'])}}" placeholder="Input Max Year (example: 12)" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="max_month">Max Month:</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="max_month" id="max_month" value="{{old('max_month', @$ageLimit['max_month'])}}" placeholder="Input Max Month (example: 10)" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="active">Set to Active?:</label>
                                <div class="col-sm-10">
                                    <input type="checkbox" name="active" class="custom-control-input" id="active" {{old('active', @$ageLimit['active']) ? 'checked' : ''}}>
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
