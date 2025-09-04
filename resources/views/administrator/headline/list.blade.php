@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Headline</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li class="active">Headline</li>
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
                        Headline
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

                        <div class="fixed-table-head period">
                            <table id="datatables-master-headline" class="table display">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th width="15%">Unit</th>
                                    <th>Status</th>
                                    <th>Type</th>
                                    <th>Gambar / Video</th>
                                    <th width="20%">Last Update</th>
                                    <th width="25%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $number=0
                                @endphp
                                @foreach($headlines as $key => $headline)
                                    @php $number++ @endphp
                                    <tr>
                                        <td>{{ $number }}</td>
                                        <td>{{ ($headline->is_unit) ? $headline->unit->name : 'KAMPUS' }}</td>
                                        <td>{!! $headline->published_label !!}</td>
                                        <td>{{$headline->type}}</td>
                                        <td>{!! $headline->preview !!}</td>
                                        <td>{{ date( 'd/m/Y H:i', strtotime($headline->updated_at)) }}</td>
                                        <td>
                                            @if (\App\Helpers\Helper::canPublishArticle())
                                            <a href="{{ route('admin.headline.toggle',$headline->id) }}" class="btn btn-xs {{ $headline->isPublished() ? 'btn-warning' : 'btn-info'}}">
                                                <icon class="icon-plus">
                                                    {!! $headline->isPublished() ? '<i class="fa fa-toggle-off"></i> Unpublish' : '<i class="fa fa-toggle-on"></i> Publish' !!}
                                                </icon>
                                            </a>
                                            @endif
                                            <a href="{{ route('admin.headline.edit',$headline['id']) }}" class="btn btn-xs btn-default">
                                                <icon class="icon-plus"><i class="fa fa-pencil"></i></icon>
                                            </a>
                                            <a onclick="confirmDelete({{$headline['id']}})" title="Delete" class="btn btn-xs btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <form id="form-delete-{{$headline['id']}}" action="{{ route('admin.headline.delete',$headline['id']) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                       
                        <div class="btn-group padding-t-10 pull-right">
                            <a href="{{ route('admin.headline.add') }}" class="btn btn-sm btn-success">
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
            $('#datatables-master-headline').DataTable();
        });
        function confirmDelete(id) {
            if(confirm('Are you sure you want to delete this item?'))
                document.getElementById('form-delete-' + id).submit();
        }
    </script>
@endpush
