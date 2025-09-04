@extends('layouts.admin.main')
@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data Master Mata Pelajaran</h1>
        <ol class="breadcrumb">
            <li>Master</li>
            <li class="active">Mata Pelajaran</li>
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
                        Data Master Mata Pelajaran
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
                        <div class="button-collection">
                            <a href="{{ route('admin.course.export') }}" download class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Export</a>
                            <a href="{{ route('admin.course.export', ['template-only']) }}" download class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Download Template .xls</a>
                            <button class="btn btn-default btn-upload-modal btn-sm"><i class="fa fa-upload"></i> Import .xls</b>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading">Filter</div>
                            <div class="panel-body">
                                <form role="form" autocomplete="off" method="GET" action="{{ route('admin.course.index') }}">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <input type="text" name="name" placeholder="Nama Mata Pelajaran" value="{{ old('name') }}" class="form-control input-sm" />
                                        </div>
                                        <div class="form-group col-md-3">
                                            <select name="unit_id" class="form-control input-sm">
                                                <option value="">== SEMUA ==</option>
                                                @foreach (@$units as $unit)
                                                    <option value="{{ $unit->id }}" {{ $unit->id == old('unit_id') ? 'selected' : NULL }}>{{ $unit->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col">
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fa fa-search"></i> Search
                                            </button>
                                            <a href="{{ route('admin.course.index') }}" class="btn btn-sm btn-warning">
                                                <i class="fa fa-refresh"></i> Clear
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="fixed-table-head">
                            <table id="datatables-master-course" class="table display">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <td>Unit</td>
                                    <th>Nama Mata Pelajaran</th>
                                    <th>Kode Mata Pelajaran</th>
                                    <th>Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($courses as $course)
                                    <tr>
                                        <td>{{ $course->id }}</td>
                                        <td>{{ $course->unit ? $course->unit->name : 'Unidefined' }}</td>
                                        <td>{{ $course->name }}</td>
                                        <td>{{ $course->code }}</td>
                                        <td>
                                            <a href="{{ route('admin.course.edit',$course) }}" class="btn btn-xs btn-default">
                                                <icon class="icon-plus"><i class="fa fa-pencil"></i></icon>
                                            </a>
                                            <a href="{{ route('admin.course.delete',$course) }}" class="btn btn-xs btn-danger"
                                               onclick="return confirm('Are you sure you want to delete this item?');">
                                                <icon class="icon-plus"><i class="fa fa-trash"></i></icon>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $courses->links() }}
                        <div class="btn-group padding-t-10 pull-right">
                            <a href="{{ route('admin.course.add') }}" class="btn btn-sm btn-success">
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
    <!-- Modal -->
    <div id="import-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Import Course</h4>
                </div>
                <div class="modal-body">
                    <div class="form">
                        <form class="fieldset-form" method="POST" enctype="multipart/form-data" action={{ route('admin.course.import')}}>
                            @csrf
                            <fieldset>
                                <legend>Import menggunakan template .xls</legend>
                                <div class="form-group">
                                    <input type="file" name="file" class="form-control" />
                                </div>
                                <div class="form-group">
                                    <label class="radio-inline"><input type="radio" style="margin-top: -7px;" value="add" name="type" checked=""> Tambah Data</label>
                                    <label class="radio-inline"><input type="radio" style="margin-top: -7px;" value="overwrite" name="type" checked=""> Perbaharui Data</label>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-default btn-upload-import" type="submit"><i class="fa fa-upload"></i> Upload</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="result">
                    </div>
                    <div class="loadings" style="display:none;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END Modal -->
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
            $('.btn-upload-modal').click(function(e) {
                e.preventDefault();
                $('#import-modal').modal();
            });
        });
    </script>
@endpush
