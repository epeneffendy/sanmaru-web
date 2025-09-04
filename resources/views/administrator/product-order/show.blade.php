@extends('layouts.admin.main')
@section('content')
@php($status="Show")
@php($status_header="Show")
<!-- Start Page Header -->
<div class="page-header">
    <h1 class="title">Data Master Pembelian Produk</h1>
    <ol class="breadcrumb">
        <li>Shop</li>
        <li><a href="{{route('admin.product-order.index')}}">Pembelian Produk</a></li>
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
                    <h3>{{$status_header}} Produk Order</h3>
                </div> <!-- /widget-header -->
                <div class="widget-content">
                    <div class="form-horizontal">
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
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="nis">No Pembayaran:</label>
                            <div class="col-sm-10">
                                {{ @$productOrder->invoice_no }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="nis">No VA:</label>
                            <div class="col-sm-10">
                                @if(!empty($productOrder->virtual_account_number))
                                    <div class="pembayaran-item__title"><b>Bank {{ $productOrder->payment_option }} <span class="{{ $productOrder->payment_option }}"></span></b></div>
                                    <div class="pembayaran-item__content"><span>{{ $productOrder->virtual_account_number }}</span></div>
                                @else
                                    <?php
                                        $isbca = \App\Helpers\Helper::isVaBcaEnable() ? 'BCA' : NULL;
                                        $bank = \App\Helpers\PriceHelper::paymentInfo($user->unit, $isbca) ?? NULL;
                                    ?>
                                    @if ($bank)
                                        <div class="pembayaran-item__title"><b>Bank {{ $bank['bank'] }} <span class="{{ $bank['bank'] }}"></span></b></div>
                                        <div class="pembayaran-item__content"><span>{{ \App\Helpers\PriceHelper::virtualAccountNumber($user, true, $isbca) }}</span></div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="name">Nama:</label>
                            <div class="col-sm-10">
                                {{ @$productOrder->user->name }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="email">Email:</label>
                            <div class="col-sm-10">
                                {{ @$productOrder->user->email }}
                            </div>
                        </div>
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
                        @if($productOrder->payment_type != '12')
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="status">Status Pembayaran:</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-12">{!! @$productOrder->labelKonfirmasiPembayaran !!}</div>
                                    @if($productOrder->status === 'new_order' && \App\Helpers\Helper::isVaBcaEnable())
                                        <div class="col-sm-12">
{{--                                            <a href="{{route('admin.product-order.check-status-payment',$productOrder->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-refresh"></i> Cek status pembayaran</a>--}}
                                            @if($productOrder->payment_option == 'BCA')
                                                <a href="{{route('admin.product-order.check-inquiry-status',$productOrder->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-refresh"></i> Cek status pembayaran</a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                        @if ($productOrder->status === \App\Models\ProductOrder::STATUS_CANCEL)
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="status">Keterangan Pembatalan:</label>
                                <div class="col-sm-10">
                                    <div class="row">
                                        @if(!empty($productOrder->payment_cancel_reason))
                                            <label class="label" style="color: #dc3545">{{$productOrder->payment_cancel_reason}}</label>
                                        @else
                                            <label class="label" style="color: #dc3545">Melebihi Batas Waktu Pembayaran</label>
                                        @endif
                                    </div>
                                </div>
                            </div>
                                @if(!empty($productOrder->payment_cancel_reason))
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="status">Waktu Pembatalan:</label>
                                        <div class="col-sm-10">
                                            <div class="row">
                                                <label class="label" style="color: #dc3545">
                                                    {{ \App\Helpers\Helper::hariTanggalJam($productOrder->payment_cancel_date) }}</label>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                        @endif
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="status">Status Pengambilan:</label>
                            <div class="col-sm-10">
                                {!! @$productOrder->pickupStatusLabel !!}
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
                                                    <th>Ukuran</th>
                                                    <th>Harga Siswa</th>
                                                    <th>Harga PPDB</th>
                                                    <th>Kuantitas</th>
                                                    <th>Note</th>
                                                    <th>Sub Total</th>
                                                </tr>
                                            </thead>
                                            <tbody id="selected-product-details">
                                                @if (@$productOrder->productOrderDetails)
                                                    @foreach ($productOrder->productOrderDetails as $key => $detail)
                                                        @include('administrator.product-order._selected_product_order_details', [
                                                            'product_order_detail_id' => $detail->id,
                                                            'product_detail_id' => $detail->product_detail_id,
                                                            'product_id' => $detail->product_id,
                                                            'size' => $detail->productDetail->size,
                                                            'price_siswa' => $detail->productDetail->price_siswa,
                                                            'price_ppdb' => $detail->productDetail->price_ppdb,
                                                            'qty' => $detail->quantity,
                                                            'note' => $detail->note,
                                                            'total_price' => $detail->total_price,
                                                            'mode' => 'show',
                                                            'product_name' => $detail->product->name,
                                                            'no' => ($key+1),
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

                        @if ($productOrder->payment_image !== null && $productOrder->status === \App\Models\ProductOrder::STATUS_NEW_ORDER)
                            <hr/>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="image">Bukti Pembayaran</label>
                                <div class="col-sm-10">
                                    <a href="{{ $productOrder->getPaymentImageUrl() }}" target="_blank">
                                        <img src="{{ $productOrder->getPaymentImageUrl() }}" alt="Payment" style="max-width: 300px; height: auto;">
                                    </a>
                                </div>
                            </div>
                            @if (\App\Helpers\Helper::isShopRole())
                            <a href="{{route('admin.product-order.index')}}" class="btn btn-warning">Back</a>
                            <button class="btn btn-danger" data-toggle="modal" data-target="#rejectPaymentModal"><i class="fa fa-times" title="Belum Lengkap"></i>Tolak Pembayaran</button>
                            <a href="{{ route('admin.product-order.confirm-payment',$productOrder['id']) }}"
                                class="btn btn-default"
                                onclick="return confirm('Apa anda yakin mau mengkonfirmasi pembayaran pesanan ini?');">
                                <i class="fa fa-dollar"></i> Konfirmasi Pembayaran
                            </a>
                            @endif
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
<div id="rejectPaymentModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Nofitication for student</h4>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.product-order.reject-payment', $productOrder['id']) }}" method="POST">
                    @csrf
                    <p style="text-align: left;">Silahkan isi alasan penolakan pembayaran.</p>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <textarea class="form-control" name="body" id="body" rows="3" placeholder="Masukkan" required>{!! old('body') !!}</textarea>

                            @error('body')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-12">
                            <div class="checkbox checkbox-success">
                                <input type="checkbox" name="send_email" id="send_email" value="1" {{ old('send_email', 1) ? 'checked' : '' }}>
                                <label for="send_email">Kirim email pemberitahuan</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-0" style="text-align: right; padding-right:10px">
                            <button type="submit" class="btn btn-warning">Reset</button>

                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END CONTAINER -->
@endsection
