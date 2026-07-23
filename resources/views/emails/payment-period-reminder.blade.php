@component('mail::message', ['user' => $student->user, 'unit_name' => $unit_name, 'header' => 'Pemberitahuan Periode Uang Kegiatan'])
<div class="email-verification-content">
    <p>Yth. Orang Tua/Wali dari <b>{{ $student->name }}</b><br />
            Siswa {{ $unit ? $unit->name : $unit_name }},</p>
    <br>
    <p>Kami informasikan bahwa periode pembayaran <b>Uang Kegiatan</b> pada periode pendaftaran <b>{{ $student->period->name }}</b> telah dibuka mulai tanggal <b>{{ \Carbon\Carbon::parse($periode->start)->translatedFormat('d F Y') }}</b> hingga <b>{{ \Carbon\Carbon::parse($periode->end)->translatedFormat('d F Y') }}</b>.</p>

    <p>Silakan login ke dalam Sistem Sanmaru dan segera melakukan pembayaran sebelum periode tersebut ditutup.</p>
{{-- 
    @component('mail::button', ['url' => config('app.url')])
        Login Sistem Sanmaru
    @endcomponent --}}

    <p>Terima kasih atas perhatian dan kerja sama Bapak/Ibu.</p>
    <br/>
    <p>Salam Hormat<br/>
    [Admin Keuangan SANMARU]</p>
</div>
@endcomponent
