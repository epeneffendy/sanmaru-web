@extends('layouts.admin.main')
@section('content')
    @if(@$usecase=="update")
        @php($action=route('admin.user.update',array($user->id)))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.user.insert'))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data Master User</h1>
        <ol class="breadcrumb">
            <li>Master</li>
            <li><a href="{{route('admin.user.index')}}">User</a></li>
            <li class="active">{{$status_header}}</li>
        </ol>

    </div>
    <!-- End Page Header -->

    <!-- START CONTAINER -->
    <div class="container-padding">
        <!-- Start Row -->
        <div class="row">
            <!-- Start Panel -->
            <div class="col-md-12">
                <div class="widget " style="padding-bottom: 150px">
                    <div class="widget-header">
                        <h3>{{$status_header}} User</h3>
                    </div> <!-- /widget-header -->

                    <div class="widget-content">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form role="form" method="POST" action="{{$action}}" class="form-horizontal"
                              enctype="multipart/form-data">
                            <input type="hidden" value="{{ @$user->id }}" name="id" />
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="username">Username:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="username" id="username"
                                           value="{{old('username') ?? @$user->username}}" placeholder="Enter username"
                                           required {{($status=="Update")?"readonly":""}}>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="email">Email:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="email" id="email"
                                           value="{{old('email') ?? @$user->email}}" placeholder="Enter email"
                                           required {{($status=="Update")?"readonly":""}}>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="mobile_phone">Mobile Phone:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="mobile_phone" id="mobile_phone"
                                           value="{{old('mobile_phone') ?? @$user->mobile_phone}}" placeholder="Enter Mobile Phone"
                                           required {{($status=="Update")?"readonly":""}}>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="sel1">Tipe User:</label>
                                <div class="col-sm-10">
                                    <select name="type" class="form-control" id="sel0" data-dropup-auto="false" title="pilih tipe user">
                                        <option data-hidden="true">pilih tipe user</option>
                                        <option value="admin" {{(@$user->type=="admin")?"selected":""}}>Admin</option>
                                        <option value="siswa" {{(@$user->type=="siswa")?"selected":""}}>Siswa</option>
                                        <option value="guru" {{(@$user->type=="guru")?"selected":""}}>Guru</option>
                                        <option value="vendor" {{(@$user->type=="vendor")?"selected":""}}>Vendor
                                        <option value="ppdb" {{(@$user->type=="ppdb")?"selected":""}}>User PPDB</option>
                                        <option value="admin_ppdb" {{(@$user->type=="admin_ppdb")?"selected":""}}>Admin PPDB</option>
                                        <option value="author" {{(@$user->type=="author")?"selected":""}}>Author</option>
                                        <option value="editor" {{(@$user->type=="editor")?"selected":""}}>Editor</option>
                                        <option value="shop" {{(@$user->type=="shop")?"selected":""}}>Admin Shop</option>
                                        <option value="super_admin" {{(@$user->type=="super_admin")?"selected":""}}>Super Admin</option>
                                        <option value="ksp" {{(@$user->type=="ksp")?"selected":""}}>KSP</option>
                                        <option value="pegawai" {{(@$user->type=="pegawai")?"selected":""}}>Pegawai</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group" {!! @in_array(old('type', @$user->type), ['admin', 'ppdb', 'admin_ppdb', 'shop', 'ksp']) ? NULL : 'style="display: none;"' !!}>
                                <label class="control-label col-sm-2" for="sel1">Role Unit:</label>
                                <div class="col-sm-10">
                                    <select name="role_units[]" class="form-control" data-style="btn-info" data-dropup-auto="false" multiple data-actions-box="true">
                                        @foreach ($units as $id => $unit)
                                            <option value="{{ $id }}" {{ @in_array($id, old('role_units', @$user->role_units)) ? 'selected' : NULL }}>{{ $unit }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="sel2">Status:</label>
                                <div class="col-sm-10">
                                    <select name="status" class="form-control" id="sel2">
                                        <option value="active" {{(@$user->status=='active')?"selected":""}}>Aktif
                                        </option>
                                        <option value="inactive" {{(@$user->status=='inactive')?"selected":""}}>Tidak
                                            Aktif
                                        </option>
                                    </select>
                                </div>
                            </div>
                            @if (@$user)
                            <fieldset>
                                <legend>Isi password hanya jika ingin diganti:</legend>
                            @endif
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="pwd">Password:</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" name="password" id="pwd"
                                            placeholder="Enter password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="pwd2">Confirm Password:</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" name="password_confirmation" id="pwd2"
                                            placeholder="Enter password confirmation">
                                    </div>
                                </div>
                            @if (@$user)
                            </fieldset>
                            @endif
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-default">{{$status}}</button>
                                </div>
                            </div>
                            <!-- /bottom-wizard -->
                            @csrf
                        </form>
                    </div>
                </div> <!-- /widget-content -->
            </div>
            <!-- End Panel -->
        </div>
        <!-- End Row -->
    </div>
    <!-- END CONTAINER -->
@endsection

@push('scripts')
    <script src="{{asset('js/bootstrap-select/bootstrap-select.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('select').selectpicker({liveSearch: true});
        });

        $(document).on('change', 'select[name=type]', function(e) {
            if (['admin', 'ppdb', 'shop', 'admin_ppdb', 'ksp'].indexOf($(this).val()) != -1) {
                $('select[name="role_units[]"]').parents('.form-group').show();
            } else {
                $('select[name="role_units[]"]').parents('.form-group').hide();
            }
        });
    </script>
@endpush
