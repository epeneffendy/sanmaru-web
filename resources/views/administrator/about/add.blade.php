@extends('layouts.admin.main')
@section('content')
    @if(@$status=="edit")
        @php($action=route('admin.about.update',array($about->category['slug'],$about['slug'])))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.about.insert',array($about->category['slug'])))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Setting About</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li><a href="{{route('admin.about.select-category')}}">Setting About</a></li>
            <li><a href="{{ route('admin.about.index', $about->category['slug']) }}">{{ $about->category['name'] }}</a></li>
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
                        <h3>{{$status_header}} About</h3>
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
                            <input type="hidden" name="id" value="{{@$about->id}}" />

                            <div class="form-group">
                                <label for="unit_id" class="control-label col-sm-2">Unit</label>
                                <div class="col-sm-10">
                                    <div class="checkbox checkbox-success">
                                        <input id="is_unit" name="is_unit" type="checkbox" {{ (old('is_unit', @$about->unit_id)) ? 'checked' : NULL }}>
                                        <label for="is_unit">
                                            Sebagai Unit
                                        </label>
                                    </div>
                                    
                                    <select name="unit_id" id="unit_id" class="form-control" {{ (old('is_unit', @$about->unit_id)) ? NULL : 'style=display:none' }}>
                                        <option value="0">--- Pilih unit ---</option>
                                        @foreach($units as $key => $value)
                                            <option value="{{ $key }}" {{ old('unit_id', @$about->unit_id) == $key ? 'selected' : NULL }}>{{ $value }}</option>
                                        @endforeach 
                                    </select>
                                    
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="title">Judul:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="title" id="title" value="{{old('title', @$about['title'])}}" placeholder="masukkan judul">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="blog_category_id">Kategori:</label>
                                <div class="col-sm-10">
                                    <input type="hidden" name="about_category_id" id="about_category_id" value="{{$about->category['id']}}">
                                    <span class="label label-primary">{{ $about->category['name'] }}<span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="short_desc">Deskripsi:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="short_desc" placeholder="masukkan deskripsi" id="short_desc">{{old('short_desc', @$about['short_desc'])}}</textarea>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="publish_date">Tanggal Publish:</label>
                                <div class="col-sm-10">
                                
                                    <input type="text" readonly class="form-control date" name="publish_date" id="publish_date" value="{{old('publish_date', @$about['pub_date'])}}" placeholder="tanggal publish">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="content">Konten:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="content" placeholder="konten" id="content">{{old('content', @$about['html_content'])}}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="featured_image">Featured Image:</label>
                                <div class="col-sm-10">
                                    <input accept="image/x-png,image/jpeg" type="file" name="featured_image" class="form-control" />
                                    <div class="preview-image {{ @$about->featured_image !== null ? NULL : 'hide' }}">
                                        <img class="responsive" src="{{ $about instanceof \App\Models\About ? $about->getFeaturedImageUrl() : NULL }}" />
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="published"> Publish?</label>
                                <div class="col-sm-10">
                                    @if (\App\Helpers\Helper::canPublishArticle())
                                    <input type="checkbox" name="published" value="1" class="custom-control-input" id="published" {{old('published', @$about['published']) ? 'checked' : ''}}>
                                    @else
                                        {!! @$about['published_label'] !!}
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
        .preview-image img {
            height: auto;
            width: 400px;
        }
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

            $("input[name=featured_image]").change(function() {
                readURL(this);
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

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('.preview-image img').attr('src', e.target.result).parent().removeClass('hide');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

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
                    'imageTextAlternative','imageStyle:full','imageStyle:side','linkImage'
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
