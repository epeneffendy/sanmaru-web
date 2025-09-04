@extends('layouts.admin.main')
@section('content')
    @if(@$method=="edit")
        @php($action=route('admin.product-order.update', $productOrder['id']))
        @php($status="Perbarui")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.product-order.insert'))
        @php($status="Simpan")
        @php($status_header="Tambah")
    @endif
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
                        <h3>{{$status_header}} Pembelian Produk</h3>
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
                        <form role="form" method="POST" action="{{$action}}" class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" value="{{@$productOrder->id}}" name="id" />
                            <input type="hidden" value="{{@$type}}" id="type_tab" name="type_tab" />
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="user_id">Unit:</label>
                                <div class="col-sm-10">
                                    <select name="unit" id="unit" class="form-control">
                                        <option value="0">=== SEMUA ===</option>
                                        @foreach (@$units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="user_id">Murid:</label>
                                <div class="col-sm-10">
                                    <select class="form-control{{ $errors->has('user_id') ? ' is-invalid' : '' }} selectpicker"
                                            name="user_id" id="user_id" required {{(@$method=="edit")?"readonly":""}}>
                                            <option value="0">=== PILIH SISWA===</option>
                                            @foreach($studentList as $key => $value)
                                            <option value="{{ $value['user_id'] }}" data-type="{{ $value['type'] }}" {{ $value['user_id'] === @$productOrder->user_id ? 'selected' : NULL }}">{{ $value['name'] }}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                            @if(@$method == 'edit')
                                @method('PATCH')
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="user_id">No Pembayaran:</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" value="{{ $productOrder->invoice_no }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="user_id">Status:</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="status" id="status" required>
                                            @foreach($orderStatus as $value)
                                                <option value="{{ $value['value'] }}" {{ $value['value'] === @$productOrder->status ? 'selected' : NULL }}>{{ $value['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div id="pickup-status-block" style="{{ $productOrder->status === 'pickup' ? 'display:block;' : 'display:none;' }}">
                                    <div class="form-group">
                                        <label for="pickup_status" class="control-label col-sm-2">Pickup Status:</label>
                                        <div class="col-sm-10">
                                            <select name="pickup_status" id="pickup_status" class="form-control">
                                                @foreach($pickupStatus as $value)
                                                    <option value="{{ $value['value'] }}" {{ $value['value'] === @$productOrder->pickup_status ? 'selected' : NULL }}>{{ $value['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="user_id">Waktu Order:</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" value="{{ $productOrder->created_at->format('d-m-Y H:i:s') }}" readonly>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <div class="col-sm-10 col-sm-offset-2">
                                    <fieldset style="padding: 20px; margin 0 2px; border-radius: 3px; border: 1px solid #ccc; padding-top: 10px">
                                        <legend style="border-bottom: 0px; width: auto; padding: 0px 10px; font-size: 16px; font-weight: 600">Product Details</legend>
                                        <div class="form-group" style="margin-left: 10px">
                                            <div class="form-group col-md-12">
                                                <label class="form-label">Produk</label>
                                                <select data-live-search="true" class="form-control{{ $errors->has('product_id') ? ' is-invalid' : '' }}" name="product_id" id="product_id">
                                                    <option value="" disabled selected>-- Pilih Produk --</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12" style="margin-left: 1px; margin-right: 5px">
                                            <table class="table table-sm table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Ukuran</th>
                                                        <th>Stok</th>
                                                        <th>Harga Siswa</th>
                                                        <th>Harga PPDB</th>
                                                        <th>Kuantitas</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="product-details"></tbody>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="4">Produk Dipilih:</td>
                                                    </tr>
                                                </tbody>
                                                <tbody id="selected-product-details" style="border-top: 10px solid #000">
                                                    @if (@$productOrder->productOrderDetails)
                                                        @foreach ($productOrder->productOrderDetails as $detail)
                                                            @include('administrator.product-order._selected_product_order_details', [
                                                                'product_order_detail_id' => $detail->id,
                                                                'product_detail_id' => $detail->product_detail_id,
                                                                'product_id' => $detail->product_id,
                                                                'size' => $detail->productDetail->size,
                                                                'stock' => $detail->productDetail->stock,
                                                                'price_siswa' => $detail->productDetail->price_siswa,
                                                                'price_ppdb' => $detail->productDetail->price_ppdb,
                                                                'qty' => $detail->quantity
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
                                <label class="control-label col-sm-2" for="voucher_code">Voucher:</label>
                                <div class="col-sm-10">
                                    <select class="form-control{{ $errors->has('voucher_code') ? ' is-invalid' : '' }} selectpicker"
                                            name="voucher_code" id="voucher_code" required {{(@$method=="edit")?"readonly":""}}>
                                        <option value="0">=== Pilih Voucher ===</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="grand_total_gross">Total Harga:</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="grand_total_gross" name="grand_total_gross" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="grand_total">Total yg harus dibayarkan:</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="grand_total" name="grand_total" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-default">{{$status}}</button>
                                </div>
                            </div>
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
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugin/bootstrap-select/bootstrap-select.css') }}" />
    <style>
        .fieldset {
            padding: 20px;
            margin: 0 2px;
            border-radius: 3px;
            border: 1px solid #ccc;
            padding-top: 10px
        }

        .legend {
            width: auto;
            padding: 0px 10px;
            margin-bottom: 20px;
            font-size: 16px;
            line-height: inherit;
            color: #333;
            border: 0;
            font-weight: 600;
            border-bottom: none
        }
    </style>
@endpush

@push('scripts')
    <script src="{{asset('js/bootstrap-select/bootstrap-select.js')}}"></script>
    <script>
        var urlProductDetail = "{{ url('administrator/product-order/product-detail') }}",
            urlUnitStudent = "{{ url('administrator/product-order/unit-student') }}",
            urlStudentData = "{{ url('administrator/product-order/student-data') }}",
            optProduct = $('#product_id'),
            productDetails = $('#product-details'),
            selectedProductDetails = $('#selected-product-details'),
            optStatus = $('#status'),
            pickupStatus = $('#pickup-status-block'),
            optUnit = $('#unit'),
            optStudent = $('#user_id'),
            optVoucher = $('#voucher_code'),
            grandTotalGross = $('#grand_total_gross'),
            grandTotal = $('#grand_total');

        optProduct.on('change', function() {
            fetch(`${urlProductDetail}/${this.value}`)
                .then(response => response.json())
                .then(data => {
                    let productDetailRow = '';

                    productDetails.html('');

                    data.forEach(function(row, key) {
                        let productDetailsTemplate = `@include("administrator.product-order._product_order_details")`;

                        productDetailsTemplate = $(productDetailsTemplate).find('td:nth-child(1) label.size').html(row.size).end()[0].outerHTML;
                        productDetailsTemplate = $(productDetailsTemplate).find('td:nth-child(1) input[name^="product_id"]').val(row.product_id).end()[0].outerHTML;
                        productDetailsTemplate = $(productDetailsTemplate).find('td:nth-child(1) input[name^="product_detail_id"]').val(row.id).end()[0].outerHTML;
                        productDetailsTemplate = $(productDetailsTemplate).find('td:nth-child(1) input[name^="size"]').val(row.size).end()[0].outerHTML;

                        productDetailsTemplate = $(productDetailsTemplate).find('td:nth-child(2) label.stock').html(row.stock).end()[0].outerHTML;
                        productDetailsTemplate = $(productDetailsTemplate).find('td:nth-child(2) input[name^="stock"]').val(row.stock).end()[0].outerHTML;

                        productDetailsTemplate = $(productDetailsTemplate).find('td:nth-child(3) label.price_siswa').html(row.price_siswa).end()[0].outerHTML;
                        productDetailsTemplate = $(productDetailsTemplate).find('td:nth-child(3) input[name^="price_siswa"]').val(row.price_siswa).end()[0].outerHTML;

                        productDetailsTemplate = $(productDetailsTemplate).find('td:nth-child(4) label.price_ppdb').html(row.price_ppdb).end()[0].outerHTML;
                        productDetailsTemplate = $(productDetailsTemplate).find('td:nth-child(4) input[name^="price_ppdb"]').val(row.price_ppdb).end()[0].outerHTML;

                        if (row.stock == 0) {
                            productDetailsTemplate = $(productDetailsTemplate).find('td:nth-child(6) button.btn-add').attr("disabled", "disabled").end()[0].outerHTML;
                        }

                        productDetailRow += productDetailsTemplate;
                    });
                    productDetails.append(productDetailRow);
                });
        });

        optStatus.on('change', function() {
            var selectedStatus = $(this).val();
            pickupStatus.hide();
            if (selectedStatus === 'pickup' || selectedStatus === 'done') {
                pickupStatus.show();
                console.log(selectedStatus);
            }
        });

        $(document).on('click', '.btn-add', function() {
            var template = `@include("administrator.product-order._selected_product_order_details")`,
                _parent = $(this).parent().parent('.product-detail-row'),
                qty = _parent.find('input[name^="qty"]').val();

            let price_siswa = 0,
                price_ppdb = 0,
                total_price = 0,
                user_type = optStudent.find(':selected').data('type');

            $(_parent).find('input').each(function(index, element) {
                let value = $(element).val(),
                    name = $(element).attr('name');

                if(name == 'qty')
                    value = qty;

                if(name == 'price_siswa')
                    price_siswa = $(element).val();

                if(name == 'price_ppdb')
                    price_ppdb = $(element).val();

                template = $(template).find(`input[name^="${name}"]`).val(value).end()[0].outerHTML;
            });

            $(_parent).find('label').each(function(index, element) {
                let value = $(element).html(),
                    classSelector = "." + $(element).attr('class').split(' ').join('.');

                template = $(template).find(classSelector).html(value).end()[0].outerHTML;
            });

            if (user_type=='siswa')
                total_price = intval(price_siswa)*intval(qty);
            if (user_type=='ppdb')
                total_price = intval(price_ppdb)*intval(qty);

            template = $(template).find('td:nth-child(4) label.qty').html(qty).end()[0].outerHTML;
            template = $(template).find('td:nth-child(5) input[name^="total_price"]').val(total_price).end()[0].outerHTML;
            template = $(template).find('td:nth-child(5) label.total_price').html(total_price).end()[0].outerHTML;
            selectedProductDetails.append(template);
            addTotal(total_price);
        });

        $(document).on('click', '.btn-delete-selected-product', function() {
            let _parent = $(this).parent().parent('.product-detail-row'),
                total_price = _parent.find('input[name^="total_price"]').val();

            $(this).parent().parent('.product-detail-row').remove();

            subTotal(total_price);
        });

        $(document).on('click', '.btn-edit-selected-product', function() {
            let parent = $(this).parent().parent();
            parent.find('td:nth-child(4) input[name^="qty"]').attr('type', 'number');
            parent.find('td:nth-child(4) label').hide();
            parent.find('td:nth-child(5) label').hide();
            parent.find('button:not(.btn-update-selected-product)').hide();
            parent.find('button.btn-update-selected-product').show();
        });

        $(document).on('click', '.btn-update-selected-product', function() {
            let parent = $(this).parent().parent(),
                qty = parent.find('td:nth-child(4) input[name^="qty"]').val(),
                price_siswa = parent.find('td:nth-child(2) input[name^="price_siswa"]').val(),
                price_ppdb = parent.find('td:nth-child(3) input[name^="price_ppdb"]').val(),
                total_price = parent.find('td:nth-child(5) input[name^="total_price"]').val(),
                user_type = optStudent.find(':selected').data('type');

            subTotal(total_price);
            if (user_type=='siswa')
                total_price = intval(qty) * intval(price_siswa);
            if (user_type=='ppdb')
                total_price = intval(qty) * intval(price_ppdb);
            
            parent.find('td:nth-child(5) input[name^="total_price"]').val(total_price);
            parent.find('td:nth-child(5) label').html(total_price);
            parent.find('td:nth-child(5) label').show();
            parent.find('td:nth-child(4) label').html(qty);
            parent.find('td:nth-child(4) label').show();
            parent.find('.form-control').attr('type', 'hidden');
            parent.find('button:not(.btn-update-selected-product)').show();
            parent.find('button.btn-update-selected-product').hide();
            addTotal(total_price);
        });

        $(document).ready(function () {
            optStudent.selectpicker({
                liveSearch: true,
                dropupAuto: false,
                title: "No Value"
            });
            optVoucher.selectpicker({
                liveSearch: false,
                dropupAuto: false,
                title: "No Value"
            });
            
        });

        optUnit.on('change', function () {
            optStudent.html('<option>=== Pilih siswa ===</option>');
            optVoucher.html('<option>=== Pilih voucher ===</option>');
            optProduct.html('<option>=== Pilih produk ===</option>');
            optStudent.selectpicker('refresh');
            optVoucher.selectpicker('refresh');
            optProduct.selectpicker('refresh');
            productDetails.html('');
            initTotal();
            getUnitStudent(`${this.value}`)
            .then(data => {
                let optStudentHtml = '';
                data.forEach(function(row, key) {
                    optStudentHtml += '<option value="'+row.user_id+'" data-type="'+row.type+'">'+row.name+'</option>';
                });
                optStudent.append(optStudentHtml);
                optStudent.selectpicker('refresh');
            })
            .catch(error => {
                console.log(error.message);
            });
        });

        async function getUnitStudent(unitId) {
            const response = await fetch(`${urlUnitStudent}/${unitId}`);

            if (!response.ok) {
                const message = `An error has occured: ${response.status}`;
                throw new Error(message);
            }

            const data = await response.json();
            return data;
        }

        optStudent.on('change', function () {
            optVoucher.html('<option>=== Pilih voucher ===</option>');
            optProduct.html('<option>=== Pilih produk ===</option>');
            optVoucher.selectpicker('refresh');
            optProduct.selectpicker('refresh');
            productDetails.html('');
            initTotal();
            getStudentData(`${this.value}`,$('#type_tab').val())
            .then(data => {
                let optProductHtml = '';
                let optVoucherHtml = '';
                data.products.forEach(function(row, key) {
                    optProductHtml += '<option value="'+row.id+'">'+row.name+'</option>';
                });
                data.vouchers.forEach(function(row, key) {
                    optVoucherHtml += '<option value="'+row.code+'"';
                    optVoucherHtml += 'data-type="'+row.type+'"';
                    optVoucherHtml += 'data-product="'+row.product+'"';
                    optVoucherHtml += 'data-discount_percent="'+row.discount_percent+'"';
                    optVoucherHtml += 'data-discount_fixed="'+row.discount_fixed+'"';
                    optVoucherHtml += 'data-usage_limit="'+row.usage_limit+'"';
                    optVoucherHtml += '>'+row.code+' - '+row.note+'</option>';
                });

                optVoucher.append(optVoucherHtml);
                optProduct.append(optProductHtml);
                optProduct.selectpicker('refresh');
                optVoucher.selectpicker('refresh');
            })
            .catch(error => {
                console.log(error.message);
            });
        });

        optVoucher.on('change', function () {
            addTotal(0);
        });

        async function getStudentData(userId, type) {
            const response = await fetch(`${urlStudentData}/${userId}/${type}`);

            if (!response.ok) {
                const message = `An error has occured: ${response.status}`;
                throw new Error(message);
            }

            const data = await response.json();
            return data;
        }

        function initVoucherOption() {
            let vouchers = $('select[name=voucher_code]')[0];
            
            for (var i=0; i < vouchers.length; i++) {
                let selected = vouchers[i].dataset.product; 
                if (selected == "" || selected === undefined)  {
                    vouchers[i].style.display = 'list-item';
                } else {
                    let arrProduct = new Array();
                    if (typeof(selected) === 'number') {
                        arrProduct.push(selected);
                    } else {
                        arrProduct = selected.split(",");
                    }
                    $('#selected-product-details tr.product-detail-row').each(function(key, row) {
                        let pid = $(this).find('td:nth-child(1) input[name^="product_id"]').val();
                        if (arrProduct.includes(pid) || arrProduct.includes('all')) {
                            vouchers[i].style.display = 'list-item';
                        } else {
                            vouchers[i].style.display = 'none';
                        }
                    });
                }
            }
            optVoucher.selectpicker('refresh');
        }

        function initTotal() {
            grandTotalGross.val(0);
            grandTotal.val(0);
        }

        function addTotal(i) {
            let gross = intval(grandTotalGross.val()),
                total = 0,
                discount = 0;

            gross += intval(i);
            discount = getDiscount(gross);
            total = Math.max(0, (gross - discount));
            grandTotalGross.val(gross);
            grandTotal.val(total);
        }

        function subTotal(i) {
            let gross = intval(grandTotalGross.val()),
                total = 0,
                discount = 0;

            gross -= intval(i);
            discount = getDiscount(gross);
            total = Math.max(0, (gross - discount));
            grandTotalGross.val(gross);
            grandTotal.val(total);
        }

        function getDiscount(gross) {
            let selected = optVoucher.find(':selected');
                disc_percent = selected.data('discount_percent'),
                disc_fixed = selected.data('discount_fixed'),
                voucher_type = selected.data('type'),
                usage_limit = selected.data('usage_limit'),
                product = selected.data('product'),
                arrProduct = new Array(),
                discount = 0,
                unlimited = false;

            usage_limit = intval(usage_limit);
            if (usage_limit < 0) unlimited = true;
            usage_limit = Math.max(0, usage_limit);

            if (product !== undefined) {
                if (typeof(product) === 'number') {
                    arrProduct.push(product.toString());
                } else {
                    arrProduct = product.split(",");
                }
            }

            discount = Math.round(intval(disc_percent)/100 * gross, 2) + intval(disc_fixed);

            $('#selected-product-details tr.product-detail-row').each(function(key, row) {
                let pid = $(this).find('td:nth-child(1) input[name^="product_id"]').val(),
                    price_siswa = $(this).find('td:nth-child(2) input[name^="price_siswa"]').val(),
                    price_ppdb = $(this).find('td:nth-child(3) input[name^="price_ppdb"]').val(),
                    qty = $(this).find('td:nth-child(4) input[name^="qty"]').val(),
                    user_type = optStudent.find(':selected').data('type');
                
                selected_price=0;
                qty = intval(qty);

                if (user_type=='siswa')
                    selected_price=intval(price_siswa);

                if (user_type=='ppdb')
                    selected_price=intval(price_ppdb);

                if (arrProduct.includes(pid) && (voucher_type === 'free_product')) {
                    usage_limit = unlimited ? qty : usage_limit;
                    discount += selected_price * (Math.min(qty, usage_limit));
                    usage_limit = Math.max(0, usage_limit - qty);
                }
            });

            return discount;
        }

        function intval(i) {
            return typeof i === 'string' ?
                i.replace(/[\$,]/g, '')*1 :
                typeof i === 'number' ?
                    i : 0;
        }


    </script>
@endpush

