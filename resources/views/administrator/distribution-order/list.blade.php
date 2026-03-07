@extends('layouts.admin.main')
@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Distribusi Order</h1>
        <ol class="breadcrumb">
            <li>SHOP</li>
            <li class="active">Distribusi Order</li>
        </ol>
    </div>
    <!-- End Page Header -->

    <!-- START CONTAINER -->
    <div class="container-padding">
        <!-- Start Row -->
        <div class="row">

            <!-- Start Panel -->
            <div class="col-md-12">
                <div class="panel panel-default table-responsive">
                    <div class="panel-title">
                        Seragam Deadline
                    </div>

                    <div class="panel-body">
                        @if (session('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif
                        @if (session('errors'))
                            <div class="alert alert-danger">
                                {!! session('errors') !!}
                            </div>
                        @endif



                        <div class="fixed-table-head period">
                            <table id="datatables-uniform-deadline" class="table display">
                                <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Unit</th>
                                    <th class="text-center">Student</th>
                                    <th class="text-center">Distribution Date</th>
                                    <th class="text-center">Date Range Order</th>
                                    <th class="text-center">Description</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $key => $item)
                                    <tr>
                                        <td class="text-center">{{$key + 1}}</td>
                                        <td class="text-center">{{$item->unit->name}}</td>
                                        <td class="text-center">{{ strtoupper($item->type_student) }}</td>
                                        <td class="text-center">{{date('d-m-Y', strtotime($item->date))}}</td>
                                        <td class="text-center">{{$item->date_range}}</td>
                                        <td class="text-center">{{$item->description}}</td>
                                        <td class="text-center">
                                            @if($item->status == 'active')
                                                <label class="label label-warning">Aktif</label>
                                            @endif
                                            @if($item->status == 'send')
                                                <label class="label label-info">Terkirim</label>
                                            @endif
                                            @if($item->status == 'confirmed')
                                                <label class="label label-success">Terkonfirmasi</label>
                                            @endif
                                            @if($item->status == 'rejected')
                                                <label class="label label-danger">Batal</label>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if(($item->created_by == auth()->user()->id) && ($item->status == 'active'))
                                                <a href="{{route('admin.distribution-order.send',$item->id) }}" class="btn btn-xs btn-default">
                                                    <icon class="icon-plus"><i class="fa fa-paper-plane"></i></icon>
                                                </a>
                                            @endif
                                            @if((in_array($item->unit_id, auth()->user()->role_units)) && ($item->status == 'send'))
                                                <a href="{{route('admin.distribution-order.confirm',$item->id) }}" class="btn btn-xs btn-success">
                                                    <icon class="icon-plus"><i class="fa fa-check"></i></icon>
                                                </a>
                                            @endif
                                            @if(($item->created_by == auth()->user()->id) || (($item->status == 'send') || $item->status == 'confirmed') )
                                                <a href="{{route('admin.distribution-order.export',$item->id) }}" class="btn btn-xs btn-info">
                                                    <icon class="icon-plus"><i class="fa fa-file-excel-o"></i></icon>
                                                </a>
                                                 <a href="{{route('admin.distribution-order.pdf',$item->id) }}" class="btn btn-xs btn-warning">
                                                    <icon class="icon-plus"><i class="fa fa-file-word-o"></i></icon>
                                                </a>   
                                            @endif

                                            @if((in_array($item->unit_id, auth()->user()->role_units)) && ($item->status == 'confirmed'))
                                            
                                            @endif

                                            @if(($item->created_by == auth()->user()->id) && ($item->status == 'active'))
                                                <a href="{{route('admin.distribution-order.delete',$item->id) }}" class="btn btn-xs btn-danger"
                                                   onclick="return confirm('Are you sure you want to change it to inactive?');">
                                                    <icon class="icon-plus"><i class="fa fa-trash"></i></icon>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if(Auth::user()->type == 'admin' || Auth::user()->type == 'super_admin')
                            <div class="btn-group padding-t-10 pull-right">
                                <a href="{{ route('admin.distribution-order.add') }}" class="btn btn-sm btn-success">
                                    <i class="fa fa-plus"></i> Tambah Data
                                </a>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{asset('css/plugin/datatables/datatables.css')}}">
    <style>
        .button-collection {
            margin-bottom: 5px;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{asset('js/datatables/datatables.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#datatables-uniform-deadline').DataTable();
        });
    </script>
@endpush
