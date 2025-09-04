@component('mail::message', ['user' => $user, 'unit' => $unit, 'order' => $order, 'header' => @$header])
<div class="email-verification-content">
    <p>Dear {{ $user->name }}<br/>Siswa Baru {{ $unit->name }},</p>
    <p>Pengambilan seragam dengan nomor tagihan <b>#{{ $order->invoice_no }}</b> dapat dilakukan pada:
    <p><strong>Waktu Pengambilan: {{ \App\Helpers\Helper::tanggal($order->pickup_date_schedule) . ($order->alt_pickup_date_schedule ? " atau " . \App\Helpers\Helper::tanggal($order->alt_pickup_date_schedule) : null) . " / " . \Carbon\Carbon::parse($order->pickup_start_time)->format('H:i') . " - " . \Carbon\Carbon::parse($order->pickup_end_time)->format('H:i') }}<br/>
    Tempat Pengambilan: {{ $order->pickup_location }}<br/>
    Catatan: {{ $order->pickup_notes }}<br/>
    </strong></p>
    <p><strong>Silahkan unduh dan tunjukkan lampiran berikut ini kepada petugas saat melakukan pengambilan seragam.</strong><br/>
    <p>Jika seragam tidak lengkap atau tidak sesuai dapat menghubungi yayasan dan mengisi pada link berikut <a href="https://bit.ly/KomplainSeragamSanmaru" target="_blank">bit.ly/KomplainSeragamSanmaru</a> <br/>

    <p>Terimakasih atas pemesanan dan kerjasama Anda.<br/>
    Salam Hormat<br/>
    Panitia SPMB Online Kampus Santa Maria</p>
</div>
@endcomponent
