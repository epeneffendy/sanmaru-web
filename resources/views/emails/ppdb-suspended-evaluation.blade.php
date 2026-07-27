@component('mail::message', [
    'header' => @$header,
])
    <div class="email-verification-content">
        <p>Yth. Orang Tua / Wali dari <b>{{ $data->student_name }}</b><br />
            Siswa {{ $data->unit_name ?? 'Kampus Santa Maria' }},</p>

        @if ($status === 'closed')
            <p>Kami menginformasikan hasil evaluasi penangguhan pendaftaran PPDB untuk calon siswa atas nama <b>{{ $data->student_name }}</b>.</p>
            <div style="background-color: #e8f5e9; border-left: 4px solid #198754; padding: 15px; margin-bottom: 25px; border-radius: 4px;">
                <h4 style="margin: 0 0 10px 0; color: #146c43; font-size: 15px;">Hasil Evaluasi: Toleransi Pembayaran</h4>
                <p style="margin: 0; font-size: 14px; color: #146c43; line-height: 1.5;">
                    Calon siswa diberikan <b>Toleransi Pembayaran</b> untuk dapat melanjutkan proses pembayaran ulang pada sistem PPDB dan segera melakukan pembayaran.<br/>
                    Silahkan lakukan penyelesaian pembayaran sebelum <b>{{ $data->payment_tolerance_expired_at ? \Carbon\Carbon::parse($data->payment_tolerance_expired_at)->locale('id')->translatedFormat('d F Y H:i') : '-' }} WIB</b>.
                </p>
            </div>
        @else
            <p>Kami menginformasikan hasil evaluasi penangguhan pendaftaran PPDB untuk calon siswa atas nama <b>{{ $data->student_name }}</b>.</p>
            <div style="background-color: #f8d7da; border-left: 4px solid #dc3545; padding: 15px; margin-bottom: 25px; border-radius: 4px;">
                <h4 style="margin: 0 0 10px 0; color: #842029; font-size: 15px;">Hasil Evaluasi: Pendaftaran Ulang</h4>
                <p style="margin: 0; font-size: 14px; color: #842029; line-height: 1.5;">
                    Calon siswa disarankan untuk melakukan <b>Pendaftaran Ulang</b> pada periode pendaftaran berikutnya yang tersedia.
                </p>
            </div>
        @endif

        <table width="100%" border="0" cellspacing="0" cellpadding="0"
            style="background-color: #f8f9fa; border-radius: 8px; border: 1px solid #e9ecef; margin-bottom: 25px;">
            <tr>
                <td width="40%" style="padding: 12px 15px; border-bottom: 1px solid #e9ecef; font-size: 14px; color: #6c757d;">Nomor Registrasi</td>
                <td width="60%" style="padding: 12px 15px; border-bottom: 1px solid #e9ecef; font-size: 15px; font-weight: 600;">
                    {{ $data->register_number ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 12px 15px; border-bottom: 1px solid #e9ecef; font-size: 14px; color: #6c757d;">Nama Calon Siswa</td>
                <td style="padding: 12px 15px; border-bottom: 1px solid #e9ecef; font-size: 15px; font-weight: 600;">
                    {{ $data->student_name }}</td>
            </tr>
            <tr>
                <td style="padding: 12px 15px; border-bottom: 1px solid #e9ecef; font-size: 14px; color: #6c757d;">Unit</td>
                <td style="padding: 12px 15px; border-bottom: 1px solid #e9ecef; font-size: 15px;">{{ $data->unit_name ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 12px 15px; font-size: 14px; color: #6c757d;">Periode</td>
                <td style="padding: 12px 15px; font-size: 15px;">{{ $data->period_name ?? '-' }}</td>
            </tr>
        </table>
    </div>
@endcomponent
