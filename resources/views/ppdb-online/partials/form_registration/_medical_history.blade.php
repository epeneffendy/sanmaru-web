<div class="row">
    @if ($inputs->get('tinggi'))
        <div class="col-md-4 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Tinggi Badan</label>
                <div class="input-group modern-input-group">
                    <div class="d-flex">
                        <input type="number" name="tinggi"
                               value="{{ old('tinggi', @$ppdbUser->tinggi) }}"
                               class="form-control required" placeholder="Tinggi">
                        <div class="input-group-append">
                            <span class="input-group-text">CM</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($inputs->get('berat'))
        <div class="col-md-4 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Berat Badan</label>
                <div class="input-group modern-input-group">
                    <div class="d-flex">
                        <input type="number" name="berat"
                               value="{{ old('berat', @$ppdbUser->berat) }}"
                               class="form-control required" placeholder="Berat">
                        <div class="input-group-append">
                            <span class="input-group-text">KG</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($inputs->get('golongan_darah'))
        <div class="col-md-4 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Golongan Darah</label>
                <div class="input-group modern-input-group">
                    <select class="form-control required" placeholder="Golongan Darah" name="golongan_darah">
                        <option value="">--Silahkan Pilih--</option>
                        <option
                            value="A" {{ old('golongan_darah', @$ppdbUser->golongan_darah) === 'A' ? 'selected' : NULL }}>
                            A
                        </option>
                        <option
                            value="AB" {{ old('golongan_darah', @$ppdbUser->golongan_darah) === 'AB' ? 'selected' : NULL }}>
                            AB
                        </option>
                        <option
                            value="B" {{ old('golongan_darah', @$ppdbUser->golongan_darah) === 'B' ? 'selected' : NULL }}>
                            B
                        </option>
                        <option
                            value="O" {{ old('golongan_darah', @$ppdbUser->golongan_darah) === 'O' ? 'selected' : NULL }}>
                            O
                        </option>
                    </select>
                </div>
            </div>
        </div>
    @endif
</div>

@if ($inputs->get('pernah_dirawat'))
    <div class="row">
        <div class="col-md-6">
            <label class="form-label fw-bold text-muted mb-2">Pernah dirawat dirumah sakit?</label>
            <div class="input-group modern-input-group">
                <select class="form-control required" placeholder="Pernah Dirawat ?" name="pernah_dirawat">
                    <option value="">--Silahkan Pilih--</option>
                    <option
                        value="ya" {{ old('pernah_dirawat', @$ppdbUser->pernah_dirawat) === 'ya' ? 'selected' : NULL }}>
                        YA
                    </option>
                    <option
                        value="tidak" {{ old('pernah_dirawat', @$ppdbUser->pernah_dirawat) === 'tidak' ? 'selected' : NULL }}>
                        TIDAK
                    </option>
                </select>
            </div>
        </div>

        @if ($inputs->get('kapan_dirawat'))
            <div class="col-md-6">
                <label class="form-label fw-bold text-muted mb-2">Jika iya, kapan dirawat</label>
                <div class="input-group modern-input-group">
                    <input type="text" name="kapan_dirawat"
                           value="{{ old('kapan_dirawat', @$ppdbUser->kapan_dirawat) }}" class="form-control uppercase-input"
                           placeholder="kapan dirawat">
                </div>
            </div>
        @endif
    </div>
@endif
<br>
@if ($inputs->get('penyakit'))
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Penyakit</label>
                <div class="input-group modern-input-group">
                    <input type="text" name="penyakit"
                           value="{{ old('penyakit', @$ppdbUser->penyakit) }}"
                           class="form-control uppercase-input required" placeholder="Penyakit">
                </div>
            </div>
        </div>
    </div>
@endif

@if ($inputs->get('alergi'))
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Jenis Alergi yang diderita</label>
                <div class="input-group modern-input-group">
                    <input type="text" name="alergi" value="{{ old('alergi', @$ppdbUser->alergi) }}"
                           class="form-control uppercase-input required" placeholder="Alergi">
                </div>
            </div>
        </div>
    </div>
@endif

@if ($inputs->get('kelainan'))
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Kelainan</label>
                <div class="input-group modern-input-group">
                    <input type="text" name="kelainan"
                           value="{{ old('kelainan', @$ppdbUser->kelainan) }}"
                           class="form-control uppercase-input required" placeholder="Kelainan">
                </div>
            </div>
        </div>
    </div>
@endif

@if ($inputs->get('kontak_darurat_keluarga'))
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Keluarga yang bisa dihubungi bila dalam keadaan
                    darurat</label>
                <div class="input-group modern-input-group">
                    <input type="text" name="kontak_darurat_keluarga"
                           value="{{ old('kontak_darurat_keluarga', @$ppdbUser->kontak_darurat_keluarga) }}"
                           class="form-control uppercase-input required" placeholder="Kontak darurat keluarga">
                </div>
                <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">*Nama + Nomor Telepon.</small>
            </div>
        </div>
    </div>
@endif
