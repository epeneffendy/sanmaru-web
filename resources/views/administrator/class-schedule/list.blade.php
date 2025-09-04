@extends('layouts.admin.main')
@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data Jadwal Pelajaran Siswa</h1>
        <ol class="breadcrumb">
            <li>Master</li>
            <li class="active">Jadwal Pelajaran Siswa</li>
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
                        Data Master Jadwal Pelajaran Siswa
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
                            <table id="datatables-master-class-schedule" class="table display">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Unit</th>
                                    <th>Kelas</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Hari / Jam Mulai - Jam Selesai</th>
                                    <th>Options</th>
                                </tr>
                                </thead>
    
                                <tbody>
                                @foreach($classSchedules as $key => $classSchedule)
                                    <tr>
                                        <td>{{ $classSchedule->id }}</td>
                                        <td>{{ $classSchedule->class->unit->name }}</td>
                                        <td>{{ $classSchedule->class->name }}</td>
                                        <td>{{ $classSchedule->course->name }}</td>
                                        <td>
                                        
                                        {{ \App\Helpers\Helper::hari($classSchedule->day) }} / {{ $classSchedule->start_time }} - {{ $classSchedule->end_time }} <br>
                                        
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.class-schedule.edit',$classSchedule->id) }}" class="btn btn-xs btn-default">
                                                <icon class="icon-plus"><i class="fa fa-pencil"></i></icon>
                                            </a>
                                            <a class="btn btn-xs btn-danger" onclick="confirmDelete({{$classSchedule->id}})">
                                                <icon class="icon-plus"><i class="fa fa-trash"></i></icon>
                                            </a>
                                            <form id="form-delete-{{$classSchedule->id}}" action="{{ route('admin.class-schedule.delete',$classSchedule->id) }}" method="POST">
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
                            <a href="{{ route('admin.class-schedule.add') }}" class="btn btn-sm btn-success">
                                <icon class="icon-plus"><i class="fa fa-plus"></i> Tambah Data</icon>
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
            $('#datatables-master-class-schedule').DataTable();
        });

        function confirmDelete(id) {
            if(confirm('Are you sure you want to delete this item?'))
                document.getElementById('form-delete-' + id).submit();
        }
    </script>
@endpush
