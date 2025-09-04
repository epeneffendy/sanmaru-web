<div class="button-collection" style="margin: 15px 0">
    <a href="{{ route('admin.product-order.export.kantin', request()->except('page')) }}" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Export</a>
</div>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Filter</h3>
    </div>
    <div class="panel-body">
        <form role="form" autocomplete="off" method="GET" action="{{ route('admin.product-order.index') }}">
            <div class="row">
                <input type="hidden" name="active_tab" value="kantin">
                <input type="hidden" name="apply_filter" value="1">
                <div class="form-group col-md-2">
                    <label for="search" class="form-label">Cari</label>
                    <input type="text" name="search" placeholder="Search" value="{{ @$params['search'] }}" class="form-control input-sm" />
                </div>
                <div class="form-group col-md-2">
                    <label for="date_range_kantin" class="form-label">rentang waktu</label>
                    <input type="text" id="date_range_kantin" name="date_range" placeholder="rentang waktu" value="{{ @$params['date_range'] }}" class="form-control input-sm date-range-field" />
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
                <td>{{ @$value->productOrderDetails->count() }} pesanan<br/><label class="label label-info">{{ \App\Helpers\PriceHelper::rupiah(@$value->grand_total) }}</label></td>
                <td>{{ \App\Helpers\Helper::tanggalJam(@$value->created_at) }}</td>
                <td>
                    <div class="row">
                        <div class="col-sm-12">{!!@$value->status_label!!}</div>

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
                    <a href="{{ route('admin.product-order.kantin.show', @$value->id) }}" title="Show" class="btn btn-xs btn-success">
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
    <a href="{{ route('admin.product-order.kantin.create') }}" class="btn btn-success">
        <icon class="icon-plus"> Tambah Data</icon>
    </a>
    {{--<a href="{{route('user.export')}}" class="btn btn-primary"><icon class="icon-save"> Export</icon></a>--}}
</div>
