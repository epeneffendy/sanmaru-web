@extends('layouts.admin.main')
@section('content')
@php($status=="Show")
@php($status_header="Show")

    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Setting Beasiswa</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li><a href="{{route('admin.scholarship.index')}}">Setting Beasiswa</a></li>
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
                        <h3>{{$status_header}} Setting Beasiswa</h3>
                    </div> <!-- /widget-header -->
                    <div class="widget-content">
                        <div class="form-horizontal">
                            <div class="form-group">
                                
                                <label class="control-label col-sm-2" for="name">Name:</label>
                                <div class="col-sm-10">
                                    {{ $scholarship->name }}
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="unit">Unit:</label>
                                
                                <div class="col-sm-10">
                                {{ ($scholarship->is_unit) ? $scholarship->unit->name : 'Kampus' }}    
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="description">Deskripsi:</label>
                                <div class="col-sm-10">
                                    {!! $scholarship->html_description !!}
                
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Tanggal Publish:</label>
                                <div class="col-sm-10">
                                    {{ date('j F Y H:i' , strtotime($scholarship->publish_date)) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="status">Status:</label>
                                <div class="col-sm-10">
                                    {!! $scholarship->published_label !!}
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <a href="{{ route('admin.scholarship.index') }}" class="btn btn-primary">
                                        <i class="fa fa-arrow-left"></i>Back To List
                                    </a>
                                    <a href="{{ route('admin.scholarship.edit', $scholarship->id) }}" class="btn btn-default">
                                        <i class="fa fa-edit"></i>Edit</a>
                                    <a onclick="confirmDelete({{$scholarship['id']}})" title="Delete" class="btn btn-danger">
                                        <i class="fa fa-trash"></i>Delete
                                    </a>
                                    <form id="form-delete-{{$scholarship->id}}" action="{{ route('admin.scholarship.delete',$scholarship->id) }}" method="POST">
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
@push('scripts')
    <script>
        function confirmDelete(id) {
            if(confirm('Are you sure you want to delete this item?'))
                document.getElementById('form-delete-' + id).submit();
        }
    </script>
@endpush
