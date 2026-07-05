@extends('layouts.admin.main')
@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data Pengunduran Diri Siswa</h1>
        <ol class="breadcrumb">
            <li>PPDB</li>
            <li class="active">Data Pengunduran Diri Siswa</li>
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
                        Data Pengunduran Diri Siswa
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
                                <a href="{{ route('admin.ppdb-resignation.add') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah data</a>   
                            @endif
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading">Filter</div>
                            <div class="panel-body">
                                <form role="form" autocomplete="off" method="GET" action="{{ route('admin.ppdb-resignation.index') }}">
                                    <input autocomplete="false" name="hidden" disabled type="text" style="display:none;">
                                    <div class="form-group col-md-3">
                                        <input type="text" name="name" placeholder="Search" value="{{ @$params['name'] }}" class="form-control input-sm" />
                                    </div>
                                    <div class="form-group col-md-3">
                                        <select name="unit" class="form-control input-sm">
                                            <option value="0">== SEMUA ==</option>
                                            {{-- @foreach (@$units as $unit)
                                                <option value="{{ $unit->id }}" {{ $unit->id == @$params['unit'] ? 'selected' : NULL }}>{{ $unit->name }}</option>
                                            @endforeach --}}
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <input type="date" name="start_date" class="form-control input-sm" value="{{ @$params['start_date'] }}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <input type="date" name="end_date" class="form-control input-sm" value="{{ @$params['end_date'] }}">
                                    </div>
                                    <a href="{{ route('admin.ppdb-resignation.index') }}" class="pull-right btn btn-sm btn-warning">
                                        <i class="fa fa-refresh"></i> clear
                                    </a>
                                    <button type="submit" class="pull-right btn btn-sm btn-success">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="fixed-table-head">
                            <table id="datatables-master-ppdb" class="table table-striped table-responsive display" style="width: 100%; border-top-width: medium; border-top-style: solid;">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Unit</th>
                                        <th class="text-center">Reason</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
    
                                <tbody>
                                @php
                                    $number = ($data->currentPage() - 1) * $data->perPage();
                                @endphp
                                @foreach($data as $key => $value)
                                    @php $number++ @endphp
                                    <tr>
                                        <td class="text-center">{{ $number }}</td>
                                        <td class="text-center"> {{ $value->ppdb->name }} </td>
                                        <td class="text-center"> {{ $value->unit->name }} </td>
                                        <td class="text-center"> {{ $value->reason }} </td>
                                        <td class="text-center">
                                            @if($value->status == 'draft')
                                                <span class="label label-warning">Pengajuan</span>
                                            @elseif($value->status == 'approved')
                                                <span class="label label-success">Disetujui</span>
                                            @else
                                                <span class="label label-danger">Dibatalkan</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.ppdb-resignation.edit', $value->id) }}" class="btn btn-primary btn-xs" title="Update">
                                                <i class="fa fa-pencil"></i> Update
                                            </a>
                                            @if($value->attachment)
                                                <a href="{{ $value->getAttachmentImageUrl() }}" target="_blank" class="btn btn-info btn-xs" title="Preview Lampiran">
                                                    <i class="fa fa-eye"></i> Lampiran
                                                </a>
                                            @endif
                                            @if($value->status == 'draft')
                                                <form action="{{ route('admin.ppdb-resignation.approve', $value->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pengajuan ini?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-xs" title="Approve">
                                                        <i class="fa fa-check"></i> Approve
                                                    </button>
                                                </form>
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

        .wrapped {
            max-width: 150px;
            word-wrap: break-word;
        }

    </style>
@endpush

@push('scripts')
   
@endpush
