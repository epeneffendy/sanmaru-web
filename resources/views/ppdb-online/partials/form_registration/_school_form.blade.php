<div class="row">
    <div class="col-md-12 mb-4">
        @if ($inputs->get('asal_sekolah'))
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Asal Sekolah</label>
                <div class="input-group modern-input-group">
                    <input type="text" name="asal_sekolah"
                           value="{{ old('asal_sekolah', @$ppdbUser->asal_sekolah) }}"
                           class="form-control border-start-0 ps-0 shadow-none uppercase-input required"
                           placeholder="Masukkan Asal Sekolah">
                </div>
            </div>
        @endif
    </div>
</div>

@if ($inputs->get('alamat_asal_sekolah'))
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Alamat Asal Sekolah</label>
                <div class="input-group modern-input-group">
                    <textarea class="form-control uppercase-input required" name="alamat_asal_sekolah" rows="3"
                              placeholder="Alamat Asal Sekolah">{{ old('alamat_asal_sekolah', @$ppdbUser->alamat_asal_sekolah) }}</textarea>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($inputs->get('kecamatan_asal_sekolah'))
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Kecamatan Asal Sekolah</label>
                <div class="input-group modern-input-group">
                    <input type="text" name="kecamatan_asal_sekolah"
                           value="{{ old('kecamatan_asal_sekolah', @$ppdbUser->kecamatan_asal_sekolah) }}"
                           class="form-control uppercase-input required" placeholder="Kecamatan Asal Sekolah">
                </div>
            </div>
        </div>
    </div>
@endif

@if ($inputs->get('kabupaten_asal_sekolah'))
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Kabupaten Asal Sekolah</label>
                <div class="input-group modern-input-group">
                    <input type="text" name="kabupaten_asal_sekolah"
                           value="{{ old('kabupaten_asal_sekolah', @$ppdbUser->kabupaten_asal_sekolah) }}"
                           class="form-control uppercase-input required" placeholder="Kabupaten Asal Sekolah">
                </div>
            </div>
        </div>
    </div>
@endif

@if ($inputs->get('kota_asal_sekolah'))
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Kota Asal Sekolah</label>
                <div class="input-group modern-input-group">
                    <input name="kota_asal_sekolah" class="form-control uppercase-input required" placeholder="Kota Asal Sekolah"
                           value="{{ old('kota_asal_sekolah', @$ppdbUser->kota_asal_sekolah) }}"/>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($inputs->get('provinsi_asal_sekolah'))
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Provinsi Asal Sekolah</label>
                <div class="input-group modern-input-group">
                    <input name="provinsi_asal_sekolah" class="form-control uppercase-input required"
                           placeholder="Provinsi Asal Sekolah"
                           value="{{ old('provinsi_asal_sekolah', @$ppdbUser->provinsi_asal_sekolah) }}"/>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($inputs->get('nomor_telepon_asal_sekolah'))
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Nomor Telepon Asal Sekolah</label>
                <div class="input-group modern-input-group">
                    <input type="text" pattern="[0-9]{7,13}" name="nomor_telepon_asal_sekolah"
                           value="{{ old('nomor_telepon_asal_sekolah', @$ppdbUser->nomor_telepon_asal_sekolah) }}"
                           class="form-control uppercase-input required" placeholder="Nomor Telepon Asal Sekolah">
                </div>
            </div>
        </div>
    </div>
@endif

@if ($inputs->get('transportasi_ke_sekolah'))
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Transportasi Ke Sekolah</label>
                <div class="input-group modern-input-group">
                    <input type="text" name="transportasi_ke_sekolah"
                           value="{{ old('transportasi_ke_sekolah', @$ppdbUser->transportasi_ke_sekolah) }}"
                           class="form-control uppercase-input required" placeholder="Transportasi ke Sekolah">
                </div>
            </div>
        </div>
    </div>
@endif

<div class="row">
    @if ($inputs->get('nisn'))
        <div class="col-md-4 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">NISN (Nomor Induk Siswa Nasional)</label>
                <div class="input-group modern-input-group">
                    <input type="text" name="nisn"
                           value="{{ old('nisn', @$ppdbUser->nisn) }}"
                           class="form-control uppercase-input required" placeholder="NISN">
                </div>
            </div>
        </div>
    @endif

    @if ($inputs->get('tahun_lulus'))
        <div class="col-md-4 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Tahun Lulus</label>
                <div class="input-group modern-input-group">
                    <input type="number" min="1900" max="2099" step="1" name="tahun_lulus"
                           value="{{ old('tahun_lulus', @$ppdbUser->tahun_lulus) }}" class="form-control required"
                           placeholder="Tahun Lulus">
                </div>
            </div>
        </div>
    @endif

    @if ($inputs->get('nomor_ujian_nasional'))
        <div class="col-md-4 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Nomor Ujian Nasional</label>
                <div class="input-group modern-input-group">
                    <input type="text" name="nomor_ujian_nasional"
                           value="{{ old('nomor_ujian_nasional', @$ppdbUser->nomor_ujian_nasional) }}"
                           class="form-control uppercase-input required" placeholder="Nomor Ujian Nasional">
                </div>
            </div>
        </div>
    @endif
</div>

<div class="row">
    @if ($inputs->get('nomor_seri_shun'))
        <div class="col-md-6 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Nomor Seri SHUN</label>
                <div class="input-group modern-input-group">
                    <input type="text" name="nomor_seri_shun"
                           value="{{ old('nomor_seri_shun', @$ppdbUser->nomor_seri_shun) }}"
                           class="form-control uppercase-input required" placeholder="Nomor Seri SHUN">
                </div>
            </div>
        </div>
    @endif

    @if ($inputs->get('nomor_seri_ijazah'))
        <div class="col-md-6 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Nomor Seri Ijazah</label>
                <div class="input-group modern-input-group">
                    <input type="text" name="nomor_seri_ijazah"
                           value="{{ old('nomor_seri_ijazah', @$ppdbUser->nomor_seri_ijazah) }}"
                           class="form-control uppercase-input required" placeholder="Nomor Seri Ijazah">
                </div>
            </div>
        </div>
    @endif

</div>


