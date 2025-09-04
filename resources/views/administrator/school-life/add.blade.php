@extends('layouts.admin.main')
@section('content')
    @if(@$status=="edit")
        @php($action=route('admin.school-life.update',array($schoolLifeCategory['id'],$schoolLife['id'])))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.school-life.insert',array($schoolLifeCategory['id'])))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Setting School Life</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li><a href="{{route('admin.school-life.select-category')}}">Setting School Life</a></li>
            <li><a href="{{ route('admin.school-life.index', $schoolLifeCategory['id']) }}">{{ $schoolLifeCategory->name }}</a></li>
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
                        <h3>{{$status_header}} School Life</h3>
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
                            <input type="hidden" name="id" value="{{@$schoolLife->id}}" />
                            
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="title">Judul:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="title" id="title" value="{{old('title', @$schoolLife['title'])}}" placeholder="masukkan judul">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="blog_category_id">Kategori:</label>
                                <div class="col-sm-10">
                                    <input type="hidden" name="category_id" id="category_id" value="{{$schoolLifeCategory['id']}}">
                                    <span class="label label-primary">{{ $schoolLifeCategory['name'] }}<span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="short_desc">Deskripsi:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="short_desc" placeholder="masukkan deskripsi" id="short_desc">{{old('short_desc', @$schoolLife['short_desc'])}}</textarea>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="publish_date">Tanggal Publish:</label>
                                <div class="col-sm-10">
                                    <input type="hidden" name="publish_date" value="{{old('publish_date', @$schoolLife['publish_date'])}}" />
                                    <input type="text" readonly class="form-control date" name="date" id="publish_date" value="{{ old('date', @$schoolLife['pub_date']) }}" placeholder="tanggal publish" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="content">Konten:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="content" placeholder="konten" id="content">{{old('content', @$schoolLife['html_content'])}}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="featured_image">Featured Image:</label>
                                <div class="col-sm-10">
                                    <input accept="image/x-png,image/jpeg" type="file" name="featured_image" class="form-control" />
                                    <div class="preview-image {{ @$schoolLife->featured_image !== null ? NULL : 'hide' }}">
                                        <img class="responsive" src="{{ $schoolLife instanceof \App\Models\SchoolLife ? $schoolLife->getFeaturedImageUrl() : NULL }}" />
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="published"> Publish?</label>
                                <div class="col-sm-10">
                                    @if (\App\Helpers\Helper::canPublishArticle())
                                    <input type="checkbox" name="published" value="1" class="custom-control-input" id="published" {{old('published', @$schoolLife['published']) ? 'checked' : ''}}>
                                    @else 
                                        {!! @$schoolLife['published_label'] !!}
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
                $('input[name=publish_date]').val(picker.startDate.format('YYYY-MM-DD HH:mm:ss'));
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
