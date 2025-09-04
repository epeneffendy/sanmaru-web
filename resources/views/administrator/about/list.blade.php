@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Setting About</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li><a href="{{route('admin.about.select-category')}}">Setting About</a></li>
            <li class="active">{{ $category->name }}</li>
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
                        <form role="form" autocomplete="off" method="GET" action="{{route('admin.about.index', [$category['slug']])}}">
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
                            <a href="{{route('admin.about.index', [$category['slug']])}}" class="pull-right btn btn-sm btn-warning">
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
                        Setting About
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
                        <table id="datatables-about" class="table display">
                            <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Category</th>
                                <th width="150px">Unit</th>
                                <th>Tanggal Publish</th>
                                <th>Publish</th>
                                <th>Option</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $key => $about)
                                <tr>
                                    <td>
                                        @if($about->featured_image)
                                        <div style="display: inline-block; width: 125px; vertical-align: top;">
                                            <img src="{{ $about->getFeaturedImageUrl() }}" style="width: 120px; height: auto;" />
                                        </div>
                                        @endif
                                        <div style="display: inline-block; vertical-align: top">
                                            <b>{{ $about->title }}</b>
                                            <p>{{ $about->short_desc }}</p>
                                        </div>
                                    </td>
                                    <td><strong>{{ $category->name }}</strong></td>
                                    <td>{{ @$about->unit->name }}</td>
                                    <td>{{ date('d F Y H:i', strtotime($about['publish_date'])) }}</td>
                                    <td>{!! $about['published_label'] !!}</td>
                                    <td>
                                        <a href="{{ route('admin.about.edit',array($category['slug'],$about['slug'])) }}" class="btn btn-xs btn-default">
                                            <icon class="icon-plus"><i class="fa fa-pencil"></i></icon>
                                        </a>
                                        <a onclick="confirmDelete({{$about['id']}})" title="Delete" class="btn btn-xs btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                        <form id="form-delete-{{$about['id']}}" action="{{ route('admin.about.delete',array($category['slug'],$about['slug'])) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="btn-group padding-t-10 pull-right">
                            <a href="{{ route('admin.about.add', array($category['slug'])) }}" class="btn btn-sm btn-success">
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
    <link rel="stylesheet" href="{{asset('css/plugin/datatables/datatables.css')}}">
@endpush
@push('scripts')
    <script src="{{asset('js/datatables/datatables.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#datatables-about').DataTable();
        });

        function confirmDelete(id) {
            if(confirm('Are you sure you want to delete this item?'))
                document.getElementById('form-delete-' + id).submit();
        }
    </script>
@endpush
