@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Penangguhan PPDB</h1>
        <ol class="breadcrumb">
            <li>PPDB</li>
            <li class="active">Penangguhan PPDB</li>
        </ol>
    </div>

    <div class="container-padding">
        <!-- Start Row -->
        <div class="row">
            <!-- Start Panel -->
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-title">
                        Penangguhan PPDB
                    </div>

                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <form role="form" autocomplete="off" method="GET"
                                action="{{ route('admin.ppdb-suspended.index') }}">
                                <input autocomplete="false" name="hidden" disabled type="text" style="display:none;">
                                <div class="form-group col-md-3">
                                    <label for="name" class="form-label">Filter</label>
                                    <input type="text" name="name" placeholder="Search" value="{{ @$params['name'] }}"
                                        class="form-control input-sm" />
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="scope" class="form-label">Berdasarkan</label>
                                    <select name="scope" class="form-control input-sm">
                                        <option value="0" {{ @$params['scope'] == '0' ? 'selected' : null }}>== SEMUA ==</option>
                                        <option value="1" {{ @$params['scope'] == '1' ? 'selected' : null }}>Register Number</option>
                                        <option value="2" {{ @$params['scope'] == '2' ? 'selected' : null }}>Nama Siswa</option>
                                    </select>
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
                                        <?php $y = date('Y') + 1; ?>
                                        <option value="0">== SEMUA ==</option>
                                        @for ($i = 2021; $i <= $y; $i++)
                                            <option value="{{ $i }}"
                                                {{ $i == @$params['year'] ? 'selected' : null }}>{{ $i }} -
                                                {{ $i + 1 }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <a href="{{ route('admin.ppdb-suspended.index') }}" class="pull-right btn btn-sm btn-warning">
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
                                        <th>Register Number</th>
                                        <th>Unit</th>
                                        <th>Period</th>
                                        <th>Tahun Ajaran</th>
                                        <th>Expired Date</th>
                                        <th>Keterangan</th>
                                        <th width="20%">Option</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $number = ($data->currentPage() - 1) * $data->perPage(); ?>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ $number + 1 }}</td>
                                            <td>{{ $item->student_name }}</td>
                                            <td>{{ $item->register_number }}</td>
                                            <td>{{ $item->unit_name }}</td>
                                            <td>{{ $item->period_name }}</td>
                                            <td>{{ $item->school_year }}</td>
                                            <?php $expiredVal = !empty($item->payment_expired_at) ? $item->payment_expired_at : $item->expired_at; ?>
                                            <td>{{ !empty($expiredVal) ? date('d-m-Y', strtotime($expiredVal)) : '-' }}</td>
                                            <td>
                                                <span class="label label-info">{{ (!empty($item->type) && $item->type == 'activity') ? 'Uang Kegiatan' : 'Penangguhan Pembayaran' }}</span></br>
                                                <?php
                                                    $lateHtml = '-';
                                                    if (!empty($expiredVal)) {
                                                        $expiredDate = \Carbon\Carbon::parse($expiredVal);
                                                        $now = \Carbon\Carbon::now();
                                                        if ($now->greaterThan($expiredDate)) {
                                                            $diff = $expiredDate->diff($now);
                                                            $months = $diff->m + ($diff->y * 12);
                                                            $days = $diff->d;
                                                            $parts = [];
                                                            if ($months > 0) {
                                                                $parts[] = $months . ' bulan';
                                                            }
                                                            if ($days > 0) {
                                                                $parts[] = $days . ' hari';
                                                            }
                                                            $lateText = empty($parts) ? '0 hari' : implode(' ', $parts);
                                                            $lateHtml = '<span class="label label-danger">Terlambat ' . $lateText . '</span>';
                                                        } else {
                                                            $lateHtml = '<span class="label label-success">Belum Jatuh Tempo</span>';
                                                        }
                                                    }
                                                ?>
                                                {!! $lateHtml !!}
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-xs btn-info btn-detail" data-id="{{ $item->id }}">
                                                    <i class="fa fa-eye"></i> Detail
                                                </button>
                                                <button type="button" class="btn btn-xs btn-success btn-evaluate" data-id="{{ $item->id }}">
                                                    <i class="fa fa-check"></i> Evaluasi
                                                </button>
                                            </td>
                                        </tr>
                                        <?php $number++; ?>
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

    @include('administrator.ppdb-suspended.modal-detail')
    @include('administrator.ppdb-suspended.modal-evaluate')
@endsection
