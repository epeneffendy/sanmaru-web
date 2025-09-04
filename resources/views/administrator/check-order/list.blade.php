@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Cek Pesanan</h1>
        <ol class="breadcrumb">
            <li>PPDB</li>
            <li class="active">Cek Pesanan</li>
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
                        Cek Pesanan
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
                        <div class="button-collection">
                            <a href="{{ route('admin.check-order.dashboard', request()->except('page')) }}" class="btn btn-success btn-sm"><i class="fa fa-home"></i> Laporan Status Pesanan</a>
                            <a href="{{ route('admin.check-order.export', request()->except('page')) }}" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Export</a>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading">Filter</div>
                            <div class="panel-body">
                                <form role="form" autocomplete="off" method="GET" action="{{ route('admin.check-order.index') }}">
                                    <input autocomplete="false" name="hidden" disabled type="text" style="display:none;">
                                    <div class="form-group col-md-3">
                                        <input type="text" name="name" placeholder="Search" value="{{ @$params['name'] }}" class="form-control input-sm" />
                                    </div>
                                    <div class="form-group col-md-3">
                                        <select name="unit" class="form-control input-sm">
                                            <option value="0">== SEMUA ==</option>
                                            @foreach (@$units as $unit)
                                                <option value="{{ $unit->id }}" {{ $unit->id == @$params['unit'] ? 'selected' : NULL }}>{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <select name="order_status[]" class="form-control input-sm selectpicker show-tick" multiple>
                                            @foreach (@$orderStatus as $key => $value)
                                                <option value="{{ $value['value'] }}" {{ @in_array($value['value'], @$params['order_status']) ? 'selected' : NULL }}>{{ $value['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <a href="{{ route('admin.check-order.index') }}" class="pull-right btn btn-sm btn-warning">
                                        <i class="fa fa-refresh"></i> clear
                                    </a>
                                    <button type="submit" class="pull-right btn btn-sm btn-success">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="fixed-table-head">
                            <table id="datatables-check-order" class="table display table-responsive">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Registrasi Siswa</th>
                                    <th style="text-align: center;">Unit</th>
                                    <th>Name</th>
                                    <th style="text-align: center;">Order Status</th>
                                    <th style="text-align: center;">Payment Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $number = ($data->currentPage() - 1) * $data->perPage();
                                @endphp
                                @foreach($data as $key => $value)
                                    <tr>
                                        <td>{{++$number}}</td>
                                        <td style="text-align: center;">{{ $value->register_number }}</td>
                                        <td style="text-align: center;">{{ @$value->unit->name }}</td>
                                        <td><b>{{$value->name}}</b></td>
                                        <td style="text-align: center;">
                                            {!! @$value->icon_order_status !!}
                                        </td>
                                        <td style="text-align: center;">
                                            {!! @$value->orders->last()->icon_konfirmasi_pembayaran !!}<br>
                                        </td>
                                        <td>
                                            @if(count($value->orders))
                                            <a href="{{ route('admin.product-order.show', $value->orders->last()->id) }}" title="Show" class="btn btn-xs btn-success">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $data->appends(request()->except('page'))->links() }}
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
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugin/bootstrap-select/bootstrap-select.css') }}" />   
    <style>
        .button-collection {
            margin: 5px 0 5px 0;
        }

        .btn-circle {
            width: 30px;
            height: 30px;
            text-align: center;
            padding: 6px 0;
            font-size: 12px;
            line-height: 1.42;
            border-radius: 15px;
        }

        .btn-circle .fa {
            margin: 0 auto;
        }
    </style>
@endpush
@push('scripts')
<script src="{{asset('js/bootstrap-select/bootstrap-select.js')}}"></script>
<script>
    $(document).ready(function () {
        $('.selectpicker').selectpicker({
            dropupAuto: false,
            title: "No Value"
        });
    });
</script>
@endpush
