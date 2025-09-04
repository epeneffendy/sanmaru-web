@component('mail::message', ['user' => $user, 'ppdbUser' => $ppdbUser])
<div class="email-verification-content">
    <p>Hi {{ $ppdbUser->name }}, Selamat kamu sekarang menjadi siswa di <b>{{ $ppdbUser->unit->name }}</b></p>
    <p>Anda dapat login pada akun siswa anda melalui link <a href="{{ route('login')}}">{{ route('login')}}</a></p>
    <p>Berikut detail login anda :</p>
    <p>Username : <b>{{ $ppdbUser->user->username }}</b></p>
    <p>Email : <b>{{ $ppdbUser->user->email }}</b></p>
    <p></p>
    <p>Pastikan akun anda terjaga dengan aman.</p>
    <p>Terimakasih<br/><br/>
    Yayasan Paratha Bhakti</p>
</div>
@endcomponent
