@component('mail::message', ['user' => $user])
<div class="email-forgot-password-body">
    <p>{{ $user->username }},</p>
    <p>Anda baru saja meminta untuk melakukan reset ulang password untuk SPMB Online Kampus Santa Maria. Untuk melanjutkan melakukan reset password,</p>
    <p>silahkan klik link berikut untuk mengganti password :</p>
    <a href="{{ route('ppdb.request-password', ['id' => $user->remember_token]) }}">{{ route('ppdb.request-password', ['token' => $user->remember_token]) }}</a>
    <p>Bila Anda tidak merasa mengirimkan permintaan untuk melakukan reset password, silahkan hiraukan email ini.</p>
    <p>Terima kasih atas perhatian Anda.</p>
    <p>Hormat kami,<br/>
    Panitia SPMB Online Kampus Santa Maria</p>
</div>
@endcomponent
