@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Gallery</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li class="active">Gallery</li>
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
                        Gallery
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
                                <form role="form" autocomplete="off" method="GET" action="{{ route('admin.gallery.index') }}">
                                    <input autocomplete="false" name="hidden" disabled type="text" style="display:none;">
                                    <div class="form-group col-md-3">
                                        <input type="text" name="title" placeholder="Search" value="{{ @$params['title'] }}" class="form-control input-sm" />
                                    </div>
                                    <div class="form-group col-md-3">
                                        <select name="unit" class="form-control input-sm" id="unit">
                                                <option value="0">--- Semua unit ---</option>
                                            @foreach (@$units as $key => $value)
                                                <option value="{{ $key }}" {{ $key == old('unit', @$params['unit']) ? 'selected' : NULL }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <a href="{{ route('admin.gallery.index') }}" class="pull-right btn btn-sm btn-warning">
                                        <i class="fa fa-refresh"></i> clear
                                    </a>
                                    <button type="submit" class="pull-right btn btn-sm btn-success">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="fixed-table-head">
                            <table id="datatables-master-galery" class="table display">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul</th>
                                    <th width="150px">Unit</th>
                                    <th width="150px;">Image</th>
                                    <th>Last Update</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $number = ($galleries->currentPage() - 1) * $galleries->perPage();
                                @endphp
                                @foreach($galleries as $key => $gallery)
                                    @php $number++ @endphp
                                    <tr>
                                        <td>{{ $number }}</td>
                                        <td>{{ $gallery->title }}</td>
                                        <td>{{ @$gallery->unit->name }}</td>
                                        <td>
                                            <div style="display: inline-block; width: 125px; vertical-align: top;">
                                                <img src="{{ $gallery->getContentImageUrl() }}" class="img-thumbnail" style="width: 120px; height: auto;" />
                                            </div>
                                        </td>
                                        <td>{{ date( 'd F Y', strtotime($gallery->updated_at)) }}</td>
                                        <td>
                                            @if (\App\Helpers\Helper::canPublishArticle())
                                            <a href="{{ route('admin.gallery.toggle',$gallery->id) }}" class="btn btn-xs {{ $gallery->isPublished() ? 'btn-warning' : 'btn-info'}}">
                                                <icon class="icon-plus">
                                                    {!! $gallery->isPublished() ? '<i class="fa fa-toggle-off"></i> Unpublish' : '<i class="fa fa-toggle-on"></i> Publish' !!}
                                                </icon>
                                            </a>
                                            @endif
                                            <a href="{{ route('admin.gallery.show',$gallery['id']) }}" class="btn btn-xs btn-info">
                                                <icon class="icon-plus"><i class="fa fa-eye"></i></icon>
                                            </a>
                                            <a href="{{ route('admin.gallery.edit',$gallery['id']) }}" class="btn btn-xs btn-default">
                                                <icon class="icon-plus"><i class="fa fa-pencil"></i></icon>
                                            </a>
                                            <a onclick="confirmDelete({{$gallery['id']}})" title="Delete" class="btn btn-xs btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <form id="form-delete-{{$gallery['id']}}" action="{{ route('admin.gallery.delete',$gallery['id']) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        {{ $galleries->appends(request()->except('page'))->links() }}
                        <div class="btn-group padding-t-10 pull-right">
                            <a href="{{ route('admin.gallery.add') }}" class="btn btn-sm btn-success">
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
@push('scripts')
    <script>
        function confirmDelete(id) {
            if(confirm('Are you sure you want to delete this item?'))
                document.getElementById('form-delete-' + id).submit();
        }
    </script>
@endpush
