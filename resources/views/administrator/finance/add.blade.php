@extends('layouts.admin.main')
@section('content')
    @if(@$status=="edit")
        @php($action=route('admin.finance.update',array($finance['id'])))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.finance.insert'))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data Master Keuangan</h1>
        <ol class="breadcrumb">
            <li>Master</li>
            <li><a href="{{route('admin.finance.index')}}">Keuangan</a></li>
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
                        <h3>{{$status_header}} Keuangan</h3>
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
                        <form role="form" method="POST" action="{{$action}}"  class="form-horizontal" style="padding-bottom: 20vh" enctype="multipart/form-data">
                            @if (@$status == 'Update')
                                @method('PATCH')
                            @endif
                            <input type="hidden" name="id" value="{{@$finance->id}}" />
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="type">Tipe:</label>
                                <div class="col-sm-10">
                                    <select name="type" class="form-control">
                                        <option value="registrasi" {{ old('type', @$finance->type) === 'registrasi' ? 'selected' : NULL }}>Registrasi / Pendaftaran</option>
                                        <option value="development" {{ old('type', @$finance->type) === 'development' ? 'selected' : NULL }}>Pengembangan / Uang Gedung</option>
                                        <option value="uniform" {{ old('type', @$finance->type) === 'uniform' ? 'selected' : NULL }}>Seragam</option>
                                        <option value="tuition" {{ old('type', @$finance->type) === 'tuition' ? 'selected' : NULL }}>SPP</option>
                                        <option value="activity" {{ old('type', @$finance->type) === 'activity' ? 'selected' : NULL }}>Kegiatan</option>
                                        <option value="other" {{ old('type', @$finance->type) === 'other' ? 'selected' : NULL }}>Lain-lain</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="unit_id">Unit:</label>
                                <div class="col-sm-10">
                                    <select name="unit_id" id="unit_id" class="form-control">
                                        <option value="">Semua</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}" {{ old('unit_name', @$finance->unit_id) === $unit->id ? 'selected' : NULL }}>{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- <div class="form-group">
                                <label class="control-label col-sm-2" for="user_id">Siswa:</label>
                                <div class="col-sm-10">
                                    <select name="user_id" class="form-control select-autocomplete">
                                        <option value="">Semua</option>
                                        @foreach ($students as $student)
                                            <option value="{{ $student->id }}" {{ old('user_id', @$finance->user_id) === $student->id ? 'selected' : NULL }}>{{ $student->ppdb ? $student->ppdb->name : ($student->student ? $student->student->name : $student->email ) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="user_ids">Siswa:</label>
                                <div class="col-sm-10">
                                    <select name="user_ids[]" id="user_ids" class="form-control select-autocomplete selectpicker" data-style="btn-success" data-selected-text-format="count > 3" multiple data-dropup-auto="false">
                                        @foreach ($students as $student)
                                            <option value="{{ $student->id }}" {{ @in_array($student->id, old('user_ids', @$finance->user_ids)) ? 'selected' : NULL }}>{{ $student->ppdb ? $student->ppdb->register_number . ' - ' . $student->ppdb->name : ($student->student ? $student->student->name : $student->email ) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div id="block-insider" {{ old('user_ids', @$finance->user_ids) ? NULL : 'style=display:none;' }}>
                                <div class="form-group">
                                    <label class="control-label col-sm-2">Status siswa</label>
                                    <div class="col-sm-10">
                                        <div class="checkbox checkbox-success">
                                            <input id="is_insider" name="is_insider" type="checkbox" value="1" {{ (old('is_insider', @$finance->is_insider) == 1) ? 'checked' : NULL }} />
                                            <label for="is_insider">Anak Pegawai</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="year">Tahun:</label>
                                <div class="col-sm-10">
                                    <select name="year" id="year" class="form-control">
                                        <option value="">Semua</option>
                                        @foreach ($years as $year)
                                        <option value="{{ $year->year }}" {{ $year->year == old('year', @$finance->year) ? 'selected' : null}}>{{ $year->year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <hr />

                            <div id="block-angsuran" {{ (old('type', @$finance->type) == 'development') ? NULL : 'style=display:none;' }}>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="start_date">Tanggal Mulai Angsur:</label>
                                    <div class="col-sm-10">
                                        <input type="date" name="start_date" id="start_date" value="{{ (old('type', @$finance->type) == 'development') ? old('start_date', @$finance->start_date) : NULL}}" placeholder="Tanggal mulai angsur">
                                    </div>
                                </div>
                                <hr />
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Jenis Biaya:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name" value="{{old('name', @$finance['name'])}}" placeholder="Masukkan nama">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="nominal_default">Nominal Default:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nominal_default" id="nominal_default" value="{{old('nominal_default', @$finance['nominal_default'])}}" placeholder="Masukkan nominal default" required>
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="period_id">Periode:</label>
                                <div class="col-sm-10">
                                    <select name="period_id" id="period_id" class="form-control select-autocomplete">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="description">Keterangan:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="description" id="description" value="{{old('description', @$finance['description'])}}">{{ old('description', @$finance->description) }}</textarea>
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
    <link rel="stylesheet" href="{{ asset('css/plugin/bootstrap-select/bootstrap-select-1.13.14.css') }}">
@endpush
@push('scripts')
    <script src="{{asset('js/bootstrap-select/bootstrap-select-1.13.14.js')}}"></script>

    <script>
        $(document).ready(function(){
            $('select.select-autocomplete').selectpicker({liveSearch: true});
            $('select[name=type]').change(function () {
                if ($(this).val() == "development") {
                    $('#block-angsuran').show();
                } else {
                    $('#start_date').val(null);
                    $('#block-angsuran').hide();
                }
            });

            $("select[name='user_ids[]']").change(function () {
                if ($(this).find('option:selected').length <= 0) {
                    $('#is_insider').removeAttr('checked');
                    $('#block-insider').hide();
                } else {
                    $('#block-insider').show();
                }
            })
        });

        $(document).ready(function() {
            var unitId = $("#unit_id").find(":selected").val()
            var year = $("#year").find(":selected").val()
            fetchPeriodOption(unitId, year)
        });
        $("#unit_id").change(function() {
            var unitId = $(this).find(":selected").val()
            var year = $("#year").find(":selected").val()
            fetchPeriodOption(unitId, year)
        })
        $("#year").change(function() {
            var unitId = $("#unit_id").find(":selected").val()
            var year = $(this).find(":selected").val()
            fetchPeriodOption(unitId, year)
        })

        function fetchPeriodOption(unitId, year) {
            $("#period_id").empty()
            $("#period_id").append('<option value="">Semua</option>')
            $.get("{{ route('admin.period.fetch') }}", { unit: unitId, year: year }, function(periods) {
                $.each(periods, function(index, period) {
                    if (period.id == {{ $finance->period_id ?? 0 }}) {
                        element = '<option value="' + period.id + '" selected>[' + period.unit.name + '] ' + period.name + '</option>'
                    } else {
                        element = '<option value="' + period.id + '">[' + period.unit.name + '] ' + period.name + '</option>'
                    }
                    $("#period_id").append(element)
                })
                $("#period_id").selectpicker("refresh")
            }, 'json')
        }
    </script>
@endpush
