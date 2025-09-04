<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Detail Transaksi {{ $productOrder->invoice_no }}</title>
    <style type="text/css">
        * {
            font-family: Verdana, Arial, sans-serif;
        }
        table {
            font-size: x-small;
        }
        .table-bordered {
            border-collapse: collapse;
        }
        .table-bordered table, .table-bordered td, .table-bordered tr, .table-bordered th {
            border: 1px solid #666;
            padding: 5px;
        }
        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }
        .gray {
            background-color: lightgray
        }
        /* Create a two-column layout */
        .column {
          float: left;
          width: 50%;
          padding: 5px;
        }

        /* Clearfix (clear floats) */
        .row::after {
          content: "";
          clear: both;
          display: table;
        }
    </style>
</head>
<body>
    <table width="100%" style="border-bottom: 1px solid black" >
        <tr>
            <td valign="top" style="width: 1%;" ><img src="{{ public_path('img/logo-serviam.png') }}" alt="logo serviam" width="75"/></td>
            <td>
                <h3 style="margin: 3px 0px"><strong>YAYASAN PARATHA BHAKTI</strong></h3>
                <div style="margin: 3px 0px">Jl. Raya Darmo No.49 Surabaya</div>
                <div style="margin: 3px 0px">Telp. 031-5567840 / WA. 081232013008</div>
                <div style="margin: 3px 0px">https://sanmaru.sanmarosu-jatim.sch.id</div>
            </td>
            <td align="right">
                {{-- <h1>Detail Transaksi</h1> --}}
            </td>
        </tr>
    </table>

    <div class="row">
        <div class="column">
            <table>
                <tbody>
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td>{{ $productOrder->user->name }}</td>
                    </tr>
                    <tr>
                        <td>Unit</td>
                        <td>:</td>
                        <td>{{ $productOrder->user->unit_name }}</td>
                    </tr>
                    <tr>
                        <td>No. Telp</td>
                        <td>:</td>
                        <td>{{ $productOrder->user->mobile_phone }}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>:</td>
                        <td>{{ $productOrder->user->email }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="column">
            <table>
                <tbody>
                    <tr>
                        <td>No. Transaksi</td>
                        <td>:</td>
                        <td>{{ $productOrder->invoice_no }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Pengambilan</td>
                        <td>:</td>
                        <td>{{ \App\Helpers\Helper::tanggal($productOrder->pickup_date_schedule) . ($productOrder->alt_pickup_date_schedule ? " atau " . \App\Helpers\Helper::tanggal($productOrder->alt_pickup_date_schedule) : null)  }}</td>
                    </tr>
                    <tr>
                        <td>Waktu Pengambilan</td>
                        <td>:</td>
                        <td>{{ \Carbon\Carbon::parse($productOrder->pickup_start_time)->format('H:i') . " - " . \Carbon\Carbon::parse($productOrder->pickup_end_time)->format('H:i') }}</td>
                    </tr>
                    <tr>
                        <td>Tempat Pengambilan</td>
                        <td>:</td>
                        <td>{{ $productOrder->pickup_location }}</td>
                    </tr>
                    <tr>
                        <td>Catatan Pengambilan</td>
                        <td>:</td>
                        <td>{{ $productOrder->pickup_notes }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <br/>
    <table width="100%" class="table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Varian</th>
                <th>Stand</th>
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
                <td>{{ $productOrderDetail->product->stand->name }}</td>
                <td align="right">{{ \App\Helpers\PriceHelper::rupiah($productOrderDetail->price) }}</td>
                <td>{{ $productOrderDetail->quantity }}</td>
                <td align="right">{{ \App\Helpers\PriceHelper::rupiah($productOrderDetail->total_price) }}</td>
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
            @endif
        </tbody>
    </table>
    <div style="margin-top: 150px">
        <table width="100%">
            <tr>
                <td width="70%" style="vertical-align: top">
                    <div style="color: #666">Notes:
                    <ol>
                        <li>
                                Pengambilan harus sesuai dengan jadwal yang telah ditentukan.
                        </li>
                        <br/>
                        <li>
                                Pastikan ukuran/pesanan yang anda pilih sudah sesuai.
                                <strong>
                                    Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.
                                </strong>
                        </li>
                    </ol>
                    </div>
                </td>
                <td align="right">
                    <img src="data:image/png;base64, {!! base64_encode(QrCode::size(150)->generate(route('admin.product-order-pickup.qr-result', $productOrder->id ))) !!}" alt="">
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
