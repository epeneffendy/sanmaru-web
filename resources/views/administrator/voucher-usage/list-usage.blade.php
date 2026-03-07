@extends('layouts.admin.main')
@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Laporan Klaim</h1>
        <ol class="breadcrumb">
            <li>Shop</li>
            <li><a href="{{route('admin.voucher.index')}}">Setting Voucher</a></li>
            <li class="active">Laporan Klaim</li>
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
                        Laporan Klaim
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
                            <a href="{{ route('admin.voucher.export-usage', request()->except('page')) }}" download
                               class="btn btn-success btn-sm"><i
                                    class="fa fa-file-excel-o"></i> Export</a>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <form role="form" autocomplete="off" method="GET"
                                      action="{{ route('admin.voucher.usage-voucher') }}">
                                    <input autocomplete="false" name="hidden" disabled type="text"
                                           style="display:none;">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label for="search">Nama</label>
                                            <input type="text" name="name" placeholder="Search"
                                                   value="{{@$params['name']}}"
                                                   class="form-control input-sm"/>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label for="unit">Status</label>
                                            <select name="status" id="status" class="form-control input-sm">
                                                <option value=0>== SEMUA ==</option>
                                                <option
                                                    value="available" {{ @$params['status'] === 'available' ? 'selected' : NULL }}>
                                                    AVAILABLE
                                                </option>
                                                <option
                                                    value="claimed" {{ @$params['status'] === 'claimed' ? 'selected' : NULL }}>
                                                    CLAIMED
                                                </option>

                                            </select>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label for="unit">Unit</label>
                                            <select name="unit" class="form-control input-sm">
                                                <option value="">== SEMUA ==</option>
                                                @foreach (@$units as $unit)
                                                    <option
                                                        value="{{ $unit->id }}" {{ $unit->id == @$params['unit'] ? 'selected' : NULL }}>{{ $unit->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label for="type_voucher">Type Voucher</label>
                                            <select name="type_voucher" id="type_voucher" class="form-control input-sm">
                                                <option value="free_product" {{ 'free_product' == @$params['type_voucher'] ? 'selected' : NULL }}>Free Product</option>
                                                <option value="discount_percent" {{ 'discount_percent' == @$params['type_voucher'] ? 'selected' : NULL }}>Diskon Persen</option>
                                                <option value="discount_fixed" {{ 'discount_fixed' == @$params['type_voucher'] ? 'selected' : NULL }}>Potongan Harga</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label for="school_year">Tahun Ajaran</label>
                                            <select name="school_year" id="school_year" class="form-control input-sm">
                                                <option value="">== SEMUA ==</option>
                                                @foreach ($years as $year)
                                                    <option
                                                        value="{{ $year }}" {{( (date('Y')  + 1) == $year) ? 'selected' : '' }}>{{ $year }}
                                                        -
                                                        {{ $year + 1 }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" class="pull-right btn btn-sm btn-success"
                                                    style="margin-left: 5px">
                                                <i class="fa fa-search"></i> Search
                                            </button>
                                            <a href="{{ route('admin.voucher.usage-voucher') }}"
                                               class="pull-right btn btn-sm btn-warning">
                                                <i class="fa fa-refresh"></i> clear
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="fixed-table-head">
                            <table id="datatables-master-ppdb" class="table table-responsive table-striped display"
                                   style="width: 100%; border-top-width: medium; border-top-style: solid;">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th width="15%">Unit</th>
                                    <th>Register Number</th>
                                    <th>Nama</th>
                                    <th>Kode</th>
                                    <th>Tipe</th>
                                    <th>Kuota</th>
                                    <th>Status</th>
                                </tr>
                                </thead>

                                <tbody>
                                @php($no = 1)
                                @foreach($datas as $ind => $item)
                                    <tr>
                                        <td>{{$no++}}</td>
                                        <td width="15%">{{$item['unit']}}</td>
                                        <th>{{$item['register_number']}}</th>
                                        <th>{{$item['name']}}</th>
                                        <td>{{$item['code']}}</td>
                                        <td>
                                            {{$item['type']}} <br>
                                            @if($item['type'] == 'Free Product')
                                                @foreach(explode(',', $item['free']) as $product)
                                                    <label class="label label-info">
                                                        {{ $product }}
                                                    </label>
                                                    <br>
                                                @endforeach
                                            @else
                                                <label class="label label-info">
                                                    {{ $item['free'] }}
                                                </label>
                                            @endif
                                        </td>
                                        <td>{{$item['limit']}}</td>
                                        <td>
                                            <label class="label label-{{ $item['label_color'] }}">
                                                {{ $item['status'] }}
                                            </label>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{--                        {{ $datas->appends(request()->except('page'))->links() }}--}}
                    </div>

                </div>
            </div>
            <!-- End Panel -->
        </div>
        <!-- End Row -->
    </div>
    <!-- END CONTAINER -->

    <!-- Modal -->
    <div id="modal-confirmation" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">&nbsp;</h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    <button type="button" id="btn-confirm-modal" class="btn btn-success">&nbsp;</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-confirmation-success" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">&nbsp;</h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>

@endsection
