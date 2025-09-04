@extends('layouts.admin.main')
@section('content')
    @if (@$method == "edit")
        @php($action=route('admin.event.update', array($event->id) ))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.event.insert'))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data Master Event</h1>
        <ol class="breadcrumb">
            <li>Master</li>
            <li><a href="{{ route('admin.event.index') }}">Event</a></li>
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
                <div class="widget">
                    <div class="widget-header">
                        <h3>{{ $status_header }} Event</h3>
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
                            <input type="hidden" name="id" value="{{ @$event->id }}" />
                            <div class="form-group">
                                <label class="col-sm-2 control-label form-label">Title</label>
                                <div class="col-sm-10">
                                    <input type="text" name="title" value="{{ old('title', @$event->title) }}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label form-label">Date</label>
                                <div class="col-sm-10">
                                    <input type="text" name="date" value="{{ old('date', isset($event->event_time) ? date('m/d/Y H:i:s', strtotime($event->event_time)) : '') }}" class="form-control date">
                                    <input type="hidden" name="event_time" value="{{ old('event_time', @$event->event_time) }}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label form-label">Location</label>
                                <div class="col-sm-10">
                                    <input type="text" name="location" value="{{ old('title', @$event->location) }}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label form-label">Description</label>
                                <div class="col-sm-10">
                                    <textarea type="text" name="description" class="form-control">{{ old('description', @$event->description) }}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label form-label">Status</label>
                                <div class="col-sm-10">
                                    <div class="radio radio-success radio-inline">
                                        <input type="radio" id="status_publish" value="published" name="status" {{ old('status', @$event->status) === 'published' ? 'checked' : null }}>
                                        <label for="status_publish"> Publish </label>
                                    </div>
                                    <div class="radio radio-danger radio-inline">
                                        <input type="radio" id="status_unpublish" value="unpublished" name="status" {{ old('status', @$event->status) === 'unpublished' ? 'checked' : null }}>
                                        <label for="status_unpublish"> Unpublish</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label form-label">Image Event</label>
                                <div class="col-sm-10">
                                    <input type="file" name="image" class="form-control">
                                    @if (@$method == "edit")
                                        @if(isset($event->image_path))
                                        <span style="margin-top:10px;" class="btn btn-sm btn-info" onclick="view_file('{{$event->getImageUrl()}}')">Show Image</span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-default">{{ $status }}</button>
                                    <a href="{{ route('admin.event.index')}}" class="btn btn-danger">Cancel</a>
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
@push('scripts')
    <script src="{{asset('js/moment/moment.min.js')}}"></script>
    <script src="{{asset('js/date-range-picker/daterangepicker.js')}}"></script>
    <script src="{{asset('js/summernote/summernote.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('.date').daterangepicker({
                singleDatePicker: true,
                timePicker: true,
                timePicker24Hour: true,
                showDropdowns: true,
                autoApply: true,
                timePickerIncrement: 1,
                timePickerSeconds: true,
                locale: {
                    format: 'YYYY-MM-DD HH:mm:ss',
                    separator: '-'
                }
            });
            $('.date').on('apply.daterangepicker', function(ev, picker) {
                $('input[name=date]').val(picker.startDate.format('MM/DD/YYYY HH:mm:ss'));
                $('input[name=event_time]').val(picker.startDate.format('YYYY-MM-DD HH:mm:ss'));
            });
            $('textarea[name=description]').summernote();
        });

        function view_file(path)
        {
            var site_url = "{{ url('/') }}";
            window.open(path,"Preview Image Event","scrollbars=yes, resizable=yes,width=1100,height=700");
        }
    </script>
@endpush
