@extends('layouts.admin.main')
@section('content')

<!-- Start Page Header -->
<div class="page-header">
    <h1 class="title">Data Pengunduran Diri Siswa</h1>
    <ol class="breadcrumb">
        <li>PPDB</li>
        <li><a href="{{route('admin.ppdb-resignation.index')}}">Data Pengunduran Diri Siswa</a></li>
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
                    <h3>
                        Data Pengunduran Diri Siswa
                    </h3>
                </div> <!-- /widget-header -->
                <div class="widget-content">
                    <form class="form-horizontal">
                        <div role="tabpanel" id="data-ppdb-resignation">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs nav-justified tabcolor5-bg" role="tablist" id="navtabs">
                                <li role="presentation" class="active"><a href="#data-administrasi"
                                        aria-controls="data-administrasi" role="tab" data-toggle="tab"
                                        aria-expanded="true" class="" data-parent="#navtabs">Administrasi Siswa</a></li>
                                <li role="presentation" class=""><a href="#data-refund" aria-controls="data-refund"
                                        role="tab" data-toggle="tab" class="" aria-expanded="false"
                                        data-parent="#navtabs">Pengembalian Dana</a></li>
                            </ul>
                            <hr />
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="data-administrasi">
                                    @include('administrator.ppdb-resignation._data-administrasi', ['data' =>
                                    $data->ppdbUser, 'mom' => $mom, 'dad' => $dad, 'wali' => $wali])
                                </div>
                                <div role="tabpanel" class="tab-pane" id="data-refund">
                                    <table id="datatables-pengembalian-dana"
                                        class="table display table-responsive table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Biaya</th>
                                                <th style="width: 15%">Nominal</th>
                                                <th style="width: 15%">Nominal Dikembalikan</th>
                                                <th>Status</th>
                                                <th>Tgl Pengembalian</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php($number=0)
                                            @foreach($data->ppdbUser->paymentRefunds as $key => $value)
                                            <tr>
                                                <td>{{++$number}}</td>
                                                <td>{{ $value->refund_name }}</td>
                                                <td>{{ \App\Helpers\PriceHelper::rupiah($value->nominal_price, false) }}</td>
                                                <td>{{ intval($value->nominal_refund) }}</td>
                                                <td>{!! $value->status_label !!}</td>
                                                <td>{{\App\Helpers\Helper::tanggal($value->updated_at)}}</td>
                                                <td>
                                                    <a href="{{ route('admin.ppdb-resignation.show-refund',['id' => $data->id, 'paymentRefundId' => $value['id']]) }}"
                                                        title="Konfirmasi" class="btn btn-xs btn-info">
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