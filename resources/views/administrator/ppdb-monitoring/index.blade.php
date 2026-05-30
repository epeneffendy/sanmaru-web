@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Monitoring PPDB</h1>
        <ol class="breadcrumb">
            <li>PPDB</li>
            <li class="active">Monitoring PPDB</li>
        </ol>
    </div>

    <div class="container-padding">
        <!-- Start Row -->
        <div class="row">
            <!-- Start Panel -->
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-title">
                        Setting Period
                    </div>

                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <form role="form" autocomplete="off" method="GET"
                                action="{{ route('admin.ppdb-monitoring.index') }}">
                                <input autocomplete="false" name="hidden" disabled type="text" style="display:none;">
                                <div class="form-group col-md-3">
                                    <label for="name" class="form-label">Filter</label>
                                    <input type="text" name="name" placeholder="Search" value="{{ @$params['name'] }}"
                                        class="form-control input-sm" />
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="unit" class="form-label">Unit</label>
                                    <select name="unit" class="form-control input-sm">
                                        <option value="0">== SEMUA ==</option>
                                        @foreach (@$units as $unit)
                                            <option value="{{ $unit->id }}"
                                                {{ $unit->id == @$params['unit'] ? 'selected' : null }}>{{ $unit->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="period" class="form-label">Period</label>
                                    <select name="period" class="form-control input-sm">
                                        <option value="0">== SEMUA ==</option>
                                        @foreach (@$periods as $period)
                                            <option value="{{ $period->id }}"
                                                {{ $period->id == @$params['period'] ? 'selected' : null }}>
                                                {{ $period->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="year" class="form-label">Tahun Ajaran</label>
                                    <select name="year" id="year" class="form-control input-sm">
                                        @php($y = date('Y') + 1)
                                        <option value="0">== SEMUA ==</option>
                                        @for ($i = 2021; $i <= $y; $i++)
                                            <option value="{{ $i }}"
                                                {{ $i == @$params['year'] ? 'selected' : null }}>{{ $i }} -
                                                {{ $i + 1 }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <a href="{{ route('admin.stage.index') }}" class="pull-right btn btn-sm btn-warning">
                                    <i class="fa fa-refresh"></i> clear
                                </a>
                                <button type="submit" class="pull-right btn btn-sm btn-success">
                                    <i class="fa fa-search"></i> Search
                                </button>
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
                        <div class="fixed-table-head">
                            <table id="datatables-period" class="table display">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th width="15%">Unit</th>
                                        <th>Period</th>
                                        <th>Tahun Ajaran</th>
                                        <th>Active</th>
                                        <th>Jumlah Siswa</th>
                                        <th width="20%">Option</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($number = ($data->currentPage() - 1) * $data->perPage())
                                    @foreach ($data as $key => $period)
                                        @php($number++)
                                        <tr>
                                            <td>{{ $number }}</td>
                                            <td>{{ $period['name'] }}</td>
                                            <td>{{ $period['desc'] }}</td>
                                            <td>{{ $period['unit'] }}</td>
                                            <td>{{ $period['periode'] }}</td>
                                            <td>{{ $period['school_year'] }}</td>
                                            <td>{!! $period['active'] !!}</td>
                                            <td>{{ $period['count_student'] }}</td>
                                            <td>
                                                <a href="{{ route('admin.ppdb-monitoring.show-detail-period', $period['id']) }}"
                                                    title="Show" class="btn btn-xs btn-success">
                                                    Lihat Data
                                                </a>
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
@endsection
