@extends('layouts.admin.main')
@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data Pengunduran Diri Siswa</h1>
        <ol class="breadcrumb">
            <li>PPDB</li>
            <li class="active">Data Pengunduran Diri Siswa</li>
        </ol>
    </div>
    <!-- End Page Header -->

    <!-- START CONTAINER -->
    <div class="container-padding">
        <!-- Start Row -->
        <div class="row">

            <!-- Start Panel -->
            <div class="col-md-12">
                <div class="panel panel-default table-responsive">
                    <div class="panel-title">
                        Data Pengunduran Diri Siswa
                    </div>
                    <div class="panel-body">
                        @if (session('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif
                        @if (session('errors'))
                            <div class="alert alert-danger">
                                {!! session('errors') !!}
                            </div>
                        @endif
                        <div class="button-collection" style="margin: 15px 0">
                            @if (\App\Helpers\Helper::isAdminRole())
                                <a href="{{ route('admin.ppdb-resignation.add') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah data</a>   
                            @endif
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading">Filter</div>
                            <div class="panel-body">
                                <form role="form" autocomplete="off" method="GET" action="{{ route('admin.ppdb-resignation.index') }}">
                                    <input autocomplete="false" name="hidden" disabled type="text" style="display:none;">
                                    <div class="form-group col-md-3">
                                        <input type="text" name="name" placeholder="Search" value="{{ @$params['name'] }}" class="form-control input-sm" />
                                    </div>
                                    <div class="form-group col-md-3">
                                        <select name="unit" class="form-control input-sm">
                                            <option value="0">== SEMUA ==</option>
                                            @foreach (@$units as $unit)
                                                <option value="{{ $unit->id }}" {{ $unit->id == @$params['unit'] ? 'selected' : NULL }}>{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <input type="date" name="start_date" class="form-control input-sm" value="{{ @$params['start_date'] }}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <input type="date" name="end_date" class="form-control input-sm" value="{{ @$params['end_date'] }}">
                                    </div>
                                    <a href="{{ route('admin.ppdb-resignation.index') }}" class="pull-right btn btn-sm btn-warning">
                                        <i class="fa fa-refresh"></i> clear
                                    </a>
                                    <button type="submit" class="pull-right btn btn-sm btn-success">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="fixed-table-head">
                            <table id="datatables-master-ppdb" class="table table-striped table-responsive display" style="width: 100%; border-top-width: medium; border-top-style: solid;">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="text-center">No</th>
                                        <th rowspan="2">Detail Calon Siswa</th>
                                        <th colspan="5" class="text-center">Status Administrasi</th>
                                        <th>&nbsp;</th>
                                        <th colspan="3" class="text-center">Status Pengembalian Dana</th>
                                        <th rowspan="2" class="text-center">Option</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Verified</th>
                                        <th class="text-center">Payment</th>
                                        <th class="text-center">Data</th>
                                        <th class="text-center">Parent</th>
                                        <th class="text-right">Accepted</th>
                                        <th></th>
                                        <th class="text-center">Kegiatan</th>
                                        <th class="text-center">Seragam</th>
                                        <th class="text-center">Gedung</th>
                                    </tr>
                                </thead>
    
                                <tbody>
                                @php
                                    $number = ($data->currentPage() - 1) * $data->perPage();
                                @endphp
                                @foreach($data as $key => $value)
                                    @php $number++ @endphp
                                    <tr>
                                        <td>{{ $number }}</td>
                                        <td>
                                            <div class="wrapped">
                                                <b style="text-transform: uppercase;">{{$value->ppdbUser->name}}</b><br/>
                                                <u>{{$value->ppdbUser->user->username}}</u><br/>
                                                <label class="label label-info label-sm">{{$value->ppdbUser->user->email}}</label><br/>
                                                <label class="label label-warning label-sm">no registrasi: {{$value->register_number}}</label><br/>
                                                <label class="label label-danger label-sm">{{@$value->unit->name}}</label><br/>
                                                <label class="label label-success label-xs">{{ $value->created_at }}</label><br/>
                                                <label class="label label-xs" style="background-color: gray">{{ $value->origin_school }}</label><br/>
                                                <small>phone: {{$value->ppdbUser->user->mobile_phone}}</small><br/>
                                                {{$value->ppdbUser->gender}}
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="btn btn-circle btn-sm {{ $value->isEmailVerified ? "btn-success" : "btn-danger" }}">
                                                <icon class="icon-plus">
                                                    @if ($value->isEmailVerified)
                                                        <i class="fa fa-check" title="Email Verified"></i>
                                                    @else
                                                        <i class="fa fa-times" title="Email belum Verified"></i>
                                                    @endif
                                                </icon>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="btn btn-circle btn-sm {{ $value->isPaymentStatusComplete ? "btn-success" : ($value->isPaymentStatusVerified ? "btn-primary" : "btn-danger") }}">
                                                <icon class="icon-plus">
                                                    @if ($value->isPaymentStatusComplete)
                                                        <i class="fa fa-check" title="Lengkap"></i>
                                                    @elseif ($value->isPaymentStatusVerified)
                                                        <i class="fa fa-check" title="Verified"></i>
                                                    @else
                                                        <i class="fa fa-times" title="Belum Lengkap"></i>
                                                    @endif
                                                </icon>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="btn btn-circle btn-sm {{ $value->isDataComplete ? "btn-success" : "btn-danger" }}">
                                                <icon class="icon-plus">
                                                    @if ($value->isDataComplete)
                                                        <i class="fa fa-check" title="Lengkap"></i>
                                                    @else
                                                        <i class="fa fa-times" title="Belum Lengkap"></i>
                                                    @endif
                                                </icon>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="btn btn-circle btn-sm {{ $value->isParentsComplete ? "btn-success" : "btn-danger" }}">
                                                <icon class="icon-plus">
                                                    @if ($value->isParentsComplete)
                                                        <i class="fa fa-check" title="Lengkap"></i>
                                                    @else
                                                        <i class="fa fa-times" title="Belum Lengkap"></i>
                                                    @endif
                                                </icon>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="btn btn-circle btn-sm {{ $value->isSubmitted ? "btn-warning" : "btn-danger" }}">
                                                <icon class="icon-plus">
                                                    @if ($value->isSubmitted)
                                                        <i class="fa fa-question"></i>
                                                    @else
                                                        <i class="fa fa-times"></i>
                                                    @endif
                                                </icon>
                                            </span>
                                        </td>
                                        <td></td>
                                        <td class="text-center"></td>
                                        <td class="text-center">
                                            <div class="wrapped">
                                                @if(!empty($value->ppdbUser->paymentRefundUniform) && !is_null($value->ppdbUser->paymentRefundUniform))
                                                    <span>Nominal : {{ $value->ppdbUser->paymentRefundUniform->nominal_price }}</span>
                                                    <span>Refund : {{ $value->ppdbUser->paymentRefundUniform->nominal_refund }}</span>
                                                    <span>Status : {!! $value->ppdbUser->paymentRefundUniform->statusLabel !!}</span>
                                                @else
                                                    -
                                                @endif
                                            </div>
                                            
                                        </td>
                                        <td class="text-center">
                                            @if(!empty($value->ppdbUser->paymentRefundDevelopment) && !is_null($value->ppdbUser->paymentRefundDevelopment))
                                                <span>Nominal : {{ $value->ppdbUser->paymentRefundDevelopment->nominal_price }}</span>
                                                <span>Refund : {{ $value->ppdbUser->paymentRefundDevelopment->nominal_refund }}</span>
                                                <span>Status : {!! $value->ppdbUser->paymentRefundDevelopment->statusLabel !!}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <a href="{{ route('admin.ppdb-resignation.show',$value['id']) }}" class="btn btn-xs btn-info">
                                                <icon class="icon-plus"><i class="fa fa-eye"></i></icon>
                                            </a>
                                            <a href="{{ route('admin.ppdb-resignation.edit',$value['id']) }}" title="Edit" class="btn btn-xs btn-default">
                                                <icon class="icon-plus"><i class="fa fa-pencil"></i></icon>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{ $data->appends(request()->except('page'))->links() }}
                    </div>

                </div>
            </div>
            <!-- End Panel -->
        </div>
        <!-- End Row -->
    </div>
    <!-- END CONTAINER -->
@endsection
@push('styles')
    <style>
        .btn-circle {
            width: 30px;
            height: 30px;
            text-align: center;
            padding: 6px 0;
            font-size: 12px;
            line-height: 1.42;
            border-radius: 15px;
        }

        .btn-circle .fa {
            margin-right: 0;
        }

        .wrapped {
            max-width: 150px;
            word-wrap: break-word;
        }

    </style>
@endpush

@push('scripts')
   
@endpush
