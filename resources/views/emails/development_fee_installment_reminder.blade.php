@component('mail::message', ['user' => $ppdbUser->user, 'unit_name' => $unit_name, 'header' => 'Pengingat Jatuh Tempo Uang Pengembangan'])
    <div class="email-verification-content">
        <p>Yth. Orang Tua/Wali dari <b>{{ $ppdb->name }}</b><br />
            Siswa {{ $unit->name }},</p>
        <br>
        <p>Kami ingin mengingatkan bahwa cicilan Uang Pengembangan untuk calon siswa atas nama <b>{{ $ppdbUser->name }}</b> akan segera jatuh tempo dalam waktu 7 hari ke depan.</p>
        
        <p>Berikut adalah rincian tagihan cicilan Anda:</p>
        <ul>
            <li><b>Cicilan Ke:</b> {{ $detail->installment_number }}</li>
            <li><b>Nominal Cicilan:</b> Rp {{ number_format($detail->nominal, 0, ',', '.') }}</li>
            <li><b>Tanggal Jatuh Tempo:</b> {{ \Carbon\Carbon::parse($detail->plan_date)->translatedFormat('d F Y') }}</li>
            @if($detail->virtual_account)
            <li><b>Nomor Virtual Account:</b> {{ $detail->virtual_account }}</li>
            @endif
        </ul>

        <p>Mohon untuk segera melakukan pembayaran sebelum tanggal jatuh tempo di atas agar proses administrasi dapat berjalan lancar. Jika Bapak/Ibu sudah melakukan pembayaran, silakan abaikan email ini.</p>
        
        <br>
        <p>Terima kasih atas perhatian dan kerja sama Bapak/Ibu.</p>
        <p>Salam Hormat,<br>
        [ADMIN SPMB] {{ $unit_name }}</p>
    </div>
@endcomponent
