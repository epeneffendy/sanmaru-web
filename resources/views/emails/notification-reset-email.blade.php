@component('mail::message', ['user' => $user, 'unit_name' => $unit_name, 'notification' => $notification, 'header' => @$header])
    <div class="email-verification-content">
        <p>Hallo Bapak/Ibu,</p>
        <p>Proses administrasi penerimaan atas nama {{ $user->name }} telah dilakukan reset surat pernyataan oleh admin.
            Reset ini dilakukan karena:</p>

        <ul>
            <li>Dokumen yang sebelumnya diunggah tidak sesuai, *atau*</li>
            <li>Terdapat perubahan skema pembayaran (lunas ↔ cicilan).</li>
        </ul>

        <p>Silakan *unduh ulang surat pernyataan terbaru, kemudian **unggah kembali melalui sistem* untuk melanjutkan finalisasi penerimaan. <br/>
            Terima kasih atas perhatian dan kerja sama Bapak/Ibu.
            <br/>
            Salam Hormat<br/>
            [ADMIN SPMB]</p>
    </div>
@endcomponent
