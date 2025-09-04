@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Setting About Category</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li><a href="{{route('admin.about.select-category')}}">Setting About</a></li>
            <li class="active">Setting About Category</li>
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
                        Setting About Category
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
                        <table id="datatables-about-category" class="table display">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Active</th>
                                <th>Option</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($aboutCategories as $key => $aboutCategory)
                                <tr>
                                    <td>{{ $aboutCategory['name'] }}</td>
                                    <td>{!! $aboutCategory['active_label'] !!}</td>
                                    <td>
                                        <a href="{{ route('admin.about.category.edit', ['aboutCategory' => $aboutCategory['slug']]) }}" class="btn btn-xs btn-default">
                                            <icon class="icon-plus"><i class="fa fa-pencil"></i></icon>
                                        </a>
                                        <a onclick="confirmDelete({{$aboutCategory['id']}})" title="Delete" class="btn btn-xs btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                        <form id="form-delete-{{$aboutCategory['id']}}" action="{{ route('admin.about.category.delete', ['aboutCategory' => $aboutCategory['slug']]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="btn-group padding-t-10 pull-right">
                            <a href="{{ route('admin.about.category.add') }}" class="btn btn-sm btn-success">
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
    <style>
        .button-collection {
            margin-bottom: 5px;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{asset('js/datatables/datatables.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#datatables-about-category').DataTable();
        });

        function confirmDelete(id) {
            if(confirm('Are you sure you want to delete this item?'))
                document.getElementById('form-delete-' + id).submit();
        }
    </script>
@endpush
