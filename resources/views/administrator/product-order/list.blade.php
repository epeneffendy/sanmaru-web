@extends('layouts.admin.main')

@inject('priceHelper', 'App\Helpers\PriceHelper')
@inject('productOrderModel', 'App\Models\ProductOrder')
@inject('voucherModel', 'App\Models\Voucher')

@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data Master Pembelian Produk</h1>
        <ol class="breadcrumb">
            <li>Shop</li>
            <li class="active">Pembelian Produk</li>
        </ol>
    </div>
    <!-- End Page Header -->

    <!-- START CONTAINER -->
    <div class="container-padding">
        <!-- Start Row -->
        <div class="row">
            <!-- Start Panel -->
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-title">
                        Data Master Pembelian Produk
                    </div>
                    <div class="panel-body table-responsive">
                        @if (session('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif
                        @if ($exportMessage = (new \App\Lib\ExportJob())->message(request()->all(), auth()->user()))
                            <div class="alert alert-success">
                                {!! $exportMessage !!}
                            </div>
                        @endif
                        @if (session('errors'))
                            <div class="alert alert-danger">
                                {!! session('errors')->first() !!}
                            </div>
                        @endif

                        <div role="tabpanel">
                            <ul class="nav nav-tabs nav-justified tabcolor5-bg" role="tablist">
                                @foreach ($collections as $tab => $data)
                                    <li role="presentation" class="{{ $activeTab == $tab ? 'active' : null }}">
                                        <a id="#{{ $tab }}Tab" href="#{{ $tab }}" aria-controls="{{$tab}}" data-toggle="tab" aria-expanded="false">{{ strtoupper($tab) }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="tab-content">
                            @foreach ($collections as $tab => $data)
                                <div role="tabpanel" class="tab-pane {{ $activeTab == $tab ? 'active in' : null }}" id="{{ $tab }}">
                                    @include('administrator.product-order.table.list_' . $tab, $data)
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
            <!-- End Panel -->
        </div>
        <!-- End Row -->
    </div>
    <!-- END CONTAINER -->

    <!-- Modal -->
    <div id="modal-upload-order-confirmation" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Upload Bukti Pembayaran</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{asset('css/plugin/datatables/datatables.css')}}">
    <style>
        .button-collection {
            margin-bottom: 5px;
        }

        .d-block {
            display: block;
        }

        .btn-circle {
            width: 30px;
            height: 30px;
            text-align: center;
            padding: 6px 0;
            font-size: 12px;
            line-height: 1.42;
            border-radius: 15px;
        }z

        .btn-circle .fa {
            margin: 0 auto;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{asset('js/moment/moment.min.js')}}"></script>
    <script src="{{asset('js/date-range-picker/daterangepicker.js')}}"></script>

    <script>
        $(document).ready(function () {
            var url = document.location.toString();
            var activeTab = `{{ $collections->keys()->first() ?? NULL }}`;

            $(document).ready(function () {
                if (url.match('#')) {
                    activeTab = url.split('#')[1];
                }

                // if (activeTab) {
                //     $('a[href="#'+activeTab+'"]').parent().addClass('active');
                //     $('#'+activeTab).addClass('active in')
                // }

                $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                    window.location.hash = e.target.hash;
                });
            })


            $('.upload-order-confirmation').click(function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var html = `
                    <form role="form" method="POST" id="upload-order-confirmation-form" class="form-horizontal" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="`+id+`" />
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="invoice">No Invoice:</label>
                            <div class="col-sm-8">
                                `+ $(this).data('no-tagihan') +`
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="name">Nama:</label>
                            <div class="col-sm-8">
                                `+ $(this).data('name') +`
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="email">Email:</label>
                            <div class="col-sm-8">
                                `+ $(this).data('email') +`
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="unit">Unit:</label>
                            <div class="col-sm-8">
                                `+ $(this).data('unit') +`
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="unit">Jml Pesanan:</label>
                            <div class="col-sm-8">
                                `+ $(this).data('jumlah-pesanan') +`
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="grand_total">Total:</label>
                            <div class="col-sm-8">
                                `+ $(this).data('total') +`
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="upload">Upload Image:</label>
                            <div class="col-sm-8">
                                <input type="file" name="payment_image" accept="image/x-png,image/jpeg" /><br/>
                                <img src="#" id="preview-img" style="display: none; width: 50%; height: auto;" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-4">
                                <button id="btn-upload" class="btn btn-sm btn-success" style="display: none;">upload</button>
                            </div>
                        </div>
                    </form>
                `;
                $('#modal-upload-order-confirmation .modal-body').html(html);
                $('#modal-upload-order-confirmation').modal();
                $("input[name=payment_image]").change(function(e) {
                    var img = Object.values($(this))[0].files[0];
                    if (img) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $('#preview-img').attr('src', e.target.result).show();
                        }
                        reader.readAsDataURL(img);
                        $('#btn-upload').show();
                    }
                });
                $("#btn-upload").click(function(e) {
                    e.preventDefault();
                    if ($('input[name=payment_image]').val()) {
                        var formData = new FormData($('#upload-order-confirmation-form')[0]);
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                            type: "POST",
                            url: "{{route('admin.product-order.upload-payment', ['id' => null])}}/"+ id,
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            beforeSend: function () {
                                $('#btn-upload').text("Uploading...").prop('disabled', true);
                            },
                            error: function (data) {
                                $('#btn-upload').text("upload").prop('disabled', false);
                            },
                            success: function (data) {
                                $('#btn-upload').text("upload").prop('disabled', false);
                                window.location.reload();
                            }
                        });
                        return false;
                    }
                });
            });

            $('.btn-upload-modal').click(function(e) {
                e.preventDefault();
                $('#import-modal').modal();
            });

            $('.date-range-field').daterangepicker();
        });
    </script>
@endpush

