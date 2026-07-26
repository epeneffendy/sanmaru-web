@component('mail::message', ['user' => $ppdbUser->user, 'unit_name' => $unit_name, 'header' => 'Pemberitahuan Tunggakan ' . $typeLabel])
    <div class="email-verification-content">
        <p>Yth. Orang Tua/Wali dari <b>{{ $ppdb->name }}</b><br />
            Siswa {{ $unit->name ?? '-' }},</p>
        <br>
        <p>Kami ingin menginformasikan bahwa Anda memiliki tunggakan cicilan <b>{{ $typeLabel }}</b> untuk siswa atas nama <b>{{ $ppdbUser->name }}</b> yang telah jatuh tempo pada <b>{{ \Carbon\Carbon::parse($detail->plan_date)->translatedFormat('d F Y') }}</b>.</p>
        
        <p>Segera lakukan pembayaran sesuai dengan rencana bayar yang telah ditentukan. Berikut adalah rincian tagihan Anda:</p>
        <ul>
            <li><b>Keterangan:</b> {{ $detail->installment_number == 0 ? 'DP' : 'Cicilan Ke-' . $detail->installment_number }}</li>
            <li><b>Nominal Tagihan:</b> Rp {{ number_format($detail->nominal - $detail->amount_paid, 0, ',', '.') }}</li>
            <li><b>Tanggal Jatuh Tempo:</b> {{ \Carbon\Carbon::parse($detail->plan_date)->translatedFormat('d F Y') }}</li>
        </ul>

        <p>Mohon untuk segera melakukan pembayaran agar proses administrasi dapat berjalan lancar. Jika Bapak/Ibu sudah melakukan pembayaran, silakan abaikan email ini.</p>
        
        <br>
        <p>Terima kasih atas perhatian dan kerja sama Bapak/Ibu.</p>
        <p>Salam Hormat,<br>
        [ADMIN SPMB] {{ $unit_name }}</p>
    </div>
@endcomponent
