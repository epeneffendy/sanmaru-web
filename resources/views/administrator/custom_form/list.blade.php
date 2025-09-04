@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Custom Form</h1>
        <ol class="breadcrumb">
            <li>PPDB</li>
            <li class="active">Custom Form</li>
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
                        Custom Form
                    </div>
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <form role="form" autocomplete="off" method="GET" action="{{ route('admin.custom_form.index') }}">
                                <input autocomplete="false" name="hidden" disabled type="text" style="display:none;">
                                <div class="form-group col-md-3">
                                    <label for="name" class="form-label">Filter</label>
                                    <input type="text" name="name" placeholder="Search" value="{{ @$params['name'] }}" class="form-control input-sm" />
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="unit" class="form-label">Unit</label>
                                    <select name="unit" class="form-control input-sm">
                                        <option value="0">== SEMUA ==</option>
                                        @foreach (@$units as $unit)
                                            <option value="{{ $unit->id }}" {{ $unit->id == @$params['unit'] ? 'selected' : NULL }}>{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="period" class="form-label">Period</label>
                                    <select name="period" class="form-control input-sm">
                                        <option value="0">== SEMUA ==</option>
                                        @foreach (@$periods as $period)
                                            <option value="{{ $period->id }}" {{ $period->id == @$params['period'] ? 'selected' : NULL }}>{{ $period->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <a href="{{ route('admin.custom_form.index') }}" class="pull-right btn btn-sm btn-warning">
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
                            <table class="table display">
                                <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="20%">Name</th>
                                    <th width="25%">Unit</th>
                                    <th width="25%">Periode</th>
                                    <th width="25%">Option</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($number = ($customForms->currentPage() - 1) * $customForms->perPage())
                                @foreach($customForms as $key => $customForm)
                                    @php($number++)
                                    <tr>
                                        <td>{{ $number }}</td>
                                        <td>{{$customForm['name']}}</td>
                                        <td>{{$customForm->unit['name']}}</td>
                                        <td>{{ @$customForm->period->name }}</td>
                                        <td>
                                            <a href="{{ route('admin.custom_form.show', $customForm['id']) }}" class="btn btn-xs btn-primary">
                                                <icon class="icon-plus"><i class="fa fa-eye"></i></icon>
                                            </a>
                                            <a href="{{ route('admin.custom_form.edit',$customForm['id']) }}" class="btn btn-xs btn-default">
                                                <icon class="icon-plus"><i class="fa fa-pencil"></i></icon>
                                            </a>
                                            <a onclick="confirmDelete({{$customForm['id']}})" title="Delete" class="btn btn-xs btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <form id="form-delete-{{$customForm['id']}}" action="{{ route('admin.custom_form.destroy', $customForm['id']) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $customForms->appends(request()->except('page'))->links() }}
                        <div class="btn-group padding-t-10 pull-right">
                            <a href="{{ route('admin.custom_form.create') }}" class="btn btn-sm btn-success">
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
