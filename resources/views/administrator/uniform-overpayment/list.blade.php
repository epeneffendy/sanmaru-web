@extends('layouts.admin.main')
@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data Kelebihan Bayar</h1>
        <ol class="breadcrumb">
            <li>SHOP</li>
            <li class="active">Data Kelebihan Bayar</li>
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
                        Data Kelebihan Bayar
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
                        <div class="button-collection" style="margin: 15px 0">
                            @if (\App\Helpers\Helper::isAdminRole())
                                <a href="{{ route('admin.uniform-overpayment.add') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah data</a>
                            @endif
                        </div>
                        <div style="overflow-x: auto;" class="fixed-table-head period">
                            <table id="datatables-overpayment" class="table table-striped" style="width: 100%; border-top-width: medium; border-top-style: solid;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Unit</th>
                                        <th>Tahun Ajaran</th>
                                        <th>No. Registrasi</th>
                                        <th>Nama Siswa</th>
                                        <th>Nominal harus dibayar</th>
                                        <th>Nominal yang dibayar</th>
                                        <th>Nominal Pengembalian</th>
                                        <th>Tangal Pembayaran</th>
                                        <th>Metode Pembayaran</th>
                                        <th>Keterangan</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                @foreach($datas as $key => $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->productOrder->user->ppdb->unit->name }}</td>
                                        <td>{{ $data->productOrder->user->ppdb->school_year . "-" . ($data->productOrder->user->ppdb->school_year + 1) }}</td>
                                        <td>{{ $data->productOrder->user->ppdb->register_number }}</td>
                                        <td>{{ $data->productOrder->user->ppdb->name }}</td>
                                        <td>{{ \App\Helpers\PriceHelper::rupiah($data->productOrder->grand_total) }}</td>
                                        <td>{{ \App\Helpers\PriceHelper::rupiah($data->payment_amount) }}</td>
                                        <td>{{ \App\Helpers\PriceHelper::rupiah(@$data->productOrder->overpayment->nominal_refund) }}</td>
                                        <td>{{ \App\Helpers\Helper::tanggal($data->payment_date) }}</td>
                                        <td>{{ ucwords(@$data->payment_method) }}</td>
                                        <td>{!! @$data->productOrder->overpayment->note !!}</td>
                                        <td>{!! @$data->productOrder->overpayment->icon_konfirmasi_pembayaran !!}</td>
                                        <td>
                                            <a href="{{ route('admin.uniform-overpayment.edit', $data->id) }}" title="Edit" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
                                            <a href="{{ route('admin.uniform-overpayment.show', $data->id) }}" title="Show" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i></a>
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
    <style>
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
            margin-right: 0;
        }

    </style>
@endpush

@push('scripts')

@endpush
