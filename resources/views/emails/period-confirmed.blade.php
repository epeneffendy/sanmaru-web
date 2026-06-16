@component('mail::message', [
    'ppdb' => $ppdb,
    'user' => $user,
    'unit' => $unit,
    'header' => @$header,
])
    <div class="email-verification-content">
        <p>Yth. Orang Tua/Wali dari <b>{{ $ppdb->name }}</b><br />
            Siswa {{ $unit->name }},</p>

        <p>Selamat!,Kami informaskan bahwa berkas persyaratan pendafaran calon peserta didik baru yang di unggah telah
            berhasil <b>Diverifikasi</b> dan dinyatakan <b>Lengkap</b> oleh tim administasi kami.</p>

        <table width="100%" border="0" cellspacing="0" cellpadding="0"
            style="background-color: #f8f9fa; border-radius: 8px; border: 1px solid #e9ecef; margin-bottom: 25px;">
            <tr>
                <td width="40%"
                    style="padding: 12px 15px; border-bottom: 1px solid #e9ecef; font-size: 14px; color: #6c757d;">Nomor
                    Registrasi</td>
                <td width="60%"
                    style="padding: 12px 15px; border-bottom: 1px solid #e9ecef; font-size: 15px; font-weight: 600;">
                    {{ $ppdb->register_number }}</td>
            </tr>
            <tr>
                <td style="padding: 12px 15px; border-bottom: 1px solid #e9ecef; font-size: 14px; color: #6c757d;">Nama
                    Calon Siswa</td>
                <td style="padding: 12px 15px; border-bottom: 1px solid #e9ecef; font-size: 15px; font-weight: 600;">
                    {{ $ppdb->name }}</td>
            </tr>
            <tr>
                <td style="padding: 12px 15px; font-size: 14px; color: #6c757d;">Tujuan Unit</td>
                <td style="padding: 12px 15px; font-size: 15px;">{{ $ppdb->unit->name }}</td>
            </tr>
            <tr>
                <td style="padding: 12px 15px; font-size: 14px; color: #6c757d;">Periode Yang Disetujui</td>
                <td style="padding: 12px 15px; font-size: 15px;">{{ $ppdb->period->name }}</td>
            </tr>
        </table>

        <div
            style="background-color: #e8f5e9; border-left: 4px solid #198754; padding: 15px; margin-bottom: 25px; border-radius: 4px;">
            <h4 style="margin: 0 0 10px 0; color: #146c43; font-size: 15px;">Langkah Selanjutnya:</h4>
            <p style="margin: 0; font-size: 14px; color: #146c43; line-height: 1.5;">
                Silakan masuk ke dasbor pendaftaran untuk melihat instruksi lebih detail.
            </p>
        </div>
    @endcomponent
