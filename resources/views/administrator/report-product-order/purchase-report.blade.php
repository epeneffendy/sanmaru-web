@extends('layouts.admin.main')

@inject('priceHelper', 'App\Helpers\PriceHelper')
@inject('productOrderModel', 'App\Models\ProductOrder')
@inject('voucherModel', 'App\Models\Voucher')
@inject('userModel', 'App\Models\User')
@inject('carbon', 'Carbon\Carbon')

@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data Master Pembelian Produk</h1>
        <ol class="breadcrumb">
            <li>Shop</li>
            <li class="active">Pembelian Produk</li>
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
                    <div class="panel-body table-responsive">
                        @if (session('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif
                        {{-- @if ($exportMessage = (new \App\Lib\ExportJob())->message(request()->all(), auth()->user()))
                            <div class="alert alert-success">
                                {!! $exportMessage !!}
                            </div>
                        @endif --}}
                        @if (session('errors'))
                            <div class="alert alert-danger">
                                {!! session('errors')->first() !!}
                            </div>
                        @endif
                        <div class="button-collection" style="margin: 15px 0">
                            <a href="{{ route('admin.product-order.report.export-purchase-report', request()->except('page')) }}"
                               download class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Export</a>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">Filter</h3>
                            </div>
                            <div class="panel-body">
                                <form role="form" autocomplete="off" method="GET"
                                      action="{{ route('admin.product-order.report.purchase-report') }}">
                                    <div class="row">
                                        <input type="hidden" name="apply_filter" value="1">

                                        <div class="form-group col-md-2">
                                            <label for="filter" class="form-label">Filter</label>
                                            <select name="filter" class="form-control input-sm">
                                                <option value="siswa" {{ (@$params['filter'] == 'siswa') ? 'selected' : NULL }} >Siswa</option>
                                                <option value="unit" {{ (@$params['filter'] == 'unit') ? 'selected' : NULL }}>Unit</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label for="unit" class="form-label">Unit</label>
                                            <select name="unit" class="form-control input-sm">
                                                <option value="all">== SEMUA ==</option>
                                                @foreach (@$units as $id => $name)
                                                    <option value="{{ $id }}" {{ $id == @$params['unit'] ? 'selected' : NULL }}>{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label for="date_range" class="form-label">Rentang Waktu</label>
                                            <input type="text" id="date_range" name="date_range"placeholder="rentang waktu" value="{{ @$params['date_range'] }}" class="form-control input-sm"/>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label for="payment_status" class="form-label">Status Pembayaran</label>
                                            <select name="payment_status" class="form-control input-sm">
                                                <option value="all">== SEMUA ==</option>
                                                <option value="new_order" {{ (@$params['payment_status'] == 'new_order') ? 'selected' : '' }}>Belum Terbayarkan</option>
                                                <option value="confirmed" {{ (@$params['payment_status'] == 'confirmed') ? 'selected' : '' }}>Sudah Terbayarkan</option>
                                                <option value="cancel" {{ (@$params['payment_status'] == 'cancel') ? 'selected' : '' }}>Batal Order</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label for="pickup_status" class="form-label">Status Pengambilan</label>
                                            <select name="pickup_status" class="form-control input-sm">
                                                <option value="all" >== SEMUA ==</option>
                                                <option value="pickup" {{ (@$params['pickup_status'] == 'pickup') ? 'selected' : '' }}>Sudah Diambil</option>
                                                <option value="not_pickup" {{ (@$params['pickup_status'] == 'not_pickup') ? 'selected' : '' }}>Belum Diambil</option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" class="pull-right btn btn-sm btn-success"
                                                    style="margin-left: 5px">
                                                <i class="fa fa-search"></i> Search
                                            </button>
                                            <a href="{{ route('admin.product-order.report.purchase-report') }}"
                                               class="pull-right btn btn-sm btn-warning">
                                                <i class="fa fa-refresh"></i> Clear
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <h1 class="text-center" style="margin: 0px;">Laporan Order Seragam</h1>
                        <h5 class="text-center" style="margin: 0px;">
{{--                            Unit : {{ @$params['unit'] ? $units[$params['unit']] : 'Semua' }}--}}
                        </h5>
                        <h5 class="text-center" style="margin: 0px;">Range Tanggal Penjualan
                            : {{ $carbon->parse($params['start_date'])->format('d-m-Y') }}
                            s/d {{ $carbon->parse($params['end_date'])->format('d-m-Y') }}</h5>
                        <div class="table-responsive">
                            <table id="datatables-product-orders" class="table display table-responsive">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    @if(@$params['filter'] == 'siswa')
                                        <th>Register Number</th>
                                        <th>Nama Siswa</th>
                                    @endif
                                    <th>Unit Sekolah</th>
                                    <th>Nama Seragam</th>
                                    <th>Ukuran Seragam</th>
                                    <th>Jumlah</th>
                                    <th>Status Pembayaran</th>
                                    <th>Status Pengambilan</th>

                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach($orders as $item)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        @if(@$params['filter'] == 'siswa')
                                            <td>{{ $item->register_number }}</td>
                                            <td>{{ $item->name }}</td>
                                        @endif
                                        <td>{{ $item->unit_name }}</td>
                                        <td>{{ $item->product_name }}</td>
                                        <td>{{ $item->size }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>
                                            @if($item->payment_status == 'confirmed')
                                                <label class="label label-success">Sudah Terbayarkan</label>
                                            @elseif($item->payment_status == 'new_order')
                                                <label class="label label-warning">Belum Terbayarkan</label>
                                            @else
                                                <label class="label label-danger">Batal Order</label>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->pickup_status == 'pickup')
                                                <label class="label label-success">Sudah Diambil</label>
                                            @else
                                                <label class="label label-warning">Belum Diambil</label>
                                            @endif
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

        .d-block {
            display: block;
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
    <script src="{{asset('js/moment/moment.min.js')}}"></script>
    <script src="{{asset('js/date-range-picker/daterangepicker.js')}}"></script>

    <script>
        var url = document.location.toString();
        var activeTab = `tab-list`;

        $(document).ready(function () {
            $('#date_range').daterangepicker();
            if (url.match('#')) {
                activeTab = url.split('#')[1];
            }

            if (activeTab) {
                $('a[href="#' + activeTab + '"]').parent().addClass('active');
                $('#' + activeTab).addClass('active in')
            }

            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                window.location.hash = e.target.hash;
            });
        })

    </script>
@endpush

