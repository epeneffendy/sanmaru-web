 @component('mail::message', ['user' => $user, 'unit_name' => $unit_name, 'notification' => $notification, 'header' => @$header])
    <div class="email-verification-content">
        <p>Dear {{ $user->name }},</p>
        <br>
        <p>Hallo Bapak/Ibu,</p>
        <p>Proses administrasi penerimaan atas nama <b> {{ $user->name }} </b> telah dilakukan reset surat pernyataan oleh admin.
            Reset ini dilakukan karena:</p>

        <ul>
            <li>Dokumen yang sebelumnya diunggah tidak sesuai, <b>atau</b></li>
            <li>Terdapat perubahan skema pembayaran <b>(lunas ↔ cicilan)</b>.</li>
        </ul>

        <p>Silakan <b>unduh ulang surat pernyataan terbaru, kemudian unggah kembali melalui sistem </b>untuk melanjutkan finalisasi penerimaan. <br/>
            Terima kasih atas perhatian dan kerja sama Bapak/Ibu.
            <br/>
            Tambahan informasi dari Unit {{$unit_name}}:
            {!! nl2br(e($notification->data['body'])) !!}
            Salam Hormat<br/>
            [ADMIN SPMB]</p>
    </div>
@endcomponent
