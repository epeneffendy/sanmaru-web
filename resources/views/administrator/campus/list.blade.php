@extends('layouts.admin.main')
@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Manage Kampus</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li><a href="{{route('admin.campus.select')}}">Kampus</a></li>
            <li class="active">Manage Kampus</li>
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
                        Manage Kampus
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
                        <table id="datatables-campus" class="table display">
                            <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Option</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($campuses as $key => $campus)
                                <tr>
                                    <td>{{ $campus['name'] }}</td>
                                    <td>
                                        <a href="{{ route('admin.campus.edit', $campus['id']) }}" class="btn btn-xs btn-default">
                                            <icon class="icon-plus"><i class="fa fa-pencil"></i></icon>
                                        </a>
                                        <a onclick="confirmDelete({{$campus['id']}})" title="Delete" class="btn btn-xs btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                        <form id="form-delete-{{$campus['id']}}" action="{{ route('admin.campus.delete',$campus['id']) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="btn-group padding-t-10 pull-right">
                            <a href="{{ route('admin.campus.add') }}" class="btn btn-sm btn-success">
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
            $('#datatables-campus').DataTable();
        });

        function confirmDelete(id) {
            if(confirm('Are you sure you want to delete this item?'))
                document.getElementById('form-delete-' + id).submit();
        }
    </script>
@endpush
