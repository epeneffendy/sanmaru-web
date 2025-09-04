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
                            <a href="{{ route('admin.product-order.report.export', request()->except('page')) }}" download class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Export</a>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">Filter</h3>
                            </div>
                            <div class="panel-body">
                                <form role="form" autocomplete="off" method="GET" action="{{ route('admin.product-order.report.index') }}">
                                    <div class="row">
                                        <input type="hidden" name="apply_filter" value="1">
                                        <div class="form-group col-md-2">
                                            <label for="status_student" class="form-label">Status Siswa</label>
                                            <select name="status_student" id="status_student" class="form-control input-sm">
                                                <option value="">== SEMUA ==</option>
                                                @foreach ([
                                                    $userModel::PPDB => 'PPDB',
                                                    $userModel::STUDENT => 'Regular'
                                                ] as $key => $status)
                                                    <option value="{{$key}}" {{$key == request('status_student') ? 'selected' : NULL}}>{{$status}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="unit" class="form-label">Unit</label>
                                            <select name="unit" class="form-control input-sm">
                                                <option value="">== SEMUA ==</option>
                                                @foreach (@$units as $id => $name)
                                                    <option value="{{ $id }}" {{ $id == @$params['unit'] ? 'selected' : NULL }}>{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="date_range" class="form-label">Rentang Waktu</label>
                                            <input type="text" id="date_range" name="date_range" placeholder="rentang waktu" value="{{ @$params['date_range'] }}" class="form-control input-sm" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" class="pull-right btn btn-sm btn-success" style="margin-left: 5px">
                                                <i class="fa fa-search"></i> Search
                                            </button>
                                            <a href="{{ route('admin.product-order.report.index') }}" class="pull-right btn btn-sm btn-warning">
                                                <i class="fa fa-refresh"></i> Clear
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div role="tabpanel">
                            <ul class="nav nav-tabs nav-justified tabcolor5-bg" role="tablist">
                                <li role="presentation" class="">
                                    <a href="#tab-list" aria-controls="tab-list" data-toggle="tab" aria-expanded="false">List</a>
                                </li>
                                <li role="presentation" class="">
                                    <a href="#tab-chart" aria-controls="tab-chart" data-toggle="tab" aria-expanded="false">Chart</a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane" id="tab-list">
                                <h1 class="text-center" style="margin: 0px;">Laporan penjualan seragam PPDB</h1>
                                <h5 class="text-center" style="margin: 0px;">
                                    Unit : {{ @$params['unit'] ? $units[$params['unit']] : 'Semua' }}
                                </h5>
                                <h5 class="text-center" style="margin: 0px;">Range Tanggal Penjualan : {{ $carbon->parse($params['start_date'])->format('d-m-Y') }} s/d {{ $carbon->parse($params['end_date'])->format('d-m-Y') }}</h5>
                                <div class="table-responsive">
                                    <table id="datatables-product-orders" class="table display table-responsive">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Unit Sekolah</th>
                                            <th>Nama Seragam</th>
                                            <th>Ukuran Seragam</th>
                                            <th>Status Siswa</th>
                                            <th>Harga Vendor</th>
                                            <th>Harga Jual</th>
                                            <th>Jumlah Seragam Terjual</th>
                                            <th>Total</th>
                                            <th>Provit</th>
                                            <th>Stock Awal</th>
                                            <th>Sisa Stock</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                        @foreach($products as $product)
                                            <tr class="product-detial-id-{{ $product['product_detail_id'] }}">
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $product['unit_name'] }}</td>
                                                <td>{{ $product['product_name'] }}</td>
                                                <td>{{ $product['size'] }}</td>
                                                <td>
                                                    @if ($product['student_type'] === $userModel::PPDB)
                                                    PPDB
                                                    @else
                                                    Regular
                                                    @endif
                                                </td>
                                                <td><label class="label label-warning">{{ \App\Helpers\PriceHelper::rupiah($product['price_vendor']) }}</label></td>
                                                <td><label class="label label-default">{{ \App\Helpers\PriceHelper::rupiah($product['sell_price']) }}</label></td>
                                                <td><label class="label label-primary">{{ $product['count_product_sell'] }}</label></td>
                                                <td><label class="label label-info">{{ \App\Helpers\PriceHelper::rupiah($product['total_sell']) }}</label></td>
                                                <td><label class="label label-success">{{ \App\Helpers\PriceHelper::rupiah($product['profit']) }}</label></td>
                                                <td><label class="label label-default">{{ $product['initial_stock'] }}</label></td>
                                                <td><label class="label label-danger">{{ $product['available_stock'] }}</label></td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>



                            <div role="tabpanel" class="tab-pane" id="tab-chart">
                                <canvas id="chart-product-order" width="400" height="400"></canvas>
                            </div>
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
    <script src="{{ asset('js/chartjs/Chart.min.js') }}"></script>

    <script>
        var dynamicColors = function() {
            var r = Math.floor(Math.random() * 255);
            var g = Math.floor(Math.random() * 255);
            var b = Math.floor(Math.random() * 255);
            return "rgb(" + r + "," + g + "," + b + ")";
        };

        $(document).ready(function () {
            var products = JSON.parse('{!! $products->toJson() !!}');
            var dataset = [];

            if (products.length > 0) {
                products.forEach(product => {
                    dataset.push({
                        label: product.unit_name + ' ' + product.size + ' ' + product.product_name,
                        data: [
                            product.size,
                            product.price_vendor,
                            product.sell_price,
                            product.count_product_sell,
                            product.total_sell,
                        ],
                        backgroundColor: dynamicColors(),
                    });
                });
            }

            var ctx = document.getElementById('chart-product-order').getContext('2d');
            var productOrderChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Ukuran Seragam', 'Harga Vendor', 'Harga Jual', 'Jumlah Terjual', 'Total'],
                    datasets: dataset
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });

        });
    </script>
    <script>
        var url = document.location.toString();
        var activeTab = `tab-list`;

        $(document).ready(function () {
            $('#date_range').daterangepicker();
            if (url.match('#')) {
                activeTab = url.split('#')[1];
            }

            if (activeTab) {
                $('a[href="#'+activeTab+'"]').parent().addClass('active');
                $('#'+activeTab).addClass('active in')
            }

            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                window.location.hash = e.target.hash;
            });
        })

    </script>
@endpush

