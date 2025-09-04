@extends('layouts.admin.main')
@section('content')
    @if (@$method == "edit")
        @php($action=route('admin.product.update', array($product->id) ))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.product.insert'))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data Master Product</h1>
        <ol class="breadcrumb">
            <li>Shop</li>
            <li><a href="{{ route('admin.product.index') }}">Product</a></li>
            <li class="active">{{ $status_header }}</li>
        </ol>
    </div>
    <!-- End Page Header -->

    <!-- START CONTAINER -->
    <div class="container-padding" style="padding-bottom: 100px;">
        <!-- Start Row -->
        <div class="row">
            <!-- Start Panel -->
            <div class="col-md-12">
                <div class="widget" style="padding-bottom: 200px;">
                    <div class="widget-header">
                        <h3>{{ $status_header }} Product</h3>
                    </div> <!-- /widget-header -->
                    <div class="widget-content">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors as $error)
                                        <li>{{ $error['message'] }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form role="form" method="POST" action="{{$action}}"  class="form-horizontal fieldset-form" enctype="multipart/form-data">
                            <button type="submit" disabled style="display: none" aria-hidden="true"></button>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Nama:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name" value="{{ old('name', @$product->name) }}" placeholder="Enter name" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-10 col-sm-offset-2">
                                    <fieldset>
                                        <legend>Product Details</legend>
                                        <div class="form-group">
                                            <div class="form-group col-sm-1">
                                                <label class="form-label small">Size</label>
                                                <input type="text" class="form-control form-control-line">
                                            </div>
                                            <div class="form-group col-sm-1">
                                                <label class="form-label small">Stock</label>
                                                <input type="number" min="0" class="form-control form-control-line text-right">
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <label class="form-label small">Vendor Price Siswa</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon rupiah">
                                                        Rp
                                                    </div>
                                                    <input type="number" min="1" class="form-control form-control-line text-right">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <label class="form-label small">Price Siswa</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon rupiah">
                                                        Rp
                                                    </div>
                                                    <input type="number" min="1" class="form-control form-control-line text-right">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <label class="form-label small">Vendor Price PPDB</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon rupiah">
                                                        Rp
                                                    </div>
                                                    <input type="number" min="1" class="form-control form-control-line text-right">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <label class="form-label small">Price PPDB</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon rupiah">
                                                        Rp
                                                    </div>
                                                    <input type="number" min="1" class="form-control form-control-line text-right">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-1 align-text-bottom align-bottom">
                                                <label class="form-label"></label>
                                                <button class="btn btn-sm button-add-details btn-success" style="display:block; margin-top:10px"><i class="fa fa-plus"></i> add </button>
                                            </div>
                                        </div>
                                        <div class="form-group product-details">
                                            @if (@$product->details)
                                                @foreach ($product->details as $detail)
                                                    @include('administrator.product._product_details', [
                                                        'product_details_id' => $detail->id,
                                                        'size' => $detail->size,
                                                        'stock' => $detail->stock,
                                                        'price_siswa' => $detail->price_siswa,
                                                        'price_vendor_regular' => $detail->price_vendor_regular,
                                                        'price_ppdb' => $detail->price_ppdb,
                                                        'price_vendor_ppdb' => $detail->price_vendor_ppdb,
                                                    ])
                                                @endforeach
                                            @endif
                                        </div>
                                    </fieldset>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Type:</label>
                                <div class="col-sm-10">
                                    <select name="type" required class="form-control selectpicker">
                                        <option data-hidden="true"></option>
                                        @php($selected = false)
                                        @foreach ($productType as $key=>$type)
                                            @if (old('type', @$product->type_id) == $key)
                                                @php ($selected = true)
                                            @endif
                                            <option value="{{ $key }}" {{ old('type', @$product->type_id == $key) ? 'selected' : null }}>{{ $type }}</option>
                                        @endforeach
                                        @if (!$selected && old('type') != '')
                                            <option value="{{ old('type') }}" data-subtext="(new)" selected="">{{ old('type') }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Category:</label>
                                <div class="col-sm-10">
                                    <select name="category" required class="form-control selectpicker">
                                        <option data-hidden="true"></option>
                                        @php($selected = false)
                                        @foreach ($productCategory as $key=>$category)
                                            @if (old('category', @$product->category_id) == $key)
                                                @php ($selected = true)
                                            @endif
                                            <option value="{{ $key }}" {{ old('category', @$product->category_id) == $key ? 'selected' : null }}>{{ $category }}</option>
                                        @endforeach
                                        @if (!$selected && old('category') != '')
                                            <option value="{{ old('category') }}" data-subtext="(new)" selected="">{{ old('category') }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Merk:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="merk" id="merk" value="{{ old('merk', @$product->merk) }}" placeholder="Enter merk" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="level">Level:</label>
                                <div class="col-sm-10">
                                    <select name="level" class="form-control selectpicker" id="level">
                                        <option data-hidden="true"></option>
                                        @foreach($levelOpt as $key => $value)
                                        <option value="{{$key}}"
                                        {{ (old('level', @$product->level) == $key) ? 'selected' : NULL }}
                                        >{{$value}}</option>
                                        @endforeach
                                    </select>
                                    {{-- <input type="text" class="form-control" name="level" id="level" value="{{ old('level', @$product->level) }}" placeholder="Enter Level" required> --}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Weight:</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="weight" id="weight" value="{{ old('weight', @$product->weight) }}" min="0" placeholder="Enter weight" required>
                                        <div class="input-group-addon">gram</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="id_published">Image:</label>
                                <div class="col-sm-10">
                                    <input type="file" name="image" class="form-control" />
                                    <div class="preview-image {{ @$product->image_path !== null ? NULL : 'hide' }}">
                                        <img class="responsive" src="{{ @$product->image }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="units">Unit:</label>
                                <div class="col-sm-10">
                                    <select name="units[]" class="form-control" id="units" data-style="btn-success" multiple required data-selected-text-format="count > 3" multiple data-actions-box="true" data-dropup-auto="false">
                                        <option data-hidden="true"></option>
                                        @foreach ($unitList as $key => $unit)
                                            <option value="{{ $key }}" {{ @in_array($key, old('units', $product ? $product->productUnits->pluck('unit_id')->all() : [])) ? 'selected' : NULL }}>{{ $unit }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="vendor_id">Vendor:</label>
                                <div class="col-sm-10">
                                    <select name="vendor_id" class="form-control" id="vendor_id" data-style="btn-primary" data-dropup-auto="false">
                                        <option data-hidden="true"></option>
                                        @foreach ($vendorList as $key => $vendor)
                                            <option value="{{ $key }}" {{ $key === old('vendor_id', @$product->vendor_id) ? 'selected' : NULL }}>{{ $vendor }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="description">Deskripsi:</label>
                                <div class="col-sm-10">
                                    <textarea name="description" rows="5" class="form-control" id="description" placeholder="Description">{{old('note', @$product->description)}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="id_published">Status:</label>
                                <div class="col-sm-10">
                                    <select name="status" class="form-control" id="status">
                                        <option value="published" {{ (old('status', @$product->status) === 'published') ? "selected" : NULL }}>Published</option>
                                        <option value="unpublished" {{ (old('status', @$product->status) === 'unpublished') ? 'selected' : NULL }}>Unpublished</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-default">{{$status}}</button>
                                </div>
                            </div>

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
    <link rel="stylesheet" href="{{asset('css/plugin/summernote/summernote-bs3.css')}}">
    <style>
        button.selectpicker option {
            display: none;
        }
        ul.selectpicker .text-st, button.selectpicker .text-muted {
            color: red;
        }
        .preview-image {
            margin-top: 5px;
            border: 1px solid #333333;
            padding: 10px;
            width: 200px;
        }

        .preview-image img {
            width: 100%;
            height: auto;
        }

        .form-horizontal fieldset .form-group .form-group {
            margin-left: 10px;
        }

        .input-group input.form-control.form-control-line {
            border: 1px solid #BDC4C9;
            border-radius: 3px;
            padding-left: 10px;
            box-shadow: inset 0px 1px 0px #F1F0F1;
        }

        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
        }

        /* Firefox */
        input[type=number] {
        -moz-appearance: textfield;
        }

        .rupiah {
            padding: 5px !important;
            font-size: 10px !important;
        }
        .form-label.small {
            font-size: 12px !important;
        }
    </style>
@endpush
@push('scripts')
    @parent
    <script src="{{asset('js/bootstrap-select/bootstrap-select.js')}}"></script>
    <script src="{{asset('js/summernote/summernote.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('.selectpicker').selectpicker({
                liveSearch: true,
                noneResultsText: 'No result found <button class="btn btn-light" onclick=(add_opt(this))>Add new option</button>',
                showSubtext: true
            });
            $('#description').summernote({
                onImageUpload: function(files, editor, welEditable) {
                    sendFile(files[0], editor, welEditable);
                }
            });

            function sendFile(file, editor, welEditable) {
                data = new FormData();
                data.append("content_image", file);
                data.append("type", "product");
                data.append("_token", "{{ csrf_token() }}");
                $.ajax({
                    data: data,
                    type: "POST",
                    url: "{{ route('upload_image') }}",
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        editor.insertImage(welEditable, response);
                    }
                });
            }

            $("input[name=image]").change(function() {
                readURL(this);
            });

            $('#units').selectpicker({liveSearch: true});
            $('#vendor_id').selectpicker({liveSearch: true});
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

        function add_opt(event) {
            var value = $(event).parents('ul').siblings('.bs-searchbox').find('input').val();
            var selectpicker = $(event).parents('div').siblings('.selectpicker');
            var option = $("<option value='"+ value +"' selected='' data-subtext='(new)'>"+ value +"</option>");
            selectpicker.append(option);
            selectpicker.selectpicker('refresh');
        }

        // FUNCTION AREA
        $('.button-add-details').on('click', function(e) {
            e.preventDefault();
            var _parent = $(this).parent().parent('.form-group');

            if (!doValidation(_parent)) {
                return;
            }

            var _html = `@include('administrator.product._product_details')`;
            $(_parent).find('.form-group input').each(function (index, element) {
                var _val = $(this).val();
                _html = $(_html).find('.form-group:nth-child('+ (index+2) +') .form-label').html(_val).end()[0].outerHTML;
                _html = $(_html).find('.form-group:nth-child('+ (index+2) +') input').val(_val).end()[0].outerHTML;
                $(element).val('');
            });

            $('.product-details').append(_html);

            $([document.documentElement, document.body]).animate({
                scrollTop: $(".product-detail:last-child").offset().top
            }, 1000);
        });

        $(document).on('click', '.button-edit-details', function(e) {
            e.preventDefault();
            var _parent = $(this).parent().parent('.product-detail');
            $(_parent).find('.form-group:last-child button:not(.btn-success)').hide();
            $(_parent).find('.form-group:last-child button.btn-success').show();
            $(_parent).find('.form-group label').hide();
            $(_parent).find('.form-group input').attr('type', 'text');
            $(_parent).find('.form-group input[data-validation=number]').attr('type', 'number');
            $(_parent).find('.form-group .input-group').show();
        });

        $(document).on('click', '.button-save-details', function(e) {
            e.preventDefault();
            var _parent = $(this).parent().parent('.product-detail');
            if (!doValidation(_parent)) {
                return;
            }

            $(_parent).find('.form-group:last-child button:not(.btn-success)').show();
            $(_parent).find('.form-group:last-child button.btn-success').hide();
            $(_parent).find('.form-group input').attr('type', 'hidden');
            $(_parent).find('.form-group .input-group').hide();
            $(_parent).find('.form-group input').each(function(index, element) {
                $(_parent).find('.form-group:nth-child('+ (index+2) +') .form-label').html($(this).val());
            });
            $(_parent).find('.form-group label').show();
        });

        $(document).on('click', '.button-delete-details', function(e) {
            e.preventDefault();
            if (!confirm("Delete this product detail ?")) {
                return;
            }
            var _parent = $(this).parent().parent('.product-detail');
            _parent.remove();
        });

        function doValidation(detailElement) {
            var valid = true;
            $(detailElement).find('input:not([type=hidden])').each(function (index, element){
                $(element).closest('.form-group').removeClass('has-error');
                if ( (!$(element).val()) ||
                    ($(element).attr('data-validation') === 'number' && !validation.isNumber($(element).val()))
                ) {
                    valid = false;
                    $(element).closest('.form-group').addClass('has-error');
                    return;
                }
            });

            return valid;
        }

        var validation = {
            isEmailAddress:function(str) {
                var pattern =/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                return pattern.test(str);  // returns a boolean
            },
            isNotEmpty:function (str) {
                var pattern =/\S+/;
                return pattern.test(str);  // returns a boolean
            },
            isNumber:function(str) {
                var pattern = /^\d+$/;
                return pattern.test(str);  // returns a boolean
            },
            isSame:function(str1,str2){
                return str1 === str2;
            }
        };
    </script>
@endpush
