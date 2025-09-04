@extends('layouts.admin.main')
@section('content')
    @php($action=route('admin.unit.insert'))
    @php($status="Save")
    @php($status_header="Tambah")

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
                            <input type="hidden" name="id" value="" />
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="unit_code">Unit Code:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="unit_code" id="unit_code" value="" placeholder="Enter Unit Code" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Unit Name:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name" value="" placeholder="Enter Unit Name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="address">Address:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="address" id="address" rows="3" placeholder="Enter Address"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="city">City:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="city" id="city" value=""  placeholder="Enter Unit City" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="email">Email:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="email" id="email" value="" placeholder="Enter Email">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="phone">Phone:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="phone" id="phone" value="" placeholder="Enter Phone">
                                </div>
                            </div>

                            <hr>

                            <div class="form-group">
                                <div class="control-label col-sm-2"><h3>Telp & Fax</h3></div>
                            </div>
                            <div class="form-group">
                                <label for="telp" class="control-label col-sm-2">Telp</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="telp" value="{{ old('telp') }}" placeholder="Telp">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="fax" class="control-label col-sm-2">Fax</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="fax" value="{{ old('fax') }}" placeholder="Fax">
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
                                    <textarea class="form-control" name="about" id="about" rows="3" placeholder="Content"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="keunggulan">Keunggulan:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="keunggulan" id="keunggulan" rows="3" placeholder="Content"></textarea>
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
                                <label class="control-label col-sm-2" for="banner_path">banner:</label>
                                <div class="col-sm-10">
                                    <input type="file" name="banner_path" id="banner_path" class="form-control" />
                                    <div class="preview-banner {{ @$unit->banner_path !== null ? null : 'hide' }}">
                                        <img class="responsive" src="{{ @$unit->banner }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="keunggulan_path">keunggulan image:</label>
                                <div class="col-sm-10">
                                    <input type="file" name="keunggulan_path" id="keunggulan_path" class="form-control" />
                                    <div class="preview-keunggulan {{ @$unit->keunggulan_path !== null ? null : 'hide' }}">
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
                                    <textarea class="form-control" name="helpdesk" id="helpdesk" rows="3" placeholder="Content">{{old('helpdesk')}}</textarea>
                                </div>
                            </div>

                            <hr>

                            <div class="form-group">
                                <div class="control-label col-sm-2"><h3>Cost</h3></div>
                            </div>
                            <div class="form-group">
                                <div class="control-label col-sm-2">
                                    <button id="btn_add_unit_cost_form" class="btn btn-default">+ Add Cost</button>
                                </div>
                            </div>
                            <div class="form-group" id="unit_cost_form">
                                <div class="col-sm-12" id="unit_cost_0">
                                    <div class="box">
                                        <div class="form-group">
                                            <input type="hidden" class="form-control" name="unit_cost_ids[]" value="">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="title">Title:</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="cost_titles[]" value="" placeholder="Enter Title">
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
                            </div>
                            <div class="form-group">
                                <button id="btn_delete_unit_cost" class="pull-right btn btn-danger" style="margin-right:20px">Delete Cost</button>
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
                                <div class="col-sm-12" id="testimoni0">
                                <div class="box">
                                    <div class="form-group">
                                        <input type="hidden" class="form-control" name="testimony_ids[]" value="">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="subject">Subject:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="subjects[]" value="" placeholder="Enter Subject">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="job">Job:</label>
                                       <div class="col-sm-10">
                                            <input type="text" class="form-control" name="jobs[]" id="job" value="" placeholder="Enter Job">
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
                                            <input type="file" name="photo_paths[]" value="" class="form-control" />
                                        </div>
                                    </div>

                                        <!-- <div class="form-group">
                                            <button id='delete_form' class="pull-right btn btn-danger">Delete Testimoni</button>
                                        </div> -->
                                </div>
                                </div>
                                <div class="col-sm-12" id="testimoni1"></div>
                        </div>
                            <div class="form-group">
                                <button id='delete_form' class="pull-right btn btn-danger">- Delete Testimoni</button>
                            </div>

                            <fieldset>
                                <legend>Peraturan Pendaftaran</legend>
                                <div class="form-group">
                                    <label class="control-label col-sm-2">Pembayaran:</label>
                                    <div class="col-sm-10">
                                        <label><input type="radio" name="payment_option" value="CIMB Niaga"> CIMB Niaga</label>
                                        <label><input type="radio" name="payment_option" value="Mandiri"> Mandiri</label>
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
    <script src="{{asset('js/summernote/summernote.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-select/bootstrap-select.js')}}"></script>
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

            let form_number = 1;
            $("#add_form").click(function(e){
                e.preventDefault();
                let new_form_number = form_number - 1;
                $('#testimoni' + form_number).html($('#testimoni' + new_form_number).html()).find('div:first-child');
                $('#testimoni_form').append('<div class="col-sm-12" id="testimoni' + (form_number + 1) + '"></tr>');
                form_number++;
            });

            $("#delete_form").click(function(e){
                e.preventDefault();
                if(form_number > 1){
                    $("#testimoni" + (form_number - 1)).html('');
                    form_number--;
                }
            });

            $('textarea[name=about], textarea[name=keunggulan], textarea[name=procedure], textarea[name^=helpdesk], textarea[name^=cost_description], textarea[name=header_info]').summernote();

            let form_unit_cost_number = 1;
            $("#btn_add_unit_cost_form").click(function(e) {
                e.preventDefault();
                $('#unit_cost_form').append(`<div class="col-sm-12" id="unit_cost_${form_unit_cost_number}"></tr>`);

                $(`#unit_cost_${form_unit_cost_number}`).html($(`#unit_cost_${(form_unit_cost_number - 1)}`).html()).find('div:first-child');

                $(`#unit_cost_${form_unit_cost_number}`).find('input').val('');
                $(`#unit_cost_${form_unit_cost_number}`).find('textarea').code('');
                $(`#unit_cost_${form_unit_cost_number}`).find('textarea').val('');
                $('textarea[name^=cost_descriptions]').summernote();

                form_unit_cost_number++;
            });

            $("#btn_delete_unit_cost").click(function(e) {
                e.preventDefault();

                if(form_unit_cost_number > 1){
                    form_unit_cost_number--;
                    $(`#unit_cost_${(form_unit_cost_number)}`).html('');
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
