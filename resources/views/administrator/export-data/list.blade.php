@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Export Data</h1>
        <ol class="breadcrumb">
            <li>PPDB</li>
            <li class="active">Export Data</li>
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
                        Export Data
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
                            <table id="datatables-period" class="table display">
                                <thead>
                                <tr>
                                    <th>Unit</th>
                                    <th>Periode</th>
                                    <th>Total Pendaftar</th>
                                    <th>Email Belum Terverifikasi</th>
                                    <th>Pembayaran Belum Diupload</th>
                                    <th>Pembayaran Belum Terverifikasi</th>
                                    <th>Option</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $key => $value)
                                    <tr>
                                        <td>{{$value->unit->name}}</td>
                                        <td>{{$value['name']}}</td>
                                        <td>{{ $value->ppdbUsers->count('id') }}</td>
                                        <td>{{ $value->totalUsersEmailNotVerified() }}</td>
                                        <td>{{ $value->totalUsersPaymentNotYetSubmitted() }}</td>
                                        <td>{{ $value->totalUsersPaymentNotYetVerified() }}</td>
                                        <td>
                                            <a href="{{ route('admin.export-data.export',$value['id']) }}" download class="btn btn-success btn-sm"><icon class="icon-plus icon">export data</icon></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
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
            $('#datatables-period').DataTable();
        });
    </script>
@endpush
