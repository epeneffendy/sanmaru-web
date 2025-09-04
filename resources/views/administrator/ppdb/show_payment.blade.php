@extends('layouts.admin.main')
@section('content')
@php($action=route('admin.ppdb.show',array($data->id)))
@php($status="Show")
@php($status_header="Show")
<!-- Start Page Header -->
<div class="page-header">
    <h1 class="title">Data Master Peserta PPDB</h1>
    <ol class="breadcrumb">
        <li>Master</li>
        <li><a href="{{route('admin.ppdb.index')}}">PPDB</a></li>
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
                        {{$status_header}} PPDB User
                    </h3>
                </div> <!-- /widget-header -->
                <div class="widget-content">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="name">Nomor Registrasi:</label>
                            <div class="col-sm-10">
                                <div class="form-control"> {{ @$data->register_number }} </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="name">Nama:</label>
                            <div class="col-sm-10">
                                <div class="form-control"> {{ @$data->name }} </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="name">Email:</label>
                            <div class="col-sm-10">
                                <div class="form-control">{{ @$data->email }} </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="name">Nomor Telepon:</label>
                            <div class="col-sm-10">
                                <div class="form-control"> {{ @$data->user->mobile_phone }} </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="m_phone">Total yang harus dibayar</label>
                            <div class="col-sm-10">
                                <div class="form-control">{{ \App\Helpers\PriceHelper::registration($data, true) }} </div>
                            </div>
                        </div>

                        <hr/>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="image">Bukti Bayar</label>
                            <div class="col-sm-10">
                                @if ($data->payment_form !== null)
                                    <a href="{{ $data->getPaymentFormImageUrl() }}" target="_blank">
                                        <img src="{{ $data->getPaymentFormImageUrl() }}" alt="Bukti bayar" style="max-width: 300px; height: auto;">
                                    </a>
                                @endif
                            </div>
                        </div>

                        @if ($data->potensi_kecerdasan_image !== null)
                        <hr/>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="image">Potensi Kecerdasan</label>
                            <div class="col-sm-10">
                                <a href="{{ $data->getPotensiKecerdasanImageUrl() }}" target="_blank">
                                    <img src="{{ $data->getPotensiKecerdasanImageUrl() }}" alt="Potensi Kecerdasan" style="max-width: 300px; height: auto;">
                                </a>
                            </div>
                        </div>
                        @endif

                        @if ($data->bakat_istimewa_image !== null)
                        <hr/>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="image">Bakat Istimewa</label>
                            <div class="col-sm-10">
                                <a href="{{ $data->getBakatIstimewaImageUrl() }}" target="_blank">
                                    <img src="{{ $data->getBakatIstimewaImageUrl() }}" alt="Bakat Istimewa" style="max-width: 300px; height: auto;">
                                </a>
                            </div>
                        </div>
                        @endif

                        @if ($data->kesiapan_psikis_image !== null)
                        <hr/>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="image">Kesiapan Psikis</label>
                            <div class="col-sm-10">
                                <a href="{{ $data->getKesiapanPsikisImageUrl() }}" target="_blank">
                                    <img src="{{ $data->getKesiapanPsikisImageUrl() }}" alt="Kesiapan Psikis" style="max-width: 300px; height: auto;">
                                </a>
                            </div>
                        </div>
                        @endif

                        <div class="clear-50"></div>
                        @csrf
                    </div>
                </div>
                <a href="{{route('admin.ppdb.index')}}" class="btn btn-warning">Back</a>
                @if ($data->isPaymentStatusComplete)
                    <button class="btn btn-danger" data-toggle="modal" data-target="#rejectPaymentModal"><i class="fa fa-times" title="Belum Lengkap"></i>Tolak Pembayaran</button>
                    <a href="{{ route('admin.ppdb.confirm-payment',$data['id']) }}"
                    class="btn btn-default"
                    onclick="return confirm('Apa anda yakin mau mengkonfirmasi pembayaran user ini?');">
                    <i class="fa fa-dollar"></i> Konfirmasi Pembayaran
                </a>
                @endif
            </div> <!-- /widget-content -->
        </div>
        <!-- End Panel -->
    </div>
    <!-- End Row -->
</div>
<div id="rejectPaymentModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Nofitication for student</h4>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.ppdb.reject-payment', $data['id']) }}" method="POST">
                    @csrf
                    <p style="text-align: left;">Silahkan isi alasan penolakan pembayaran.</p>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <textarea class="form-control" name="body" id="body" rows="3" placeholder="Masukkan" required>{!! old('body') !!}</textarea>

                            @error('body')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-12">
                            <div class="checkbox checkbox-success">
                                <input type="checkbox" name="send_email" id="send_email" value="1" {{ old('send_email', 1) ? 'checked' : '' }}>
                                <label for="send_email">Kirim email pemberitahuan</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-0" style="text-align: right; padding-right:10px">
                            <button type="submit" class="btn btn-warning">Reset</button>

                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END CONTAINER -->
@endsection
