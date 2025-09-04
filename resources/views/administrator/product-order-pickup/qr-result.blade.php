@extends('layouts.admin.main')
@section('content')
<!-- Start Page Header -->
<div class="page-header">
    <h1 class="title">QR Code Pengambilan Pesanan</h1>
    <ol class="breadcrumb">
        <li>Shop</li>
        <li><a href="{{route('admin.product-order-pickup.index')}}">Pengambilan Pesanan</a></li>
        <li class="active">Hasil QR</li>
    </ol>
</div>

<div id="qr-code-page" class="container-padding">
    <div class="content-wrapper">
        <h4 class="text-lg bold">Pengambilan Pesanan</h4>
        <div class="card">
            <div class="row">
                <div class="col-md-2 profile-wrapper">
                    <div class="text-center">
                        <div class="profile-picture mb-20 mt-24">
                            <img src="{{asset('img/profile-grey.png')}}" alt="Profile Santa Maria">
                        </div>
                        <p class="text-md bold black">{{ @$productOrder->user->name ?? 'Unknown' }}</p>
                        <p class="text-sm">{{ @$productOrder->user->unit_name ?? '-' }}</p>
                        <p class="text-sm">{{ @$productOrder->user->email ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-base grey">Tanggal pengambilan</p>
                        <p class="text-base grey">{{ @$productOrder->pickup_date ?? '-' }}</p>
                    </div>
                </div>
                <div class="col-md-10" style="position: relative;">

                    <div class="line"></div>

                    <p class="text-base grey mb-13 mt-24">Informasi Pengambilan Pesanan</p>
                    <div class="row border-bottom">
                        <div class="col-sm-4">
                            {{-- <p class="text-sm">Uang Gedung</p>
                            <p class="text-base green mb-16">LUNAS</p> --}}

                            <p class="text-sm">No. Pembayaran Pesanan</p>
                            <p class="text-base black mb-16">{{ @$productOrder->invoice_no ?? '-' }}</p>
                        </div>
                        <div class="col-sm-4">
                            {{-- <p class="text-sm">Uang Kegiatan</p>
                            <p class="text-base green mb-16">LUNAS</p> --}}

                            <p class="text-sm">Jadwal Pengambilan</p>
                            <p class="text-base black mb-16">{{ @$productOrder->pickup_date_schedule ? \App\Helpers\Helper::tanggal($productOrder->pickup_date_schedule) . ($productOrder->alt_pickup_date_schedule ? " atau " . \App\Helpers\Helper::tanggal($productOrder->alt_pickup_date_schedule) : null) : '-' }}</p>
                        </div>
                        <div class="col-sm-4">
                            {{-- <p class="text-sm">Total Pembayaran {Pesanan}</p>
                            <p class="text-base black mb-16">Rp 165.000</p> --}}

                            <p class="text-sm">Status Pesanan</p>
                            @if (!@$productOrder->pickup_date_schedule)
                            <p class="text-base mb-16">-</p>
                            @elseif (@$productOrder->pickup_status == 'pickup')
                            <p class="text-base green mb-16">SUDAH DIAMBIL</p>
                            @else
                            <p class="text-base yellow mb-16">BELUM DIAMBIL</p>
                            @endif
                        </div>
                    </div>

                    <p class="text-base grey mb-16 mt-24">Detail Order</p>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="fixed-table-head fixed-table-foot">
                                <table class="table display table-responsive">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Produk</th>
                                            <th>Uk.</th>
                                            <th>Harga Satuan</th>
                                            <th>Qty</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (@$productOrder->productOrderDetails)
                                        @php($total = 0)
                                        @foreach ($productOrder->productOrderDetails as $productOrderDetail)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $productOrderDetail->product->name }}</td>
                                            <td>{{ $productOrderDetail->productDetail->size }}</td>
                                            <td>{{ \App\Helpers\PriceHelper::rupiah($productOrderDetail->price) }}</td>
                                            <td>{{ $productOrderDetail->quantity }}</td>
                                            <td class="text-right">{{ \App\Helpers\PriceHelper::rupiah($productOrderDetail->total_price) }}</td>
                                        </tr>
                                        @php($total += $productOrderDetail->total_price)
                                        @endforeach
                                        @php($voucher = json_decode($productOrder->voucher, TRUE))
                                        <tr style="background-color: rgb(228, 228, 228); position: sticky; inset-block-end: 0; font-weight: 700;">
                                            <td colspan="5" align="right">Total</td>
                                            <td align="right">{{ \App\Helpers\PriceHelper::rupiah($total) }}</td>
                                        </tr>
                                        @if (isset($voucher))
                                            <tr style="background-color: rgb(228, 228, 228); position: sticky; inset-block-end: 0; font-weight: 700;">
                                                <td colspan="5" align="right">Voucher ({{ $voucher['code'] }})</td>
                                                <td align="right">{{ \App\Helpers\PriceHelper::rupiah($productOrder->discount_total) }}</td>
                                            </tr>
                                        @endif
                                        <tr style="background-color: rgb(228, 228, 228); position: sticky; inset-block-end: 0; font-weight: 700;">
                                            <td colspan="5" align="right">Total Dibayarkan</td>
                                            <td align="right">{{ \App\Helpers\PriceHelper::rupiah($productOrder->grand_total) }}</td>
                                        </tr>
                                        @else
                                        <tr>
                                            <td colspan="7" class="text-center grey"><i class="fa fa-ban fa-3x" style="margin-top: 40px"></i></td>
                                        </tr>
                                        <tr>
                                            <td colspan="7" class="text-center grey">No data found</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="button-wrapper">
                                @if ($productOrder)
                                @if ($productOrder->isPickup())
                                <button type="submit" form="cancelPickupForm" class="btn btn-danger" style="margin-right: 10px">Batalkan Pengambilan</button>
                                <button type="button" class="btn btn-primary disabled" disabled>Konfirmasi pengambilan</button>
                                <form id="cancelPickupForm" action="{{ route('admin.product-order-pickup.cancel-pickup', $productOrder->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin membatalkan pengambilan pesanan?');">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="id" value="{{ $productOrder->id }}">
                                    <input type="hidden" name="response" value="page">
                                </form>
                                @else
                                <button type="submit" form="pickupForm" class="btn btn-primary">Konfirmasi pengambilan</button>
                                <form id="pickupForm" action="{{ route('admin.product-order-pickup.pickup', $productOrder->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin mengonfirmasi pengambilan pesanan?');">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="id" value="{{ $productOrder->id }}">
                                    <input type="hidden" name="response" value="page">
                                </form>
                                @endif
                                @else
                                <button form="pickupForm" class="btn btn-primary disabled" disabled>Konfirmasi pengambilan</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('styles')
    <style>
        .preview-image img {
            height: auto;
            width: 400px;
        }
    </style>
@endpush
@push('scripts')
<script>
     $(document).ready(function () {
        $("input[name=pickup_image]").change(function() {
            readURL(this);
        });
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('.preview-image img').attr('src', e.target.result).parent().removeClass('hide');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function confirmCancel() {
        if(confirm('Apakah anda yakin ingin membatalkan pengambilan pesanan?'))
            document.getElementById('form-cancel-pickup').submit();
    }
</script>
@endpush
