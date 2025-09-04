@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Cek Pembayaran Seragam</h1>
        <ol class="breadcrumb">
            <li>Master</li>
            <li class="active">Cek Pembayaran Seragam</li>
        </ol>
    </div>
    <!-- End Page Header -->

    <!-- START CONTAINER -->
    <div class="container-padding">
        <!-- Start Row -->
        <div class="row">
            <!-- Start Panel -->
            <div class="col-md-12 pading-b-20 margin-b-20">
                <h4 class="font-title">Hasil Import - Cek Pembayaran Seragam</h4>
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
                <p>
                    Ini adalah fitur untuk melakukan pengecekan pembayaran Seragam, cara kerjanya adalah
                </p>
                <form action="{{ route('admin.uniform-payment.store') }}" id="cek-pembayaran-form" method="POST">
                    @csrf
                    <table class="table table-responsive table-striped">
                        <thead>
                            <tr>
                                <th>Virtual Account Number</th>
                                <th>Nama</th>
                                <th>Unit</th>
                                <th>Detail Transaksi</th>
                                <th>Bukti Pembayaran</th>
                                <th>Tanggal Pembayaran</th>
                                <th>Nominal harus dibayar</th>
                                <th>Nominal yang dibayar</th>
                                <th>Nominal harus dikembalikan</th>
                                <th>Pilihan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($product_orders as $key => $product_order)
                                @if(@$params['payment_method'] == 'cimb')
                                    @php($va_number = $import_datas->get($key)['Virtual Account Number'])
                                    @php($va_amount = $import_datas->get($key)['Virtual Account Amount'])
                                    @php($va_payment_date = $import_datas->get($key)['Posting Date'])
                                @elseif(@$params['payment_method'] == 'mandiri')
                                    @php($va_number = $import_datas->get($key)['NIS'])
                                    @php($va_amount = $import_datas->get($key)['Nominal Pembayaran'])
                                    @php($va_payment_date = $import_datas->get($key)['Tanggal Pembayaran'])
                                @elseif(@$params['payment_method'] == 'mandiri_v2')
                                    @php($va_number = $import_datas->get($key)['NIS'])
                                    @php($va_amount = $import_datas->get($key)['Txn Amount'])
                                    @php($va_payment_date = $import_datas->get($key)['Tanggal Pembayaran'])
                                @endif
                                <tr>
                                    <td>{{ $va_number }}</td>
                                    <td>{{ $product_order->user->ppdb->name }}</td>
                                    <td>{{ $product_order->user->ppdb->unit->name }}</td>
                                    <td><a href="{{ route('admin.product-order.show', ['id' => $product_order]) }}" target="_blank">detail transaksi</a></td>
                                    <td>
                                        @if ($product_order->payment_form)
                                            <a class="btn btn-sm btn-info" href="{{ $product_order->getPaymentImageUrl() }}" target="_blank">klik disini</a>
                                        @else
                                            {{ '-' }}
                                        @endif
                                    </td>
                                    <td>
                                        {{ $va_payment_date }}
                                        <input type="date" name="payment_dates[{{ $product_order->id }}]" value="{{date('Y-m-d', strtotime($va_payment_date))}}" style="display:none;">
                                    </td>
                                    <td>
                                        {{ App\Helpers\PriceHelper::rupiah($product_order->grand_total) }}
                                    </td>
                                    <td>
                                        {{ App\Helpers\PriceHelper::rupiah($va_amount) }}
                                    </td>
                                    <td>
                                        @php($overpaid = $va_amount - $product_order->grand_total)
                                        {{ App\Helpers\PriceHelper::rupiah($overpaid) }}
                                    </td>
                                    <td>
                                        @if (!in_array($product_order->status, [\App\Models\ProductOrder::STATUS_NEW_ORDER, \App\Models\ProductOrder::STATUS_CANCEL]))
                                            <i class="fa fa-check" style="color: #266c34;"></i>
                                        @else
                                            <div class="checkbox checkbox-success">
                                                <input id="checkbox-{{ $product_order->id }}" class="checkbox-product-orders" checked="true" name="product_orders[{{ $product_order->id }}]" type="checkbox" value="1">
                                                <label for="checkbox-{{ $product_order->id }}"></label>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">No data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if (!$product_orders->isEmpty())
                        <button class="btn btn-success" id="button-submit"><i class="fa fa-save"></i> Simpan</button>
                    @endif
                </form>

                @if (count($errors))
                    <ul>
                    @foreach ($errors as $key => $value)
                        <li><i class="fa fa-times" style="color: #EF4836"></i> {{ $value }}</li>
                    @endforeach
                    </ul>
                @endif
            </div>
            <!-- End Panel -->
        </div>
        <!-- End Row -->
    </div>
    <!-- END CONTAINER -->

@endsection
@push('styles')
    <link rel="stylesheet" href="{{asset('css/plugin/sweet-alert/sweet-alert.css')}}">
@endpush
@push('scripts')
    <script src="{{asset('js/sweet-alert/sweet-alert.min.js')}}"></script>
    <script>
        $(document).on('click', '#button-submit', function(e) {
            e.preventDefault();
            swal({
                title: "PERHATIAN",
                text: "Apakah Anda yakin untuk mengubah status pembayaran?",
                icon: "warning",
                buttons: [
                    'tidak!',
                    'Ya, Saya yakin!'
                ],
                dangerMode: false,
            }).then(function(isConfirm) {
                if (isConfirm) {
                    if ($('.checkbox-product-orders:checked').length) {
                        $('#cek-pembayaran-form').submit();
                    } else {
                        swal('warning', 'tidak ada data yang dapat diubah status pembayarannya', 'error');
                    }
                }
            });
        });
    </script>
@endpush
