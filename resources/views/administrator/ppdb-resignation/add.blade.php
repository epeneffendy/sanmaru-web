@extends('layouts.admin.main')
@section('content')
    @php($action=route('admin.ppdb-resignation.insert'))
    @php($status="Save")
    @php($status_header="Tambah")
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Pengunduran Diri Siswa</h1>
        <ol class="breadcrumb">
            <li>PPDB</li>
            <li><a href="{{route('admin.ppdb-resignation.index')}}">Pengunduran Diri Siswa</a></li>
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
                <div class="widget ">
                    <div class="widget-header">
                        <h3>{{$status_header}} Pengunduran Diri Siswa</h3>
                    </div> <!-- /widget-header -->
                    <div class="widget-content">
                        @if (session('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form role="form" method="POST" action="{{$action}}"  class="form-horizontal">
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="type">Unit:</label>
                                <div class="col-sm-10">
                                    <select name="unit_id" class="form-control">
                                        @foreach($units as $unit)
                                            <option value="{{$unit->id}}" {{ old('unit_id') == $unit->id ? 'selected' : NULL }}>{{$unit->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="register_number">Nomor Registrasi:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="register_number" id="register_number" value="{{old('register_number')}}" placeholder="Masukkan Nomor Registrasi">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Nama:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name" value="{{old('name')}}"  readonly>
                                </div>
                            </div>

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
<script>
    $(document).ready(function () {
        $('input').on('blur', function () {
            getPPDBUser();
        });

        $('select').on('change', function () {
            getPPDBUser();
        });

        function getPPDBUser() {
            let unitId = $('select[name=unit_id]').val();
            let registerNumber = $('input[name=register_number]').val();
            if (unitId != '' && registerNumber != '') {
                $.get("{{route('admin.ppdb-resignation.ajax')}}?unit_id="+unitId+"&register_number="+registerNumber, function (res) {
                    if (res.data.name) {
                        $('input[name=name]').val(res.data.name);
                    } else {
                        $('input[name=name]').val('');
                        $('input[name=register_number]').val('');
                        alert('data tidak ditemukan') ;
                    }
                }).fail(function (err) {
                    $('input[name=name]').val('');
                    $('input[name=register_number]').val('');
                    alert('data tidak ditemukan');
                });
            }
        }
    });
</script>
@endpush
