@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Setting Voucher</h1>
        <ol class="breadcrumb">
            <li>Shop</li>
            <li class="active">Setting Voucher</li>
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
                        Setting Voucher
                    </div>
                    <div class="button-collection">
                        {{--                        <a href="{{ route('admin.voucher.usage') }}" class="btn btn-sm btn-success">--}}
                        {{--                            <i class="fa fa-list"></i> Laporan Klaim--}}
                        {{--                        </a>--}}
                        <a href="{{ route('admin.voucher.usage-voucher') }}" class="btn btn-sm btn-success">
                            <i class="fa fa-list"></i> Laporan Klaim
                        </a>
                        <a href="{{ route('admin.voucher.usage-miss') }}" class="btn btn-sm btn-warning">
                            <i class="fa fa-list"></i> Laporan Penggunaan voucher
                        </a>
                    </div>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">Filter</h4>
                        </div>
                        <div class="panel-body">
                            <form role="form" autocomplete="off" method="GET" action="{{ route('admin.voucher.index') }}" autocomplete="off">
                                <input type="hidden" name="apply_filter" value="1">
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label for="code" class="form-label">Kode Voucher</label>
                                        <input type="text" name="code" placeholder="Kode" value="{{ @$params['code'] }}" class="form-control input-sm" />
                                    </div>
                                    <div class="form group col-md-3">
                                        <label for="name" class="form-label">Nama Siswa</label>
                                        <input type="text" name="name" placeholder="Nama" value="{{ @$params['name'] }}" class="form-control input-sm" />
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="type">Tipe Voucher</label>
                                        <select name="type" class="form-control input-sm">
                                            <option value="">== SEMUA ==</option>
                                            <option value="free_product" {{ @$params['type'] === 'free_product' ? 'selected' : NULL }}>Produk Gratis</option>
                                            <option value="discount_fixed" {{ @$params['type'] === 'discount_fixed' ? 'selected' : NULL }}>Diskon Harga</option>
                                            <option value="discount_percent" {{ @$params['type'] === 'discount_percent' ? 'selected' : NULL }}>Diskon Persentase</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="unit">Unit</label>
                                        <select name="unit" class="form-control input-sm">
                                            <option value="">== SEMUA ==</option>
                                            @foreach (@$units as $unit)
                                                <option value="{{ $unit->id }}" {{ $unit->id == @$params['unit'] ? 'selected' : NULL }}>{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="year">Tahun Ajaran</label>
                                        <select name="year" class="form-control input-sm">
                                            <option value="">== SEMUA ==</option>
                                            @foreach (@$years as $year)
                                                <option value="{{ $year->year }}" {{ $year->year == @$params['year'] ? 'selected' : NULL }}>{{ $year->year }} - {{ $year->year + 1 }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="pull-right btn btn-sm btn-success" style="margin-left: 5px">
                                            <i class="fa fa-search"></i> Search
                                        </button>
                                        <a href="{{ route('admin.voucher.index') }}" class="pull-right btn btn-sm btn-warning">
                                            <i class="fa fa-refresh"></i> Clear
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="panel-body table-responsive">
                        @if (session('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif
                        @if (session('errors'))
                            <div class="alert alert-danger">
                                {!! session('errors')->first() !!}
                            </div>
                        @endif

                        <div class="fixed-table-head">
                            <table id="datatables-voucher" class="table display">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Tipe</th>
                                    <th>Target</th>
                                    <th>Sisa Penggunaan</th>
                                    <th>Aktif</th>
                                    <th>Tahun Ajaran</th>
                                    @can('create', \App\Models\Voucher::class)
                                        <th>Option</th>
                                    @endcan
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $number = ($vouchers->currentPage() - 1) * $vouchers->perPage();
                                @endphp
                                @foreach($vouchers as $key => $voucher)
                                    @php
                                        $number++;
                                        $is_voucher_development = false;
                                        $kode_voucher = substr($voucher->code,-2);
                                        if($voucher->is_development == 1){
                                            $is_voucher_development = true;
                                        }

                                    @endphp
                                    <tr>
                                        <td>{{ $number }}</td>
                                        <td>{{ $voucher->code }}</td>
                                        <td>
                                            {{ $voucher->type }}<br/>
                                            {!! $voucher->type_value !!}
                                        </td>
                                        <td>
                                            {{ $voucher->target }}<br/>
                                            {!! $voucher->target_value !!}
                                        </td>
                                        <td class="text-center">
                                            {{ $voucher->usage_remaining }}<br/>
                                            <label class="label label-success">{{ $voucher->usage_type }}</label>
                                        </td>
                                        <td>{!! $voucher->active_label !!}</td>
                                        <td>{{ $voucher->year ? $voucher->year . ' - ' . ($voucher->year + 1) : 'Undefined' }}</td>
                                        @can('update', $voucher)
                                            <td>
                                                @if(!$is_voucher_development)
                                                    <a href="{{ route('admin.voucher.edit',$voucher['id']) }}" class="btn btn-xs btn-default">
                                                        <icon class="icon-plus"><i class="fa fa-pencil"></i></icon>
                                                    </a>
                                                    <a onclick="confirmDelete({{$voucher['id']}})" title="Delete" class="btn btn-xs btn-danger">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                    <form id="form-delete-{{$voucher['id']}}" action="{{ route('admin.voucher.delete',$voucher['id']) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                @else
                                                    <button id="voucher-recipient-{{$voucher['id']}}" class="btn btn-sm btn-info voucher-recipient" data-id="{{$voucher['id']}}" style="margin-top: 5px">
                                                        <i class="fa fa-list"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        @endcan
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{ $vouchers->appends(request()->except('page'))->links() }}
                        @can('create', \App\Models\Voucher::class)
                            <div class="btn-group padding-t-10 pull-right">
                                {{--                            <a href="{{ route('admin.voucher.add') }}" class="btn btn-sm btn-success">--}}
                                {{--                                <i class="fa fa-plus"></i> Tambah Data--}}
                                {{--                            </a>--}}
                                <a href="{{ route('admin.voucher.add-voucher') }}" class="btn btn-sm btn-success">
                                    <i class="fa fa-plus"></i> Tambah Data
                                </a>
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
            <!-- End Panel -->
        </div>
        <!-- End Row -->
    </div>
    <!-- END CONTAINER -->

    <div id="receive-voucher-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal">&times;
                    </button>
                    <h4 class="modal-title">Nofitication for student</h4>
                </div>
                <div class="modal-body">
                    <div id="detail_receive_voucher"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        .button-collection {
            margin-bottom: 5px;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{asset('js/datatables/datatables.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#datatables-uniform-deadline').DataTable();
        });

        function confirmDelete(id) {
            if(confirm('Are you sure you want to delete this item?'))
                document.getElementById('form-delete-' + id).submit();
        }

        $(document).on('click', '.voucher-recipient', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            let url = "{{ url('administrator/voucher/detail-receive-voucher') }}";
            $.get(`${url}/${id}`, function (data) {
                $('#receive-voucher-modal').modal();
                $('#detail_receive_voucher').html(data);
            });
        });
    </script>
@endpush
