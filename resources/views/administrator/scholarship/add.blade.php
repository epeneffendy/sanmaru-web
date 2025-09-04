@extends('layouts.admin.main')
@section('content')
    @if(@$status=="edit")
        @php($action=route('admin.scholarship.update',array($scholarship['id'])))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.scholarship.insert'))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Setting Beasiswa</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li><a href="{{route('admin.scholarship.index')}}">Setting Beasiswa</a></li>
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
                        <h3>{{$status_header}} Beasiswa</h3>
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
                            <input type="hidden" name="id" value="{{@$scholarship->id}}" />
                            <div class="form-group">
                                <label for="is_unit" class="control-label col-sm-2">Sebagai unit?</label>
                                <div class="col-sm-10">
                                    <div class="btn-group" data-toggle="buttons">
                                        <label class="btn btn-info {{ old('is_unit', @$scholarship->is_unit) == '1' ? 'active' : null }}">
                                            <input type="radio" name="is_unit" value="1" data-for="#unit_id" {{ old('is_unit', @$scholarship->is_unit) == '1' ? 'checked' : NULL }}>Unit
                                        </label>
                                        <label class="btn btn-info {{ old('is_unit', @$scholarship->is_unit) == '0' ? 'active' : null }}">
                                            <input type="radio" name="is_unit" value="0" data-for="#kampus" {{ old('is_unit', @$scholarship->is_unit) == '0' ? 'checked' : NULL }}>Kampus
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group block-unit {{ old('is_unit', @$scholarship->is_unit) != "1" ? 'hide' : null }}">
                                <label for="unit" class="control-label col-sm-2">Unit:</label>
                                <div class="col-sm-10">
                                    <select name="unit_id" id="unit_id" class="form-control">
                                        <option value="{{ null }}">---- Pilih unit ----</option>
                                        @foreach($unitOptions as $value => $label)
                                            <option value="{{ $value }}" {{ @$scholarship['unit_id'] == $value ? 'selected' : null }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <div class="kampus"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Nama:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name" value="{{old('name', @$scholarship['name'])}}" placeholder="nama beasiswa" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="description">Konten:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="description" placeholder="description" id="description">{{old('description', @$scholarship['html_description'])}}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="publish_date">Tanggal Publish:</label>
                                <div class="col-sm-10">
                                    <input type="text" readonly class="form-control date" name="publish_date" id="publish_date" value="{{old('publish_date', @$scholarship['pub_date'])}}" placeholder="tanggal publish" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="published"> Publish?</label>
                                <div class="col-sm-10">
                                    @if (\App\Helpers\Helper::canPublishArticle())
                                    <input type="checkbox" name="published" value="1" class="custom-control-input" id="published" {{old('published', @$scholarship['published']) ? 'checked' : ''}}>
                                    @else
                                        {!! @$scholarship['published_label'] !!}
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button class="btn btn-default">{{$status}}</button>
                                    @if($status=="Update")
                                        <a href="{{ route('admin.scholarship.index') }}" class="btn btn-secondary">Cancel</a>
                                    @endif
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
    <style>
    .ck-editor__editable_inline {
        min-height: 250px;
    }
    </style>
@endpush
@push('scripts')
    <script src="{{asset('js/bootstrap-select/bootstrap-select.js')}}"></script>
    <script src="{{asset('js/moment/moment.min.js')}}"></script>
    <script src="{{asset('js/date-range-picker/daterangepicker.js')}}"></script>
    <script src="{{asset('js/ckeditor5/build/ckeditor.js')}}"></script>
    <script src="{{asset('js/myuploadadapter.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#unit_id').selectpicker({liveSearch: true});

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
                $('input[name=publish_date]').val(picker.startDate.format('MM/DD/YYYY HH:mm:ss'));
            });
        });

        $(document).on('change', 'input[name=is_unit]', function(e) {
            let target = $(this).data('for');
            $('.block-unit').addClass('hide');
            $(target).closest('.form-group').removeClass('hide');
        });

        ClassicEditor
        .create( document.querySelector( '#description' ), {
            toolbar: {
                items: [
                    'heading','|','undo','redo','bold','italic','link','bulletedList','numberedList',
                    'alignment','|','outdent','indent','|','imageUpload','blockQuote','insertTable',
                    'mediaEmbed','|','fontFamily','highlight','fontColor','fontSize','fontBackgroundColor',
                    'underline','strikethrough','todoList','imageInsert'
                ]
            },
            language: 'en',
            image: {
                toolbar: [
                    'imageTextAlternative','imageStyle:full','imageStyle:side'
                ]
            },
            table: {
                contentToolbar: [
                    'tableColumn','tableRow','mergeTableCells','tableCellProperties','tableProperties'
                ]
            },
            mediaEmbed: {
                previewsInData: true
            },
        })
        .then( editor => {
            window.editor = editor;
            let uploadUrl = "{{route('upload_temp_image')}}";
            let csrfToken = "{{csrf_token()}}";
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return new MyUploadAdapter(loader,uploadUrl,csrfToken);
            };
        })
        .catch( error => {
            console.error( 'Oops, something went wrong!' );
            console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
            console.warn( 'Build id: 9npjdcpi56ca-4w7i4xa5ygj3' );
            console.error( error );
        });
    </script>
    </script>
@endpush
