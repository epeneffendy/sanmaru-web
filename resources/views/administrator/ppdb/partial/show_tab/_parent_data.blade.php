<div class="row">
    <div class="col-md-6">
        <h4 class="fw-bold text-success mb-3"><i class="fa fa-male me-2"></i> Data Ayah </h4>

        <label class="small text-muted">Nama Ayah</label>
        <p class="mb-3 border-bottom pb-2">{{ @$dad->name ?: '-' }}</p>

        <label class="small text-muted">Tempat, Tanggal Lahir</label>
        <p class="mb-3 border-bottom pb-2">{{ @$dad['place_of_birth'] .', '. @$dad['date_of_birth'] ?: '-' }}</p>

        <label class="small text-muted">No. Telp</label>
        <p class="mb-3 border-bottom pb-2 text-uppercase">{{ @$dad->phone ?: '-' }}</p>

        <label class="small text-muted">Agama</label>
        <p class="mb-3 border-bottom pb-2 text-uppercase">{{ @$dad->religion ?: '-' }}</p>

        <label class="small text-muted">Pendidikan</label>
        <p class="mb-3 border-bottom pb-2 text-uppercase">{{ @$dad->education ?: '-' }}</p>

        <label class="small text-muted">Pekerjaan</label>
        <p class="mb-3 border-bottom pb-2 text-uppercase">{{ @$dad->job ?: '-' }}</p>

        <label class="small text-muted">Penghasilan</label>
        <p class="mb-3 border-bottom pb-2 text-uppercase">{{ @$dad->salary ?: '-' }}</p>

        <label class="small text-muted">Kewarganegaraan</label>
        <p class="mb-3 border-bottom pb-2 text-uppercase">{{ @$dad->country ?: '-' }}</p>

        <label class="small text-muted">Kota</label>
        <p class="mb-3 border-bottom pb-2 text-uppercase">{{ @$dad->city ?: '-' }}</p>

        <label class="small text-muted">Provinsi</label>
        <p class="mb-3 border-bottom pb-2 text-uppercase">{{ @$dad->region ?: '-' }}</p>

        <label class="small text-muted">Alamat</label>
        <p class="mb-3 border-bottom pb-2 text-uppercase">{{ @$dad->address ?: '-' }}</p>
    </div>
    <div class="col-md-6">
        <h4 class="fw-bold text-danger mb-3"><i class="fa fa-female me-2"></i> Data Ibu
        </h4>
        <label class="small text-muted">Nama Ibu</label>
        <p class="mb-3  border-bottom pb-2">{{ @$mom->name ?: '-' }}</p>

        <label class="small text-muted">Tempat, Tanggal Lahir</label>
        <p class="mb-3 border-bottom pb-2">{{ @$mom['place_of_birth'] .', '. @$mom['date_of_birth'] ?: '-' }}</p>

        <label class="small text-muted">No. Telp</label>
        <p class="mb-3 border-bottom pb-2 text-uppercase">{{ @$mom->phone ?: '-' }}</p>

        <label class="small text-muted">Agama</label>
        <p class="mb-3 border-bottom pb-2 text-uppercase">{{ @$mom->religion ?: '-' }}</p>

        <label class="small text-muted">Pendidikan</label>
        <p class="mb-3 border-bottom pb-2 text-uppercase">{{ @$mom->education ?: '-' }}</p>

        <label class="small text-muted">Pekerjaan</label>
        <p class="mb-3 border-bottom pb-2 text-uppercase">{{ @$mom->job ?: '-' }}</p>

        <label class="small text-muted">Penghasilan</label>
        <p class="mb-3 border-bottom pb-2 text-uppercase">{{ @$mom->salary ?: '-' }}</p>

        <label class="small text-muted">Kewarganegaraan</label>
        <p class="mb-3 border-bottom pb-2 text-uppercase">{{ @$mom->country ?: '-' }}</p>

        <label class="small text-muted">Kota</label>
        <p class="mb-3 border-bottom pb-2 text-uppercase">{{ @$mom->city ?: '-' }}</p>

        <label class="small text-muted">Provinsi</label>
        <p class="mb-3 border-bottom pb-2 text-uppercase">{{ @$mom->region ?: '-' }}</p>

        <label class="small text-muted">Alamat</label>
        <p class="mb-3 border-bottom pb-2 text-uppercase">{{ @$mom->address ?: '-' }}</p>
    </div>
</div>
