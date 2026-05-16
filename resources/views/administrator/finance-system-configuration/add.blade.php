@extends('layouts.admin.main')
@section('content')
    @if (@$status == 'edit')
        @php($action = route('admin.system-configuration.update', [@$configuration['id']]))
        @php($status = 'Update')
        @php($status_header = 'Edit')
    @else
        @php($action = route('admin.system-configuration.store'))
        @php($status = 'Save')
        @php($status_header = 'Tambah')
    @endif
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Setup Tahun Ajaran & Aturan Sistem</h1>
        <ol class="breadcrumb">
            <li>Keuangan</li>
            <li><a href="{{ route('admin.system-configuration.index') }}">Setup Tahun Ajaran & Aturan Sistem</a></li>
            <li class="active">{{ $status_header }}</li>
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
                        <h3>{{ $status_header }} Data</h3>
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
                        <form role="form" method="POST" action="{{ $action }}" class="form-horizontal"
                            enctype="multipart/form-data">
                            <input type="hidden" value="{{ @$configuration['id'] }}" name="id" />

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="min_down_payment">DP Minimum (%)</label>
                                <div class="col-sm-3">
                                    <input type="number" step="0.01" class="form-control" name="min_down_payment"
                                        id="min_down_payment"
                                        value="{{ old('min_down_payment') ?? @$configuration['min_down_payment'] }}"
                                        placeholder="Contoh: 10" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="down_payment_multiple">DP Kelipatan (%)</label>
                                <div class="col-sm-3">
                                    <input type="number" step="0.01" class="form-control" name="down_payment_multiple"
                                        id="down_payment_multiple"
                                        value="{{ old('down_payment_multiple') ?? @$configuration['down_payment_multiple'] }}"
                                        placeholder="Contoh: 5" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="recommended_down_payment">DP Rekomendasi
                                    (%)</label>
                                <div class="col-sm-3">
                                    <input type="number" step="0.01" class="form-control"
                                        name="recommended_down_payment" id="recommended_down_payment"
                                        value="{{ old('recommended_down_payment') ?? @$configuration['recommended_down_payment'] }}"
                                        placeholder="Contoh: 20" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="max_absolute_installment">Max Cicilan
                                    Absolut</label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" name="max_absolute_installment"
                                        id="max_absolute_installment"
                                        value="{{ old('max_absolute_installment') ?? @$configuration['max_absolute_installment'] }}"
                                        placeholder="Contoh: 12" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="effective_date">Tanggal Berlaku</label>
                                <div class="col-sm-3">
                                    <input type="date" class="form-control" name="effective_date" id="effective_date"
                                        value="{{ old('effective_date') ?? @$configuration['effective_date'] }}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-default">{{ $status }}</button>
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
