@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Setting Popup</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li class="active">Setting Popup</li>
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
                        <form role="form" autocomplete="off" method="GET" action="{{route('admin.popup.index')}}">
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
                            <a href="{{route('admin.popup.index')}}" class="pull-right btn btn-sm btn-warning">
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
                        Setting Popup
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
                                    <th>Judul</th>
                                    <th style="width: 160px;">Tanggal Publish</th>
                                    <th>Unit</th>
                                    <th style="width: 100px;">Publish</th>
                                    <th style="width: 120px;">Option</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($popups as $key => $popup)
                                    <tr>
                                        <td>
                                            <div style="display: inline-block; vertical-align: top; width: calc(100% - 130px); ">
                                                <b>{{ $popup->title }}</b>
                                                <p>{{ $popup->short_desc }}</p>
                                            </div>
                                        </td>
                                        <td>{{ date('j F Y H:i', strtotime($popup['publish_date'])) }}</td>
                                        <td>{{ @$popup->unit->name }}</td>
                                        <td>{!! $popup['published_label'] !!}</td>
                                        <td>
                                            <a href="{{ route('admin.popup.edit',$popup['id']) }}" class="btn btn-xs btn-default">
                                                <icon class="icon-plus"><i class="fa fa-pencil"></i></icon>
                                            </a>
                                            <a onclick="confirmDelete({{$popup['id']}})" title="Delete" class="btn btn-xs btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <form id="form-delete-{{$popup['id']}}" action="{{ route('admin.popup.delete',$popup['id']) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        {{ $popups->appends(request()->except('page'))->links() }}
                        <div class="btn-group padding-t-10 pull-right">
                            <a href="{{ route('admin.popup.add') }}" class="btn btn-sm btn-success">
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
