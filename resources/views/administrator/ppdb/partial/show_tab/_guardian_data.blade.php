<div class="row">
    <div class="col-md-6">
        <h4 class="fw-bold text-success mb-3"><i class="fa fa-male me-2"></i> Data Wali </h4>

        <label class="small text-muted">Nama Wali</label>
        <p class="mb-3 border-bottom pb-2">{{ @$wali->name ?: '-' }}</p>

        <label class="small text-muted">Tempat, Tanggal Lahir</label>
        <p class="mb-3 border-bottom pb-2">{{ @$wali['place_of_birth'] .', '. @$wali['date_of_birth'] ?: '-' }}</p>

        <label class="small text-muted">No. Telp</label>
        <p class="mb-3 border-bottom pb-2 text-uppercase">{{ @$wali->phone ?: '-' }}</p>

        <label class="small text-muted">Agama</label>
        <p class="mb-3 border-bottom pb-2 text-uppercase">{{ @$wali->religion ?: '-' }}</p>

        <label class="small text-muted">Pendidikan</label>
        <p class="mb-3 border-bottom pb-2 text-uppercase">{{ @$wali->education ?: '-' }}</p>

        <label class="small text-muted">Pekerjaan</label>
        <p class="mb-3 border-bottom pb-2 text-uppercase">{{ @$wali->job ?: '-' }}</p>

        <label class="small text-muted">Penghasilan</label>
        <p class="mb-3 border-bottom pb-2 text-uppercase">{{ @$wali->salary ?: '-' }}</p>

        <label class="small text-muted">Kewarganegaraan</label>
        <p class="mb-3 border-bottom pb-2 text-uppercase">{{ @$wali->country ?: '-' }}</p>

        <label class="small text-muted">Kota</label>
        <p class="mb-3 border-bottom pb-2 text-uppercase">{{ @$wali->city ?: '-' }}</p>

        <label class="small text-muted">Provinsi</label>
        <p class="mb-3 border-bottom pb-2 text-uppercase">{{ @$wali->region ?: '-' }}</p>

        <label class="small text-muted">Alamat</label>
        <p class="mb-3 border-bottom pb-2 text-uppercase">{{ @$wali->address ?: '-' }}</p>

    </div>
</div>
