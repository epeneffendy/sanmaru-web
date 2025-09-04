@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Setting Jadwal Fitting</h1>
        <ol class="breadcrumb">
            <li>Shop</li>
            <li class="active">Setting Jadwal Fitting</li>
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
                        Setting Jadwal Fitting
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
                            <table id="datatables-fitting" class="table display">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Unit</th>
                                    <th>Quota</th>
                                    <th>Option</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($fittings as $key => $fitting)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>
                                            {{ \App\Helpers\Helper::tanggal($fitting->date) }}<br/>
                                            <small>{{ $fitting->hour_start .' - '. $fitting->hour_end }}</small>
                                        </td>
                                        <td>{{ $fitting->unit->name }}</td>
                                        <td>{{ $fitting->quota }}</td>
                                        <td>
                                            <a href="{{ route('admin.fitting.edit',$fitting['id']) }}" class="btn btn-xs btn-default">
                                                <icon class="icon-plus"><i class="fa fa-pencil"></i></icon>
                                            </a>
                                            <a onclick="confirmDelete({{$fitting['id']}})" title="Delete" class="btn btn-xs btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <form id="form-delete-{{$fitting['id']}}" action="{{ route('admin.fitting.delete',$fitting['id']) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="btn-group padding-t-10 pull-right">
                            <a href="{{ route('admin.fitting.add') }}" class="btn btn-sm btn-success">
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
        .button-collection {
            margin-bottom: 5px;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{asset('js/datatables/datatables.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#datatables-fitting').DataTable();
        });
        function confirmDelete(id) {
            if(confirm('Are you sure you want to delete this item?'))
                document.getElementById('form-delete-' + id).submit();
        }
    </script>
@endpush