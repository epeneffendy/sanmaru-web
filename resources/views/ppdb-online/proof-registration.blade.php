<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bukti Pendaftaran PPDB Online</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        h1,h3,h4,h5 {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 5px;
        }
        .container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    {!! $user_ppdb->unit->header_info !!}
    <div class="container">
        <p><b>Nomor Pendaftaran: {{ $user_ppdb->register_number }}</b></p>
        <p>1. Nama Siswa: {{ $user_ppdb->name }}</p>
        <p>2. Alamat: {{ $user_ppdb->address }}</p>
        <p>3. Nomor HP (WA): {{ $user['mobile_phone'] }}</p>
        <p>4. Asal Sekolah: {{ $user_ppdb->origin_school }}</p>
    </div>
</body>
</html>
