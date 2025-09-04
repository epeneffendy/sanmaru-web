@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Setting Beasiswa</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li class="active">Setting Beasiswa</li>
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
                        Setting Beasiswa
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
                        <div class="panel panel-primary">
                            <div class="panel-heading">Filter</div>
                            <div class="panel-body">
                                <form role="form" autocomplete="off" method="GET" action="{{ route('admin.scholarship.index') }}">
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
                                    <a href="{{ route('admin.scholarship.index') }}" class="pull-right btn btn-sm btn-warning">
                                        <i class="fa fa-refresh"></i> clear
                                    </a>
                                    <button type="submit" class="pull-right btn btn-sm btn-success">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="fixed-table-head">
                            <table id="datatables-scholarship" class="table display">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Beasiswa</th>
                                    <th width="15%">Tanggal Publish</th>
                                    <th width="10%">Publish</th>
                                    <th width="25%">Option</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $number = ($scholarships->currentPage() - 1) * $scholarships->perPage();
                                @endphp
                                @foreach($scholarships as $key => $scholarship)
                                    @php $number++ @endphp
                                    <tr>
                                        <td>{{$number}}</td>
                                        <td>
                                            <div style="display: inline-block; vertical-align: top">
                                                <p><strong>{{ $scholarship->name }}</strong><br>
                                                <strong>UNIT: {{ ($scholarship->is_unit) ? $scholarship->unit->name : 'KAMPUS' }}</strong><br>
                                                {{ $scholarship->short_desc }}</p>
                                            </div>
                                        </td>
                                        <td>{{ date('j F Y', strtotime($scholarship['publish_date'])) }}</td>
                                        <td>{!! $scholarship['published_label'] !!}</td>
                                        <td>
                                            @if (\App\Helpers\Helper::canPublishArticle())
                                            <a href="{{ route('admin.scholarship.toggle',$scholarship->id) }}" class="btn btn-xs {{ $scholarship->isPublished() ? 'btn-warning' : 'btn-info'}}">
                                                <icon class="icon-plus">
                                                    {!! $scholarship->isPublished() ? '<i class="fa fa-toggle-off"></i> Unpublish' : '<i class="fa fa-toggle-on"></i> Publish' !!}
                                                </icon>
                                            </a>
                                            @endif
                                            <a href="{{ route('admin.scholarship.show', $scholarship['id']) }}" class="btn btn-xs btn-info">
                                                <icon class="icon-plus"><i class="fa fa-eye"></i></icon>
                                            </a>
                                            <a href="{{ route('admin.scholarship.edit',$scholarship['id']) }}" class="btn btn-xs btn-default">
                                                <icon class="icon-plus"><i class="fa fa-pencil"></i></icon>
                                            </a>
                                            <a onclick="confirmDelete({{$scholarship['id']}})" title="Delete" class="btn btn-xs btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <form id="form-delete-{{$scholarship['id']}}" action="{{ route('admin.scholarship.delete',$scholarship['id']) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            
                                </tbody>
                            </table>
                        </div>
                        
                        {{ $scholarships->appends(request()->except('page'))->links() }}
                        <div class="btn-group padding-t-10 pull-right">
                            <a href="{{ route('admin.scholarship.add') }}" class="btn btn-sm btn-success">
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
