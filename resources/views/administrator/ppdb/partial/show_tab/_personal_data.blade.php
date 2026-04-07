<div class="row">
    <div class="col-md-6 mb-3">
        <label class="text-muted small fw-bold text-uppercase">Nama Panggilan</label>
        <p class="border-bottom pb-2">{{ $data->nama_panggilan ?: '-' }}</p>
    </div>

    <div class="col-md-6 mb-3">
        <label class="text-muted small fw-bold text-uppercase">NIK Siswa</label>
        <p class="border-bottom pb-2">{{ $data->nik_siswa ?: '-' }}</p>
    </div>
    <div class="col-md-6 mb-3">
        <label class="text-muted small fw-bold text-uppercase">Tempat, Tanggal Lahir</label>
        <p class="border-bottom pb-2 text-uppercase">{{ $data->place_of_birth }}, {{ $data->date_of_birth }}</p>
    </div>

    <div class="col-md-6 mb-3">
        <label class="text-muted small fw-bold text-uppercase">Jenis Kelamin</label>
        <p class="border-bottom pb-2 text-uppercase">{{ (@$data->gender =='male') ? 'Perempuan' : 'Laki-laki' }}</p>
    </div>

    <div class="col-md-6 mb-3">
        <label class="text-muted small fw-bold text-uppercase">Email</label>
        <p class="border-bottom pb-2">{{ @$data->email }}</p>
    </div>
    <div class="col-md-6 mb-3">
        <label class="text-muted small fw-bold text-uppercase">No.Telp</label>
        <p class="border-bottom pb-2">{{ @$data->user->mobile_phone }}</p>
    </div>

    <div class="col-md-6 mb-3">
        <label class="text-muted small fw-bold text-uppercase">Agama</label>
        <p class="border-bottom pb-2 text-uppercase">{{ @$data->religion }}</p>
    </div>
    <div class="col-md-6 mb-3">
        <label class="text-muted small fw-bold text-uppercase">Kewarganegaraan</label>
        <p class="border-bottom pb-2 text-uppercase">{{ @$data->country }}</p>
    </div>

    <div class="col-md-6 mb-3">
        <label class="text-muted small fw-bold text-uppercase">Kota</label>
        <p class="border-bottom pb-2 text-uppercase">{{ @$data->city }}</p>
    </div>
    <div class="col-md-6 mb-3">
        <label class="text-muted small fw-bold text-uppercase">Provinsi</label>
        <p class="border-bottom pb-2 text-uppercase">{{ @$data->region }}</p>
    </div>

    <div class="col-md-12 mb-3">
        <label class="text-muted small fw-bold text-uppercase">Alamat</label>
        <p class="border-bottom pb-2 text-uppercase">{{ $data->address }}, {{ $data->city }}</p>
    </div>
</div>
