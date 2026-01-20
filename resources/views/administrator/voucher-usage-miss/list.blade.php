@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Laporan Penggunaan Voucher</h1>
        <ol class="breadcrumb">
            <li>Shop</li>
            <li><a href="{{route('admin.voucher.index')}}">Setting Voucher</a></li>
            <li class="active">Laporan Penggunaan Voucher</li>
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
                        Laporan Penggunaan Voucher
                    </div>

                    <div class="button-collection" style="margin: 15px 0">
                        <a href="{{ route('admin.voucher.export-usage-miss', request()->except('page')) }}" download
                           class="btn btn-success btn-sm"><i
                                class="fa fa-file-excel-o"></i> Export</a>
                    </div>

                    <div class="panel panel-primary">
                        <div class="panel-heading">Filter</div>
                        <div class="panel-body">
                            <form role="form" autocomplete="off" method="GET" action="{{ route('admin.voucher.usage-miss') }}">
                                <div class="row">
                                    <input type="hidden" name="apply_filter" value="1">
                                    <div class="form-group col-md-2">
                                        <label for="search" class="form-label">Cari</label>
                                        <input type="text" name="search" placeholder="Search" value="{{ @$params['search'] }}"
                                            class="form-control input-sm" />
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="scope" class="form-label">berdasarkan</label>
                                        <select name="scope" id="scope" class="form-control input-sm">
                                            @foreach (@$search_scopes as $key => $scope)
                                            <option value="{{$key}}" {{$key==@$params['scope'] ? 'selected' : NULL}}>{{$scope}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="status" class="form-label">Status</label>
                                        <select name="status" id="status" class="form-control input-sm">
                                            <option value="">== SEMUA ==</option>
                                            <option value="payment_not_confirmed" {{ @$params['status']=='payment_not_confirmed' ? 'selected' : NULL }}>
                                                Order Baru (belum bayar)</option>
                                            <option value="payment_uploaded" {{ @$params['status']=='payment_uploaded' ? 'selected' : NULL }}>Order Baru
                                                (sudah bayar)</option>
                                            <option value="payment_confirmed" {{ @$params['status']=='payment_confirmed' ? 'selected' : NULL }}>
                                                Terkonfirmasi</option>
                                            <option value="cancel" {{ @$params['status']=='cancel' ? 'selected' : NULL }}>Batal</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="unit" class="form-label">Unit</label>
                                        <select name="unit" class="form-control input-sm">
                                            <option value="">== SEMUA ==</option>
                                            @foreach (@$units as $unit)
                                            <option value="{{ $unit->id }}" {{ $unit->id == @$params['unit'] ? 'selected' : NULL }}>{{ $unit->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <a href="{{ route('admin.voucher.usage-miss') }}" class="pull-right btn btn-sm btn-warning">
                                    <i class="fa fa-refresh"></i> clear
                                </a>
                                <button type="submit" class="pull-right btn btn-sm btn-success">
                                    <i class="fa fa-search"></i> Search
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="panel-body table-responsive">
                        <table id="datatables-voucher-usage-misss" class="table display">
                            <thead>
                            <tr>
                                <th>No Tagihan</th>
                                <th>Detail Siswa</th>
                                <th>Voucher</th>
                                <th>Produk yang tidak ada</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                    <tr>
                                        <td>{{ $order->invoice_no }}</td>
                                        <td>
                                            @php
                                                $name = '';
                                                if ($order->user->student)
                                                    $name = $order->user->student->name;
                                                elseif ($order->user->ppdb)
                                                    $name = $order->user->ppdb->name;
                                                else

                                            @endphp
                                            <b class="d-block">{{ $name }}</b>
                                            <small class="d-block text-muted">email: {{$order->user->email}}</small>
                                        </td>
                                        <td>{{ json_decode($order->voucher, TRUE)['code'] }}</td>
                                        <td>
                                            @foreach ($order->missProductIds as $missProductId)
                                                <label class="label label-info">{{ $products[$missProductId]->name }}</label><br />
                                            @endforeach
                                        </td>
                                        <td>{!! $order->status_label !!}</td>
                                        <th>
                                            <a target="_blank" href="{{ route('admin.product-order.show', $order->id) }}" title="Show" class="btn btn-xs btn-success">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </th>
                                    </tr>
                                @empty
                                <tr>
                                    <td colspan="3">Tidak Ada Data</td>
                                </tr>
                                @endforelse
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
    <style>
        .button-collection {
            margin-bottom: 5px;
        }

        .d-block {
            display: block;
        }

    </style>
@endpush
