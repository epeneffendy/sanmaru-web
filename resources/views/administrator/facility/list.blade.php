@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Setting Fasilitas</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li class="active">Setting Fasilitas</li>
        </ol>
    </div>
    <!-- End Page Header -->

    <!-- START CONTAINER -->
    <div class="container-padding">
        <!-- Start Row -->
        <div class="row">
            <!-- Start Panel -->
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">Filter</div>
                    <div class="panel-body">
                        <form role="form" autocomplete="off" method="GET" action="{{route('admin.facility.index')}}">
                            <input autocomplete="false" name="hidden" disabled type="text" style="display:none;">
                            <div class="form-group col-md-3">
                                <input type="text" name="search" placeholder="Search" value="{{ @$params['search'] }}" class="form-control input-sm" />
                            </div>
                            <div class="form-group col-md-3">
                                <select name="unit" class="form-control input-sm" id="unit">
                                        <option value="0">--- Semua unit ---</option>
                                    @foreach (@$units as $key => $value)
                                        <option value="{{ $key }}" {{ $key == old('unit', @$params['unit']) ? 'selected' : NULL }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <select name="category" class="form-control input-sm" id="category">
                                        <option value="0">--- Semua kategori ---</option>
                                    @foreach (@$categories as $key => $value)
                                        <option value="{{ $key }}" {{ $key == old('category', @$params['category']) ? 'selected' : NULL }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <a href="{{route('admin.facility.index')}}" class="pull-right btn btn-sm btn-warning">
                                <i class="fa fa-refresh"></i> clear
                            </a>
                            <button type="submit" class="pull-right btn btn-sm btn-success">
                                <i class="fa fa-search"></i> Search
                            </button>
                        </form>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-title">
                        Setting Fasilitas
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
                            <table id="datatables-facility" class="table display">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th width="150px">Unit</th>
                                    <th width="200px">Name</th>
                                    <th>Kategori</th>
                                    <th>Deskripsi</th>
                                    <th>Option</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($datas as $key => $data)
                                    <tr>
                                        <td>{{(($datas->currentPage()-1) * $datas->perPage()) + ($key+1)}}</td>
                                        <td>{{ $data->unit->name }}</td>
                                        <td>{{ $data->name }}</td>
                                        <td>{{ $data->category->name }}</td>
                                        <td>{{ $data->excerpt }}</td>
                                        <td>
                                            <a href="{{ route('admin.facility.edit',$data['id']) }}" class="btn btn-xs btn-default">
                                                <icon class="icon-plus"><i class="fa fa-pencil"></i></icon>
                                            </a>
                                            <a onclick="confirmDelete({{$data['id']}})" title="Delete" class="btn btn-xs btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <form id="form-delete-{{$data['id']}}" action="{{ route('admin.facility.delete',$data['id']) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        {{ $datas->appends(request()->except('page'))->links() }}
                        <div class="btn-group padding-t-10 pull-right">
                            <a href="{{ route('admin.facility.add') }}" class="btn btn-sm btn-success">
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
