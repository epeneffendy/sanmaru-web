<div id="modal-detail-voucher" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Detail Voucher</h4>
            </div>
            <div class="modal-body">
                <h1 style="text-align: center">{{$voucher->code}}</h1>
                <h6 style="font-size: 18px">
                    @if($voucher->type == 'free_product')
                        Free Product
                    @elseif($voucher->type == 'discount_percent')
                        Discount {{$voucher->rule}} %
                    @else
                        Discount Rp. {{$voucher->rule}}
                    @endif
                </h6>

                @if(!empty($voucher->note))
                    <h1 style="font-size: 15px">Keterangan</h1>
                    <p>{{$voucher->note}}</p>
                @endif
                <p class="mt-2" style="color:red">***Voucher tidak dapat diuangkan</p>
            </div>
            <div class="modal-footer">
                <span class="btn btn-green btn-get-voucher" data-code="{{$voucher->code}}"><i class="fa fa-gift"></i>Gunakan</span>
                <span class="btn btn-green" data-dismiss="modal"><i class="fa fa-gift"></i>Close</span>
            </div>
        </div>
    </div>
</div>