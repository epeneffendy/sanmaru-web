@extends('layouts.admin.main')
@section('content')
    @if(@$status=="edit")
        @php($action=route('admin.period.update',array($period['id'])))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.period.insert'))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Setting Period</h1>
        <ol class="breadcrumb">
            <li>PPDB</li>
            <li><a href="{{route('admin.period.index')}}">Setting Period</a></li>
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
                        <h3>{{$status_header}} Setting Period</h3>
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
                            <input type="hidden" name="id" value="{{@$period->id}}" />
                            <div role="tabpanel">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs nav-justified tabcolor5-bg" role="tablist">
                                    <li role="presentation" class="active"><a href="#data-period" aria-controls="data-period" role="tab" data-toggle="tab" aria-expanded="true" class="">Informasi Periode</a></li>
                                    <li role="presentation" class=""><a href="#data-bill" aria-controls="data-bill" role="tab" data-toggle="tab" class="" aria-expanded="false">Informasi Biaya Tagihan</a></li>
                                </ul>
                            </div>

                            <hr/>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="data-period">
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="name">Name:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="name" id="name" value="{{old('name', @$period['name'])}}" placeholder="Input a name" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="is_feeder_school" class="control-label col-sm-2"></label>
                                        <div class="col-sm-10">
                                            <div class="checkbox checkbox-success">
                                                <input id="is_feeder_school" name="is_feeder_school" type="checkbox" {{ (old('is_feeder_school', @$period->is_feeder_school)) ? 'checked' : NULL }}>
                                                <label for="is_feeder_school">
                                                    Jalur Khusus Feeder School
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="origin_school_block" {{ (old('is_feeder_school', @$period->is_feeder_school)) ? NULL : 'style=display:none;' }}>
                                        <div class="form-group">
                                            <label for="origin_school_options" class="control-label col-sm-2">Pilih Sekolah Asal:</label>
                                            <div class="col-sm-10">
                                                <select name="origin_school_options[]" id="origin_school_options" class="form-control" data-style="btn-success" multiple data-selected-text-format="count > 3" data-dropup-auto="false" >
                                                    <option data-hidden="true"></option>
                                                    @foreach($originSchoolOptions as $value)
                                                        <option value="{{ $value }}" {{ @in_array($value, old('origin_school_options', $period ? $period->origin_school_options : [])) ? 'selected' : NULL }}>{{ $value }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="additional_origin_school" class="control-label col-sm-2"></label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="additional_origin_school" value="{{ old('additional_origin_school') }}" placeholder="Sekolah asal tambahan">
                                                <small>**NB: Isi disini jika sekolah asal tidak terdapat pada pilihan. Jika lebih dari 1 pisahkan dengan tanda koma</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="show_registration_popup" class="control-label col-sm-2"></label>
                                        <div class="col-sm-10">
                                            <div class="checkbox checkbox-success">
                                                <input name="show_registration_popup" type="hidden" value="0">
                                                <input id="show_registration_popup" name="show_registration_popup" type="checkbox" {{ (old('show_registration_popup', @$period->show_registration_popup)) ? 'checked' : NULL }} value="1">
                                                <label for="show_registration_popup">
                                                    Tampilkan Popup Registrasi
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="popup_content_block" {{ (old('show_registration_popup', @$period->show_registration_popup)) ? NULL : 'style=display:none;' }}>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="popup_content">Popup Content:</label>
                                            <div class="col-sm-10">
                                                <textarea name="popup_content" rows="5" class="form-control" placeholder="Popup Content">{{old('popup_content', @$period['popup_content'])}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="description">Description:</label>
                                        <div class="col-sm-10">
                                            <textarea name="description" rows="5" class="form-control" placeholder="Description">{{old('description', @$period['description'])}}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="unit">Unit:</label>
                                        <div class="col-sm-10">
                                            <select name="unit_id" id="unit_id" class="form-control register-number-fields">
                                                @foreach($unitOption as $value => $label)
                                                    <option value="{{ $value }}" {{ @$period['unit_id'] == $value ? 'selected' : null }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <!-- <div class="form-group">
                                        <label class="control-label col-sm-2" for="class">Class:</label>
                                        <div class="col-sm-10">
                                            <select name="class_id" id="class_id" class="form-control">
                                                @foreach($classOption as $value => $label)
                                                    <option value="{{ $value }}" {{ @$period['class_id'] == $value ? 'selected' : null }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> -->
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="period">Period:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="period" id="period" value="{{ old('period', @$period['period']) }}" placeholder="Select Period" required readonly style="cursor:pointer">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="school_year">Tahun Mulai Ajaran:</label>
                                        <div class="col-sm-10">
                                            {{-- <input type="number" class="form-control register-number-fields" name="school_year" id="school_year" value="{{old('school_year', @$period['school_year'])}}" maxlength="4" placeholder="Tahun Mulai Ajaran"> --}}
                                            <select name="school_year" id="school_year" class="form-control">
                                                @php($is_selected = false)
                                                @foreach ($schoolYearOptions as $option)
                                                @if ($option['year'] == @$period['school_year'])
                                                    @php($is_selected = true)
                                                @endif
                                                <option value="{{ $option['year'] }}" {{ $option['year'] == @$period['school_year'] ? 'selected' : ($option['year'] == $suggestedSchoolYear && !$is_selected ? 'selected' : NULL) }}>{{ $option['year'] . '/' . ($option['year'] + 1) . ($option['year'] == $suggestedSchoolYear ? ' (Disarankan)' : NULL) }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger" id="schoolYearWarning" style="display: none;"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Status:</label>
                                        <div class="col-sm-10">
                                            <div class="radio radio-success radio-inline">
                                                <input type="radio" id="status_active" value="true" name="active" {{ old('active', @$period['active'] ? 'checked' : '') }}>
                                                <label for="status_active"> Active </label>
                                            </div>
                                            <div class="radio radio-danger radio-inline">
                                                <input type="radio" id="status_inactive" value="false" name="active" {{ old('active', @!$period['active'] ? 'checked' : '') }}>
                                                <label for="status_inactive"> Inactive</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="quota">Kuota:</label>
                                        <div class="col-sm-10">
                                            <input type="number" class="form-control register-number-fields" name="quota" id="quota" value="{{old('quota', @$period['quota'])}}" placeholder="Input maximal quota registrar">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="start_register_number">Nomor Registrasi Awal:</label>
                                        <div class="col-sm-10">
                                            <input type="number" class="form-control register-number-fields" name="start_register_number" id="start_register_number" value="{{old('start_register_number', @$period['start_register_number'])}}" placeholder="Input start register number">
                                            <span>Nomor registrasi yang terbuat: <strong  id="previewRegisterNumber"></strong></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" class="btn btn-default">{{$status}}</button>
                                        </div>
                                    </div>
                                    <!-- /bottom-wizard -->
                                    @csrf
                                </div>
                                <div role="tabpanel" class="tab-pane" id="data-bill">
                                    @if ($status == 'Update')
                                        <div class="form-group">
                                            <label class="control-label col-sm-2">Nominal Uang Formulir</label>
                                            <div class="col-sm-3">
                                                <textarea class="form-control" readonly="true">{{ \App\Helpers\PriceHelper::getNameFinance($period, 'registration') }}</textarea>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" value="{{ \App\Helpers\PriceHelper::registration($period, true) }}" class="form-control" readonly="true" />
                                            </div>
                                            <label class="control-label col-sm-1">Keterangan</label>
                                            <div class="col-sm-4">
                                                <textarea class="form-control" readonly="true">{{ \App\Helpers\PriceHelper::getDescriptionFinance($period, 'registration') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2">Nominal Uang Pengembangan</label>
                                            <div class="col-sm-3">
                                                <textarea class="form-control" readonly="true">{{ \App\Helpers\PriceHelper::getNameFinance($period, 'development') }}</textarea>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" value="{{ \App\Helpers\PriceHelper::development($period, true) }}" class="form-control" readonly="true" />
                                            </div>
                                            <label class="control-label col-sm-1">Keterangan</label>
                                            <div class="col-sm-4">
                                                <textarea class="form-control" readonly="true">{{ \App\Helpers\PriceHelper::getDescriptionFinance($period, 'development') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2">Nominal Uang SPP</label>
                                            <div class="col-sm-3">
                                                <textarea class="form-control" readonly="true">{{ \App\Helpers\PriceHelper::getNameFinance($period, 'tuition') }}</textarea>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" value="{{ \App\Helpers\PriceHelper::tuition($period, true) }}" class="form-control" readonly="true" />
                                            </div>
                                            <label class="control-label col-sm-1">Keterangan</label>
                                            <div class="col-sm-4">
                                                <textarea class="form-control" readonly="true">{{ \App\Helpers\PriceHelper::getDescriptionFinance($period, 'tuition') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2">Nominal Uang Kegiatan</label>
                                            <div class="col-sm-3">
                                                <textarea class="form-control" readonly="true">{{ \App\Helpers\PriceHelper::getNameFinance($period, 'activity') }}</textarea>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" value="{{ \App\Helpers\PriceHelper::activity($period, true) }}" class="form-control" readonly="true" />
                                            </div>
                                            <label class="control-label col-sm-1">Keterangan</label>
                                            <div class="col-sm-4">
                                                <textarea class="form-control" readonly="true">{{ \App\Helpers\PriceHelper::getDescriptionFinance($period, 'activity') }}</textarea>
                                            </div>
                                        </div>
                                    @else
                                        <h3>Harap save periode terlebih dahulu ! </h3>
                                    @endif
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
<link rel="stylesheet" type="text/css" href="{{asset('css/plugin/bootstrap-datepicker/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugin/bootstrap-select/bootstrap-select.css') }}" />
@endpush
@push('scripts')
    <script src="{{asset('js/moment/moment.min.js')}}"></script>
    <script src="{{asset('js/date-range-picker/daterangepicker.js')}}"></script>
    <script src="{{asset('js/summernote/summernote.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-select/bootstrap-select.js')}}"></script>
    <script src="{{asset('js/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>

    <script>
        $(document).ready(function () {
            triggerFeederField();
            triggerPreviewRegisterNumber();
            triggerSchoolYearWarning();
            $('.register-number-fields').on('input', function() {
                triggerPreviewRegisterNumber();
            });
            $('#school_year').on('change', function() {
                triggerPreviewRegisterNumber();
                triggerSchoolYearWarning();
            })
            $('#period').daterangepicker({
                timePickerIncrement: 1,
                timePickerSeconds: false,
                // minDate: moment(),
                startDate: moment()
            });

            $('#period').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(`${picker.startDate.format('DD/MM/YYYY')} - ${picker.endDate.format('DD/MM/YYYY')}`);
            });

            // $('#school_year').datepicker({
            //     autoclose: true,
            //     format: 'yyyy',
            //     viewMode: 'years',
            //     minViewMode: 'years',
            //     onSelect: function() {
            //         $(this).change();
            //     }
            // });

            $('textarea[name=description]').summernote();
            $('textarea[name=popup_content]').summernote();
            $('#origin_school_options').selectpicker({liveSearch: true});
            $('#is_feeder_school').on('change', function () {
                if (this.checked) {
                    $('#origin_school_block').show();
                } else {
                    $('#origin_school_options option:selected').each(function () {
                        $(this).removeAttr('selected');
                    });
                    $('#origin_school_options').selectpicker('refresh');
                    $('input[name=additional_origin_school]').val(null);
                    $('#origin_school_block').hide();
                }
            });
            $('#show_registration_popup').on('change', function () {
                if (this.checked) {
                    $('#popup_content_block').show();
                } else {
                    $('#popup_content_block').hide();
                }
            });
        });
        function triggerFeederField() {
            if ($('#is_feeder_school').is(':checked')) {
                $('#origin_school_block').show();
            } else {
                $('#origin_school_options option:selected').each(function () {
                    $(this).removeAttr('selected');
                });
                $('#origin_school_options').selectpicker('refresh');
                $('input[name=additional_origin_school]').val(null);
                $('#origin_school_block').hide();
            }
        }
        function triggerPreviewRegisterNumber() {
            $('#previewRegisterNumber').html('');
            if (!$('#school_year').val() || !$('#school_year').val() || !$('#quota').val() || !$('#start_register_number').val() || $('#start_register_number').val() >= 1000 || $('#quota').val() >= 1000) {
                return;
            }
            let schoolYear = ("" + $('#school_year').val()).substring(2);
            let unitCode = String($('#unit_id').val()).padStart(2, '0');
            let startNumber = String($('#start_register_number').val()).padStart(3, '0');
            let endNumber = String(parseInt($('#start_register_number').val()) + parseInt($('#quota').val())).padStart(3, '0');

            let startRegisterNumber = schoolYear + unitCode + startNumber;
            let endRegisterNumber = schoolYear + unitCode + endNumber;

            $('#previewRegisterNumber').html(startRegisterNumber + ' - ' + endRegisterNumber).removeClass('text-danger');
        }

        function triggerSchoolYearWarning() {
            let date = new Date();
            let selectedYear = $('#school_year').find(':selected').val();
            let suggestedSchoolYear = date.getMonth() >= 6 ? (date.getFullYear() + 1) : (date.getFullYear())
            let periodDate  = $('#period').val().split(" - ");

            let startDate   = new Date(periodDate[0]);
            let endDate     = new Date(periodDate[1]);
            let startYear   = periodDate[0].substr(periodDate[0].lastIndexOf('/')+1);
            let endYear     = periodDate[1].substr(periodDate[1].lastIndexOf('/')+1);

            if (selectedYear != suggestedSchoolYear && selectedYear != startYear && selectedYear != endYear) {
                $('#schoolYearWarning').html('Memasuki tahun ajaran <strong>' + suggestedSchoolYear + '/' + (suggestedSchoolYear + 1) + '</strong>');
                $('#schoolYearWarning').show();
            } else {
                $('#schoolYearWarning').hide();
            }
        }
    </script>
@endpush
