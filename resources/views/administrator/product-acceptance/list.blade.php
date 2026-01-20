@extends('layouts.admin.main')
@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Penerimaan Product</h1>
        <ol class="breadcrumb">
            <li>SHOP</li>
            <li class="active">Penerimaan Product</li>
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
                        Penerimaan Product Seragam
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
                                    <th class="text-center">Tanggal Penerimaan</th>
                                    <th class="text-center">Vendor</th>
                                    <th class="text-center">Product</th>
                                    <th class="text-center">Keterangan</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $key => $item)
                                    <tr>
                                        <td class="text-center">{{$key + 1}}</td>
                                        <td class="text-center">{{$item->date}}</td>
                                        <td class="text-center">{{$item->vendor->name}}</td>
                                        <td class="text-center">{{$item->product->name}}</td>
                                        <td  class="text-center">{{$item->description}}</td>
                                        <td  class="text-center">
                                            <a href="{{ route('admin.product-acceptance.show',$item->id) }}" title="Show"
                                               class="btn btn-xs btn-success">
                                                <icon class="icon-plus"><i class="fa fa-eye"></i></icon>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="btn-group padding-t-10 pull-right">
                            <a href="{{ route('admin.product-acceptance.add') }}" class="btn btn-sm btn-success">
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
