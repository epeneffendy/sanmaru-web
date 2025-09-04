@extends('layouts.admin.main')
@section('content')
    @if(isset($customForm) && $customForm->exists)
        @php($action=route('admin.custom_form.update',array($customForm['id'])))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.custom_form.store'))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif

    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Custom Form</h1>
        <ol class="breadcrumb">
            <li>PPDB</li>
            <li><a href="{{route('admin.custom_form.index')}}">Custom Form</a></li>
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
                        <h3>{{$status_header}} Custom Form</h3>
                    </div> <!-- /widget-header -->
                    <div class="widget-content">
                        @if (session('message'))
                        <div class="alert alert-danger">
                            <ul>
                                <li>{{ session('message') }}</li>
                            </ul>
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
                        <form role="form" method="POST" action="{{$action}}"  class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            @if (@$status == 'Update')
                                @method('PUT')
                            @endif
                            <input type="hidden" name="id" value="{{@$customForm->id}}" />
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Name:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name" value="{{old('name', @$customForm['name'])}}" placeholder="Input a name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="unit">Unit:</label>
                                <div class="col-sm-10">
                                    <select name="unit_id" id="unit_id" class="form-control selectpicker" data-style="btn-success">
                                        <option data-hidden="true"></option>
                                        @foreach($units as $value => $label)
                                            <option value="{{ $value }}" {{ old('unit_id', @$customForm['unit_id']) == $value ? 'selected' : null }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="unit">Periode:</label>
                                <div class="col-sm-10">
                                    <select name="period_id[]" id="periode" data-style="btn-primary" class="form-control selectpicker" multiple>
                                        <option data-hidden="true"></option>
                                        @foreach($periods as $value => $label)
                                            <option value="{{ $value }}" {{ is_numeric(array_search($value, old('period_id', $customForm->periods->pluck('id')->toArray()))) ? 'selected' : null }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <hr>

                            <div class="form-group">
                                <div class="control-label col-sm-2"><h3>Input Column</h3></div>
                            </div>
                            <div class="form-group">
                                <div class="control-label col-sm-2">
                                    <button id="add_form" class="btn btn-default">+ Add Input Column</button>
                                </div>
                            </div>
                            <div class="form-group" id="input_column_form">
                            @if (isset($customForm) && $customForm->exists)
                                @foreach ($customForm->columns as $i => $column)
                                    <div class="col-sm-12" id="input_column{{ $i }}">
                                        <div class="box">
                                            <div class="form-group">
                                                <input type="hidden" class="form-control" name="column_ids[]" value="{{ $column->id }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="subject">Label:</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" name="label[]" value="{{ $column->label }}" placeholder="Enter Label" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="type">Type:</label>
                                            <div class="col-sm-10">
                                                    <select name="type[]" class="form-control" data-style="btn-success">
                                                        <option value="0" {{ $column->type == 0 ? 'selected' : '' }}>Text</option>
                                                        <option value="1" {{ $column->type == 1 ? 'selected' : '' }}>Number</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="order">Order:</label>
                                            <div class="col-sm-10">
                                                    <input type="number" min="1" class="form-control" name="order[]" value="{{ $column->order }}" placeholder="Enter Order">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="col-sm-12" id="input_column{{ $i+1 }}"></div>
                            @else
                                <div class="col-sm-12" id="input_column0">
                                <div class="box">
                                    <div class="form-group">
                                        <input type="hidden" class="form-control" name="column_ids[]" value="">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="subject">Label:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="label[]" value="" placeholder="Enter Label" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="type">Type:</label>
                                       <div class="col-sm-10">
                                            <select name="type[]" class="form-control" data-style="btn-success">
                                                <option value="0">Text</option>
                                                <option value="1">Number</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="order">Order:</label>
                                       <div class="col-sm-10">
                                            <input type="number" min="1" class="form-control" name="order[]" value="1" placeholder="Enter Order">
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="col-sm-12" id="input_column1"></div>
                            @endif
                            </div>

                            <div class="form-group">
                                <button id='delete_form' class="pull-right btn btn-danger">- Delete Input Column</button>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-default">Save</button>
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
@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugin/jquery-transfer/icon_font.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugin/sweet-alert/sweet-alert.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugin/bootstrap-select/bootstrap-select.css') }}" />
    <style>
    table {
        display: flex;
        flex-flow: column;
        height: 100%;
        width: 100%;
    }
    table thead {
        /* head takes the height it requires,
        and it's not scaled when table is resized */
        flex: 0 0 auto;
        width: calc(100% - 0.9em);
    }
    table thead tr th,
    table tbody tr td {
        padding: 3px !important;
    }
    table tbody {
        /* body takes all the remaining available space */
        flex: 1 1 auto;
        display: block;
        overflow-y: scroll;
    }
    table tbody tr {
        width: 100%;
    }
    table thead,
    table tbody tr {
        display: table;
        table-layout: fixed;
    }

    #selection {
        margin-top: 80px;
    }
    </style>
@endpush
@push('scripts')
    <script src="{{asset('js/summernote/summernote.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-select/bootstrap-select.js')}}"></script>
    <script src="{{asset('js/sweet-alert/sweet-alert.min.js')}}"></script>
    <script>
        var transfer;
        var nullTotal = pendingTotal = notpassedTotal = passedTotal = 0;
        let form_number = parseInt('{{ isset($customForm) && $customForm->exists ? $customForm->columns->count() : 1 }}');
        $(document).ready(function () {
            $('.selectpicker').selectpicker({
                liveSearch: true,
                dropupAuto: false,
                title: "No Value"
            });

            $("#add_form").click(function(e){
                e.preventDefault();
                let new_form_number = form_number - 1;
                var innertHtml = $('#input_column' + new_form_number).html();
                $('#input_column' + form_number).html(innertHtml).find('div:first-child');
                $('#input_column' + form_number + ' input[name="column_ids[]"]').val("");
                $('#input_column' + form_number + ' input[name="label[]"]').val("");
                $('#input_column' + form_number + ' input[name="order[]"]').val("1");
                $('#input_column_form').append('<div class="col-sm-12" id="input_column' + (form_number + 1) + '"></tr>');
                form_number++;
            });

            $("#delete_form").click(function(e){
                e.preventDefault();
                if(form_number > 1){
                    $("#input_column" + (form_number - 1)).html('');
                    form_number--;
                }
            });

            $(document).on('change', '#unit_id', function () {
                $.get('{{ route('admin.custom_form.get-periods') }}/'+$(this).val(), function(data, status) {
                    $('#periode').html(`<option data-hidden="true"></option>`);
                    if (data.length) {
                        $.each(data, function(index, value) {
                            $('#periode').append(`
                                <option value="${value.id}">${value.name}</option>
                            `);
                        });
                        $('#periode').selectpicker('refresh');
                    }
                });
            });
        });
    </script>
@endpush
