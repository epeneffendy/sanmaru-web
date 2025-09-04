@extends('layouts.admin.main')
@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data Master Student</h1>
        <ol class="breadcrumb">
            <li>Master</li>
            <li class="active">Student</li>
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
                        Data Master Student
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
                            <a href="{{ route('admin.student.export', request()->except('page')) }}" download class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Export</a>
                            <a href="{{ route('admin.student.export', ['template-only']) }}" download class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Download Template .xls</a>
                            <button class="btn btn-default btn-upload-modal btn-sm"><i class="fa fa-upload"></i> Import .xls</b>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="panel-title">Filter</div>
                            </div>
                            <div class="panel-body">
                                <form role="form" autocomplete="off" method="GET" action="{{ route('admin.student.index') }}">
                                    <input type="hidden" name="apply_filter" value="1">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label for="search" class="form-label">Pencarian</label>
                                            <input id="search" type="text" name="search" placeholder="Search" value="{{ old('search', @$params['search']) }}" class="form-control input-sm" />
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="scope" class="form-label">berdasarkan</label>
                                            <select name="scope" id="scope" class="form-control input-sm">
                                                @foreach ($scopes as $key => $scope)
                                                <option value="{{ $key }}" {{ $key == old('scope', @$params['scope']) ? 'selected' : null }}>{{ $scope }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="unit" class="form-label">Unit</label>
                                            <select name="unit" id="unit" class="form-control input-sm">
                                                <option value="">== Semua ==</option>
                                                @foreach (@$units as $unit)
                                                    <option value="{{ $unit->id }}" {{ $unit->id == old('unit', @$params['unit']) ? 'selected' : NULL }}>{{ $unit->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="year" class="form-label">Tahun</label>
                                            <select name="year" id="year" class="form-control input-sm">
                                                <option value="">== Semua ==</option>
                                                @foreach ($years as $year)
                                                <option value="{{ $year->year }}" {{ $year->year == old('year', @$params['year']) ? 'selected' : null }}>{{ $year->year }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" class="pull-right btn btn-sm btn-success" style="margin-left: 5px">
                                                <i class="fa fa-search"></i> Search
                                            </button>
                                            <a href="{{ route('admin.student.index') }}" class="pull-right btn btn-sm btn-warning">
                                                <i class="fa fa-refresh"></i> Clear
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="fixed-table-head">
                            <table id="datatables-master-siswa" class="table display">
                                <thead>
                                <tr>
                                    <th>NIS</th>
                                    <th>Detail Siswa</th>
                                    <th>Mobile Phone</th>
                                    <th>Alamat</th>
                                    <th>Class Name</th>
                                    {{-- <th>Payment ID</th> --}}
                                    <th>Tahun</th>
                                    <th>Status</th>
                                    <th width="150px">Option</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($students as $student)
                                    <tr>
                                        <td>{{$student->nis}}</td>
                                        <td>
                                            <b class="d-block">{{$student->name}}</b>
                                            <small class="d-block text-muted">email: {{$student->email}}</small>
                                            <label class="label label-sm label-default">username: {{@$student->user->username}}</label>
                                        </td>
                                        <td>{{$student->mobile_phone}}</td>
                                        <td>{{$student->address}}</td>
                                        <td>
                                            <b class="d-block">{{@$student->class->name}}</b>
                                            unit: {{@$student->class->unit->name}}
                                        </td>
                                        {{-- <td>{{$student->payment->name}}</td> --}}
                                        <td>{{$student->school_year}}</td>
                                        <td>{!! $student->status_label !!}</td>
                                        <td>
                                            <a href="{{ route('admin.student.show', $student->id) }}" title="Show" class="btn btn-xs btn-success">
                                                <icon class="icon-plus"><i class="fa fa-eye"></i></icon>
                                            </a>
                                            <a href="{{ route('admin.student.edit', $student->id) }}" title="Edit" class="btn btn-xs btn-default">
                                                <icon class="icon-plus"><i class="fa fa-pencil"></i></icon>
                                            </a>
                                            <a href="{{ route('admin.student.delete', $student->id) }}" title="Delete" class="btn btn-xs btn-danger"
                                               onclick="return confirm('Are you sure you want to delete this item?');">
                                                <icon class="icon-plus"><i class="fa fa-trash"></i></icon>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $students->appends(request()->except('page'))->links() }}
                        <div class="btn-group padding-t-10 pull-right">
                            <a href="{{ route('admin.student.add') }}" class="btn btn-sm btn-success">
                                <icon class="icon-plus"><i class="fa fa-plus"></i> Tambah Data</icon>
                            </a>
                            {{--<a href="{{route('user.export')}}" class="btn btn-primary"><icon class="icon-save"> Export</icon></a>--}}
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
                    <h4 class="modal-title">Import Students</h4>
                </div>
                <div class="modal-body">
                    <div class="form">
                        <form class="fieldset-form" method="POST" enctype="multipart/form-data" action={{ route('admin.student.import')}}>
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
    <style>
        .button-collection {
            margin-bottom: 5px;
        }

        .d-block {
            display: block;
        }
    </style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function () {
            $('.btn-upload-modal').click(function(e) {
                e.preventDefault();
                $('#import-modal').modal();
            });
        });
    </script>
@endpush

