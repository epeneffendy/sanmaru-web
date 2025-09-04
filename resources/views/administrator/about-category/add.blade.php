@extends('layouts.admin.main')
@section('content')
    @if(@$status=="edit")
        @php($action=route('admin.about.category.update',array($aboutCategory['slug'])))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.about.category.insert'))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Setting About Kategori</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li><a href="{{route('admin.about.category.index')}}">Setting About Kategori</a></li>
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
                        <h3>{{$status_header}} About Kategori</h3>
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
                            <input type="hidden" name="id" value="{{@$aboutCategory->id}}" />
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Nama:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name" value="{{old('name', @$aboutCategory['name'])}}" placeholder="Masukkan nama kategori" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="active"> Jadikan Aktif?:</label>
                                <div class="col-sm-10">
                                    <input type="checkbox" name="active" value="1" class="custom-control-input" id="active" {{old('active', @$aboutCategory['active']) ? 'checked' : ''}}>
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
