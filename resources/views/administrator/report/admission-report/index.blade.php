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
                            <a href="{{ route('admin.report.admission-report.export', request()->except('page')) }}"
                                class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Export</a>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">Filter</h3>
                            </div>
                            <div class="panel-body">
                                <form role="form" autocomplete="off" method="GET"
                                    action="{{ route('admin.report.admission-report.index') }}">
                                    <div class="row">
                                        <input type="hidden" name="apply_filter" value="1">

                                        <div class="form-group col-md-2">
                                            <label for="unit" class="form-label">Unit</label>
                                            <select name="unit" class="form-control input-sm">
                                                <option value="all">== SEMUA ==</option>
                                                @foreach (@$units as $id => $name)
                                                    <option value="{{ $id }}"
                                                        {{ $id == @$params['unit'] ? 'selected' : null }}>
                                                        {{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

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

                        <h1 class="text-center" style="margin: 0px;">Laporan Penerima Siswa Baru</h1>


                        <div class="table-responsive">
                            <table id="datatables-product-orders" class="table display table-responsive">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Siswa</th>
                                        <th>Register Number</th>
                                        <th>Unit Sekolah</th>
                                        <th>Periode</th>
                                        <th>Tgl Daftar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp

                                    @foreach ($data as $item)
                                        <tr style="font-weight: bold;">
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $item['name'] }}</td>
                                            <td>{{ $item['register_number'] }}</td>
                                            <td>{{ $item['unit'] }}</td>
                                            <td>{{ $item['periode'] }}</td>
                                            <td>{{ $item['created_at'] }}</td>
                                        </tr>
                                        @if (count($item['detail']) > 0)
                                            <tr id="detail-{{ $no }}" style="background-color: #f9fafb;">
                                                <td></td>
                                                <td colspan="10" style="padding: 15px;">
                                                    <div style="border-left: 3px solid #399BFF; padding-left: 15px;">
                                                        <h5 style="margin-top: 0; font-weight: bold; color: #58666E;">Detail
                                                            Pembayaran</h5>
                                                        <table class="table table-bordered table-condensed"
                                                            style="background-color: #fff; margin-bottom: 0;">
                                                            <thead>
                                                                <tr style="background-color: #f1f5f9;">
                                                                    <th>Pembayaran Formulir</th>
                                                                    <th>Data Administrasi</th>
                                                                    <th>Surat Pernyataan</th>
                                                                    <th>Pembelian Seragam</th>
                                                                    <th>Penerimaan Akhir</th>
                                                                    <th>NISN & Kelas</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        @if (@$item['detail']['registration'])
                                                                            <span
                                                                                class="badge badge-modern badge-soft-success"><i
                                                                                    class="fa fa-check"></i> Lunas</span>
                                                                        @else
                                                                            <span
                                                                                class="badge badge-modern badge-soft-warning"><i
                                                                                    class="fa fa-clock-o"></i>
                                                                                Belum Terbayarkan</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if (@$item['detail']['administrasi'])
                                                                            <span
                                                                                class="badge badge-modern badge-soft-success"><i
                                                                                    class="fa fa-check"></i> Data
                                                                                Lengkap</span>
                                                                        @else
                                                                            <span
                                                                                class="badge badge-modern badge-soft-warning"><i
                                                                                    class="fa fa-clock-o"></i>
                                                                                Data Belum Lengkap</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if (@$item['detail']['statement_letter_verif'])
                                                                            <span
                                                                                class="badge badge-modern badge-soft-success"><i
                                                                                    class="fa fa-check"></i>
                                                                                Sudah Terverifikasi</span>
                                                                        @else
                                                                            @if (@$item['detail']['statement_letter'])
                                                                                <span
                                                                                    class="badge badge-modern badge-soft-success"><i
                                                                                        class="fa fa-check"></i>
                                                                                    Sudah Diunggah</span>
                                                                            @else
                                                                                <span
                                                                                    class="badge badge-modern badge-soft-warning"><i
                                                                                        class="fa fa-clock-o"></i>
                                                                                    Belum Diunggah</span>
                                                                            @endif
                                                                        @endif

                                                                    </td>
                                                                    <td>
                                                                        @if (@$item['detail']['order_uniform'])
                                                                            <span
                                                                                class="badge badge-modern badge-soft-success"><i
                                                                                    class="fa fa-check"></i> Sudah Melakukan
                                                                                Pembelian Seragam</span>
                                                                        @else
                                                                            <span
                                                                                class="badge badge-modern badge-soft-warning"><i
                                                                                    class="fa fa-clock-o"></i>
                                                                                Belum Melakukan Pembelian Seragam</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if (@$item['detail']['final_acceptance'])
                                                                            <span
                                                                                class="badge badge-modern badge-soft-success"><i
                                                                                    class="fa fa-check"></i> Telah Diterima
                                                                                Sebegai Siswa</span>
                                                                        @else
                                                                            <span
                                                                                class="badge badge-modern badge-soft-warning"><i
                                                                                    class="fa fa-clock-o"></i>
                                                                                Belum Diterima Sebagai Siswa</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if (@$item['detail']['class_nisn'])
                                                                            <span
                                                                                class="badge badge-modern badge-soft-success"><i
                                                                                    class="fa fa-check"></i> Kelas & NISN
                                                                                sudah ditentukan</span>
                                                                        @else
                                                                            <span
                                                                                class="badge badge-modern badge-soft-warning"><i
                                                                                    class="fa fa-clock-o"></i>
                                                                                Kelas & NISN belum ditentukan</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
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
