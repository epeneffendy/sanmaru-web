@extends('layouts.admin.main')
@section('content')
    @if(@$status=="edit")
        @php($action=route('admin.popup.update',array($popup['id'])))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.popup.insert'))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Setting Popup</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li><a href="{{route('admin.popup.index')}}">Setting Popup</a></li>
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
                        <h3>{{$status_header}} Popup</h3>
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
                            <input type="hidden" name="id" value="{{@$popup->id}}" />
                            
                            <div class="form-group">
                                <label for="unit_id" class="control-label col-sm-2">Unit</label>
                                <div class="col-sm-10">
                                    <div class="checkbox checkbox-success">
                                        <input id="is_unit" name="is_unit" type="checkbox" {{ (old('is_unit', @$popup->unit_id)) ? 'checked' : NULL }}>
                                        <label for="is_unit">
                                            Sebagai Unit
                                        </label>
                                    </div>
                                    
                                    <select name="unit_id" id="unit_id" class="form-control" {{ (old('is_unit', @$popup->unit_id)) ? NULL : 'style=display:none' }}>
                                        <option value="0">--- Pilih unit ---</option>
                                        @foreach($units as $key => $value)
                                            <option value="{{ $key }}" {{ old('unit_id', @$popup->unit_id) == $key ? 'selected' : NULL }}>{{ $value }}</option>
                                        @endforeach 
                                    </select>
                                    
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="title">Judul:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="title" id="title" value="{{old('title', @$popup['title'])}}" placeholder="masukkan judul">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="short_desc">Deskripsi:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="short_desc" placeholder="masukkan deskripsi" id="short_desc">{{old('short_desc', @$popup['short_desc'])}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="publish_date">Tanggal Publish:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control date" name="publish_date" id="publish_date" value="{{old('publish_date', @$popup['pub_date'])}}" placeholder="tanggal publish">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="content">Konten:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="content" placeholder="konten" id="content">{{old('content', @$popup['html_content'])}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="published"> Publish?</label>
                                <div class="col-sm-10">
                                    @if (\App\Helpers\Helper::canPublishArticle())
                                        <input type="checkbox" name="published" value="1" class="custom-control-input" id="published" {{old('published', @$popup['published']) ? 'checked' : ''}}>
                                    @else
                                        {!! @$blog->published_label !!}
                                    @endif
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
    <style>
        .ck-editor__editable_inline {
            min-height: 250px;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{asset('js/moment/moment.min.js')}}"></script>
    <script src="{{asset('js/date-range-picker/daterangepicker.js')}}"></script>
    <script src="{{asset('js/ckeditor5/build/ckeditor.js')}}"></script>
    <script src="{{asset('js/myuploadadapter.js')}}"></script>
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
                $('input[name=publish_date]').val(picker.startDate.format('MM/DD/YYYY HH:mm:ss'));
            });
            $('#is_unit').on('change', function () {
                if (this.checked) {
                    console.log('#unit_id');
                    $('#unit_id').show();
                } else {
                    $('#unit_id').find(':selected').removeAttr('selected');
                    $('#unit_id').hide();
                }
            });
        });

        ClassicEditor
        .create( document.querySelector( '#content' ), {
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
@endpush
