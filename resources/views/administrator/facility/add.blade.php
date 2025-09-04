@extends('layouts.admin.main')
@section('content')
    @if(@$status=="edit")
        @php($action=route('admin.facility.update',array($data['id'])))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.facility.insert'))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Setting Fasilitas</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li><a href="{{route('admin.facility.index')}}">Setting Fasilitas</a></li>
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
                        <h3>{{$status_header}} Fasilitas</h3>
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
                            <input type="hidden" name="id" value="{{@$data->id}}" />
                            <div class="form-group">
                                <label for="unit_id" class="control-label col-sm-2">Unit</label>
                                <div class="col-sm-10">
                                    <select name="unit_id" id="unit_id" class="form-control">
                                        <option value="0">--- Pilih unit ---</option>
                                        @foreach($units as $key => $value)
                                            <option value="{{ $key }}" {{ old('unit_id', @$data->unit_id) == $key ? 'selected' : NULL }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="facility_category_id" class="control-label col-sm-2">Kategori</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="facility_category_id" id="facility_category_id">
                                        <option value="0">--- Pilih kategori ----</option>
                                        @foreach($categories as $key => $value)
                                        <option value="{{ $key }}" {{ old('facility_category_id', @$data->facility_category_id) == $key ? 'selected' : NULL }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Nama:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name" value="{{old('name', @$data['name'])}}" placeholder="Nama fasilitas">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="description">Deskripsi:</label>
                                <div class="col-sm-10">
                                    <textarea name="description" id="description" class="form-control" cols="30" rows="10">{!! @$data['description'] !!}</textarea>
                                    {{-- deploy --}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="galery_ids" class="control-label col-sm-2">Image:</label>
                                <div class="col-sm-10">
                                    <div class="block-image margin-b-5">
                                        @if(old('gallery_ids') && old('image_url'))
                                            @foreach(old('gallery_ids') as $key => $value)
                                        <div>
                                            <input type="hidden" name="gallery_ids[]" value="{{ $value }}">
                                            <input type="hidden" name="image_url[]" value="{{  old('image_url')[$key] ?? '' }}">
                                            <img src="{{ old('image_url')[$key] ?? '' }}" style="width:400px;height:auto;padding:5px">
                                            <a onclick="removeImage(this)" style="cursor:pointer;">hapus gambar</a>
                                        </div>
                                            @endforeach
                                        @elseif(@$data->galleries)
                                            @foreach($data->galleries as $gallery)
                                        <div>
                                            <input type="hidden" name="gallery_ids[]" value="{{ $gallery->id }}">
                                            <input type="hidden" name="image_url[]" value="{{  $gallery->getContentImageUrl() }}">
                                            <img src="{{ $gallery->getContentImageUrl() }}" style="width:400px;height:auto;padding:5px">
                                            <a onclick="removeImage(this)" style="cursor:pointer;">hapus gambar</a>
                                        </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-sm btn-success add-image"><i class="fa fa-plus"></i> Tambah Gambar</button>
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

    <!-- Modal -->
    <div id="modal-gallery" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Gallery</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugin/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}" />
    <style>
        .preview-image {
            display: block;
            text-align: center;
            border-radius: 10px;
            border: 2px solid #c3c3c3;
            margin-bottom: 15px;
        }
        .preview-image h6 {
            margin: 0 1px 0 1px;
        }
        .preview-image:hover {
            border: 2px solid #337ab7;
            cursor: pointer;
        }
        .preview-image img {
            height: auto;
            width: 300px;
            padding: 5px;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{asset('js/bootstrap-wysihtml5/wysihtml5-0.3.0.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-wysihtml5/bootstrap-wysihtml5.js')}}"></script>
    <script src="{{asset('js/moment/moment.min.js')}}"></script>
    <script src="{{asset('js/ckeditor5/build/ckeditor.js')}}"></script>
    <script src="{{asset('js/myuploadadapter.js')}}"></script>
    <script>
        var urlGalleryData = `{{url('administrator/facility/gallery-data')}}`;

        $(document).ready(function () {
            // $('textarea[name=description]').wysihtml5();

            $('.add-image').click(function(e) {
                e.preventDefault();
                fetch(`${urlGalleryData}`)
                .then(res => res.json())
                .then(data => {
                    $('.modal-body').html(data.html);
                    $('#modal-gallery').modal();
                });
            });
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

        $('body').on('click', '.pagination a', function(e) {
            e.preventDefault();
            let url = $(this).attr('href');
            fetch(`${url}`)
            .then(res => res.json())
            .then(data => {
                $('.modal-body').html(data.html);
            });
        });

        $('body').on('click', '.preview-image', function(e) {
            e.preventDefault();
            let imageUrl = $(this).find('img').attr('src'),
                galleryId = $(this).find('input[name^="gallery_id"]').val();

            let html = `<div>
                            <input type="hidden" name="gallery_ids[]" value="${galleryId}">
                            <input type="hidden" name="image_url[]" value="${imageUrl}">
                            <img src="${imageUrl}" style="width:400px;height:auto;padding:5px">
                            <a onclick="removeImage(this)" style="cursor:pointer;">hapus gambar</a>
                        <div>`;

            $('.modal-body').html('');
            $('#modal-gallery').modal('hide');
            $('.block-image').append(html);
        });

        $('body').on('click', '.upload-image', function(e) {
            e.preventDefault();
            var title = $('#upload_title').val(),
                description = $('#upload_desc').val(),
                file = document.getElementById('upload_content_url');

            var formData = new FormData();

            formData.append('title', title);
            formData.append('description', description);
            formData.append('content_url', file.files[0]);

            $.ajax({
                url: `${urlGalleryData}`,
                method: 'post',
                dataType: 'json',
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': `{{ csrf_token() }}`
                },
                data: formData,
                success: function (data) {
                    let html = `<div>
                                    <input type="hidden" name="gallery_ids[]" value="${data.id}">
                                    <input type="hidden" name="image_url[]" value="${data.content_image_url}">
                                    <img src="${data.content_image_url}" style="width:400px;height:auto;padding:5px">
                                    <a onclick="removeImage(this)" style="cursor:pointer;">hapus gambar</a>
                                </div>`;

                    $('.modal-body').html('');
                    $('#modal-gallery').modal('hide');
                    $('.block-image').append(html);
                },
                error: function (data) {
                    if (data.status === 422) {
                        let html = `<div class="alert alert-danger"><ul>`;
                        $.each(data.responseJSON.errors, function (key, row) {
                            html += `<li>${row[0]}</li>`
                        });
                        html += `</ul></div>`;
                        $('.upload-error').html(html);
                    }
                }
            })
        });

        function removeImage(element) {
            $(element).parent().remove();
        }
    </script>
@endpush
