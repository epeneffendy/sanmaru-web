@extends('layouts.admin.main')
@section('content')
    @if(@$status=="edit")
        @php($action=route('admin.faq.update',array($faq['id'])))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.faq.insert'))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Setting FAQ</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li><a href="{{route('admin.faq.index')}}">Setting FAQ</a></li>
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
                        <h3>{{$status_header}} FAQ</h3>
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
                            <input type="hidden" name="id" value="{{@$faq->id}}" />
                           
                            <div class="form-group">
                                <label for="category" class="control-label col-sm-2">Kategori: </label>
                                <div class="col-sm-10">
                                    <select name="category" class="form-control" id="category">
                                        <option value="0"> ------------------ Pilih kategori ------------------ </option>
                                        @foreach($categories as $key => $value)
                                        <option value="{{ $value['value'] }}" {{ old('category', @$faq['category']) == $value['value'] ? 'selected' : null }} >{{ $value['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="title">Judul:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="title" id="title" value="{{old('title', @$faq['title'])}}" placeholder="masukkan judul">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="content">Pertanyaan:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="content" rows=5 placeholder="masukkan pertanyaan" id="content">{{old('content', @$faq['content'])}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="answer">Jawaban:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control editor" name="answer" rows=5 placeholder="masukkan jawaban" id="answer">{{old('answer', @$faq['html_answer'])}}</textarea>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="tags" class="control-label col-sm-2">Tags:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="tags" id="tags" data-role="tagsinput" style="display:none;" value="{{old('tags', $tags)}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="publish_date">Tanggal Publish:</label>
                                <div class="col-sm-10">
                                    <input type="text" readonly class="form-control date" name="publish_date" id="publish_date" value="{{old('publish_date', @$faq['pub_date'])}}" placeholder="tanggal publish">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="published"> Publish?</label>
                                <div class="col-sm-10">
                                    @if (\App\Helpers\Helper::canPublishArticle())
                                    <input type="checkbox" name="published" value="1" class="custom-control-input" id="published" {{old('published', @$faq['published']) ? 'checked' : ''}}>
                                    @else
                                        {!! @$faq['published_label'] !!}
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button class="btn btn-default">{{$status}}</button>
                                    @if($status=="Update")
                                        <a href="{{ route('admin.faq.index') }}" class="btn btn-secondary">Cancel</a>
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
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugin/bootstrap-select/bootstrap-select.css') }}" />  
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugin/bootstrap-tagsinput/bootstrap-tagsinput.css') }}" />
    <style>
        .ck-editor__editable_inline {
            min-height: 250px;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{asset('js/moment/moment.min.js')}}"></script>
    <script src="{{asset('js/ckeditor5/build/ckeditor.js')}}"></script>
    <script src="{{asset('js/myuploadadapter.js')}}"></script>
    <script src="{{asset('js/date-range-picker/daterangepicker.js')}}"></script>
    <script src="{{asset('js/bootstrap-tagsinput/bootstrap-tagsinput.js')}}"></script>
    <script>
        $(document).ready(function () {

            $('#tags').tagsinput({
                confirmKeys: [13, 188]
            });
            $('.bootstrap-tagsinput input').keypress(function (e) {
                if (e.keyCode==13) {
                    e.keyCode = 188;
                    e.preventDefault();
                }
            });

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

        var allEditor = document.querySelectorAll('.editor').forEach(function (val) {
            ClassicEditor
                .create( val, {
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
            });
    </script>
@endpush
