@extends('layouts.admin.main')
@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Kelebihan Bayar</h1>
        <ol class="breadcrumb">
            <li>SHOP</li>
            <li><a href="{{route('admin.uniform-overpayment.index')}}">Kelebihan Bayar</a></li>
            <li class="active">Detail</li>
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
                        <h3>Detail Kelebihan Bayar</h3>
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
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="control-label col-sm-2">Unit</label>
                                <div class="col-sm-10">
                                    <div class="form-control">{{$data->productOrder->user->ppdb->unit->name}}</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2">Nomor Registrasi</label>
                                <div class="col-sm-10">
                                    <div class="form-control">{{$data->productOrder->user->ppdb->register_number}}</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2">Nama Siswa</label>
                                <div class="col-sm-10">
                                    <div class="form-control">{{$data->productOrder->user->ppdb->name}}</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2">Tanggal Pembayaran</label>
                                <div class="col-sm-10">
                                    <div class="form-control">{{\App\Helpers\Helper::tanggal($data->payment_date)}}</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2">Metode Pembayaran</label>
                                <div class="col-sm-10">
                                    <div class="form-control">{{ucwords($data->payment_method)}}</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2">Virtual Account Number</label>
                                <div class="col-sm-10">
                                    <div class="form-control">{{@$data->payment_number}}</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2">Virtual Account Name</label>
                                <div class="col-sm-10">
                                    <div class="form-control">{{@$data->payment_name}}</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2">No. Pembayaran</label>
                                <div class="col-sm-10">
                                    <div class="form-control">{{@$data->productOrder->invoice_no}}</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2">Nominal harus dibayar</label>
                                <div class="col-sm-10">
                                    <div class="form-control">{{\App\Helpers\PriceHelper::rupiah(@$data->productOrder->grand_total)}}</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2">Nominal yang dibayar</label>
                                <div class="col-sm-10">
                                    <div class="form-control">{{\App\Helpers\PriceHelper::rupiah(@$data->payment_amount)}}</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2">Nominal Kelebihan</label>
                                <div class="col-sm-10">
                                    <div class="form-control">{{\App\Helpers\PriceHelper::rupiah(@$data->overpayment)}}</div>
                                </div>
                            </div>

                            <hr>
                            <h3>Data Pengembalian</h3>

                            <div class="form-group">
                                <label class="control-label col-sm-2">Jenis Pengembalian</label>
                                <div class="col-sm-10">
                                    <div class="form-control">{{'Seragam'}}</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2">Status Pengembalian</label>
                                <div class="col-sm-10">
                                    <div>{!! @$data->productOrder->overpayment->status_label !!}</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2">Alasan Pengembalian</label>
                                <div class="col-sm-10">
                                    <div>{!! @$data->productOrder->overpayment->cause !!}</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2">Nominal Pengembalian</label>
                                <div class="col-sm-10">
                                    <div class="form-control">{{\App\Helpers\PriceHelper::rupiah(@$data->productOrder->overpayment->nominal_refund)}}</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2">Bukti Pengembalian</label>
                                <div class="col-sm-10">
                                    <div class="preview-image">
                                        @if ( @$data->productOrder->overpayment->refund_image )
                                        <img src="{{ @$data->productOrder->overpayment->getRefundImageUrl() }}" alt="bukti_pengembalian">
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2">Keterangan</label>
                                <div class="col-sm-10">
                                    <div class="form-control">{!! @$data->productOrder->overpayment->note !!}</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-10 col-sm-offset-2">
                                    <a href="{{route('admin.uniform-overpayment.index')}}" class="btn btn-warning">Back</a>
                                    @if(@$data->productOrder->overpayment->refund_image && $data->productOrder->overpayment->status == 'new_refund')
                                    <a class="btn btn-default" onclick="confirmRefund({{$data->productOrder->overpayment->id}})">
                                        <i class="fa fa-dollar"></i> Konfirmasi Pengembalian
                                    </a>
                                    <form id="form-confirm-{{$data->productOrder->overpayment->id}}" action="{{ route('admin.payment-refund.confirm-refund',$data->productOrder->overpayment->id) }}" method="POST">
                                        @csrf
                                    </form>
                                    @endif
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
    .preview-image img {
        height: auto;
        width: 400px;
    }
</style>
@endpush
@push('scripts')
<script>
    function confirmRefund(id) {
        if(confirm('Apakah anda yakin ingin mengkonfirmasi data pengembalian pembayaran ini?'))
            document.getElementById('form-confirm-' + id).submit();
    }
</script>
@endpush
