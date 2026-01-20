<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Detail Transaksi </title>
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
            {{-- <td valign="top" style="width: 1%;" ><img src="{{ public_path('img/logo-serviam.png') }}" alt="logo serviam" width="75"/></td> --}}
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
        <br>
        <h3 style="text-align:center; font-weight:bold; font-size:25px">Surat Tanda Terima Seragam</h3>

        <p style="font-size:12px">{{ $day .', '. $date }} telah diterima seragam untuk unit {{ $data->unit->name }} dengan rincian sebagai berikut :</p>

        <table width="100%" class="table-bordered">
            <tr>
                <td>No.</td>
                <td>Nama Siswa</td>
                <td>Nama Product</td>
                <td>Ukuran Seragam</td>
                <td>Jumlah</td>
            </tr>
            @if(count($orders))
                @foreach($orders as $ind => $item)
                    <tr>
                        <td>{{ $ind + 1 }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->size }}</td>
                        <td>{{ $item->qty }}</td>
                    </tr>
                @endforeach
            @endif

        </table>
        <p style="font-size:12px">Barang tersebut telah diterima dengan lengkap dan dengan keadaan baik, untuk selanjutkan akan didistribusikan kepada siswa yang bersangkutan. Terimakasih</p>
    </div>


    <table width="100%">
        <tr>
            <td style="text-align: center"></td>
            <td style="text-align: center">Surabaya, {{ $day .' '. $date }}</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td style="text-align: center">Pengirim</td>
            <td style="text-align: center">Penerima</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td style="text-align: center">Admin Yayasan</td>
            <td style="text-align: center">(.............................)</td>
        </tr>

    </table>
</body>
</html>
