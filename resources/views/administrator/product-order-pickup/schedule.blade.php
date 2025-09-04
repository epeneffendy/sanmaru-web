@extends('layouts.admin.main')
@section('content')
@php($status_header = 'Jadwal')
<!-- Start Page Header -->
<div class="page-header">
    <h1 class="title">Pengambilan Pesanan</h1>
    <ol class="breadcrumb">
        <li>Shop</li>
        <li><a href="{{route('admin.product-order-pickup.index')}}">Pengambilan Pesanan</a></li>
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
                    <h3>{{$status_header}} Pengambilan Pesanan</h3>
                </div> <!-- /widget-header -->
                <div class="widget-content">
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
                    <form role="form" method="POST" action="{{ route('admin.product-order-pickup.store-schedule') }}"  class="form-horizontal" style="padding-bottom: 20vh">
                        @csrf
                        @if (@$productOrder)
                        <input type="hidden" name="product_order" value="{{ $productOrder->id }}">
                        @else
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="unit">Unit:</label>
                            <div class="col-sm-10">
                                <select name="unit" id="unit" class="form-control">
                                    <option value="">Semua</option>
                                    @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}" {{ old('unit') === $unit->id ? 'selected' : NULL }}>{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="year">Tahun Ajaran:</label>
                            <div class="col-sm-10">
                                <select name="year" id="year" class="form-control">
                                    <option value="">Semua</option>
                                    @foreach ($years as $year)
                                    <option value="{{ $year->year }}" {{ old('year') == $year->year }}>{{ $year->year }} - {{ $year->year + 1 }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="period">Periode:</label>
                            <div class="col-sm-10" >
                                <select name="periods[]" id="periods" class="form-control select-autocomplete" data-style="btn-success" data-dropup-auto="false" data-actions-box="true" multiple>
                                </select>
                            </div>
                        </div>
                        @endif
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="pickup_date_schedule">Tanggal:</label>
                            <div class="col-sm-10">
                                <input type="date" name="pickup_date_schedule" id="pickup_date_schedule" class="form-control" value="{{ old('pickup_date_schedule') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="pickup_date">Tanggal Alternatif:</label>
                            <div class="col-sm-10">
                                <input type="date" name="alt_pickup_date_schedule" id="alt_pickup_date_schedule" class="form-control" value="{{ old('pickup_date_schedule') }}">
                                <small class="text-danger">*Kosongkan jika tidak ada</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="pickup_time">Waktu:</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="time" name="pickup_start_time" id="pickup_start_time" class="form-control" value="{{ old('pickup_start_time') }}">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="time" name="pickup_end_time" id="pickup_end_time" class="form-control" value="{{ old('pickup_end_time') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="pickup_location">Tempat:</label>
                            <div class="col-sm-10">
                                <input type="text" name="pickup_location" id="pickup_location" class="form-control" value="{{ old('pickup_location') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="pickup_notes">Catatan:</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="pickup_notes" id="pickup_notes">{{ old('pickup_notes') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                                <div class="checkbox checkbox-success">
                                    <input type="checkbox" name="send_email" id="send_email" value="1">
                                    <label for="send_email">Kirim email pemberitahuan</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-default">Submit</button>
                            </div>
                        </div>
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
@if (!@$productOrder)
<script src="{{asset('js/bootstrap-select/bootstrap-select.js')}}"></script>
<script>
    $(document).ready(function() {
        $('select.select-autocomplete').selectpicker({liveSearch: true});
        var unitId = $("#unit").find(":selected").val()
        var year = $("#year").find(":selected").val()
        fetchPeriodOption(unitId, year)
    });
    $("#unit").change(function() {
        var unitId = $(this).find(":selected").val()
        var year = $("#year").find(":selected").val()
        fetchPeriodOption(unitId, year)
    })
    $("#year").change(function() {
        var unitId = $("#unit").find(":selected").val()
        var year = $(this).find(":selected").val()
        fetchPeriodOption(unitId, year)
    })

    function fetchPeriodOption(unitId, year) {
        $("#periods").empty()
        $.get("{{ route('admin.product-order-pickup.fetch-period') }}", { unit: unitId, year: year }, function(periods) {
            $.each(periods, function(index, period) {
                element = '<option value="' + period.id + '">[' + period.unit.name + '] ' + period.name + '</option>'
                $("#periods").append(element)
            })
            $('select.select-autocomplete').selectpicker('refresh')
        }, 'json');
    }
</script>
@endif
@endpush
