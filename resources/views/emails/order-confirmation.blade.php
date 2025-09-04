@component('mail::message', ['user' => $user, 'order' => $order, 'unit' => $unit, 'header' => @$header])
<div class="email-verification-content">
    <p>Dear {{ $user->name }},</p>
    <p>Pesanan Anda dengan nomor tagihan <b>#{{ $order->invoice_no }}</b> sudah berhasil dilakukan pada {{ \App\Helpers\Helper::tanggalJam($order['created_at']) }} dengan metode pembayaran VA {{ (!empty($order->payment_option) ? $order->payment_option : \App\Helpers\PriceHelper::paymentInfo($unit)['bank']) }}.</p>
    <p>Pesanan Anda akan segera kami proses setelah pembayaran terkonfirmasi otomatis oleh sistem. Mohon lakukan pembayaran sejumlah <b>{{ \App\Helpers\PriceHelper::rupiah($order->grand_total) }}</b> dalam jangka waktu <b>{{ $order->expired_days_remaining }}x24 jam</b>. Jika anda tidak melakukan pembayaran hingga tanggal <b>{{ \App\Helpers\Helper::tanggalJam($order->expired_at) }}</b>, maka pesanan Anda akan kami batalkan secara otomatis.</p>

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

    <p>CARA MELAKUKAN PEMBAYARAN<br/>
    Silahkan bayar melalui virtual account berikut: {{ (!empty($order->virtual_account_number) ? $order->virtual_account_number : $user->seragam_virtual_account_number) }}<br/>
    Status pembayaran akan terkonfirmasi otomatis oleh sistem, <strong>tanpa perlu mengirim bukti melalui WA atau mengupload secara manual</strong>.<br/>
    <b>Catatan:</b> Jika menggunakan voucher atau nilai pembayaran Rp0, Anda wajib mengupload bukti screenshot nominal Rp0 di halaman pemesanan seragam karena sistem tidak dapat memverifikasi otomatis untuk transaksi ini.<br/>
    Disarankan melakukan pembayaran melalui Bank VA BCA untuk memastikan proses verifikasi berjalan lancar.
    </p>

    <p>Terimakasih atas pemesanan dan kerjasama Anda.<br/>
    Salam Hormat<br/>
    Panitia SPMB Online Kampus Santa Maria</p>
</div>
@endcomponent