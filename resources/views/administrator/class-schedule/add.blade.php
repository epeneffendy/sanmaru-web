@extends('layouts.admin.main')
@section('content')
    @if (@$status == "edit")
        @php($action=route('admin.class-schedule.update', array($classSchedule->id) ))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.class-schedule.insert'))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data Master Jadwal Pelajaran Siswa</h1>
        <ol class="breadcrumb">
            <li>Master</li>
            <li><a href="{{ route('admin.class-schedule.index') }}">Jadwal Pelajaran Siswa</a></li>
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
                        <h3>{{ $status_header }} Jadwal Pelajaran Siswa</h3>
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
                        <form role="form" method="POST" action="{{ $action }}" class="form-horizontal">
                            <input type="hidden" name="id" value="{{ @$classSchedule->id }}" />
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="unit_id">Unit</label>
                                <div class="col-sm-10">
                                    <select name="unit_id" id="unit_id" class="form-control">
                                        <option>--- Pilih Unit ---</option>
                                        @foreach($units as $unit)
                                        <option value="{{$unit->id}}" {{(old('unit_id', @$classSchedule->class->unit_id) == $unit->id) ? 'selected' : NULL}} >{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="class_id">Kelas</label>
                                <div class="col-sm-10">
                                    <div class="row">
                                        <div class="col-sm-9">
                                            <select name="class_id" id="class_id" class="form-control">
                                                <option value="0">--- Pilih Kelas ---</option>
                                                @foreach($classes as $class)
                                                <option value="{{$class->id}}" {{(old('class_id', @$classSchedule->class_id) == $class->id) ? 'selected' : NULL}} >{{ $class->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <button class="btn btn-primary btn-schedule-modal" {{old('class_id', @$classSchedule->class_id) ? NULL : 'disabled'}}><i class="fa fa-eye"></i> Lihat Jadwal</b>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="day">Hari:</label>
                                <div class="col-sm-10">
                                    <select name="day" id="day" class="form-control">
                                        <option>--- Pilih Hari ---</option>
                                        @foreach($weekDays as $day)
                                            <option value="{{$day}}" {{ old('day', @$classSchedule->day) == $day ? 'selected' : NULL }}>{{ \App\Helpers\Helper::hari($day) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="course_id">Mata Pelajaran:</label>
                                <div class="col-sm-10">
                                    <select name="course_id" id="course_id" class="form-control">
                                        <option>--- Pilih Mata Pelajaran ---</option>
                                        @foreach($courses as $course)
                                        <option value="{{$course->id}}" {{ (old('course_id', @$classSchedule->course_id) == $course->id) ? 'selected' : NULL }} >{{ $course->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="start_time">Jam Mulai:</label>
                                <div class="col-sm-10">
                                    <input type="time" class="form-control" name="start_time" id="start_time"
                                           value="{{ old('start_time', @$classSchedule->start_time) }}" placeholder="Enter start time">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="end_time">Jam Selesai:</label>
                                <div class="col-sm-10">
                                    <input type="time" class="form-control" name="end_time" id="end_time"
                                           value="{{ old('end_time', @$classSchedule->end_time) }}" placeholder="Enter end time">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-default">{{ $status }}</button>
                                    <a href="{{ route('admin.class-schedule.index')}}" class="btn btn-danger">Cancel</a>
                                </div>
                            </div>
                            <!-- /bottom-wizard -->
                            @csrf
                            @if(@$status == "Update")
                                @method('PATCH')
                            @endif
                        </form>
                    </div>
                </div> <!-- /widget-content -->
            </div>
            <!-- End Panel -->
        </div>
        <!-- End Row -->
    </div>
    <!-- END CONTAINER -->
    <!-- Modal -->
    <div id="schedule-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Jadwal Pelajaran</h4>
                </div>
                <div class="modal-body">
                    <div id="schedule-table">
                        @if(@$calendarData)
                            @include('administrator.class-schedule._schedule-table', $calendarData)
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END Modal -->
@endsection
@push('styles')
@endpush 
@push('scripts')
<script>
    var urlUnitClasses = "{{ url('administrator/class-schedule/unit-class') }}",
        urlCalendarData = "{{ url('administrator/class-schedule/calendar-data') }}",
        optUnit = $('#unit_id'),
        optClass = $('#class_id'),
        btnScheduleModal = $('.btn-schedule-modal');

    btnScheduleModal.click(function(e) {
        e.preventDefault();
        $('#schedule-modal').modal();
    });

    optUnit.on('change', function () {
        optClass.html('<option value="0">--- Pilih Kelas ---</option>');
        fetch(`${urlUnitClasses}/${this.value}`)
        .then( response => response.json())
        .then ( data => {
            let optClassHtml = '';
            data.forEach(function(row, key) {
                optClassHtml += "<option value="+row.id+">"+row.name+"</option>";
            });
            optClass.append(optClassHtml);
        });
    });

    optClass.on('change', function () {
        let classId = this.value;
        btnScheduleModal.attr("disabled", "disabled");
        if (classId !== "0") {
            fetch(`${urlCalendarData}/${classId}`)
            .then( response => response.json())
            .then( data => {
                btnScheduleModal.removeAttr('disabled');
                $('#schedule-table').html(data.html);
            });
        }
    });

</script>
@endpush
