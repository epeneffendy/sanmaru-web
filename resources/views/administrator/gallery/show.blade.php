@extends('layouts.admin.main')
@php($status="Show")
@php($status_header="Show")
@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Gallery</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li><a href="{{route('admin.gallery.index')}}">Gallery</a></li>
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
                        <h3>{{$status_header}} Gallery</h3>
                    </div> <!-- /widget-header -->
                    <div class="widget-content">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <img src="{{ $gallery->getContentImageUrl() }}" class="img-responsive img-thumbnail" /><br>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Judul:</label>
                                <div class="col-sm-10">
                                    {{ $gallery->title }}
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="unit">Deskripsi:</label>
                                
                                <div class="col-sm-10">
                                {{ $gallery->description }}    
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="period">Terakhir diperbarui:</label>
                                <div class="col-sm-10">
                                    {{ $gallery->updated_at }}
                
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Status:</label>
                                <div class="col-sm-10">
                                    {!! $gallery->published_label !!}
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <a href="{{ route('admin.gallery.index') }}" class="btn btn-primary">
                                        <i class="fa fa-arrow-left"></i>Back To List
                                    </a>
                                    <a href="{{ route('admin.gallery.edit', $gallery->id) }}" class="btn btn-default">
                                        <i class="fa fa-edit"></i>Edit</a>
                                    <a onclick="confirmDelete({{$gallery['id']}})" title="Delete" class="btn btn-danger">
                                        <i class="fa fa-trash"></i>Delete
                                    </a>
                                    <form id="form-delete-{{$gallery->id}}" action="{{ route('admin.gallery.delete',$gallery->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    
                                </div>
                            </div>
                            <!-- /bottom-wizard -->
                        </div>
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
    .img-preview {
        margin-bottom: 5px;
    }
@endpush
@push('scripts')
    <script>
        function confirmDelete(id) {
            if(confirm('Are you sure you want to delete this item?'))
                document.getElementById('form-delete-' + id).submit();
        }
    </script>
@endpush
