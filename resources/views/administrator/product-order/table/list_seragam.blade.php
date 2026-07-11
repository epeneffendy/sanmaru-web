<div class="button-collection" style="margin: 15px 0">
    @if($user->type != 'admin_ppdb')
        <a href="{{ route('admin.product-order.export', request()->except('page')) }}" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Export</a>
        <a href="{{ route('admin.product-order.export-list') }}" class="btn btn-success btn-sm"><i class="fa fa-list"></i> Daftar Export Data</a>
    @endif
    <a href="{{ route('admin.product-order.report.index') }}" class="btn btn-success btn-sm"><i class="fa fa-bar-chart"></i> Summary</a>
    <a href="{{ route('admin.product-order.report.purchase-report') }}" class="btn btn-success btn-sm"><i class="fa fa-bar-chart"></i> Laporan Pembelian</a>
</div>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Filter</h3>
    </div>
    <div class="panel-body">
        <form role="form" autocomplete="off" method="GET" action="{{ route('admin.product-order.index') }}">
            <div class="row">
                <input type="hidden" name="active_tab" value="seragam">
                <input type="hidden" name="apply_filter" value="1">
                <div class="form-group col-md-2">
                    <label for="search" class="form-label">Cari</label>
                    <input type="text" name="search" placeholder="Search" value="{{ @$params['search'] }}" class="form-control input-sm" />
                </div>
                <div class="form-group col-md-2">
                    <label for="date_range_seragam" class="form-label">rentang waktu</label>
                    <input type="text" id="date_range_seragam" name="date_range" placeholder="rentang waktu" value="{{ @$params['date_range'] }}" class="form-control input-sm date-range-field" />
                </div>
                <div class="form-group col-md-2">
                    <label for="scope" class="form-label">berdasarkan</label>
                    <select name="scope" id="scope" class="form-control input-sm">
                        @foreach (@$search_scopes as $key => $scope)
                            <option value="{{$key}}" {{$key == @$params['scope'] ? 'selected' : NULL}}>{{$scope}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-control input-sm">
                        <option value="">== SEMUA ==</option>
                        <option value="payment_not_confirmed" {{ @$params['status'] == 'payment_not_confirmed' ? 'selected' : NULL }}>Order Baru (belum bayar)</option>
                        <option value="payment_uploaded" {{ @$params['status'] == 'payment_uploaded' ? 'selected' : NULL }}>Order Baru (sudah bayar)</option>
                        <option value="payment_confirmed" {{ @$params['status'] == 'payment_confirmed' ? 'selected' : NULL }}>Terkonfirmasi</option>
                        <option value="cancel" {{ @$params['status'] == 'cancel' ? 'selected' : NULL }}>Cancel</option>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="unit" class="form-label">Unit</label>
                    <select name="unit" class="form-control input-sm">
                        <option value="">== SEMUA ==</option>
                        @foreach (@$units as $unit)
                            <option value="{{ $unit->id }}" {{ $unit->id == @$params['unit'] ? 'selected' : NULL }}>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="year" class="form-label">Tahun Ajaran</label>
                    <select name="year" id="year" class="form-control input-sm">
                        <option value="">== SEMUA ==</option>
                        @foreach ($years as $year)
                            <option value="{{ $year->year }}" {{ $year->year == @$params['year'] ? 'selected' : NULL}}>{{ $year->year }} - {{ $year->year + 1 }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    @php
                        $statusPickups = [
                            $productOrderModel::PICKUP_STATUS_NOT_PICKUP => 'Belum Diambil',
                            $productOrderModel::PICKUP_STATUS_PICKUP => 'Diambil',
                            $productOrderModel::PICKUP_STATUS_SENT => 'Dikirim',
                        ];
                    @endphp
                    <label for="pickup_status" class="form-label">Status Pengambilan</label>
                    <select name="pickup_status" id="pickup_status" class="form-control input-sm">
                        <option value="">== SEMUA ==</option>
                        @foreach ($statusPickups as $key => $name)
                            <option value="{{ $key }}" {{ @$params['pickup_status'] == $key ? 'selected' : NULL }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    @php
                        $typeVouchers = [
                            $voucherModel::TYPE_FREE => 'Free Produk',
                            $voucherModel::TYPE_DISC_FIXED => 'Diskon Fixed',
                            $voucherModel::TYPE_DISC_PERCENT => 'Diskon Persen',
                        ];
                    @endphp
                    <label for="type_voucher" class="form-label">Type Voucher</label>
                    <select name="type_voucher" id="type_voucher" class="form-control input-sm">
                        <option value="">== SEMUA ==</option>
                        @foreach ($typeVouchers as $key => $name)
                            <option value="{{ $key }}" {{ @$params['type_voucher'] == $key ? 'selected' : NULL }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="type_user" class="form-label">Siswa</label>
                    <select name="type_user" id="type_user" class="form-control input-sm">
                        <option value="">== SEMUA ==</option>
                        <option value="siswa" {{ @$params['type_user'] == 'siswa' ? 'selected' : NULL }} >Siswa Reguler</option>
                        <option value="ppdb" {{ @$params['type_user'] == 'ppdb' ? 'selected' : NULL }}>Siswa PPDB</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="pull-right btn btn-sm btn-success" style="margin-left: 5px">
                        <i class="fa fa-search"></i> Search
                    </button>
                    <a href="{{ route('admin.product-order.index') }}" class="pull-right btn btn-sm btn-warning">
                        <i class="fa fa-refresh"></i> Clear
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="fixed-table-head">
    <table id="datatables-product-orders" class="table display table-responsive">
        <thead>
        <tr>
            <th>No</th>
            <th>No Tagihan</th>
            <th>Detail Siswa</th>
            <th>Unit</th>
            <th>Pesanan</th>
            <th>Waktu Order</th>
            <th>Status</th>
            <th>Pembayaran</th>
            <th>Status Pengambilan</th>
            <th>Voucher</th>
            <th width="100px">Option</th>
        </tr>
        </thead>
        <tbody>
        @foreach($product_orders as $value)
            <tr>
                <td>{{ ($product_orders->currentPage()-1) * $product_orders->perPage() + $loop->iteration }}</td>
                <td>{{ @$value->invoice_no}}</td>
                <td>
                    <b class="d-block">{{ @$value->user->name }}</b>
                    <small class="d-block text-muted">email: {{@$value->user->email}}</small>
                    @if(@$value->user->ppdb)
                        <span class="label label-default">{{@$value->user->ppdb->register_number}}</span>
                    @endif
                </td>
                <td>{{ @$value->user->unit_name }}</td>
                <td>{{ @$value->productOrderDetails->count() }} pesanan<br/><label class="label label-info">{{ \App\Helpers\PriceHelper::rupiah(@$value->order_amount) }}</label></td>
                <td>{{ \App\Helpers\Helper::tanggalJam(@$value->created_at) }}</td>
                <td>
                    <div class="row">
                        <div class="col-sm-12">{!!@$value->status_label!!}</div>
                        @if (@$value->status === \App\Models\ProductOrder::STATUS_NEW_ORDER && !@$value->payment_image)
                            @if (\App\Helpers\Helper::isVaBcaEnable())
                                {{--                                <div class="col-sm-12"><a href="{{route('admin.product-order.check-status-payment',@$value->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-refresh"></i> Cek status pembayaran</a></div>--}}
                                @if(@$value->payment_option == 'BCA')
                                    <div class="col-sm-12"><a href="{{route('admin.product-order.check-inquiry-status',@$value->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-refresh"></i> Cek status pembayaran</a></div>
                                @endif
                            @endif
                                @if(@$value->payment_option != 'BCA')
                                    <div class="col-sm-12"><a href="#" class="upload-order-confirmation" data-no-tagihan="{{@$value->invoice_no}}" data-name="{{ @$value->user->name }}" data-unit="{{ @$value->user->unit_name }}" data-id="{{ @$value->id }}" data-jumlah-pesanan="{{ @$value->productOrderDetails->count() }} pesanan" data-total="{{ \App\Helpers\PriceHelper::rupiah(@$value->grand_total) }}" data-email="{{ @$value->user->email }}">upload bukti pembayaran</a></div>
                                @endif
                        @endif
                    </div>
                </td>
                <td>{!! @$value->icon_konfirmasi_pembayaran !!}</td>
                <td>
                    {!! $value->pickupStatusLabel !!}
                    @if ($value->isPickup())
                        <button type="submit" form="cancelPickupForm" class="btn btn-danger btn-sm" style="margin-right: 10px">Batalkan</button>
                        <form id="cancelPickupForm" action="{{ route('admin.product-order.cancel-pickup', $value->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin membatalkan pengambilan pesanan?');">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" value="{{ $value->id }}">
                            <input type="hidden" name="response" value="page">
                        </form>
                    @endif
                </td>
                <td>
                    @if ($voucher = json_decode($value->voucher, TRUE))
                        <b>{{ $voucher['code'] }}</b> - {{ $typeVouchers[$voucher['type']] ?? '' }}
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.product-order.show', @$value->id) }}" title="Show" class="btn btn-xs btn-success">
                        <i class="fa fa-eye"></i>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

{{ $product_orders->appends(request()->except('page'))->links() }}

<div class="btn-group padding-t-10 pull-right">
    {{-- Button "Tambah Data" di-hidden karena pembuatan order seragam seharusnya dilakukan oleh siswa --}}
    @if($user->type != 'admin_ppdb')
        <a href="{{ route('admin.product-order.add') }}" class="btn btn-success">
            <icon class="icon-plus"> Tambah Data</icon>
        </a>
    @endif
    {{--<a href="{{route('user.export')}}" class="btn btn-primary"><icon class="icon-save"> Export</icon></a>--}}
</div>
