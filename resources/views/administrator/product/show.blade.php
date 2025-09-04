@extends('layouts.admin.main')
@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data Master Product</h1>
        <ol class="breadcrumb">
            <li>Shop</li>
            <li><a href="{{ route('admin.product.index') }}">Product</a></li>
            <li class="active">Detail</li>
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
                        <h3>Detail Product</h3>
                    </div> <!-- /widget-header -->
                    <div class="widget-content">
                        <div class="form-horizontal fieldset-form">
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Nama:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name" value="{{ @$product->name }}" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-10 col-sm-offset-2">
                                    <fieldset>
                                        <legend>Product Details</legend>
                                        <div class="form-group">
                                            <div class="form-group col-sm-2">
                                                <label class="form-label">Size</label>
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <label class="form-label">Stock Initial</label>
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <label class="form-label">Stock Sold</label>
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <label class="form-label">Stock Available</label>
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <label class="form-label">Price Siswa</label>
                                            </div>
                                            <div class="form-group col-sm-2">
                                                <label class="form-label">Price PPDB</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            @if (@$product->details)
                                                @foreach ($product->details as $detail)
                                            <div class="row">
                                                <div class="form-group col-sm-2">
                                                    <label class="label label-default label-sm">{{ $detail->size }}</label>
                                                </div>
                                                <div class="form-group col-sm-2">
                                                    <label class="label label-primary label-sm">{{ $detail->initial_stock }}</label>
                                                </div>
                                                <div class="form-group col-sm-2">
                                                    <label class="label label-warning label-sm">{{ $detail->stock_sold }}</label>
                                                </div>
                                                <div class="form-group col-sm-2">
                                                    <label class="label label-success label-sm">{{ $detail->available_stock }}</label>
                                                </div>
                                                <div class="form-group col-sm-2">
                                                    <label class="label label-default label-sm">{{ \App\Helpers\PriceHelper::rupiah($detail->price_siswa) }}</label>
                                                    <small>
                                                        <label class="label label-danger label-sm">{{ \App\Helpers\PriceHelper::rupiah($detail->price_vendor_regular) }}</label>
                                                    </small>
                                                </div>
                                                <div class="form-group col-sm-2">
                                                    <label class="label label-default label-sm">{{ \App\Helpers\PriceHelper::rupiah($detail->price_ppdb) }}</label>
                                                    <small>
                                                        <label class="label label-danger label-sm">{{ \App\Helpers\PriceHelper::rupiah($detail->price_vendor_regular) }}</label>
                                                    </small>
                                                </div>
                                            </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </fieldset>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Type:</label>
                                <div class="col-sm-10">
                                    <input type="text" name="type" class="form-control" value="{{ @$product->type->name }}" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Category:</label>
                                <div class="col-sm-10">
                                    <input type="text" name="category" class="form-control" value="{{ @$product->category->name }}" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Merk:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="merk" id="merk" value="{{ @$product->merk }}" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Level:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="level" id="level" value="{{ @$product->level }}"  readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Weight:</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="weight" id="weight" value="{{ @$product->weight }}" min="0" readonly>
                                        <div class="input-group-addon">gram</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="id_published">Image:</label>
                                <div class="col-sm-10">
                                    <div class="preview-image {{ @$product->image_path !== null ? NULL : 'hide' }}">
                                        <img class="responsive" src="{{ @$product->image }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="units">Unit:</label>
                                <div class="col-sm-10">
                                    @if (@$product->productUnits)
                                        @foreach ($product->productUnits as $unit)
                                            <label id="units" class="label label-{{ @$unit->unit->present_color }}">{{ @$unit->unit->name }}</label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="vendor_id">Vendor:</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ @$product->vendor->name }}" id="vendor_id" class="form-control" placeholder="-" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="description">Deskripsi:</label>
                                <div class="col-sm-10">
                                    {!! @$product->description !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="id_published">Status:</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ @$product->status }}" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <a href="{{ route('admin.product.index') }}" class="btn btn-primary">
                                        <span><i class="fa fa-arrow-left"></i></span>&nbsp;Back To List
                                    </a>
                                </div>
                            </div>
                        </div>
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
    </style>
@endpush

