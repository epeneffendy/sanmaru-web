@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Setting School Life Category</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li><a href="{{route('admin.school-life.select-category')}}">Setting School Life</a></li>
            <li class="active">Setting School Life Category</li>
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
                        Setting School Life Category
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
                        <table id="datatables-schol-life-category" class="table display">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Active</th>
                                <th>Option</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($schoolLifeCategories as $key => $category)
                                <tr>
                                    <td>{{ $category['name'] }}</td>
                                    <td>{!! $category['active_label'] !!}</td>
                                    <td>
                                        <a href="{{ route('admin.school-life.category.edit',$category['id']) }}" class="btn btn-xs btn-default">
                                            <icon class="icon-plus"><i class="fa fa-pencil"></i></icon>
                                        </a>
                                        <a onclick="confirmDelete({{$category['id']}})" title="Delete" class="btn btn-xs btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                        <form id="form-delete-{{$category['id']}}" action="{{ route('admin.school-life.category.delete',$category['id']) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="btn-group padding-t-10 pull-right">
                            <a href="{{ route('admin.school-life.category.add') }}" class="btn btn-sm btn-success">
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
            $('#datatables-school-life-category').DataTable();
        });

        function confirmDelete(id) {
            if(confirm('Are you sure you want to delete this item?'))
                document.getElementById('form-delete-' + id).submit();
        }
    </script>
@endpush
