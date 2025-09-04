@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Laporan Klaim</h1>
        <ol class="breadcrumb">
            <li>Shop</li>
            <li><a href="{{route('admin.voucher.index')}}">Setting Voucher</a></li>
            <li class="active">Laporan Klaim</li>
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
                        Laporan Klaim
                    </div>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">Filter</h3>
                        </div>
                        <div class="panel-body">
                            <form role="form" autocomplete="off" method="GET" action="{{ route('admin.voucher.usage') }}">
                                <input autocomplete="false" name="hidden" disabled type="text" style="display:none;">
                                <div class="form-group col-md-3">
                                    <label for="name" class="form-label">Nama</label>
                                    <input type="text" name="name" placeholder="Search" value="{{ @$params['name'] }}" class="form-control input-sm" />
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="date_range" class="form-label">Rentang waktu</label>
                                    <input type="text" id="date_range" name="date_range" placeholder="Rentang waktu"
                                        value="{{ @$params['date_range'] }}" class="form-control input-sm" />
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" class="form-control input-sm">
                                        <option value=0>== SEMUA ==</option>
                                        <option value="available" {{ @$params['status'] === 'available' ? 'selected' : NULL }}>AVAILABLE</option>
                                        <option value="claimed" {{ @$params['status'] === 'claimed' ? 'selected' : NULL }}>CLAIMED</option>
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
                                <div class="form-group col-md-2">
                                    <label for="year" class="form-label">Tahun Ajaran</label>
                                    <select name="year" id="year" class="form-control input-sm">
                                        <option value="">== SEMUA ==</option>
                                        @foreach ($years as $year)
                                        <option value="{{ $year->year }}" {{ $year->year == @$params['year'] ? 'selected' : NULL}}>{{ $year->year }} -
                                            {{ $year->year + 1 }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="pull-right btn btn-sm btn-success" style="margin-left: 5px">
                                            <i class="fa fa-search"></i> Search
                                        </button>
                                        <a href="{{ route('admin.voucher.usage') }}" class="pull-right btn btn-sm btn-warning">
                                            <i class="fa fa-refresh"></i> clear
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
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
                        <table id="datatables-voucher-usages" class="table display">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th width="15%">Unit</th>
                                <th>Nama</th>
                                <th>Kode</th>
                                <th>Tipe</th>
                                <th>Kuota</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $number = 0;
                            @endphp

                            @foreach($datas as $data)
                                @foreach($data['voucher'] as $voucher)

                                    @php $number++ @endphp
                                    <tr>
                                        <td>{{ $number }}</td>
                                        <td>{{ isset($data['unit']) ? $data['unit'] : '-' }}</td>
                                        <td><strong>{{ isset($data['name']) ? $data['name'] : '-' }}</strong></td>
                                        <td>{{ $voucher['code'] }}</td>
                                        <td>
                                            {{ $voucher['type'] }}<br/>
                                            {!! $voucher['type_value'] !!}

                                        </td>
                                        <td class="text-center">
                                            {{ $voucher['usage_remaining'] }}
                                        </td>
                                        <td>
                                            <label class="label label-{{ $voucher['label_color'] }}">
                                                {{ $voucher['status'] }}
                                            </label>
                                        </td>
                                    </tr>
                                @endforeach
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
    $(document).ready(function () {
            $('#date_range').daterangepicker();
        });
</script>
@endpush
