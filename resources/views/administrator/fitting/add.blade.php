@extends('layouts.admin.main')
@section('content')
    @if(@$status=="edit")
        @php($action=route('admin.fitting.update',array($fitting['id'])))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.fitting.insert'))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Setting Jadwal Fitting</h1>
        <ol class="breadcrumb">
            <li>Shop</li>
            <li><a href="{{route('admin.fitting.index')}}">Setting Jadwal fitting</a></li>
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
                        <h3>{{$status_header}} Setting Jadwal fitting</h3>
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
                            <input type="hidden" name="id" value="{{@$fitting->id}}" />
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="unit">Unit:</label>
                                <div class="col-sm-10">
                                    <select name="unit_id" id="unit_id" required class="form-control selectpicker" data-style="btn-success">
                                        <option data-hidden="true"></option>
                                        @foreach($unitOption as $value => $label)
                                            <option value="{{ $value }}" {{ @$fitting['unit_id'] == $value ? 'selected' : null }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="date">Tanggal:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="date" id="date" value="{{ old('date', @$fitting['date']) }}" placeholder="Select Date" required readonly style="cursor:pointer">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Jam:</label>
                                <div class="col-sm-2">
                                    <select class="form-control" name="hour_start_hour" required>
                                        @if (old('hour_start_hour', @$fitting->hour_start))
                                            <option value="{{ old('hour_start_hour', substr(@$fitting->hour_start, 0, 2)) }}" selected hidden>{{ old('hour_start_hour', substr(@$fitting->hour_start, 0, 2)) }}</option>
                                        @endif
                                        @for ($i=0; $i<=23; $i++)
                                            <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <select class="form-control" name="hour_start_minute" required>
                                        @if (old('hour_start_minute', @$fitting->hour_start))
                                            <option value="{{ old('hour_start_minute', substr(@$fitting->hour_start, 3, 2)) }}" selected hidden>{{ old('hour_start_minute', substr(@$fitting->hour_start, 3, 2)) }}</option>
                                        @endif
                                        @for ($i=0; $i<=59; $i++)
                                            <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-sm-1">
                                    Sampai
                                </div>
                                <div class="col-sm-2">
                                    <select class="form-control" name="hour_end_hour" required>
                                        @if (old('hour_end_hour', @$fitting->hour_start))
                                            <option value="{{ old('hour_end_hour', substr(@$fitting->hour_end, 0, 2)) }}" selected hidden>{{ old('hour_end_hour', substr(@$fitting->hour_end, 0, 2)) }}</option>
                                        @endif
                                        @for ($i=0; $i<=23; $i++)
                                            <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <select class="form-control" name="hour_end_minute" required>
                                        @if (old('hour_end_minute', @$fitting->hour_end))
                                            <option value="{{ old('hour_end_minute', substr(@$fitting->hour_end, 3, 2)) }}" selected hidden>{{ old('hour_end_minute', substr(@$fitting->hour_end, 3, 2)) }}</option>
                                        @endif
                                        @for ($i=0; $i<=59; $i++)
                                            <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="quota">Kuota:</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="quota" id="quota" value="{{old('quota', @$fitting['quota'])}}" required placeholder="Input maximal quota fitting">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="note">Note:</label>
                                <div class="col-sm-10">
                                    <textarea name="note" rows="5" class="form-control" placeholder="Note">{{old('note', @$fitting['note'])}}</textarea>
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
    <script src="{{asset('js/date-range-picker/daterangepicker.js')}}"></script>
    <script src="{{asset('js/bootstrap-wysihtml5/wysihtml5-0.3.0.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-wysihtml5/bootstrap-wysihtml5.js')}}"></script>
    <script src="{{asset('js/bootstrap-select/bootstrap-select.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#date').daterangepicker({
                singleDatePicker: true,
                startDate: moment(),
            });

            $('.selectpicker').selectpicker({
                liveSearch: true,
                dropupAuto: false,
                title: "No Value"
            });

            $('#date').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(`${picker.startDate.format('YYYY-MM-DD')}`);
            });

            $('textarea[name=note]').wysihtml5();
        });
    </script>
@endpush
