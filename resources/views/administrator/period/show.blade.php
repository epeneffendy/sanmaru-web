@extends('layouts.admin.main')
@section('content')
@php($status=="Show")
@php($status_header="Show")

    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Setting Period</h1>
        <ol class="breadcrumb">
            <li>PPDB</li>
            <li><a href="{{route('admin.period.index')}}">Setting Period</a></li>
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
                        <h3>{{$status_header}} Setting Period</h3>
                    </div> <!-- /widget-header -->
                    <div class="widget-content">
                        <div class="form-horizontal">
                            <div class="form-group">
                                
                                <label class="control-label col-sm-2" for="name">Name:</label>
                                <div class="col-sm-10">
                                    {{ $period->name }}
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="unit">Unit:</label>
                                
                                <div class="col-sm-10">
                                {{ $period->unit->name }}    
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="period">Period:</label>
                                <div class="col-sm-10">
                                    {{ $period->period }}
                
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Status:</label>
                                <div class="col-sm-10">
                                    {!! $period->active_label !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="quota">Kuota:</label>
                                <div class="col-sm-10">
                                    {{ $period->quota }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="start_register_number">Nomor Registrasi Awal:</label>
                                <div class="col-sm-10">
                                    {{ $period->start_register_number }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="description">Description:</label>
                                <div class="col-sm-10">
                                    {!! $period->description !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <a href="{{ route('admin.period.index') }}" class="btn btn-primary">
                                        <i class="fa fa-arrow-left"></i>Back To List
                                    </a>
                                    <a href="{{ route('admin.period.edit', $period->id) }}" class="btn btn-default">
                                        <i class="fa fa-edit"></i>Edit</a>
                                    <a onclick="confirmDelete({{$period['id']}})" title="Delete" class="btn btn-danger">
                                        <i class="fa fa-trash"></i>Delete
                                    </a>
                                    <form id="form-delete-{{$period->id}}" action="{{ route('admin.period.delete',$period->id) }}" method="POST">
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
