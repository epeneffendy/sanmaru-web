<div class="row">
    <div class="col-md-12 mb-4">
        @if ($inputs->get('asal_sekolah'))
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Asal Sekolah</label>
                <div class="input-group modern-input-group">
                    <input type="text" name="asal_sekolah"
                           value="{{ old('asal_sekolah', @$ppdbUser->asal_sekolah) }}"
                           class="form-control border-start-0 ps-0 shadow-none required"
                           placeholder="Masukkan Asal Sekolah">
                </div>
            </div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-4">
        @if ($inputs->get('nisn'))
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">NISN (Nomor Induk Siswa Nasional)</label>
                <div class="input-group modern-input-group">
                    <input type="text" name="nisn"
                           value="{{ old('nisn', @$ppdbUser->nisn) }}"
                           class="form-control border-start-0 ps-0 shadow-none required"
                           placeholder="Masukkan NISN">
                </div>
            </div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-4">
        @if ($inputs->get('alamat_asal_sekolah'))
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Alamat Asal Sekolah</label>
                <div class="input-group modern-input-group">
                    <textarea class="form-control required" name="alamat_asal_sekolah" rows="3"
                              placeholder="Masukan Alamat Asal Sekolah">{{ old('alamat_asal_sekolah', @$ppdbUser->alamat_asal_sekolah) }}</textarea>
                </div>
            </div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        @if ($inputs->get('kecamatan_asal_sekolah'))
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Kecamatan Asal Sekolah</label>
                <div class="input-group modern-input-group">
                    <input type="text" name="kecamatan_asal_sekolah"
                           value="{{ old('kecamatan_asal_sekolah', @$ppdbUser->kecamatan_asal_sekolah) }}"
                           class="form-control required" placeholder="Kecamatan Asal Sekolah">
                </div>
            </div>
        @endif
    </div>

    <div class="col-md-4 mb-4">
        @if ($inputs->get('kota_asal_sekolah'))
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Kota Asal Sekolah</label>
                <div class="input-group modern-input-group">
                    <input type="text" name="kota_asal_sekolah"
                           value="{{ old('kota_asal_sekolah', @$ppdbUser->kota_asal_sekolah) }}"
                           class="form-control required" placeholder="Kota Asal Sekolah">
                </div>
            </div>
        @endif
    </div>

    <div class="col-md-4 mb-4">
        @if ($inputs->get('provinsi_asal_sekolah'))
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Provinsi Asal Sekolah</label>
                <div class="input-group modern-input-group">
                    <input type="text" name="provinsi_asal_sekolah"
                           value="{{ old('provinsi_asal_sekolah', @$ppdbUser->provinsi_asal_sekolah) }}"
                           class="form-control required" placeholder="Provinsi Asal Sekolah">
                </div>
            </div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        @if ($inputs->get('nomor_telepon_asal_sekolah'))
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">No. Telp Sekolah</label>
                <div class="input-group modern-input-group">
                    <input type="number" name="nomor_telepon_asal_sekolah"
                           value="{{ old('nomor_telepon_asal_sekolah', @$ppdbUser->nomor_telepon_asal_sekolah) }}"
                           class="form-control required" placeholder="No. Telp Sekolah">
                </div>
            </div>
        @endif
    </div>

    <div class="col-md-6 mb-4">
        @if ($inputs->get('tahun_lulus'))
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Tahun Lulus</label>
                <div class="input-group modern-input-group">
                    <input type="number" name="tahun_lulus"
                           value="{{ old('tahun_lulus', @$ppdbUser->tahun_lulus) }}"
                           class="form-control required" placeholder="Kecamatan Asal Sekolah">
                </div>
            </div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        @if ($inputs->get('nomor_seri_shun'))
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Nomor Seri SHUN</label>
                <div class="input-group modern-input-group">
                    <input type="text" name="nomor_seri_shun"
                           value="{{ old('nomor_seri_shun', @$ppdbUser->nomor_seri_shun) }}"
                           class="form-control required" placeholder="Nomor Seri SHUN">
                </div>
            </div>
        @endif
    </div>

    <div class="col-md-4 mb-4">
        @if ($inputs->get('nomor_seri_ijazah'))
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Nomor Seri Ijazah</label>
                <div class="input-group modern-input-group">
                    <input type="text" name="nomor_seri_ijazah"
                           value="{{ old('nomor_seri_ijazah', @$ppdbUser->nomor_seri_ijazah) }}"
                           class="form-control required" placeholder="Nomor Seri Ijazah">
                </div>
            </div>
        @endif
    </div>

    <div class="col-md-4 mb-4">
        @if ($inputs->get('nomor_ujian_nasional'))
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Nomor Seri Ijazah</label>
                <div class="input-group modern-input-group">
                    <input type="text" name="nomor_ujian_nasional"
                           value="{{ old('nomor_ujian_nasional', @$ppdbUser->nomor_ujian_nasional) }}"
                           class="form-control required" placeholder="Nomor Ujian Nasional">
                </div>
            </div>
        @endif
    </div>

</div>
