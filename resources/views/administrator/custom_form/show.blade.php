@extends('layouts.admin.main')
@section('content')
@php($status="Show")
@php($status_header="Show")
<!-- Start Page Header -->
<div class="page-header">
    <h1 class="title">Data Input</h1>
    <ol class="breadcrumb">
        <li>PPDB</li>
        <li><a href="{{route('admin.custom_form.index')}}">Custom Form</a></li>
        <li class="active">{{$customForm->name}}</li>
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
                <div class="button-collection pull-right">
                    <a href="{{ route('admin.custom_form.export', ['id' => $customForm['id'], 'period' => @$params['period']]) }}" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Export</a>
                </div>
                <div class="widget-header">
                    <h3>{{$status_header}} {{$customForm->name}}</h3>
                </div> <!-- /widget-header -->
                <div class="widget-content">
                    <div class="form-horizontal">
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
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="name">Nama:</label>
                            <div class="col-sm-10">
                                {{ @$customForm->name }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="name">Unit:</label>
                            <div class="col-sm-10">
                                {{ @$customForm->unit->name }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="name">Periode:</label>
                            <div class="col-sm-10">
                                {{ @$customForm->periods->pluck('name')->join(', ') }}
                            </div>
                        </div>
                        <div class="form-group">
                            <form action="{{ route('admin.custom_form.show', $customForm->id) }}" method="GET">
                                <label class="control-label col-sm-2" for="unit">Filter:</label>
                                <div class="col-sm-8">
                                    <select name="period" id="period" data-style="btn-primary" class="form-control selectpicker">
                                        <option value="">Semua Periode</option>
                                        @foreach(@$customForm->periods as $period)
                                            <option value="{{ $period->id }}" {{ @$params['period'] == $period->id ? 'selected' : NULL }}>{{ $period->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <button type="submit" class="btn btn-primary">Apply</button>
                                </div>
                            </form>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                                <fieldset style="padding: 20px; margin 0 2px; border-radius: 3px; border: 1px solid #ccc; padding-top: 10px">
                                    <legend style="border-bottom: 0px; width: auto; padding: 0px 10px; font-size: 16px; font-weight: 600">Input</legend>
                                    <div class="form-group col-md-12" style="margin-left: 1px; margin-right: 5px">
                                        <a class="show-columns details-toggle" href="#">Show details</a>
                                        <a class="hide-columns details-toggle" href="#">Hide details</a>
                                        <div class="table-horizontal-scroll">
                                            <div class="table-wrap">
                                                <table class="table table-sm table-bordered table-hideable">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 50px">No</th>
                                                            <th>Register Number</th>
                                                            <th>Student</th>
                                                            @foreach ($customForm->columns as $column)
                                                            <th>{{ $column->label }}</th>
                                                            @endforeach
                                                        </tr>
                                                    </thead>
                                                    <tbody id="selected-product-details">
                                                        @php($no=1)

                                                        @foreach ($customForm->columnInputs->groupBy('ppdb_user_id') as $key => $details)
                                                        @if (!@$params['period'] || (@$params['period'] && $details->first()->ppdb_user->periode == $params['period']))
                                                        <tr>
                                                            <td style="width: 50px">{{ $no++ }}</td>
                                                            <td>{{ $details->first()->ppdb_user->register_number }}</td>
                                                            <td>{{ $details->first()->ppdb_user->name }}</td>
                                                            @foreach ($details as $detail)
                                                                <td>{{ $detail->value }}</td>
                                                            @endforeach
                                                        </tr>
                                                        @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    @csrf
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
<link rel="stylesheet" type="text/css" href="{{ asset('css/plugin/bootstrap-select/bootstrap-select.css') }}" />
@endpush
@push('scripts')
    <script src="{{asset('js/bootstrap-select/bootstrap-select.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('.selectpicker').selectpicker({
                liveSearch: true,
                dropupAuto: false,
                title: "No Value"
            });
            // Init

            $(".table-hideable tr").children(":nth-child(n+6)").hide();
            $(".hide-columns").hide();

            $(".show-columns").click(function() {
                $(".show-columns").hide();
                $(".hide-columns").show();
                $(".table-hideable tr").children(":nth-child(n+6)").show();

            });
            $(".hide-columns").click(function() {
                $(".show-columns").show();
                $(".hide-columns").hide();
                $(".table-hideable tr").children(":nth-child(n+6)").hide();;
            })
        });
    </script>
@endpush
