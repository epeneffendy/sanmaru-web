@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Setting Period</h1>
        <ol class="breadcrumb">
            <li>PPDB</li>
            <li class="active">Setting Period</li>
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
                        Setting Period
                    </div>
                    <div class="panel panel-primary">
                        <div class="panel-heading">Filter</div>
                        <div class="panel-body">
                            <form role="form" autocomplete="off" method="GET" action="{{ route('admin.period.index') }}">
                                <input autocomplete="false" name="hidden" disabled type="text" style="display:none;">
                                <div class="form-group col-md-3">
                                    <input type="text" name="name" placeholder="Search" value="{{ @$params['name'] }}" class="form-control input-sm" />
                                </div>
                                <div class="form-group col-md-3">
                                    <select name="unit" class="form-control input-sm">
                                        <option value="0">== SEMUA ==</option>
                                        @foreach (@$units as $unit)
                                            <option value="{{ $unit->id }}" {{ $unit->id == @$params['unit'] ? 'selected' : NULL }}>{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <a href="{{ route('admin.period.index') }}" class="pull-right btn btn-sm btn-warning">
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
                                    <!-- <th>Class</th> -->
                                    <th>Period</th>
                                    <th>Active</th>
                                    <th>Data Keuangan</th>
                                    <th width="20%">Option</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $number = ($periods->currentPage() - 1) * $periods->perPage();
                                @endphp
                                @foreach($periods as $key => $period)
                                    @php $number++ @endphp
                                    <tr>
                                        <td>{{ $number }}</td>
                                        <td>{{$period['name']}}</td>
                                        <td>{{$period->short_desc}}</td>
                                        <td>{{$period->unit->name}}</td>
                                        <!-- <td>{{@$period->class->name}}</td> -->
                                        <td>{{$period['period']}}</td>
                                        <td>{!!$period['active_label']!!}</td>
                                        <td>
                                            <a href="{{ route('admin.period.export', ['id' => $period['id']]) }}" class="btn btn-xs btn-success">
                                                <icon class="icon-plus"><i class="fa fa-file-excel-o"></i></icon>&nbsp;Export
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.period.show', $period['id']) }}" class="btn btn-xs btn-info">
                                                <icon class="icon-plus"><i class="fa fa-eye"></i></icon>
                                            </a>
                                            <a href="{{ route('admin.period.edit',$period['id']) }}" class="btn btn-xs btn-default">
                                                <icon class="icon-plus"><i class="fa fa-pencil"></i></icon>
                                            </a>
                                            <a onclick="confirmDelete({{$period['id']}})" title="Delete" class="btn btn-xs btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <form id="form-delete-{{$period['id']}}" action="{{ route('admin.period.delete',$period['id']) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $periods->appends(request()->except('page'))->links() }}
                        <div class="btn-group padding-t-10 pull-right">
                            <a href="{{ route('admin.period.add') }}" class="btn btn-sm btn-success">
                                <i class="fa fa-plus"></i> Tambah Data
                            </a>
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
        .button-collection {
            margin-bottom: 5px;
        }
    </style>
@endpush
@push('scripts')
    <script>
        function confirmDelete(id) {
            if(confirm('Are you sure you want to delete this item?'))
                document.getElementById('form-delete-' + id).submit();
        }
    </script>
@endpush
