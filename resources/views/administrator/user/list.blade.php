@extends('layouts.admin.main')
@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data Master User</h1>
        <ol class="breadcrumb">
            <li>Master</li>
            <li class="active">User</li>
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
                        Data Master User
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
                            <a href="{{ route('admin.user.export', request()->except('page')) }}" download class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Export</a>
                            <!-- <a href="{{ route('admin.user.export', ['template-only']) }}" download class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Download Template .xls</a>
                            <button class="btn btn-default btn-upload-modal btn-sm"><i class="fa fa-upload"></i> Import .xls</b> -->
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading">Filter</div>
                            <div class="panel-body">
                                <form role="form" autocomplete="off" method="GET" action="{{ route('admin.user.index') }}">
                                    <input autocomplete="false" name="hidden" disabled type="text" style="display:none;">
                                    <div class="form-group col-md-3">
                                        <input type="text" name="user" placeholder="Search" value="{{ @$params['user'] }}" class="form-control input-sm" />
                                    </div>
                                    <div class="form-group col-md-3">
                                        <select name="type" class="form-control input-sm">
                                            <option value="0">== SEMUA ==</option>
                                            @php 
                                                $roles = ['admin', 'guru', 'siswa', 'vendor', 'ppdb', 'author', 'admin_ppdb', 'editor', 'shop', 'super_admin', 'ksp'];
                                            @endphp
                                            @foreach ($roles as $role)
                                                <option value="{{ $role }}" {{ @$params['type'] == $role ? 'selected' : NULL }}> {{ $role }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <a href="{{ route('admin.user.index') }}" class="pull-right btn btn-sm btn-warning">
                                        <i class="fa fa-refresh"></i> clear
                                    </a>
                                    <button type="submit" class="pull-right btn btn-sm btn-success">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </form>
                            </div>
                        </div>
                        <table id="datatables-master-user" class="table display">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Status</th>
                                @if (\App\Helpers\Helper::isSuperAdminRole())
                                <th>Option</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $key => $value)
                                <tr>
                                    <td>{{$value['id']}}</td>
                                    <td>
                                        <b class="d-block">{{$value['username']}}</b><br/>
                                        <small class="d-block text-muted">email: {{$value['email']}}</small>
                                    </td>
                                    <td>{{$value['user_type']}}</td>
                                    <td>{{($value['status'])=='active'?"Aktif":"Tidak Aktif"}}</td>

                                    @if (\App\Helpers\Helper::isSuperAdminRole())
                                    <td>
                                        <a href="{{ route('admin.user.edit',$value['id']) }}" class="btn btn-xs btn-default">
                                            <icon class="icon-plus"><i class="fa fa-pencil"></i></icon>
                                        </a>
                                        <a href="{{ route('admin.user.delete',$value['id']) }}" class="btn btn-xs btn-danger"
                                           onclick="return confirm('Are you sure you want to delete this item?');">
                                            <icon class="icon-plus"><i class="fa fa-trash"></i></icon>
                                        </a>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $data->appends(request()->except('page'))->links() }}
                        @if (\App\Helpers\Helper::isSuperAdminRole())
                        <div class="btn-group padding-t-10 pull-right">
                            <a href="{{ route('admin.user.add') }}" class="btn btn-sm btn-success">
                                <i class="fa fa-plus"></i> Tambah Data
                            </a>
                        </div>
                        @endif
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
                    <h4 class="modal-title">Import Users</h4>
                </div>
                <div class="modal-body">
                    <div class="form">
                        <form class="fieldset-form" method="POST" enctype="multipart/form-data" action={{ route('admin.user.import')}}>
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
    <script>
        $(document).ready(function () {
            $('.btn-upload-modal').click(function(e) {
                e.preventDefault();
                $('#import-modal').modal();
            });

        });
    </script>
@endpush
