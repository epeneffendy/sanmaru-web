@extends('layouts.admin.main')
@section('content')
    @php($action=route('admin.unit.update',array($unit['id'])))
    @php($status="Update")
    @php($status_header="Edit")

    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data Master Unit</h1>
        <ol class="breadcrumb">
            <li>Master</li>
            <li><a href="{{route('admin.unit.index')}}">Unit</a></li>
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
                        <h3>{{$status_header}} Unit</h3>
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
                            <input type="hidden" name="id" value="{{@$unit->id}}" />
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="unit_code">Unit Code:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="unit_code" id="unit_code" value="{{@$unit['unit_code']}}" placeholder="Enter Unit Code" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Unit Name:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name" value="{{@$unit['name']}}" placeholder="Enter Unit Name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="address">Address:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="address" id="address" rows="3" placeholder="Enter Address">{{@$unit['address']}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="city">City:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="city" id="city" value="{{@$unit['city']}}" placeholder="Enter Unit City" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="email">Email:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="email" id="email" value="{{@$unit['email']}}" placeholder="Enter Email">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="phone">Phone:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="phone" id="phone" value="{{@$unit['phone']}}" placeholder="Enter Phone">
                                </div>
                            </div>

                            <hr>

                            <div class="form-group">
                                <div class="control-label col-sm-2"><h3>Telp & Fax</h3></div>
                            </div>
                            <div class="form-group">
                                <label for="telp" class="control-label col-sm-2">Telp</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="telp" value="{{ old('telp', @implode(',', @$unit['telp'])) }}" placeholder="Telp">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="fax" class="control-label col-sm-2">Fax</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="fax" value="{{ old('fax', @implode(',', @$unit['fax'])) }}" placeholder="Fax">
                                    <small>**NB: Jika lebih dari satu pisahkan dengan tanda koma</small>
                                </div>
                            </div>

                            <hr>

                            <div class="form-group">
                                <div class="control-label col-sm-2"><h3>Profile Unit</h3></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="about">About:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="about" id="about" rows="3" placeholder="Content">{{@$unit['about']}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="keunggulan">Keunggulan:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="keunggulan" id="keunggulan" rows="3" placeholder="Content">{{@$unit['keunggulan']}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="image_path">Image:</label>
                                <div class="col-sm-10">
                                    <input type="file" name="image_path" id="image_path" class="form-control" />
                                    <div class="preview-image {{ @$unit->image_path !== null ? NULL : 'hide' }}">
                                        <img class="responsive" src="{{ @$unit->image }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="banner_path">Banner:</label>
                                <div class="col-sm-10">
                                    <input type="file" name="banner_path" id="banner_path" class="form-control" />
                                    <div class="preview-banner {{ @$unit->banner_path !== null ? NULL : 'hide' }}">
                                        <img class="responsive" src="{{ @$unit->banner }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="keunggulan_path">Keunggulan Section Image:</label>
                                <div class="col-sm-10">
                                    <input type="file" name="keunggulan_path" id="keunggulan_path" class="form-control" />
                                    <div class="preview-keunggulan {{ @$unit->keunggulan_path !== null ? NULL : 'hide' }}">
                                        <img class="responsive" src="{{ @$unit->keunggulan_image_path }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="procedure">Prosedur:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="procedure" id="procedure" rows="3" placeholder="Content">{{@$unit['procedure']}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="helpdesk">Helpdesk:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="helpdesk" id="helpdesk" rows="3" placeholder="Content">{{old('helpdesk', $unit['helpdesk'])}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="header_info">Header Info (Bukti Pendaftaran):</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="header_info" id="header_info" rows="3" placeholder="Content">{{old('header_info', $unit['header_info'])}}</textarea>
                                </div>
                            </div>

                            <hr>

                            <div class="form-group">
                                <div class="control-label col-sm-2"><h3>Cost</h3></div>
                            </div>
                            <div class="form-group">
                                <div class="control-label col-sm-2">
                                    <button id="btn_add_unit_cost_form" class="btn btn-default" type="button">+ Add Cost</button>
                                </div>
                            </div>
                            <div class="form-group" id="unit_cost_form">
                                @forelse($unit->costs as $cost)
                                    <div class="col-sm-12" id="unit_cost_{{ $loop->iteration }}">
                                        <div class="box">
                                            <div class="form-group">
                                                <input type="hidden" class="form-control" name="unit_cost_ids[]" value="{{ $cost->id }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="title">Title:</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" name="cost_titles[]" value="{{ $cost->title }}" placeholder="Enter Title">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="description">Description:</label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control" name="cost_descriptions[]" rows="3" placeholder="Description">{{ $cost->description }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-sm-12" id="unit_cost_0" style="visibility: hidden; display: none">
                                        <div class="box">
                                            <div class="form-group">
                                                <input type="hidden" class="form-control" name="unit_cost_ids[]">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="title">Title:</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" name="cost_titles[]" placeholder="Enter Title">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="description">Description:</label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control" name="cost_descriptions[]" rows="3" placeholder="Description"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                            <div class="form-group">
                                <button id="btn_delete_unit_cost" class="pull-right btn btn-danger">Delete Cost</button>
                            </div>

                            <hr>

                            <div class="form-group">
                                <div class="control-label col-sm-2"><h3>Testimoni</h3></div>
                            </div>
                            <div class="form-group">
                                <div class="control-label col-sm-2">
                                    <button id="add_form" class="btn btn-default">+ Add Testimoni</button>
                                </div>
                            </div>
                            <div class="form-group" id="testimoni_form">
                            @if (@$unit->testimonies)
                                @foreach ($unit->testimonies as $testimony)
                                    <div class="box">
                                        <div class="row">
                                            <div class="col-sm-8" id="testimoni{{ $loop->index }}">
                                                <input type="hidden" class="form-control" name="testimony_ids[]" value="{{ @$testimony->id }}">
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="subject">Subject:</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="subjects[]" value="{{ @$testimony->subject }}" placeholder="Enter Subject">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="job">Job:</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="jobs[]" id="job" value="{{ @$testimony->job }}" placeholder="Enter Job">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="content">Content</label>
                                                    <div class="col-sm-10">
                                                        <textarea class="form-control" name="contents[]" id="content" rows="3" placeholder="Content">{{@$testimony['content']}}</textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                <label class="control-label col-sm-2" for="photo_path">Photo:</label>
                                                    <div class="col-sm-10">
                                                        <input type="file" name="photo_paths[]" class="form-control" />
                                                    </div>
                                                </div>

                                                <!-- <div class="form-group">
                                                    <button id='delete_form' class="pull-right btn btn-danger">Delete Testimoni</button>
                                                </div> -->
                                            </div>
                                            <div class="col-sm-3">
                                                @if ($testimony['photo_path'])
                                                    <img src="{{ \App\Helpers\ImageHelper::imageUrl($testimony->photo_path) }}" class="img-responsive img-circle img-thumbnail" style="margin-top: -15px;" />
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                    <div class="box" id="testimoni{{ count($unit->testimonies) }}"></div>
                            @endif
                                    <div class="box" id="testimony" style="visibility: hidden; display: none">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="form-group">
                                                    <input type="hidden" class="form-control" name="testimony_ids[]">
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="subject">Subject:</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="subjects[]" placeholder="Enter Subject">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="job">Job:</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="jobs[]" id="job" placeholder="Enter Job">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2" for="content">Content</label>
                                                    <div class="col-sm-10">
                                                        <textarea class="form-control" name="contents[]" id="content" rows="3" placeholder="Content"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                <label class="control-label col-sm-2" for="photo_path">Photo:</label>
                                                    <div class="col-sm-10">
                                                        <input type="file" name="photo_paths[]" class="form-control" />
                                                    </div>
                                                </div>

                                                <!-- <div class="form-group">
                                                    <button id='delete_form' class="pull-right btn btn-danger">Delete Testimoni</button>
                                                </div> -->
                                            </div>
                                            <div class="col-sm-3">
                                                <img src="" class="img-responsive img-circle img-thumbnail" style="margin-top: -15px;" />
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <div class="form-group">
                                <button id='delete_form' class="pull-right btn btn-danger">- Delete Testimoni</button>
                            </div>

                            <fieldset>
                                <legend>Peraturan Pendaftaran</legend>
                                <div class="form-group">
                                    <label class="control-label col-sm-2">Pembayaran:</label>
                                    <div class="col-sm-10">
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="cimb_option" {!! $unit->payment_option === 'CIMB Niaga' ? 'checked="true"' : NULL !!} value="CIMB Niaga" name="payment_option" />
                                            <label for="cimb_option"> CIMB Niaga </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="mandiri_option" value="Mandiri" name="payment_option" {!! $unit->payment_option === 'Mandiri' ? 'checked="true"' : NULL !!}/>
                                            <label for="mandiri_option"> Mandiri </label>
                                        </div>
                                    </div>
                                </div>

                            </fieldset>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-default">{{$status}}</button>
                                    <a href="{{ route('admin.unit.index')}}" class="btn btn-danger">Cancel</a>
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
    @parent
    <link rel="stylesheet" href="{{asset('css/plugin/bootstrap-select/bootstrap-select.css')}}">
    <style>
        .preview-image, .preview-banner, .preview-keunggulan{
            margin-top: 5px;
            border: 1px solid #333333;
            padding: 10px;
            width: 200px;
        }

        .preview-image img,
        .preview-banner img,
        .preview-photo img,
        .preview-keunggulan img {
            width: 100%;
            height: auto;
        }

        .box{
            border:1px solid #ccc;
            padding-top: 20px;
            margin-top: 20px;
        }

        #delete_form {
            margin-right: 20px;
        }

    </style>
@endpush
@push('scripts')
    @parent
    <script src="{{asset('js/bootstrap-select/bootstrap-select.js')}}"></script>
    <script src="{{asset('js/summernote/summernote.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $("input[name=image_path]").change(function() {
                readImg(this);
            });
            $("input[name=banner_path]").change(function() {
                readBanner(this);
            });

            $("input[name=keunggulan_path]").change(function(e) {
                e.preventDefault();
                var parent = this;
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $(parent).parent().find('.preview-keunggulan img').attr('src', e.target.result).parent().removeClass('hide');
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });

            $(document).on('change', "input[name='photo_paths[]']", function() {
                var parent = $(this);
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $(parent).parent().parent().parent().parent().find('.col-sm-3 img').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });

            let form_number =  {{ count($unit->testimonies) }} ;
            $("#add_form").click(function(e){
                e.preventDefault();
                let new_form_number = form_number - 1;
                $('#testimoni' + form_number).html($('#testimony').html()).find('div:first-child');
                $('#testimoni_form').append('<div class="col-sm-12" id="testimoni' + (form_number + 1) + '"></tr>');
                form_number++;
                document.querySelector('#delete_form').scrollIntoView({behavior: 'smooth'});
            });

            $("#delete_form").click(function(e){
                e.preventDefault();
                if(form_number > 1){
                    $("#testimoni" + (form_number - 1)).html('');
                    form_number--;
                }
            });

            $('textarea[name=about], textarea[name=keunggulan], textarea[name=procedure], textarea[name=helpdesk], textarea[name^=cost_descriptions], textarea[name=header_info]').summernote();

            let form_unit_cost_number = {{ count($unit->costs) }};
            $("#btn_add_unit_cost_form").click(function(e) {
                e.preventDefault();
                form_unit_cost_number++;

                $('#unit_cost_form').append(`<div class="col-sm-12" id="unit_cost_${form_unit_cost_number}"></tr>`);

                $(`#unit_cost_${form_unit_cost_number}`).html($(`#unit_cost_${(form_unit_cost_number - 1)}`).html()).find('div:first-child');

                $(`#unit_cost_${form_unit_cost_number}`).find('input').val('');
                $(`#unit_cost_${form_unit_cost_number}`).find('textarea').code('');
                $(`#unit_cost_${form_unit_cost_number}`).find('textarea').val('');
                $('textarea[name^=cost_descriptions]').summernote();
            });

            $("#btn_delete_unit_cost").click(function(e) {
                e.preventDefault();

                if(form_unit_cost_number > 1){
                    $(`#unit_cost_${(form_unit_cost_number)}`).html('');
                    form_unit_cost_number--;
                }
            })
        });

        function readImg(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('.preview-image img').attr('src', e.target.result).parent().removeClass('hide');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function readBanner(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('.preview-banner img').attr('src', e.target.result).parent().removeClass('hide');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
