@extends('layouts.admin.main')
@section('content')
    @if(@$status=="edit")
        @php($action=route('admin.campus.unit.update',array($campus['id'], $campusUnit['id'])))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.campus.unit.insert',array($campus['id'])))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Kampus Unit - {{$campus->name}}</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li><a href="{{route('admin.campus.select')}}">Kampus</a></li>
            <li><a href="{{route('admin.campus.unit.index', $campus['id'])}}">{{$campus->name}}</a></li>
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
                        <h3>{{$status_header}} Kampus Unit</h3>
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
                            <input type="hidden" name="id" value="{{@$campusUnit->id}}" />
                            <input type="hidden" name="campus_id" value="{{@$campus->id}}" />
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="unit_id">Unit:</label>
                                <div class="col-sm-10">
                                    <select name="unit_id" class="form-control" id="unit_id" data-style="btn-primary" data-dropup-auto="false">
                                        <option data-hidden="true"></option>
                                        @foreach ($units as $key => $unit)
                                            <option value="{{ $unit->id }}" {{ $unit->id === old('unit_id', @$campusUnit->unit_id) ? 'selected' : NULL }}>{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="title">Profile URL:</label>
                                <div class="col-sm-10">
                                    <input type="url" class="form-control" name="permalink" id="permalink" value="{{old('permalink', @$campusUnit['permalink'])}}" placeholder="masukkan link">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="image_path">Image 1:1 (1080px x 1080px):</label>
                                <div class="col-sm-10">
                                    <input accept="image/x-png,image/jpeg" type="file" name="image_path" class="form-control" />
                                    <div class="preview-image {{ @$campusUnit->image_path !== null ? NULL : 'hide' }}" id="preview_image_path">
                                        <img class="responsive" src="{{ $campusUnit instanceof \App\Models\CampusUnit ? $campusUnit->getImagePathUrl() : NULL }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="image_landscape_path">Image Landscape (1920px x 1080px):</label>
                                <div class="col-sm-10">
                                    <input accept="image/x-png,image/jpeg" type="file" name="image_landscape_path" class="form-control" />
                                    <div class="preview-image {{ @$campusUnit->image_landscape_path !== null ? NULL : 'hide' }}" id="preview_image_landscape_path">
                                        <img class="responsive" src="{{ $campusUnit instanceof \App\Models\CampusUnit ? $campusUnit->getImageLandscapePathUrl() : NULL }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="image_potrait_path">Image Potrait (1080px x 1920px):</label>
                                <div class="col-sm-10">
                                    <input accept="image/x-png,image/jpeg" type="file" name="image_potrait_path" class="form-control" />
                                    <div class="preview-image {{ @$campusUnit->image_potrait_path !== null ? NULL : 'hide' }}" id="preview_image_potrait_path">
                                        <img class="responsive" src="{{ $campusUnit instanceof \App\Models\CampusUnit ? $campusUnit->getImagePotraitPathUrl() : NULL }}" />
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="about">About:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control editor" name="about" placeholder="tentang kampus unit" id="about">{{old('about', @$campusUnit['html_about'])}}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="keunggulan">Keunggulan:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control editor" name="keunggulan" placeholder="keunggulan" id="keunggulan">{{old('keunggulan', @$campusUnit['html_keunggulan'])}}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="sambutan">Sambutan Kepala Sekolah:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control editor" name="sambutan" placeholder="sambutan" id="sambutan" cols="30" rows="10">{{old('sambutan', @$campusUnit['sambutan'])}}</textarea>
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
    <script src="{{asset('js/ckeditor5/build/ckeditor.js')}}"></script>
    <script src="{{asset('js/myuploadadapter.js')}}"></script>
    <script src="{{asset('js/bootstrap-wysihtml5/wysihtml5-0.3.0.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-wysihtml5/bootstrap-wysihtml5.js')}}"></script>
    <script>
        $(document).ready(function () {
            $("input[type=file]").change(function() {
                readURL(this);
            });
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview_'+input.name+' img').attr('src', e.target.result).parent().removeClass('hide');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

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

