<div class="form-group">
    <label class="control-label col-sm-2" for="order_status">Waktu Order:</label>
    <div class="col-sm-10">
        <div class="form-control" id="order_status">{{ @$productOrder->created_at->format('d-m-Y H:i:s') }}</div>
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
                            <th>Harga</th>
                            <th>Kuantitas</th>
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
                                    'price' => $detail->productDetail->price,
                                    'qty' => $detail->quantity,
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
    <label for="order_total" class="control-label col-sm-2">Total Harga:</label>
    <div class="col-sm-10">
        <div class="form-control" id="order_total">{{ \App\Helpers\PriceHelper::rupiah($productOrder->grand_total_gross) }}</div>
    </div>
</div>

@if ($voucher = json_decode($productOrder->voucher, TRUE))
<div class="form-group">
    <label class="control-label col-sm-2" for="order_voucher">Voucher:</label>
    <div class="col-sm-10">
        <div class="form-control" id="order_voucher"><b>{{ $voucher['code'] }}</b> - {{ \App\Helpers\PriceHelper::rupiah($productOrder->discount_total) }} off </div>
    </div>
</div>
@endif

<div class="form-group">
    <label class="control-label col-sm-2" for="order_grand_total">Total yang harus dibayarkan:</label>
    <div class="col-sm-10">
        <div class="form-control">{{ \App\Helpers\PriceHelper::rupiah($productOrder->grand_total) }}</div>
    </div>
</div>