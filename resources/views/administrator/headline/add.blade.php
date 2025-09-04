@extends('layouts.admin.main')
@section('content')
    @if(@$status=="edit")
        @php($action=route('admin.headline.update',array($headline['id'])))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.headline.insert'))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif
    <!-- Statrt Page Header -->
    <div class="page-header">
        <h1 class="title">Headline</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li><a href="{{route('admin.headline.index')}}">Headline</a></li>
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
                <div class="widget">
                    <div class="widget-header">
                        <h3>{{$status_header}} Headline</h3>
                    </div><!--  widget-header -->
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
                        <form role="form" method="POST" action="{{$action}}" class="form-horizontal" enctype="multipart/form-data">
                            @if (@$status == 'Update')
                                @method('PATCH')
                            @endif

                            <input type="hidden" name="id" value="{{@$headline->id}}" />

                            <div class="form-group">
                                <label for="is_unit" class="control-label col-sm-2">Sebagai unit?</label>
                                <div class="col-sm-10">
                                    <div class="btn-group" data-toggle="buttons">
                                        <label class="btn btn-info {{ old('is_unit', @$headline->is_unit) == '1' ? 'active' : null }}">
                                            <input type="radio" name="is_unit" value="1" data-for="#unit_id" {{ old('is_unit', @$headline->is_unit) == '1' ? 'checked' : NULL }}>Unit
                                        </label>
                                        <label class="btn btn-info {{ old('is_unit', @$headline->is_unit) == '0' ? 'active' : null }}">
                                            <input type="radio" name="is_unit" value="0" data-for="#kampus" {{ old('is_unit', @$headline->is_unit) == '0' ? 'checked' : NULL }}>Kampus
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group block-unit {{ old('is_unit', @$headline->is_unit) != "1" ? 'hide' : null }}">
                                <label for="unit" class="control-label col-sm-2">Unit:</label>
                                <div class="col-sm-10">
                                    <select name="unit_id" id="unit_id" class="form-control">
                                        <option value="{{ null }}">---- Pilih unit ----</option>
                                        @foreach($unitOption as $value => $label)
                                            <option value="{{ $value }}" {{ @$headline['unit_id'] == $value ? 'selected' : null }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <div class="kampus"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="type" class="control-label col-sm-2">Type:</label>
                                <div class="col-sm-10">
                                    <div class="btn-group" data-toggle="buttons">
                                        <label class="btn btn-info {{ old('type', @$headline->type) === 'image' ? 'active' : null }}">
                                            <input type="radio" name="type" value="image" data-for="#image" {{ old('type', @$headline->type) === 'image' ? 'checked' : NULL }}>Image
                                        </label>
                                        <label class="btn btn-info {{ old('type', @$headline->type) === 'video' ? 'active' : null }}">
                                            <input type="radio" name="type" value="video" data-for="#video" {{ old('type', @$headline->type) === 'video' ? 'checked' : NULL }}>Video URL
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group block-content {{ old('type', @$headline->type) !== 'image' ? 'hide' : null }}">
                                <label for="image" class="control-label col-sm-2">Upload Gambar:</label>
                                <div class="col-sm-10">
                                    <input accept="image/x-png,image/jpeg" type="file" name="content_img" id="image" class="form-control">
                                    <div class="preview-image {{ @$headline->type !== 'image' ? 'hide' : null }}">
                                        <img class="responsive" src="{{ $headline instanceof \App\Models\Headline ? $headline->image_url : NULL }}" />
                                    </div>
                                </div>
                            </div>

                            <div class="form-group block-content {{ old('type', @$headline->type) !== 'video' ? 'hide' : null }}">
                                <label for="video" class="control-label col-sm-2">Youtube URL:</label>
                                <div class="col-sm-10">
                                    <input type="url" name="content_url" id="video" class="form-control" placeholder="Video URL" value="{{ old('content_url', @$headline->video_url) }}">

                                    <div id="videobox" style="{{ @$headline->type !== 'video' ? 'display:none;' : 'display:block;' }}">
                                        <div id="preview-video">
                                            @if($headline instanceof \App\Models\Headline)
                                                {!! $headline->preview !!}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="color_overlay" class="control-label col-sm-2">Color Overlay</label>
                                <div class="col-sm-10">
                                    <input id="color_overlay" name="color_overlay" type="text" class="form-control" value="{{ old('color_overlay', @$headline->color_overlay) }}" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="type" class="control-label col-sm-2">Publish:</label>
                                <div class="col-sm-10">
                                    @if (\App\Helpers\Helper::canPublishArticle())
                                        <div class="radio radio-success radio-inline">
                                            <input type="radio" id="published" value="1" name="published" {{ old('published', @$headline->published) == '1' ? 'checked' : '' }}>
                                            <label for="published">Ya</label>
                                        </div>
                                        <div class="radio radio-danger radio-inline">
                                            <input type="radio" id="unpublished" value="0" name="published" {{ old('published', @$headline->published) == 0 ? 'checked' : '' }}>
                                            <label for="unpublished">Tidak</label>
                                        </div>
                                    @else
                                        {!! @$headline->published_label !!}
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button class="btn btn-default">{{$status}}</button>
                                    @if($status=="Update")
                                        <a href="{{ route('admin.headline.index') }}" class="btn btn-secondary">Cancel</a>
                                    @endif
                                </div>
                            </div>
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{asset('css/plugin/bootstrap-colorpicker/bootstrap-colorpicker.css')}}">
    <style>
        .preview-image img {
            height: auto;
            width: 400px;
        }
        .colorpicker-2x .colorpicker-saturation {
            width: 200px;
            height: 200px;
        }

        .colorpicker-2x .colorpicker-hue,
        .colorpicker-2x .colorpicker-alpha {
            width: 30px;
            height: 200px;
        }

        .colorpicker-2x .colorpicker-color,
        .colorpicker-2x .colorpicker-color div {
            height: 30px;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/bootstrap-colorpicker/bootstrap-colorpicker.js') }}"></script>
    <script>
        $("input[name=content_img]").change(function() {
            readURL(this);
        });

        $(function() {
            $('#color_overlay').colorpicker({
                customClass: 'colorpicker-2x',
                sliders: {
                    saturation: {
                        maxLeft: 200,
                        maxTop: 200
                    },
                    hue: {
                        maxTop: 200
                    },
                    alpha: {
                        maxTop: 200
                    }
                }
            });
        });
    
        $(document).on('keyup', '#video', function() {
            let preview = document.getElementById('videobox');
            if ($(this).val() === "") {
                preview.style.display = "none";
            } else {
                let contentUrl = $(this).val();
                let video_id = getVideoId(contentUrl);
                if (video_id) {
                    let ifrm = document.createElement('iframe');
                    ifrm.src = 'https://youtube.com/embed/' + video_id;
                    ifrm.width = "400";
                    ifrm.height = "300";
                    ifrm.frameborder="0";
                    ifrm.scrolling="no";
                    $('#preview-video').html(ifrm);
                    preview.style.display = "block";
                } else {
                    preview.style.display = "none";
                }
            }
        });
        
        $(document).on('change', 'input[name=type]', function(e) {
            let target = $(this).data('for');
            $('.block-content').addClass('hide');
            $(target).closest('.form-group').removeClass('hide');
        });

        $(document).on('change', 'input[name=is_unit]', function(e) {
            let target = $(this).data('for');
            $('.block-unit').addClass('hide');
            $(target).closest('.form-group').removeClass('hide');
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

        function getVideoId(url) {
            var re = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
            return (url.match(re)) ? RegExp.$1 : false;
        }
    </script>
@endpush