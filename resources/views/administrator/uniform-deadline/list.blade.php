@extends('layouts.admin.main')
@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Seragam Deadline</h1>
        <ol class="breadcrumb">
            <li>SHOP</li>
            <li class="active">Seragam Deadline</li>
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
                                            <th class="text-center">Tahun Ajaran</th>
                                            <th class="text-center">Deadline</th>
                                            <th class="text-center">Aktif</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($deadlines as $key => $item)
                                        <tr>
                                            <td class="text-center">{{$key + 1}}</td>
                                            <td class="text-center">{{$item->unit->name}}</td>
                                            <td class="text-center">{{$item->school_year .' - ' .($item->school_year + 1)}}</td>
                                            <td>{{$item->uniform_payment_deadline}}</td>
                                            <td  class="text-center">
                                                @if($item->status == 1)
                                                    <label class="label label-success">Aktif</label>
                                                @else
                                                    <label class="label label-danger">Tidak Aktif</label>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($item->status == 1)
                                                    <a href="{{route('admin.uniform-deadline.edit',$item->id) }}" class="btn btn-xs btn-default">
                                                        <icon class="icon-plus"><i class="fa fa-pencil"></i></icon>
                                                    </a>

                                                    <a href="{{route('admin.uniform-deadline.delete',$item->id) }}" class="btn btn-xs btn-danger"
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

                            <div class="btn-group padding-t-10 pull-right">
                                <a href="{{ route('admin.uniform-deadline.add') }}" class="btn btn-sm btn-success">
                                    <i class="fa fa-plus"></i> Tambah Data
                                </a>
                            </div>

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
