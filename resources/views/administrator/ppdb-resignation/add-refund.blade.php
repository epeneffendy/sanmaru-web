@extends('layouts.admin.main')
@section('content')
    @php($action=route('admin.ppdb-resignation.insert-refund', $ppdbResignation->id))
    @php($status="Save")
    @php($status_header="Tambah")
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data Pengembalian</h1>
        <ol class="breadcrumb">
            <li>PPDB</li>
            <li><a href="{{route('admin.ppdb-resignation.index')}}">Data Pengembalian</a></li>
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
                        <h3>{{$status_header}} Data Pengembalian</h3>
                    </div> <!-- /widget-header -->
                    <div class="widget-content">
                        @if (session('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form role="form" method="POST" action="{{$action}}" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="type">Unit:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="unit_id" value="{{$ppdbUser->unit->name}}" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="register_number">Nomor Registrasi:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="register_number" id="register_number" value="{{$ppdbUser->register_number}}" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Nama:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name" value="{{$ppdbUser->name}}"  disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="refund_type" class="control-label col-sm-2">Jenis Pengembalian</label>
                                <div class="col-sm-10">
                                    <select name="refund_type" class="form-control" id="refund_type">
                                        <option value="0">=== Pilih Jenis Pengembalian ===</option>
                                        @if ($development)
                                        <option data-price="{{$development->nominal_default}}" data-id="{{$development->id}}" value="development">Pembinaan \ Uang Gedung</option>
                                        @endif
                                        <option data-price="0" value="uniform">Seragam</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                               <label for="cause" class="control-label col-sm-2">Alasan pengembalian</label>
                               <div class="col-sm-10">
                                    <input type="text" name="cause" id="cause" class="form-control" value="repayment" readonly>
                               </div>
                           </div>

                            <div id="refund_order">
                               <div class="form-group">
                                   <label for="product_order_id" class="control-label col-sm-2">No. Pembayaran</label>
                                   <div class="col-sm-10">
                                       <select name="product_order_id" id="product_order_id" class="form-control">
                                            <option value="0">=== Pilih Nomor Pembayaran ===</option>
                                           @foreach( $ppdbUser->ordersConfirmed as $key => $value )
                                           <option value="{{ $value->id }}">{{ $value->invoice_no }}</option>
                                           @endforeach
                                       </select>
                                   </div>
                               </div>
                            </div>

                            <div id="order_detail"></div>

                            <div class="form-group">
                                <label for="nominal_price" class="control-label col-sm-2">Nominal Pembayaran</label>
                                <div class="col-sm-10">
                                    <input type="hidden" name="user_id" id="user_id" value="{{ $ppdbUser->user_id }}" readonly>
                                    <input type="hidden" name="refund_id" id="refund_id" readonly>
                                    <input type="number" class="form-control" name="nominal_price" id="nominal_price" value="{{ old('nominal_price') }}" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="nominal_refund" class="control-label col-sm-2">Nominal Pengembalian</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="nominal_refund" id="nominal_refund" value="{{ old('nominal_refund') }}" min=0>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="refund_image" class="control-label col-sm-2">Bukti Pengembalian</label>
                                <div class="col-sm-10">
                                    <input accept="image/x-png,image/jpeg" type="file" class="form-control" name="refund_image">
                                    <div class="preview-image">
                                        <img class="responsive" />
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="note" class="control-label col-sm-2">Keterangan</label>
                                <div class="col-sm-10">
                                    <textarea name="note" id="note" class="form-control" cols="30" rows="10"></textarea>
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
    var refundOrder = $('#refund_order'),
        orderDetail = $('#order_detail'),
        optRefundType = $('select[name=refund_type]'),
        optRefundOrder = $('select[name=product_order_id]'),
        txtRefundId = $('#refund_id'),
        txtNominalPrice = $('#nominal_price'),
        urlOrderDetail = "{{ url('administrator/payment-refund/order-detail') }}";

    $(document).ready(function () {
        initFormValue();
    });

    optRefundType.on('change', function () {
        initFormValue();
        let refundType = $(this).val();
        let refundPrice = 0;
        let refundId = '';

        if (refundType == 0) return;

        if (refundType !== 'uniform') {
            refundId = $(this).find(':selected').data('id');
            refundPrice = $(this).find(':selected').data('price');
        } else {
            refundOrder.show();
        }
        txtRefundId.val(refundId);
        txtNominalPrice.val(refundPrice);
    });

    optRefundOrder.on('change', function () {
        orderDetail.html('');
        let productOrderId = $(this).val();

        if (productOrderId != 0) {
            fetch(`${urlOrderDetail}/${productOrderId}`)
            .then(res => res.json())
            .then(data => {
                if (data.data && data.html) {
                    txtRefundId.val(productOrderId);
                    txtNominalPrice.val(data.data.grand_total);
                    orderDetail.html(data.html);
                }

                if (data.error) {
                    alert(data.error);
                }
            })
            .catch(err => {
                console.log(err);
            });
        }
    });

    $("input[name=refund_image]").change(function() {
        readURL(this);
    });

    function initFormValue() {
        optRefundOrder[0].selectedIndex = 0;
        refundOrder.hide();
        orderDetail.html('');
        txtNominalPrice.val(0);
        txtRefundId.val('');
    }

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
