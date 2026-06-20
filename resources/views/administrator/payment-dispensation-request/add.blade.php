@extends('layouts.admin.main')
@section('content')
    @if (@$status == 'edit')
        @php($action = route('admin.dispensation-request.store', [@$dispensation['id']]))
        @php($status_btn = 'Update')
        @php($status_header = 'Edit')
    @else
        @php($action = route('admin.dispensation-request.store'))
        @php($status_btn = 'Save')
        @php($status_header = 'Tambah')
    @endif
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Kelola Dispensasi Pembayaran Siswa</h1>
        <ol class="breadcrumb">
            <li>Keuangan</li>
            <li><a href="{{ route('admin.dispensation-request.index') }}">Kelola Dispensasi Pembayaran Siswa</a></li>
            <li class="active">{{ $status_header }}</li>
        </ol>
    </div>

    <div class="container-padding">
        <div class="row">
            <div class="widget">
                <div class="widget-header">
                    <h3>{{ $status_header }} Data</h3>
                </div>
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

                    <form role="form" method="POST" action="{{ $action }}" class="form-horizontal"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="{{ @$dispensation['id'] }}" name="id" />


                        <div class="form-group">
                            <label class="col-sm-2 control-label">Tahun Ajaran</label>
                            <div class="col-sm-10">
                                <select name="school_year" id="school_year" class="form-control selectpicker"
                                    data-style="btn-success">
                                    <option value="">Pilih Tahun Ajaran</option>
                                    @foreach ($school_year ?? [] as $year)
                                        <option value="{{ $year }}"
                                            {{ @$dispensation['school_year'] == $year ? 'selected' : '' }}>
                                            {{ $year }} - {{ $year + 1 }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Unit</label>
                            <div class="col-sm-10">
                                <select class="form-control selectpicker" name="unit_id" id="unit_id"
                                    data-style="btn-success">
                                    <option value="">Pilih Unit</option>
                                    @foreach ($units ?? [] as $unit)
                                        <option value="{{ $unit->id }}"
                                            {{ @$dispensation['unit_id'] == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Siswa</label>
                            <div class="col-sm-10">
                                <select class="form-control selectpicker" name="ppdb_user_id" id="ppdb_user_id"
                                    data-style="btn-success" data-live-search="true" required>
                                    <option value="">Pilih Siswa</option>
                                </select>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Dispensation Type</label>
                            <div class="col-sm-10">
                                <select class="form-control selectpicker" name="dispensation_type" id="dispensation_type"
                                    data-style="btn-success" data-live-search="true" required>
                                    <option value="" selected>Pilih Jenis Dispensasi</option>
                                    @foreach ($dispensation_type as $type)
                                        <option value="{{ $type['value'] }}"
                                            {{ @$dispensation['dispensation_type'] == $type['value'] ? 'selected' : '' }}>
                                            {{ $type['label'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
