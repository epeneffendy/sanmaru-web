@component('mail::message', ['user' => $user, 'unit' => $unit, 'header' => @$header, 'title' => $title])
    <div class="email-verification-content">

        <p>Yth. Orang Tua/Wali dari <b>{{ $user->name }}</b><br />
            Siswa {{ $unit->name }},</p>

        <p>Kami informasikan bahwa pembayaran <b>{{ $header }}</b> dengan Invoice
            <b>#{{ $tagihan->invoice_number }}</b> telah kami
            terima dan
            berhasil diverifikasi oleh
            sistem
            kami. Berikut adalah rincian pembayaran anda :
        </p>

        <table width="100%" border="0" cellspacing="0" cellpadding="0"
            style="background-color: #f8f9fa; border-radius: 8px; border: 1px solid #e9ecef; margin-bottom: 25px;">
            <tr>
                <td width="40%"
                    style="padding: 12px 15px; border-bottom: 1px solid #e9ecef; font-size: 14px; color: #6c757d;">Nomor
                    Registrasi</td>
                <td width="60%"
                    style="padding: 12px 15px; border-bottom: 1px solid #e9ecef; font-size: 15px; font-weight: 600;">
                    {{ $user->register_number }}
                </td>
            </tr>
            <tr>
                <td style="padding: 12px 15px; border-bottom: 1px solid #e9ecef; font-size: 14px; color: #6c757d;">Nama
                    Siswa</td>
                <td style="padding: 12px 15px; border-bottom: 1px solid #e9ecef; font-size: 15px; font-weight: 600;">
                    {{ $user->name }}
                </td>
            </tr>
            <tr>
                <td style="padding: 12px 15px; border-bottom: 1px solid #e9ecef; font-size: 14px; color: #6c757d;">Tanggal
                    Pembayaran</td>
                <td style="padding: 12px 15px; border-bottom: 1px solid #e9ecef; font-size: 15px;">
                    {{ $tagihan->payment_date }}</td>
            </tr>
            <tr>
                <td style="padding: 12px 15px; border-bottom: 1px solid #e9ecef; font-size: 14px; color: #6c757d;">Metode
                    Pembayaran</td>
                <td style="padding: 12px 15px; border-bottom: 1px solid #e9ecef; font-size: 15px;">Virtual Account
                    {{ $tagihan->payment_option }}</td>
            </tr>
            <tr>
                <td style="padding: 15px; font-size: 14px; color: #6c757d; font-weight: 600;">Total Dibayar</td>
                <td style="padding: 15px; font-size: 18px; font-weight: bold; color: #198754;">Rp
                    {{ number_format($tagihan->total_payment) }}</td>
            </tr>
        </table>

        <p><b>Silahkan buka menu Keuangan dan Download serta Upload Surat Pernyataan, agar dapat melanjutkan ketahap
                berikutnya.</b></p>

        <p style="margin-top: 20px;">Email ini merupakan tanda bukti pembayaran administrasi yang sah. Harap simpan email
            ini untuk keperluan Anda di kemudian hari.</p>

        <p>Terima kasih atas kepercayaan dan kerjasama Bapak/Ibu.<br /><br />
            Salam Hormat<br />
            <b>Panitia SPMB Kampus Santa Maria</b>
        </p>
    </div>
@endcomponent
