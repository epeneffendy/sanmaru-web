@extends('layouts.admin.main')
@section('content')
@php($action=route('admin.ppdb-resignation.update',array($data->id)))
@php($status="Update")
@php($status_header="Edit")

<!-- Start Page Header -->
<div class="page-header">
    <h1 class="title">Data Pengunduran Diri Siswa</h1>
    <ol class="breadcrumb">
        <li>PPDB</li>
        <li><a href="{{route('admin.ppdb-resignation.index')}}">Data Pengunduran Diri Siswa</a></li>
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
                    <h3>
                        {{$status_header}} Data Pengunduran Diri Siswa
                    </h3>
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
                    <form role="form" method="POST" action="{{$action}}" class="form-horizontal" enctype="multipart/form-data">
                        <div role="tabpanel" id="data-ppdb-resignation">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs nav-justified tabcolor5-bg" role="tablist" id="navtabs">
                                <li role="presentation" class="active"><a href="#data-administrasi" aria-controls="data-administrasi" role="tab" data-toggle="tab" aria-expanded="true" class="" data-parent="#navtabs">Administrasi Siswa</a></li>
                                <li role="presentation" class=""><a href="#data-refund" aria-controls="data-refund" role="tab" data-toggle="tab" class="" aria-expanded="false" data-parent="#navtabs">Pengembalian Dana</a></li>
                            </ul>
                            <hr/>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="data-administrasi">
                                    @include('administrator.ppdb-resignation._data-administrasi', ['data' => $data->ppdbUser, 'mom' => $mom, 'dad' => $dad, 'wali' => $wali])
                                </div>
                                <div role="tabpanel" class="tab-pane" id="data-refund">
                                    <input type="hidden" name="id" value="{{$data->id}}" readonly>
                                    <div class="button-collection" style="margin: 15px 0">
                                        @if (\App\Helpers\Helper::isAdminRole())
                                            <a href="{{ route('admin.ppdb-resignation.add-refund', ['id' => $data->id]) }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah data</a>   
                                        @endif
                                    </div>
                                    <table id="datatables-pengembalian-dana" class="table display table-responsive table-striped">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Biaya</th>
                                        <th style="width: 15%">Nominal</th>
                                        <th style="width: 15%">Nominal Dikembalikan</th>
                                        <th>Keterangan</th>
                                        <th>Tgl Pengembalian</th>
                                        <th>Bukti</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @php($number=0)
                                        @foreach($data->ppdbUser->paymentRefunds as $key => $value)
                                            <tr>
                                                <td>{{++$number}}</td>
                                                <td>{{ $value->refund_name }}</td>
                                                <td><input type="text" class="form-control" name="nominal_price[{{$value->id}}]" value="{{ \App\Helpers\PriceHelper::rupiah($value->nominal_price, false) }}" disabled style="text-align: right"></td>
                                                <td><input type="number" class="form-control" name="nominal_refund[{{$value->id}}]" value="{{ intval($value->nominal_refund) }}" min=0 max={{intval($value->nominal_price)}} style="text-align: right"></td>
                                                <td><textarea class="form-control" name="note[{{$value->id}}]" rows="3">{{$value->note}}</textarea></td>
                                                <td>{{\App\Helpers\Helper::tanggal($value->updated_at)}}</td>
                                                <td>
                                                    <input accept="image/x-png,image/jpeg" type="file" class="form-control" name="refund_image[{{$value->id}}]" id="image_{{$value->id}}">
                                                    <div class="preview-image {{ @$value->refund_image !== null ? NULL : 'hide' }}" id="preview_image_{{$value->id}}">
                                                        <img class="responsive" src="{{ $value instanceof \App\Models\PaymentRefund ? $value->getRefundImageUrl() : NULL }}"/>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.ppdb-resignation.show-refund',['id' => $data->id, 'paymentRefundId' => $value['id']]) }}" title="Konfirmasi" class="btn btn-xs btn-info">
                                                        <icon class="icon-plus"><i class="fa fa-eye"></i></icon>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        
                                    </tbody>
                                </table>
                                </div>
                            </div>
                                    
                        </div>              
                        
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-default">{{$status}}</button>
                            </div>
                        </div>
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
        width: 150px;
    }
</style>
@endpush
@push('scripts')
<script>
    $(document).ready(function () {
        $("input[type=file]").change(function() {
            readURL(this);
        })
    });
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#preview_'+input.id+' img').attr('src', e.target.result).parent().removeClass('hide');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush