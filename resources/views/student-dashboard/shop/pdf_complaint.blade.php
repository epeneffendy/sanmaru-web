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
<table width="100%" style="border-bottom: 1px solid black">
    <tr>
        <td valign="top" style="width: 1%;"><img src="{{ public_path('img/logo-serviam.png') }}" alt="logo serviam"
                                                 width="75"/></td>
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
                <td>{{ $user->student->name }}</td>
            </tr>
            <tr>
                <td>Unit</td>
                <td>:</td>
                <td>{{ $user->ppdb->unit->name }}</td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>:</td>
                <td>{{ $user->student->class->name ?? '' }}</td>
            </tr>
            <tr>
                <td>NIS</td>
                <td>:</td>
                <td>{{ $user->student->nis }}
                <td>
            </tr>
            <tr>
                <td>No. Telp</td>
                <td>:</td>
                <td>{{ $user->mobile_phone }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>:</td>
                <td>{{ $user->email }}</td>
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
                <td>{{ \App\Helpers\Helper::tanggal($complaintOrder->date_pickup) }}</td>
            </tr>

            <tr>
                <td>Tempat Pengambilan</td>
                <td>:</td>
                <td>{{ $complaintOrder->location_pickup }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<br/>
<hr>
<h4 style="margin: 3px 0px"><strong>DETAIL KOMPLAIN</strong></h4>
<table>
    <tbody>
    <tr>
        <td>Tanggal Komplain</td>
        <td>:</td>
        <td>{{ \Carbon\Carbon::parse($complaintOrder->created_at)->format('d-m-Y H:i:s') }}</td>
    </tr>

    <tr>
        <td>Product</td>
        <td>:</td>
        <td>{{ $complaintOrder->product->name . " (Size : ". $complaintOrder->productDetail->size . ")" }}</td>
    </tr>

    <tr>
        <td>Alasan</td>
        <td>:</td>
        <td>{{ $complaintOrder->complaintCategory->name }}</td>
    </tr>

    <tr>
        <td>Keterangan</td>
        <td>:</td>
        <td>{{ $complaintOrder->description ??  '-' }}</td>
    </tr>
    </tbody>
</table>

<div style="margin-top: 150px">
    <table width="100%">
        <tr>
            <td width="70%" style="vertical-align: top">
                <div style="color: #666">Notes:
                    <ol>
                        <li>
                            Pengambilan harus sesuai dengan jadwal yang telah ditentukan. Jika berhalangan hadir, mohon
                            menghubungi admin seragam yayasan di nomor +62 812-3201-3008 (Whatsapp) atau 0315673967
                            (Telp.).
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
{{--                <img--}}
{{--                    src="data:image/png;base64, {!! base64_encode(QrCode::size(150)->generate(route('admin.product-order-pickup.qr-result', $productOrder->id ))) !!}"--}}
{{--                    alt="">--}}
            </td>
        </tr>
    </table>
</div>
</body>
</html>
