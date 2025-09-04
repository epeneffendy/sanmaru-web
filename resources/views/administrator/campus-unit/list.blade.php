@extends('layouts.admin.main')
@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Kampus Unit - {{$campus->name}}</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li><a href="{{route('admin.campus.select')}}">Kampus</a></li>
            <li class="active">{{$campus->name}}</li>
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
                        Kampus Unit - {{ $campus->name }}
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
                        <table id="datatables-campus-unit" class="table display">
                            <thead>
                            <tr>
                                <th>Unit</th>
                                <th width="30%">Option</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($campus->campusUnits as $key => $campusUnit)
                                <tr>
                                    <td>{{ $campusUnit->unit->name }}</td>
                                    <td>
                                        <a href="{{ $campusUnit->permalink }}" class="btn btn-xs btn-default" target="_blank">
                                            <icon class="icon-plus"><i class="fa fa-eye"></i></icon>Lihat Profil
                                        </a>
                                        <a href="{{ route('admin.campus.unit.edit', array($campus['id'], $campusUnit['id'])) }}" class="btn btn-xs btn-default">
                                            <icon class="icon-plus"><i class="fa fa-pencil"></i></icon>
                                        </a>
                                        <a onclick="confirmDelete({{$campusUnit['id']}})" title="Delete" class="btn btn-xs btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                        <form id="form-delete-{{$campusUnit['id']}}" action="{{ route('admin.campus.unit.delete',array($campus['id'], $campusUnit['id'])) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="btn-group padding-t-10 pull-right">
                            <a href="{{ route('admin.campus.unit.add', array($campus['id'])) }}" class="btn btn-sm btn-success">
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
            $('#datatables-campus-unit').DataTable();
        });

        function confirmDelete(id) {
            if(confirm('Are you sure you want to delete this item?'))
                document.getElementById('form-delete-' + id).submit();
        }
    </script>
@endpush
