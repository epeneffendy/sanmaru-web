@extends('layouts.admin.main')

@push('styles')
    <style>
        .badge-modern {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-right: 4px;
            margin-bottom: 4px;
        }

        .badge-modern i {
            font-size: 10px;
        }

        .badge-soft-success {
            background-color: #dcfce7 !important;
            color: #166534 !important;
            border: 1px solid #bbf7d0 !important;
        }

        .badge-soft-danger {
            background-color: #fee2e2 !important;
            color: #991b1b !important;
            border: 1px solid #fecaca !important;
        }

        .badge-soft-warning {
            background-color: #fef3c7 !important;
            color: #92400e !important;
            border: 1px solid #fde68a !important;
        }

        .badge-soft-info {
            background-color: #e0f2fe !important;
            color: #075985 !important;
            border: 1px solid #bae6fd !important;
        }

        .badge-soft-secondary {
            background-color: #f1f5f9 !important;
            color: #475569 !important;
            border: 1px solid #e2e8f0 !important;
        }
    </style>
@endpush


@section('content')
    <div class="page-header">
        <h1 class="title">Laporan Penerima Siswa Baru</h1>
        <ol class="breadcrumb">
            <li>Report</li>
            <li class="active">Penerimaan Siswa Baru</li>
        </ol>
    </div>

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
                        @if (session('errors'))
                            <div class="alert alert-danger">
                                {!! session('errors')->first() !!}
                            </div>
                        @endif
                        <div class="button-collection" style="margin: 15px 0">
                            <a href="{{ route('admin.report.recapitulation-admission.export', request()->except('page')) }}"
                                class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Export</a>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">Filter</h3>
                            </div>
                            <div class="panel-body">
                                <form role="form" autocomplete="off" method="GET"
                                    action="{{ route('admin.report.recapitulation-admission.index') }}">
                                    <div class="row">
                                        <input type="hidden" name="apply_filter" value="1">

                                        <div class="form-group col-md-2">
                                            <label for="period" class="form-label">Periode</label>
                                            <select name="period" class="form-control input-sm">
                                                <option value="all">== SEMUA ==</option>
                                                @foreach (@$periods as $id => $name)
                                                    <option value="{{ $id }}"
                                                        {{ $id == @$params['period'] ? 'selected' : null }}>
                                                        {{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label for="school_year">Tahun Ajaran</label>
                                            <select name="school_year" id="school_year" class="form-control input-sm">
                                                <option value="all">== SEMUA ==</option>
                                                @for ($i = 0; $i < 5; $i++)
                                                    @php
                                                        $startYear = date('Y') - $i;
                                                        $schoolYear = $startYear . '-' . ($startYear + 1);
                                                    @endphp
                                                    <option value="{{ $schoolYear }}"
                                                        {{ @$params['school_year'] == $schoolYear ? 'selected' : '' }}>
                                                        {{ $schoolYear }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" class="pull-right btn btn-sm btn-success"
                                                style="margin-left: 5px">
                                                <i class="fa fa-search"></i> Search
                                            </button>
                                            <a href="{{ route('admin.report.admission-report.index') }}"
                                                class="pull-right btn btn-sm btn-warning">
                                                <i class="fa fa-refresh"></i> Clear
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <h1 class="text-center" style="margin: 0px;">Rekap Pendaftaran Per Unit</h1>


                        <div class="table-responsive">
                            <table id="datatables-product-orders" class="table display table-responsive">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Unit</th>
                                        <th>Jumlah Pendaftar</th>
                                        <th>Pembayaran Formulir</th>
                                        <th>Administrasi</th>
                                        <th>Upload Surat Pernyataan</th>
                                        <th>Konfirmasi Surat Pernyataan</th>
                                        <th>Pembelian Seragam</th>
                                        <th>Diterima</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td style="text-align: left">{{ $item['unit_name'] }} Siswa</td>
                                            <td style="text-align: left">{{ $item['total_student'] }} Siswa</td>
                                            <td style="text-align: left">{{ $item['payment_registration'] }} Siswa</td>
                                            <td style="text-align: left">0 Siswa</td>
                                            <td style="text-align: left">{{ $item['upload_statement_letter'] }} Siswa
                                            </td>
                                            <td style="text-align: left">{{ $item['verif_statement_letter'] }} Siswa</td>
                                            <td style="text-align: left">{{ $item['order_uniform'] }} Siswa</td>
                                            <td style="text-align: left">{{ $item['final_stage'] }} Siswa</td>
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
