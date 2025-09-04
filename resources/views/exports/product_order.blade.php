<table>
    <thead>
    <tr>
        <th>No</th>
        @foreach ($headings as $heading)
            <th>{{ $heading }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @php $nomor = 1; @endphp
    @foreach($productOrders as $key => $productOrder)
        @php
            $rowspan = 1;
        @endphp
        <tr>
        @if (isset($merged[$key]))
            @php
                $rowspan = ($merged[$key] + 1) - $key;
            @endphp
            <td rowspan="{{ $rowspan }}">{{ $nomor }}</td>
            <td rowspan="{{ $rowspan }}">{{ $productOrder['invoice_no'] }}</td>
            <td rowspan="{{ $rowspan }}">{{ $productOrder['no_va'] }}</td>
            <td rowspan="{{ $rowspan }}">{{ $productOrder['payment_option'] }}</td>
            <td rowspan="{{ $rowspan }}">{{ $productOrder['no_pendaftaran'] }}</td>
            <td rowspan="{{ $rowspan }}">{{ $productOrder['nama_anak'] }}</td>
            <td rowspan="{{ $rowspan }}">{{ $productOrder['unit'] }}</td>
        @endif
            <td>{{ $productOrder['nama_anak'] }}</td>
            <td>{{ $productOrder['unit'] }}</td>
            <td>{{ $productOrder['nama_product'] }}</td>
            <td>{{ $productOrder['ukuran'] }}</td>
            <td>{{ $productOrder['nama_vendor'] }}</td>
            <td>{{ $productOrder['jumlah'] }}</td>
            <td>{{ $productOrder['harga'] }}</td>
            <td>{{ $productOrder['jumlah_pesanan_harga'] }}</td>
        @if (isset($merged[$key]))
            <td rowspan="{{ $rowspan }}">{{ $productOrder['jumlah_pesanan'] }}</td>
            <td rowspan="{{ $rowspan }}">{{ $productOrder['total_harga_pesanan'] }}</td>
            <td rowspan="{{ $rowspan }}">{{ $productOrder['voucher_code'] }}</td>
            <td rowspan="{{ $rowspan }}">{{ $productOrder['total_voucher'] }}</td>
            <td rowspan="{{ $rowspan }}">{{ $productOrder['grand_total'] }}</td>
            <td rowspan="{{ $rowspan }}">{{ $productOrder['status'] }}</td>
            <td rowspan="{{ $rowspan }}">{!! $productOrder['status_pembayaran'] !!}</td>
            <td rowspan="{{ $rowspan }}">{{ $productOrder['tanggal'] }}</td>
            @php $nomor++; @endphp
        @endif
        </tr>
    @endforeach
    </tbody>
</table>
