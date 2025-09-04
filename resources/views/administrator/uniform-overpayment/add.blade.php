@extends('layouts.admin.main')
@section('content')
    @if(@$status=="edit")
        @php($action=route('admin.uniform-overpayment.update', $data->id))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.uniform-overpayment.insert'))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Kelebihan Bayar</h1>
        <ol class="breadcrumb">
            <li>SHOP</li>
            <li><a href="{{route('admin.uniform-overpayment.index')}}">Kelebihan Bayar</a></li>
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
                        <h3>{{$status_header}} Kelebihan Bayar</h3>
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
                                    @if($data->id)
                                    <input type="hidden" name="unit_id" value="{{$data->productOrder->user->ppdb->unit_id}}" readonly>
                                    <input type="text" name="unit_name" class="form-control" value="{{@$data->productOrder->user->ppdb->unit->name}}" readonly>
                                    @else
                                    <select name="unit_id" class="form-control">
                                        @foreach($units as $unit)
                                            <option value="{{$unit->id}}" {{ old('unit_id') == $unit->id ? 'selected' : NULL }}>{{$unit->name}}</option>
                                        @endforeach
                                    </select>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="register_number">Nomor Registrasi:</label>
                                <div class="col-sm-10">
                                    @if($data->id)
                                    <input type="text" class="form-control" name="register_number" id="register_number" value="{{ old('register_number', @$data->productOrder->user->ppdb->register_number) }}" readonly>
                                    @else
                                    <input type="text" name="register_number" id="register_number" class="form-control" value="{{old('register_number')}}">
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Nama:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name" value="{{ old('name', @$data->productOrder->user->ppdb->name) }}"  readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="payment_date" class="control-label col-sm-2">Tanggal Pembayaran</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" name="payment_date" id="payment_date" value="{{ old('payment_date', @$data->payment_date) }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="payment_option" class="control-label col-sm-2">Metode Pembayaran</label>
                                <div class="col-sm-10">
                                    @if($data->id)
                                    <input type="hidden" name="payment_method" id="payment_method" value="{{$data->payment_method}}" readonly>
                                    <input type="text" class="form-control" name="payment_method_name" value="{{strtoupper($data->payment_method)}}" readonly>
                                    @else
                                    <select class="form-control" name="payment_method" id="payment_method">
                                        <option>=== Pilih metode pembayaran ===</option>
                                        <option value="cimb">CIMB</option>
                                        <option value="mandiri">MANDIRI</option>
                                    </select>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="payment_number" class="control-label col-sm-2">Virtual Account Number</label>
                                <div class="col-sm-10">
                                @if($data->id)
                                    <input type="text" class="form-control" name="payment_number" id="payment_number" value="{{ @$data->payment_number}}" readonly>
                                @else
                                    <input type="text" class="form-control" name="payment_number" id="payment_number" value="{{old('payment_number')}}">
                                @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="payment_number" class="control-label col-sm-2">Virtual Account Name</label>
                                <div class="col-sm-10">
                                @if($data->id)
                                    <input type="text" class="form-control" name="payment_name" id="payment_name" value="{{@$data->payment_name}}" readonly>
                                @else 
                                    <input type="text" class="form-control" name="payment_name" id="payment_name" value="{{old('payment_name')}}">
                                @endif
                                </div>
                            </div>

                           <div class="form-group">
                               <label for="product_order_id" class="control-label col-sm-2">No. Pembayaran</label>
                               <div class="col-sm-10">
                                @if($data->id)
                                    <input type="text" name="invoice_no" class="form-control" value="{{$data->productOrder->invoice_no}}" readonly>
                                @else
                                  <select name="invoice_no" id="invoice_no" class="form-control">
                                      <option>=== Pilih nomor pembayaran ===</option>
                                  </select>
                                @endif
                               </div>
                           </div>

                           <div class="form-group">
                               <label for="grand_total" class="control-label col-sm-2">Nominal harus bayar</label>
                               <div class="col-sm-10">
                                    <input type="number" name="grand_total" class="form-control" id="grand_total" value="{{ ($data->id) ? old('grand_total', $data->productOrder->grand_total) : old('grand_total') }}" readonly>
                                </div>
                           </div>

                           <div class="form-group">
                                <label for="payment_amount" class="control-label col-sm-2">Nominal yang dibayar</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="payment_amount" id="payment_amount" value="{{ old('payment_amount', @$data->payment_amount) }}">
                                </div>
                            </div>

                           <div class="form-group">
                               <label for="overpayment" class="control-label col-sm-2">Nominal Kelebihan</label>
                               <div class="col-sm-10">
                                   <input type="number" name="overpayment" id="overpayment" class="form-control" value="{{old('overpayment', @$data->overpayment)}}" readonly>
                               </div>
                           </div>
                           <hr>
                           <h3>Data Pengembalian</h3>

                           <div class="form-group">
                               <label for="cause" class="control-label col-sm-2">Jenis pengembalian</label>
                               <div class="col-sm-10">
                                    <input type="hidden" name="refund_type" id="refund_type" value="uniform" readonly>
                                    <input type="text" class="form-control" name="refund_type_text" value="Seragam" readonly>
                               </div>
                           </div>

                           <div class="form-group">
                               <label for="status" class="control-label col-sm-2">Status pengembalian</label>
                               <div class="col-sm-10">
                                    @if($data->id && $data->productOrder->overpayment)
                                   <input type="hidden" name="status" id="status" class="form-control" value="{{$data->productOrder->overpayment->status}}" readonly>
                                   {!! $data->productOrder->overpayment->status_label !!}
                                   @else 
                                   <input type="hidden" name="status" id="status" class="form-control" value="{{'new_refund'}}" readonly>
                                   <label class="label label-warning">Pengembalian Baru</label>
                                   @endif
                               </div>
                           </div>

                           <div class="form-group">
                               <label for="cause" class="control-label col-sm-2">Alasan pengembalian</label>
                               <div class="col-sm-10">
                                    <input type="text" name="cause" id="cause" class="form-control" value="overpayment" readonly>
                               </div>
                           </div>

                           <div class="form-group">
                               <label for="nominal_refund" class="control-label col-sm-2">Nominal Pengembalian</label>
                               <div class="col-sm-10">
                                @if ($data->id && $data->productOrder->overpayment)
                                @php($nominal_refund = $data->productOrder->overpayment->nominal_refund)
                                @else 
                                @php($nominal_refund = $data->overpayment)
                                @endif
                                   <input type="number" name="nominal_refund" id="nominal_refund" class="form-control" value="{{old('nominal_refund', $nominal_refund) }}" readonly>
                               </div>
                           </div>

                           <div class="form-group">
                               <label class="control-label col-sm-2">Bukti pengembalian</label>
                               <div class="col-sm-10">
                                   <input accept="image/x-png,image/jpeg" type="file" class="form-control" name="refund_image">
                                    <div class="preview-image">
                                        @if($data->id && $data->productOrder->overpayment) 
                                            <img class="responsive" src="{{$data->productOrder->overpayment->getRefundImageUrl()}}" />
                                        @else
                                        <img class="responsive" />
                                        @endif
                                    </div>
                               </div>
                           </div>

                           <div class="form-group">
                               <label for="note" class="control-label col-sm-2">Keterangan</label>
                               <div class="col-sm-10">
                                   <textarea name="note" class="form-control" id="note" cols="30" rows="7">@if($data->id && $data->productOrder->overpayment) {{$data->productOrder->overpayment->note}} @endif</textarea>
                               </div>
                           </div>

                           <div class="form-group">
                               <div class="col-sm-10 col-sm-offset-2">
                                   <button type="submit" class="btn btn-default">{{$status}}</button>
                               </div>
                           </div>

                            @if($status=="Update")
                            @method('PUT')
                            @endif
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
    @if(!$data->id)
    var optUnit = $('select[name=unit_id]'),
        optProductOrder = $('select[name=invoice_no]'),
        txtRegisterNumber = $('#register_number'),
        txtName = $('#name'),
        numGrandTotal = $('#grand_total'),
        numOverpayment = $('#overpayment'),
        numPaymentAmount = $('#payment_amount'),
        numNominalRefund = $('#nominal_refund'),
        txtUserId = $('#user_id'),
        txtPpdbUserId = $('#ppdb_user_id'),
        urlStudentData = "{{url('administrator/uniform-overpayment/student-data')}}";

    optUnit.on('change', function () {
        initStudent();
    });

    $(document).ready(function () {
        generateStudent();
    });

    txtRegisterNumber.on('blur', function () {
        generateStudent();
    });

    optProductOrder.on('change', function () {
        calculateOverpayment();
    });

    numPaymentAmount.on('blur', function () {
        calculateOverpayment();
    });


    function generateStudent() {
        let unitId = optUnit.val();
        let registerNumber = txtRegisterNumber.val();
        if (unitId && registerNumber) {
            getStudentData(unitId, registerNumber)
            .then(data => {
                txtName.val(data.name);
                txtPpdbUserId.val(data.ppdb_user_id);
                txtUserId.val(data.user_id);
                let productOrderHtml = '<option>=== Pilih nomor pembayaran ===</option>';
                if (data.product_order.length) {
                    data.product_order.forEach(function(row, key){
                        productOrderHtml += '<option value='+row.invoice_no+' data-grand_total='+row.grand_total+'>'+row.invoice_no+'</option>'
                    });
                }
                optProductOrder.html(productOrderHtml);

            })
            .catch(error => {
                alert('data tidak ditemukan');
                initStudent();
            });
        }
    }

    async function getStudentData(unitId, registerNumber) {
        const response = await fetch(`${urlStudentData}/?unit=${unitId}&register_number=${registerNumber}`);

        if (!response.ok) {
            const message = `An error has occured: ${response.status}`;
            throw new Error(message);
        }

        const data = await response.json();
        return data;
    }

    function initStudent() {
        txtRegisterNumber.val(null);
        txtName.val(null);
        txtUserId.val(null);
        txtPpdbUserId.val(null);
        optProductOrder[0].selectedIndex = 0;
    }

    function intval(i) {
        return typeof i === 'string' ?
            i.replace(/[\$,]/g, '')*1 :
            typeof i === 'number' ?
                i : 0;
    }


    function calculateOverpayment() {
        let selectedProductOrder = optProductOrder.find(":selected");
        let grandTotal = intval(selectedProductOrder.data('grand_total'));
        let paymentAmount = intval(numPaymentAmount.val());
        let overpayment = paymentAmount-grandTotal;
        numGrandTotal.val(0);
        numOverpayment.val(0);
        numNominalRefund.val(0);
        numGrandTotal.val(grandTotal);
        numOverpayment.val(overpayment);
        numNominalRefund.val(overpayment);
    }
    @endif


    $("input[name=refund_image]").change(function() {
        readURL(this);
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
</script>
@endpush
