@extends('layouts.admin.main')
@section('content')
@php($status="Show")
@php($status_header="Show")
<!-- Start Page Header -->
<div class="page-header">
    <h1 class="title">Pengambilan Pesanan</h1>
    <ol class="breadcrumb">
        <li>Shop</li>
        <li><a href="{{route('admin.product-order-pickup.index')}}">Pengambilan Pesanan</a></li>
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
                    <h3>{{$status_header}} Pengambilan Pesanan</h3>
                </div> <!-- /widget-header -->
                <div class="widget-content">
                    @if (session('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif
                    @if (session('errors'))
                        <div class="alert alert-danger">
                            {!! session('errors')->first() !!}
                        </div>
                    @endif
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="nis">No Pembayaran:</label>
                            <div class="col-sm-10">
                                {{ @$productOrder->invoice_no }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="name">Nama:</label>
                            <div class="col-sm-10">
                                {{ $productOrder->user->name }}
                            </div>
                        </div>
                        @if ($productOrder->payment_type != '12' || $productOrder->payment_type == null)                            
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="email">Email:</label>
                            <div class="col-sm-10">
                                {{ @$productOrder->user->email }}
                            </div>
                        </div>                            
                        @endif
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="email">Unit:</label>
                            <div class="col-sm-10">
                                {{ @$productOrder->user->unit_name }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="status">Status:</label>
                            <div class="col-sm-10">
                                {!! @$productOrder->statusLabel !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="status">Status Pembayaran:</label>
                            <div class="col-sm-10">
                                {!! @$productOrder->labelKonfirmasiPembayaran !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="status">Status Pengambilan:</label>
                            <div class="col-sm-10">
                                {!! @$productOrder->pickupStatusLabel !!}
                                <br>
                                   {!! @$productOrder->pickup_date !!}
                                 <br>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Status User:</label>
                            <div class="col-sm-10">
                                {{ strtoupper($productOrder->user->type) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="status">Waktu Order:</label>
                            <div class="col-sm-10">
                                {{ @$productOrder->created_at->format('d-m-Y H:i:s') }}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                                <fieldset style="padding: 20px; margin 0 2px; border-radius: 3px; border: 1px solid #ccc; padding-top: 10px">
                                    <legend style="border-bottom: 0px; width: auto; padding: 0px 10px; font-size: 16px; font-weight: 600">Product Details</legend>
                                    <div class="form-group col-md-12" style="margin-left: 1px; margin-right: 5px">
                                        <table class="table table-sm table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Produk</th>
                                                    @if ($productOrder->payment_type == '12')
                                                        <th>Varian</th>
                                                        <th>Harga</th>
                                                    @else
                                                        <th>Ukuran</th>
                                                        <th>Harga Siswa</th>
                                                        <th>Harga PPDB</th>
                                                    @endif
                                                    <th>Kuantitas</th>
                                                    <th>Sub Total</th>
                                                </tr>
                                            </thead>
                                            <tbody id="selected-product-details">
                                                @if (@$productOrder->productOrderDetails)
                                                    @foreach ($productOrder->productOrderDetails as $key => $detail)
                                                        @include('administrator.product-order-pickup._selected_product_order_pickup_details', [
                                                            'product_order_detail_id' => $detail->id,
                                                            'product_detail_id' => $detail->product_detail_id,
                                                            'product_id' => $detail->product_id,
                                                            'size' => $detail->productDetail->size,
                                                            'price_siswa' => $detail->productDetail->price_siswa,
                                                            'price_ppdb' => $detail->productDetail->price_ppdb,
                                                            'qty' => $detail->quantity,
                                                            'total_price' => $detail->total_price,
                                                            'mode' => 'show',
                                                            'product_name' => $detail->product->name,
                                                            'no' => ($key+1),
                                                            'type' => $productOrder->payment_type
                                                        ])
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </fieldset>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="total" class="control-label col-sm-2">Total Harga:</label>
                            <div class="col-sm-10">
                                {{ \App\Helpers\PriceHelper::rupiah($productOrder->grand_total_gross) }}
                            </div>
                        </div>

                        @if ($voucher = json_decode($productOrder->voucher, TRUE))
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="status">Voucher:</label>
                            <div class="col-sm-10">
                                <b>{{ $voucher['code'] }}</b> -
                                {{ \App\Helpers\PriceHelper::rupiah($productOrder->discount_total) }} off
                            </div>
                        </div>
                        @endif
                        
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="status">Total yang harus dibayarkan:</label>
                            <div class="col-sm-10">
                                {{ \App\Helpers\PriceHelper::rupiah($productOrder->grand_total) }}
                            </div>
                        </div>

                        
                        <hr/>
                        @if($productOrder->isPickup())
                            <form action="{{route('admin.product-order-pickup.upload-pickup-image', $productOrder->id)}}" method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="pickup_image">Bukti Pengambilan:</label>
                                    <div class="col-sm-10">
                                        <input accept="image/x-png,image/jpeg" type="file" name="pickup_image" class="form-control" />
                                        <div class="preview-image {{ @$productOrder->pickup_image !== null ? NULL : 'hide' }}">
                                            <img class="responsive" src="{{ $productOrder->getPickupImageUrl() }}" />
                                        </div>
                                        @csrf
                                        @method('PUT')
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" class="btn btn-default"><i class="fa fa-upload"></i> Upload</button>
                                    </div>
                                </div>
                            </form>
                            
                            <form action="{{route('admin.product-order-pickup.cancel-pickup', $productOrder->id)}}" method="POST" id="form-cancel-pickup">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="id" value="{{$productOrder->id}}">
                            </form>
                        @endif
                        </div>
                        <div class="button-collection">
                            <a href="{{route('admin.product-order-pickup.index')}}" class="btn btn-warning"><span><i class="fa fa-arrow-left"></i></span>Back</a>
                            @if($productOrder->isPickup())
                            <a onclick="confirmCancel()" title="cancel" class="btn btn-danger"><span><i class="fa fa-trash"></i></span>Batal Pengambilan</a>
                            @endif
                        </div>
                    </div>
                    @csrf
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
    </style>
@endpush
@push('scripts')
<script>
     $(document).ready(function () {
        $("input[name=pickup_image]").change(function() {
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

    function confirmCancel() {
        if(confirm('Apakah anda yakin ingin membatalkan pengambilan seragam?'))
            document.getElementById('form-cancel-pickup').submit();
    }
</script>
@endpush
