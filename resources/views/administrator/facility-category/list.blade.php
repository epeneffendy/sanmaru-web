@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Setting Kategori Fasilitas</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li class="active">Setting Kategori Fasilitas</li>
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
                        Setting Kategori Fasilitas
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
                            <table id="datatables-facility-category" class="table display">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Section</th>
                                    <th>Option</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($datas as $key => $data)
                                    <tr>
                                        <td>{{ $data->name }}</td>
                                        <td>{{ $data->section }}</td>
                                        <td>
                                            <a href="{{ route('admin.facility-category.edit',$data['id']) }}" class="btn btn-xs btn-default">
                                                <icon class="icon-plus"><i class="fa fa-pencil"></i></icon>
                                            </a>
                                            <a onclick="confirmDelete({{$data['id']}})" title="Delete" class="btn btn-xs btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <form id="form-delete-{{$data['id']}}" action="{{ route('admin.facility-category.delete',$data['id']) }}" method="POST">
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
                            <a href="{{ route('admin.facility-category.add') }}" class="btn btn-sm btn-success">
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
            $('#datatables-facility-category').DataTable({
                "ordering": false
            });
        });

        function confirmDelete(id) {
            if(confirm('Are you sure you want to delete this item?'))
                document.getElementById('form-delete-' + id).submit();
        }
    </script>
@endpush
