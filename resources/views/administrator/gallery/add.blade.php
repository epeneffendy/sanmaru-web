@extends('layouts.admin.main')
@section('content')
    @if(@$status=="edit")
        @php($action=route('admin.gallery.update',array($gallery['id'])))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.gallery.insert'))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif
    <!-- Statrt Page Header -->
    <div class="page-header">
        <h1 class="title">Gallery</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li><a href="{{route('admin.gallery.index')}}">Gallery</a></li>
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
                        <h3>{{$status_header}} Gallery</h3>
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

                            <input type="hidden" name="id" value="{{@$gallery->id}}" />
                            <div class="form-group">
                                <label for="unit_id" class="control-label col-sm-2">Unit</label>
                                <div class="col-sm-10">
                                    <div class="checkbox checkbox-success">
                                        <input id="is_unit" name="is_unit" type="checkbox" {{ (old('is_unit', @$gallery->unit_id)) ? 'checked' : NULL }}>
                                        <label for="is_unit">
                                            Sebagai Unit
                                        </label>
                                    </div>
                                    
                                    <select name="unit_id" id="unit_id" class="form-control" {{ (old('is_unit', @$gallery->unit_id)) ? NULL : 'style=display:none' }}>
                                        <option value="0">--- Pilih unit ---</option>
                                        @foreach($units as $key => $value)
                                            <option value="{{ $key }}" {{ old('unit_id', @$gallery->unit_id) == $key ? 'selected' : NULL }}>{{ $value }}</option>
                                        @endforeach 
                                    </select>
                                    
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="title" class="control-label col-sm-2">Judul</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="title" id="title" value="{{old('title', @$gallery['title'])}}" placeholder="Judul" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="content_url" class="control-label col-sm-2">Upload Image:</label>
                                <div class="col-sm-10">
                                    <input accept="image/x-png,image/jpeg" type="file" name="content_url" id="content_url" class="form-control">
                                    <div class="preview-image {{ $gallery instanceof \App\Models\Gallery ? '' : 'hide' }}">
                                        <img class="img-thumbnail" src="{{ $gallery instanceof \App\Models\Gallery ? $gallery->getContentImage() : NULL }}" />
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="description">Deskripsi (optional):</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="description" placeholder="Deskripsi kontent" id="description">{{old('description', @$gallery['description'])}}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="type" class="control-label col-sm-2">Publish:</label>
                                <div class="col-sm-10">
                                    @if (\App\Helpers\Helper::canPublishArticle())
                                        <div class="radio radio-success radio-inline">
                                            <input type="radio" id="published" value="1" name="published" {{ old('published', @$gallery->published) == '1' ? 'checked' : '' }}>
                                            <label for="published">Ya</label>
                                        </div>
                                        <div class="radio radio-danger radio-inline">
                                            <input type="radio" id="unpublished" value="0" name="published" {{ old('published', @$gallery->published) == 0 ? 'checked' : '' }}>
                                            <label for="unpublished">Tidak</label>
                                        </div>
                                    @else
                                        {!! @$gallery->published_label !!}
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button class="btn btn-default">{{$status}}</button>
                                    @if($status=="Update")
                                        <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary">Cancel</a>
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
    <style>
        .preview-image img {
            height: auto;
            width: 400px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            $("input[name=content_url]").change(function() {
                readURL(this);
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
    </script>
@endpush