@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Setting School Life</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li><a href="{{route('admin.school-life.select-category')}}">Setting School Life</a></li>
            <li class="active">{{ $schoolLifeCategory->name }}</li>
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
                        Setting School Life
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
                        <table id="datatables-school-life" class="table display">
                            <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Category</th>
                                <th>Tanggal Publish</th>
                                <th>Publish</th>
                                <th>Option</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($data as $key => $schoolLife)
                                <tr>
                                    <td>
                                        <div style="display: inline-block; width: 125px; vertical-align: top;">
                                            <img src="{{ $schoolLife->getFeaturedImageUrl() }}" style="width: 120px; height: auto;" />
                                        </div>
                                        <div style="display: inline-block; vertical-align: top">
                                            <b>{{ $schoolLife->title }}</b>
                                            <p>{{ $schoolLife->short_desc }}</p>
                                        </div>
                                    </td>
                                    <td><strong>{{ $schoolLife->category->name }}</strong></td>
                                    <td>{{ date('d/m/Y H:i', strtotime($schoolLife['publish_date'])) }}</td>
                                    <td>{!! $schoolLife['published_label'] !!}</td>
                                    <td>
                                        <a href="{{ route('admin.school-life.edit',array($schoolLifeCategory['id'],$schoolLife['id'])) }}" class="btn btn-xs btn-default">
                                            <icon class="icon-plus"><i class="fa fa-pencil"></i></icon>
                                        </a>
                                        <a onclick="confirmDelete({{$schoolLife['id']}})" title="Delete" class="btn btn-xs btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                        <form id="form-delete-{{$schoolLife['id']}}" action="{{ route('admin.school-life.delete',array($schoolLifeCategory['id'],$schoolLife['id'])) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <td>No data</td>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="btn-group padding-t-10 pull-right">
                            <a href="{{ route('admin.school-life.add', array($schoolLifeCategory['id'])) }}" class="btn btn-sm btn-success">
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
