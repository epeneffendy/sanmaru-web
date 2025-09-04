@component('mail::message', ['user' => $user, 'ppdbUser' => $ppdbUser])
<div class="email-verification-content">
    <p>Selamat Datang di PPDB Online Kampus Santa Maria</p>
    <p>Terimakasih sudah mendaftarkan <b> {{ $user->name }} </b> sebagai calon siswa di <b> {{ $ppdbUser->unit->name }} </b>. Berikut informasi detail registrasi Anda:</p>
    <ul><li>
    Nomor Registrasi: <b>{{ $ppdbUser->register_number }}</b>
    </li><li>
    Nama: <b>{{ $ppdbUser->name }}</b>
    </li><li>
    Email: <b>{{ $user->email }}</b>
    </li><li>
    Nomor Telepon: <b>{{ $user->mobile_phone }}</b>
    </li><li>
    Unit: <b>{{ $ppdbUser->unit->name }}</b>
    </li></ul>
    @if(env('PAYMENT_REGISTRATION_FORM') == true)
        <p>Silakan melakukan pembayaran formulir sebesar <b>{{ \App\Helpers\PriceHelper::registration($ppdbUser, true) }}</b> melalui {{ (!empty($ppdbUser->payment_option) ? $ppdbUser->payment_option : \App\Helpers\PriceHelper::paymentInfo($unit)['bank']) }} virtual account berikut:</p>

        <p>Kode Bank: <b>{{ (!empty($ppdbUser->payment_option) ? $ppdbUser->payment_option : \App\Helpers\PriceHelper::paymentInfo($unit)['bank']) }}</b><br/>
        Kode Virtual Account: <b>{{ (!empty($ppdbUser->payment_option) ? $ppdbUser->virtual_account_number : \App\Helpers\PriceHelper::virtualAccountNumber($ppdbUser))  }}</b></p>

        <p>Lakukan pembayaran sebelum {{ \App\Helpers\Helper::hariTanggalJam($ppdbUser->expired_at) }}, jika sudah melakukan pembayaran Anda dapat login kembali di SPMB Online Kampus Santa Maria melalui link berikut:</p>
        <p>Anda dapat melakukan login dengan username: <strong>{{ $user->username }}</strong></p>
        <p><a href="{{ route('ppdb.verify', ['v' => $user->register_token])}}">{{ route('ppdb.verify', ['v' => $user->register_token])}}</a></p>

        <p style="color: red">*Note : Disarankan pembayaran melalui BANK BCA. Jika menggunakan bank lain pastikan nominal harus sesuai agar pendaftaran bisa terproses.</p>
    @else
        <p>Silakan melakukan pembayaran formulir sebesar <b>{{ \App\Helpers\PriceHelper::registration($ppdbUser, true) }}</b> melalui {{ \App\Helpers\PriceHelper::paymentInfo($ppdbUser->unit)['bank'] }} virtual account berikut:</p>

        <p>Kode Bank: <b>{{ \App\Helpers\PriceHelper::paymentInfo($ppdbUser->unit)['kode_bank'] }}</b><br/>
        Kode Virtual Account: <b>{{ \App\Helpers\PriceHelper::virtualAccountNumber($ppdbUser) }}</b></p>

        <p>Jika sudah melakukan pembayaran Anda dapat login kembali serta upload bukti pembayaran formulir SPMB Online Kampus Santa Maria melalui link berikut:</p>
        <p>Anda dapat melakukan login dengan username: <strong>{{ $user->username }}</strong></p>
        <p><a href="{{ route('ppdb.verify', ['v' => $user->register_token])}}">{{ route('ppdb.verify', ['v' => $user->register_token])}}</a></p>
    @endif
    @if ($ppdbUser->unit->helpdesk)
    <br/>{!! $ppdbUser->unit->helpdesk !!}<br/>
    @endif

    <p>Terimakasih<br/>
    Salam Hormat<br/>
    Panitia SPMB Online Kampus Santa Maria</p>
</div>
@endcomponent
