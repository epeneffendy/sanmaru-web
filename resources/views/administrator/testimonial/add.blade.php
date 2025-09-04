@extends('layouts.admin.main')
@section('content')
    @if(@$status=="edit")
        @php($action=route('admin.testimonial.update',array($testimonial['id'])))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.testimonial.insert'))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif
    <!-- Statrt Page Header -->
    <div class="page-header">
        <h1 class="title">Testimonial</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li><a href="{{route('admin.testimonial.index')}}">Testimonial</a></li>
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
                        <h3>{{$status_header}} Testimonial</h3>
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

                            <input type="hidden" name="id" value="{{@$testimonial->id}}" />

                            <div class="form-group">
                                <label for="unit_id" class="control-label col-sm-2">Unit</label>
                                <div class="col-sm-10">
                                    <div class="checkbox checkbox-success">
                                        <input id="is_unit" name="is_unit" type="checkbox" {{ (old('is_unit', @$testimonial->unit_id)) ? 'checked' : NULL }}>
                                        <label for="is_unit">
                                            Sebagai Unit
                                        </label>
                                    </div>
                                    
                                    <select name="unit_id" id="unit_id" class="form-control" {{ (old('is_unit', @$testimonial->unit_id)) ? NULL : 'style=display:none' }}>
                                        <option value="0">--- Pilih unit ---</option>
                                        @foreach($units as $key => $value)
                                            <option value="{{ $key }}" {{ old('unit_id', @$testimonial->unit_id) == $key ? 'selected' : NULL }}>{{ $value }}</option>
                                        @endforeach 
                                    </select>
                                    
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="subject" class="control-label col-sm-2">Subject</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="subject" id="subject" value="{{old('subject', @$testimonial['subject'])}}" placeholder="Nama">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="photo_path" class="control-label col-sm-2">Photo:</label>
                                <div class="col-sm-10">
                                    <input accept="image/x-png,image/jpeg" type="file" name="photo_path" id="photo_path" class="form-control">
                                    <div class="preview-image">
                                        <img class="responsive" src="{{ $testimonial instanceof \App\Models\Testimonial ? $testimonial->getPhotoPathUrl() : NULL }}" />
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="content">Konten:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" rows=8 name="content" placeholder="konten" id="content">{{old('content', @$testimonial['content'])}}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="type" class="control-label col-sm-2">Publish:</label>
                                <div class="col-sm-10">
                                    @if (\App\Helpers\Helper::canPublishArticle())
                                    <div class="radio radio-success radio-inline">
                                        <input type="radio" id="published" value="1" name="published" {{ old('published', @$testimonial->published) == '1' ? 'checked' : '' }}>
                                        <label for="published">Ya</label>
                                    </div>
                                    <div class="radio radio-danger radio-inline">
                                        <input type="radio" id="unpublished" value="0" name="published" {{ old('published', @$testimonial->published) == 0 ? 'checked' : '' }}>
                                        <label for="unpublished">Tidak</label>
                                    </div>
                                    @else
                                        {!! @$testimonial->published_label !!}
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button class="btn btn-default">{{$status}}</button>
                                    @if($status=="Update")
                                        <a href="{{ route('admin.testimonial.index') }}" class="btn btn-secondary">Cancel</a>
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
            width: 150px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#is_unit').on('change', function () {
                if (this.checked) {
                    console.log('#unit_id');
                    $('#unit_id').show();
                } else {
                    $('#unit_id').find(':selected').removeAttr('selected');
                    $('#unit_id').hide();
                }
            });

            $("input[name=photo_path]").change(function() {
                readURL(this);
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