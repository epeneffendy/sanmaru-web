@extends('layouts.admin.main')
@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data Master Event</h1>
        <ol class="breadcrumb">
            <li>Master</li>
            <li class="active">Event</li>
        </ol>
    </div>
    <!-- End Page Header -->

    <!-- START CONTAINER -->
    <div class="container-padding">
        <!-- Start Row -->
        <div class="row">
            <!-- Start Panel -->
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-title">
                        Data Master Event
                    </div>
                    <div class="panel-body table-responsive">
                        @if (session('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif
                        @if (session('errors'))
                            <div class="alert alert-danger">
                                {!! session('errors')->first() !!}
                            </div>
                        @endif
                        
                        <div class="fixed-table-head period">
                            <table id="datatables-master-event" class="table display">
                                <thead>
                                    <tr>
                                        <th width="30px">ID</th>
                                        <th>Title</th>
                                        <th width="150px">Date</th>
                                        <th width="100px">Status</th>
                                        <th width="200px">Action</th>
                                    </tr>
                                </thead>
    
                                <tbody>
                                @foreach($events as $key => $value)
                                    <tr>
                                        <td data-id="{{$value->id}}">{{ ($key+1) }}</td>
                                        <td>{{ $value->title }}</td>
                                        <td>{{ date('j F Y H:i', strtotime($value->event_time)) }}</td>
                                        <td><span class="label {{ $value->isPublished() ? 'label-success' : 'label-danger'}}">{{ $value->status }}</label></td>
                                        <td class="dt-body-right dt-option">
                                            <a href="{{ route('admin.event.toggle',$value->id) }}" class="btn btn-xs {{ $value->isPublished() ? 'btn-warning' : 'btn-info'}}">
                                                <icon class="icon-plus">
                                                    {!! $value->isPublished() ? '<i class="fa fa-toggle-off"></i> Unpublish' : '<i class="fa fa-toggle-on"></i> Publish' !!}
                                                </icon>
                                            </a>
                                            <a href="{{ route('admin.event.edit',$value->id) }}" class="btn btn-xs btn-default">
                                                <icon class="icon-plus"><i class="fa fa-pencil"></i></icon>
                                            </a>
                                            <a href="{{ route('admin.event.delete',$value->id) }}" class="btn btn-xs btn-danger"
                                               onclick="return confirm('Are you sure you want to delete this item?');">
                                                <icon class="icon-plus"><i class="fa fa-trash"></i></icon>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="btn-group padding-t-10 pull-right">
                            <a href="{{ route('admin.event.add') }}" class="btn btn-sm btn-success">
                                <i class="fa fa-plus"></i> Tambah Data
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Panel -->
        </div>
        <!-- End Row -->
    </div>
    <!-- END CONTAINER -->
@endsection
@push('styles')
    <link rel="stylesheet" href="{{asset('css/plugin/datatables/datatables.css')}}">
    <style>
        .dt-option .btn-info{
            width: 90px;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{asset('js/datatables/datatables.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#datatables-master-event').DataTable();
        });
    </script>
@endpush
