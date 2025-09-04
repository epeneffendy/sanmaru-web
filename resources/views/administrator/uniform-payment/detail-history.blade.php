@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Cek Pembayaran Seragam</h1>
        <ol class="breadcrumb">
            <li>Master</li>
            <li><a href="{{ route('admin.uniform-payment.index') }}">Cek Pembayaran Seragam</a></li>
            <li class="active">Detail History</li>
        </ol>
    </div>
    <!-- End Page Header -->

    <!-- START CONTAINER -->
    <div class="container-padding" style="padding-bottom: 100px;">
        <!-- Start Row -->
        <div class="row">
            <!-- Start Panel -->
            <div class="col-md-12">
                <div class="widget" style="padding-bottom: 200px;">
                    <div class="widget-header">
                        <h3>Detail History</h3>
                    </div> <!-- /widget-header -->
                    <div class="widget-content">
                        <div class="form-horizontal fieldset-form">    
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">File import:</label>
                                <div class="col-sm-10">
                                    <div class="form-control">{{ @$importJob->filename }}</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Tanggal:</label>
                                <div class="col-sm-10">
                                    <div class="form-control">{{ \App\Helpers\Helper::tanggal(@$importJob->created_at) }}</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Jam:</label>
                                <div class="col-sm-10">
                                    <div class="form-control">{{ @$importJob->created_at->format('H:i') }}</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">User:</label>
                                <div class="col-sm-10">
                                    <div class="form-control">{{ @$importJob->user->username }}</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Status:</label>
                                <div class="col-sm-10">
                                    <div class="form-control">{{ @$importJob->status }}</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-10 col-sm-offset-2">
                                    <fieldset>
                                        <legend>Errors ({{ @$importJob->total_errors }})</legend>
                                        <div class="form-group">
                                            <ul>
                                            @foreach($errors as $key => $value)
                                                <li><label class="label label-danger label-sm">{{ $value }}</label></li>
                                            @endforeach
                                            </ul>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-10 col-sm-offset-2">
                                    <fieldset>
                                        <legend>Success ({{ @$importJob->total_success }})</legend>
                                        <div class="form-group">
                                            <ul>
                                            @foreach($success as $key => $value)
                                                <li><label class="label label-success label-sm">{{ $value }} </label></li>
                                            @endforeach
                                            </ul>
                                        </div>
                                    </fieldset>
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
        .form-horizontal fieldset .form-group .form-group {
            margin-left: 10px;
        }
    </style>
@endpush

