@extends('layouts.admin.main')
@section('content')
    @php($status="Show")
    @php($status_header="Show")

    <div class="page-header">
        <h1 class="title">Detail Pengajuan Dispensasi</h1>
        <ol class="breadcrumb">
            <li>Keuangan</li>
            <li><a href="{{route('admin.dispensation-request.index')}}">Pengajuan Dispensasi</a></li>
            <li class="active">{{$status_header}}</li>
        </ol>
    </div>

    <div class="container-padding">
        <div class="row">
            <div class="col-md-12">
                <div class="widget ">

                    <div class="widget-content">
                        <div class="form-horizontal">

                            <div class="card">
                                <div class="card-header">
                                    <h4>Detail Siswa</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-group" style="padding: 1em">
                                        <table width="100%">
                                            <tr>
                                                <td>Nama</td>
                                                <td><strong> : {{ $data->ppdb->name }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Register Number</td>
                                                <td><strong> : {{ $data->ppdb->register_number }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Unit</td>
                                                <td><strong> : {{ $data->ppdb->unit->name }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Periode</td>
                                                <td><strong> : {{ $data->ppdb->period->name }}</strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h4>Detail Pengajuan Dispensasi</h4>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title text-primary">#Status :
                                        @if($data->status == \App\Models\PaymentDispensationRequest::STATUS_WAITING)
                                            <label class="label label-warning">Menunggu</label>
                                        @endif
                                        @if($data->status == \App\Models\PaymentDispensationRequest::STATUS_APPROVED)
                                            <label class="label label-success">Disetujui Oleh Admin</label>
                                        @endif
                                        @if($data->status == \App\Models\PaymentDispensationRequest::STATUS_CONFIRMED)
                                            <label class="label label-info">Sudah Diajukan Dispensasi</label>
                                        @endif
                                        @if($data->status == \App\Models\PaymentDispensationRequest::STATUS_SUBMITTED)
                                            <label class="label label-primary">Dispensasi Telah Diterima Oleh Siswa</label>
                                        @endif
                                        @if($data->status == \App\Models\PaymentDispensationRequest::STATUS_REJECTED)
                                            <label class="label label-danger">Dispensasi Ditolak</label>
                                        @endif
                                    </h5>
                                    <div class="form-group" style="padding: 1em">
                                        <table width="100%">
                                            <tr>
                                                <td>Dispensation Type</td>
                                                <td><strong> : {{ $data->dispensation_type }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Alasan</td>
                                                <td><strong> : {{ $data->reason }}</strong></td>
                                            </tr>
                                        </table>
                                    </div>

                                    <div class="row">
                                        <h5>Lampiran Pengajuan Dispensasi (Klik Gambar Untuk Zoom)</h5>
                                        <div class="text-title-3 font-italic text-black mt-2"
                                                style="font-weight: bold">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    @if(@$data->attachment !== null)
                                                        <div
                                                            class="preview-image {{ @$data->attachment !== null ? NULL : 'hide' }}">
                                                            <img src="{{$data->attachment}}"
                                                                    onclick="showImage('{{ $data->attachment }}')"
                                                                    class="header-image" width="300" height="300"/>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
            

@endsection