@extends('layouts.admin.main')
@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Export Data History</h1>
        <ol class="breadcrumb">
            <li>Shop</li>
            <li><a href="{{ route('admin.product-order.index') }}">Pembelian Produk</a></li>
            <li class="active">Export Data History</li>
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
                        Export Data History
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
                       
                        <table id="datatables-shop-export-list" class="table display table-responsive">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th style="min-width: 150px;">Nama Data</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th>User</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($exports as $key => $data)
                                <tr>
                                    <td>{{ ($key+1) }}</td>
                                    <td>{{ $data->path }}</td>
                                    <td>{{ $data->start_date }}</td>
                                    <td>{{ $data->start_hour }}</td>
                                    <td>{!! $data->status_label !!}</td>
                                    <td>{{ @$data->user->name }}</td>
                                    <td>
                                        {!! $data->download_link !!}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
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
            $('#datatables-shop-export-list').DataTable();
        });
    </script>
@endpush
