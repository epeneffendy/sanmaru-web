@component('mail::message', ['user' => $user, 'ppdbUser' => $ppdbUser])
<div class="email-verification-content">
    <p>Selamat Datang di SPMB Online Kampus Santa Maria</p>
    <p>Bukti pembayaran formulir Anda sudah diverifikasi. Anda dapat login kembali serta melengkapi dokumen formulir SPMB Online Kampus Santa Maria melalui link berikut:</p>
    <p><a href="{{ route('ppdb.login')}}">{{ route('ppdb.login')}}</a></p>
    <p>Terimakasih<br/>
    Salam Hormat<br/>
    Panitia SPMB Online Kampus Santa Maria</p>
</div>
@endcomponent
