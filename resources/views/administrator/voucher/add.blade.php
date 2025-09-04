@extends('layouts.admin.main')
@section('content')
    @if(@$status=="edit")
        @php($action=route('admin.voucher.update',array($voucher['id'])))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.voucher.insert'))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Setting Voucher</h1>
        <ol class="breadcrumb">
            <li>Shop</li>
            <li><a href="{{route('admin.voucher.index')}}">Setting Voucher</a></li>
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
                        <h3>{{$status_header}} Setting Voucher</h3>
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
                        <form role="form" method="POST" action="{{$action}}"  class="form-horizontal" enctype="multipart/form-data">
                            @if (@$status == 'Update')
                                @method('PATCH')
                            @endif
                            <input type="hidden" name="id" value="{{@$voucher->id}}" />
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="code">Kode Voucher:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="code" id="code" value="{{old('code', @$voucher['code'])}}" required placeholder="Input Kode voucher">
                                </div>
                                <div class="col-sm-2">
                                    <spab class="btn btn-default" id="generate-code">generate</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="type">Tipe Voucher:</label>
                                <div class="col-sm-10">
                                    <div class="btn-group" data-toggle="buttons">
                                        <label class="btn btn-info {{ old('type', @$voucher->type) === 'free_product' ? 'active' : null }}">
                                            <input type="radio" name="type" value="free_product" data-for="#product_id" {{ old('type', @$voucher->type) === 'free_product' ? 'checked' : NULL }}> Produk Gratis
                                        </label>
                                        <label class="btn btn-info {{ old('type', @$voucher->type) === 'discount_fixed' ? 'active' : null }}">
                                            <input type="radio" name="type" value="discount_fixed" data-for="#discount_fixed" {{ old('type', @$voucher->type) === 'discount_fixed' ? 'checked' : NULL }}> Potongan Harga
                                        </label>
                                        <label class="btn btn-info {{ old('type', @$voucher->type) === 'discount_percent' ? 'active' : null }}">
                                            <input type="radio" name="type" value="discount_percent" data-for="#discount_percent" {{ old('type', @$voucher->type) === 'discount_percent' ? 'checked' : NULL }}> Diskon persen
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group block-discount {{ old('type', @$voucher->type) !== 'free_product' ? 'hide' : NULL}}">
                                <label class="control-label col-sm-2" for="product_id">Product:</label>
                                <div class="col-sm-10">
                                    <select name="product_id[]" id="product_id" class="form-control selectpicker show-tick" data-style="btn-info" multiple data-selected-text-format="count > 3">
                                        <option data-hidden="true"></option>
                                        @foreach($productOption as $value => $label)
                                            <option value="{{ $value }}" {{ @in_array($value, $voucher->product) ? 'selected' : null }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group block-discount {{ old('type', @$voucher->type) !== 'discount_fixed' ? 'hide' : NULL}}">
                                <label class="control-label col-sm-2" for="discount_fixed">Potongan Harga:</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <div class="input-group-addon">Rp</div>
                                        <input type="text" name="discount_fixed" min="1" class="form-control" id="discount_fixed" placeholder="Jumlah" value="{{ old('discount_fixed', @$voucher->type === 'discount_fixed' ? @$voucher->rule : NULL) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group block-discount {{ old('type', @$voucher->type) !== 'discount_percent' ? 'hide' : NULL}}">
                                <label class="control-label col-sm-2" for="discount_percent">Potongan dalam Persen:</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <input type="text" name="discount_percent" min="1" class="form-control" id="discount_percent" placeholder="Jumlah" value="{{ old('discount_percent', @$voucher->type === 'discount_percent' ? @$voucher->rule : NULL) }}">
                                        <div class="input-group-addon">%</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="unit">Target Voucher:</label>
                                <div class="col-sm-10">
                                    @if (@$voucher->unit_id)
                                        @php ($target = 'unit')
                                    @elseif (@$voucher->user_id)
                                        @php ($target = 'student')
                                    @else
                                        @php ($target = 'all')
                                    @endif
                                    <div class="radio radio-info radio-inline">
                                        <input type="radio" id="target_all" value="all" name="target" {{ old('target', $target) === 'all' ? 'checked' : null }}>
                                        <label for="target_all"> Semua Siswa </label>
                                    </div>
                                    <div class="radio radio-info radio-inline">
                                        <input type="radio" id="target_unit" value="unit" data-for="#unit_id" name="target" {{ old('target', $target) === 'unit' ? 'checked' : null }}>
                                        <label for="target_unit"> Khusus Unit </label>
                                    </div>
                                    <div class="radio radio-info radio-inline">
                                        <input type="radio" id="target_student" value="student" data-for="#user_id" name="target" {{ old('target', $target) === 'student' ? 'checked' : null }}>
                                        <label for="target_student"> Khusus Siswa </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="year">Tahun Ajaran:</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="year" id="year" value="{{old('year', @$voucher['year'])}}" required placeholder="Input tahun ajaran">
                                </div>
                            </div>
                            <div class="form-group block-target {{ old('target', $target) === 'unit' ? null : 'hide' }}">
                                <label class="control-label col-sm-2" for="unit_id">Unit:</label>
                                <div class="col-sm-10">
                                    <select name="unit_id[]" id="unit_id" class="form-control selectpicker" data-style="btn-success" multiple>
                                        <option data-hidden="true"></option>
                                        @foreach($unitOption as $value => $label)
                                            <option value="{{ $value }}" {{ @in_array($value, old('unit_id', @$voucher->unit_id)) ? 'selected' : null }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group block-target {{ old('target', $target) === 'student' ? null : 'hide' }}">
                                <label class="control-label col-sm-2" for="user_id">Siswa:</label>
                                <div class="col-sm-10">
                                    <select name="user_id[]" id="user_id" class="form-control selectpicker show-tick" data-style="btn-info" multiple>
                                        <option data-hidden="true"></option>
                                        @foreach($userOption as $value => $label)
                                            <option value="{{ $value }}" {{ @in_array($value, old('user_id', @$voucher->user_id)) ? 'selected' : null }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="note">Note:</label>
                                <div class="col-sm-10">
                                    <textarea name="note" rows="5" class="form-control" placeholder="Note">{{old('note', @$voucher['note'])}}</textarea>
                                </div>
                            </div>
                            <fieldset>
                                <legend>Batas Penggunaan Voucher</legend>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="unit">Tipe Kuota:</label>
                                    <div class="col-sm-10">
                                        @if (@$voucher->usage_type === 'per_user')
                                            @php ($usageType = 'per_user')
                                        @else
                                            @php ($usageType = 'cumulative')
                                        @endif
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="usage_cumulative" value="cumulative" name="usage_type" {{ old('usage_type', $usageType) === 'cumulative' ? 'checked' : null }}>
                                            <label for="usage_cumulative"> Semua / Kumulatif </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="usage_per_user" value="per_user" name="usage_type" {{ old('usage_type', $usageType) === 'per_user' ? 'checked' : null }}>
                                            <label for="usage_per_user"> Per User </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="usage_limit">Jumlah:</label>
                                    <div class="col-sm-2">
                                        <div class="checkbox checkbox-inline checkbox-circle">
                                            @php ($usageLimitOptionAll = isset($voucher) && $voucher && $voucher->usage_limit === -1)
                                            <input id="usage_limit_option_all" type="checkbox" name="usage_limit_option_all" class="checkbox" value="1" {{ old('usage_limit_option_all', $usageLimitOptionAll) ? 'checked' : NULL }} />
                                            <label for="usage_limit_option_all"> Tidak ada batas</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        @php (@$usageLimit = $voucher->usage_limit !== -1 ? $voucher->usage_limit : NULL)
                                        <input type="number" class="form-control" min="1" name="usage_limit" id="usage_limit" value="{{old('usage_limit', $usageLimit) }}" required placeholder="Input maximal batas penggunaan voucher" {{ old('usage_limit_option_all', $usageLimitOptionAll) ? 'disabled' : NULL }}>
                                        <span class="help-block">Masukkan jumlah maksimal penggunaan voucher, centang "tidak ada batas" jika ingin voucher bisa terus digunakan.</span>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="note">Active:</label>
                                <div class="col-sm-10">
                                    <div class="checkbox checkbox-inline checkbox-primary">
                                        <input id="active" type="checkbox" name="active" class="checkbox" value="1" {{ old('active', @$voucher->active) ? 'checked' : null }} />
                                        <label for="active"> Aktif</label>
                                    </div>
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
@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugin/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugin/bootstrap-select/bootstrap-select.css') }}" />
@endpush
@push('scripts')
    <script src="{{asset('js/moment/moment.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-wysihtml5/wysihtml5-0.3.0.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-wysihtml5/bootstrap-wysihtml5.js')}}"></script>
    <script src="{{asset('js/bootstrap-select/bootstrap-select.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('.selectpicker').selectpicker({
                liveSearch: true,
                dropupAuto: false,
                title: "No Value"
            });

            $('textarea[name=note]').wysihtml5();
        });

        $(document).on('change', 'input[name=type]', function(e) {
            let target = $(this).data('for');
            $('.block-discount').addClass('hide');
            $(target).closest('.form-group').removeClass('hide');
        });
        $(document).on('change', 'input[name=target]', function(e) {
            let target = $(this).data('for');
            $('.block-target').addClass('hide');
            $(target).closest('.form-group').removeClass('hide');
        });
        $(document).on('click', '#usage_limit_option_all', function(e) {
            if ($(this).is(":checked")) {
                $('#usage_limit').prop('disabled', true);
            } else {
                $('#usage_limit').prop('disabled', false);
            }
        });
        $(document).on('click', '#generate-code', function(e) {
            e.preventDefault();
            $.get('{{ route('admin.voucher.ajax', ['type' => 'generate-code']) }}', function(data, status) {
                if (data && data.code)
                    $('#code').val(data.code);
            });
        })
    </script>
@endpush
