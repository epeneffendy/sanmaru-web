@component('mail::message', ['user' => $user, 'ppdbUser' => $ppdbUser])
<div class="email-verification-content">
    <p>Selamat Datang di SPMB Online Kampus Santa Maria</p>
    <p>Terimakasih sudah melakukan pembayaran melalui <b> BANK {{ (!empty($ppdbUser->payment_option) ? $ppdbUser->payment_option : \App\Helpers\PriceHelper::paymentInfo($unit)['bank']) }} </b>. Pembayaran telah kami terima senilai <b>{{ \App\Helpers\PriceHelper::registration($ppdbUser, true) }} </b> pada <b>{{ \App\Helpers\Helper::hariTanggalJam($ppdbUser->payment_date) }}</b>.
        Berikut informasi detail registrasi Anda:</p>
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
    <p>Silakan melakukan lakukan pengisisan data administrasi siswa dengan melakukan login email: <strong>{{ $user->email }}</strong></p>
    <p><a href="{{ route('ppdb.verify', ['v' => $user->register_token])}}">{{ route('ppdb.verify', ['v' => $user->register_token])}}</a></p>
    @if ($ppdbUser->unit->helpdesk)
    <br/>{!! $ppdbUser->unit->helpdesk !!}<br/>
    @endif

    <p style="color: red">*Note : Jika mengalami kendala bisa menghubungi Admin SPMB Online Kampus Santa Maria.</p>

    <p>Terimakasih<br/>
    Salam Hormat<br/>
    Panitia SPMB Online Kampus Santa Maria</p>
</div>
@endcomponent
