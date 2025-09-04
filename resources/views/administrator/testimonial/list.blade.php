@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Testimonial</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li class="active">Testimonial</li>
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
                        <form role="form" autocomplete="off" method="GET" action="{{route('admin.testimonial.index')}}">
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
                            <a href="{{route('admin.testimonial.index')}}" class="pull-right btn btn-sm btn-warning">
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
                        Testimonial
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
                            <table id="datatables-master-testimonial" class="table display">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Subject</th>
                                    <th width="15px">Photo</th>
                                    <th width="150px">Unit</th>
                                    <th>Content</th>
                                    <th>Last Update</th>
                                    <th width="250px">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $number = ($testimonials->currentPage() - 1) * $testimonials->perPage();
                                @endphp
                                @foreach($testimonials as $key => $testimonial)
                                    @php $number++ @endphp
                                    <tr>
                                        <td>{{ $number }}</td>
                                        <td>{{ $testimonial->subject }}</td>
                                        <td>{!! $testimonial->preview !!}</td>
                                        <td>{{ @$testimonial->unit->name }}</td>
                                        <td>{{ $testimonial->content }}</td>
                                        <td>{{ date( 'd/m/Y', strtotime($testimonial->updated_at)) }}</td>
                                        <td>
                                            @if (\App\Helpers\Helper::canPublishArticle())
                                            <a href="{{ route('admin.testimonial.toggle',$testimonial->id) }}" class="btn btn-xs {{ $testimonial->isPublished() ? 'btn-warning' : 'btn-info'}}">
                                                <icon class="icon-plus">
                                                    {!! $testimonial->isPublished() ? '<i class="fa fa-toggle-off"></i> Unpublish' : '<i class="fa fa-toggle-on"></i> Publish' !!}
                                                </icon>
                                            </a>
                                            @endif
                                            <a href="{{ route('admin.testimonial.edit',$testimonial['id']) }}" class="btn btn-xs btn-default">
                                                <icon class="icon-plus"><i class="fa fa-pencil"></i></icon>
                                            </a>
                                            <a onclick="confirmDelete({{$testimonial['id']}})" title="Delete" class="btn btn-xs btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <form id="form-delete-{{$testimonial['id']}}" action="{{ route('admin.testimonial.delete',$testimonial['id']) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        {{ $testimonials->appends(request()->except('page'))->links() }}
                        <div class="btn-group padding-t-10 pull-right">
                            <a href="{{ route('admin.testimonial.add') }}" class="btn btn-sm btn-success">
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
