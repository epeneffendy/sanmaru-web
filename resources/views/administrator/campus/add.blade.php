@extends('layouts.admin.main')
@section('content')
    @if(@$status=="edit")
        @php($action=route('admin.campus.update',array($campus['id'])))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.campus.insert'))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Manage Kampus</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li><a href="{{route('admin.campus.index')}}">Manage Kampus</a></li>
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
                        <h3>{{$status_header}} Kampus</h3>
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
                            <input type="hidden" name="id" value="{{@$campus->id}}" />
                            
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Nama Kampus:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" value="{{old('name', @$campus['name'])}}" placeholder="Nama Kampus" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="description">Deskripsi:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="description" placeholder="deskripsi (optional)" id="description">{{old('description', @$campus['description'])}}</textarea>
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

