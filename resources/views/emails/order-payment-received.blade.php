@component('mail::message', ['user' => $user, 'order' => $order, 'unit' => $unit, 'header' => @$header])
<div class="email-verification-content">

    <p>Dear {{ $user->name }}<br/>Siswa Baru {{ $unit->name }},</p>
    <p>Pembayaran Anda dengan nomor tagihan <b>#{{ $order->invoice_no }}</b> sudah berhasil terkonfirmasi pada {{ \App\Helpers\Helper::tanggalJam($order['payment_confirmed_date']) }} dengan metode pembayaran VA BANK BCA. Gunakan pesan ini untuk pengambilan seragam</p>

    <p>Rincian Tagihan Anda:<br/>
    @component('mail::table')
    |**No**  |**Nama Produk**           |**Ukuran**|**Jumlah Pesanan**|**Harga**     |**Subtotal**  |
    |--------|--------------------------|:--------:|-----------------:|-------------:|-------------:|
    @foreach ($order->productOrderDetails as $key => $detail)
    |{{$key+1}}|{{$detail->product->name}}|{{$detail->productDetail->size}}|{{$detail->quantity}}|{{\App\Helpers\PriceHelper::rupiah($detail->price)}}|{{\App\Helpers\PriceHelper::rupiah($detail->total_price)}}|
    @endforeach
    @endcomponent
    </p>

    <p><b>Total:</b> {{ \App\Helpers\PriceHelper::rupiah($order->grand_total_gross) }}<br/>
    @if ($order->voucher)
    @php ($voucher = json_decode($order->voucher, TRUE))
    <b>Voucher: {{ $voucher['code'] }}</b> - ({{ \App\Helpers\PriceHelper::rupiah($order->discount_total) }} off)<br/>
    {{ nl2br($voucher['note']) }}
    @endif
    <b>Total yang harus dibayarkan:</b> {{ \App\Helpers\PriceHelper::rupiah($order->grand_total) }}</p>

    <p>Terimakasih atas pemesanan dan kerjasama Anda.<br/>
    Salam Hormat<br/>
    Panitia SPMB Online Kampus Santa Maria</p>
</div>
@endcomponent
