<div class="row">
    <div class="col-md-12 mb-4">
        <div class="form-group custom-form-group">
            <label class="form-label fw-bold text-muted mb-2">Nama Lengkap (Sesuai Akta Kelahiran)</label>
            <div class="input-group modern-input-group">
                <input type="text" name="nama_siswa" value="{{ old('nama_siswa', @$ppdbUser->nama_siswa) }}"
                    class="form-control border-start-0 ps-0 shadow-none uppercase-input required"
                    placeholder="Masukkan Nama Lengkap Sesuai Ijazah">
            </div>
            <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">*Pastikan Sesuai Akta Kelahiran.</small>
        </div>
    </div>
</div>

<div class="row">
    @if ($inputs->get('nama_panggilan'))
        <div class="col-md-12 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Nama Panggilan</label>
                <div class="input-group modern-input-group">
                    <input type="text" name="nama_panggilan"
                        value="{{ old('nama_panggilan', @$ppdbUser->nama_panggilan) }}"
                        class="form-control border-start-0 ps-0 shadow-none uppercase-input required"
                        placeholder="Masukan Nama Panggilan Siswa">
                </div>
            </div>
        </div>
    @endif
</div>

<div class="row">
    @if ($inputs->get('nama_saudara_se_sekolah'))
        <div class="col-md-12 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Nama Saudara Satu Sekolah</label>
                <div class="input-group modern-input-group">
                    <input type="text" name="nama_saudara_se_sekolah"
                        value="{{ old('nama_saudara_se_sekolah', @$ppdbUser->nama_saudara_se_sekolah) }}"
                        class="form-control border-start-0 ps-0 shadow-none uppercase-input required"
                        placeholder="Masukan Nama Saudara">
                </div>
                <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">*Isi Jika Ada Saudara Adik / Kakak /
                    Saudara yang sekolah di Santa Maria (Nama + Unit).</small>
            </div>
        </div>
    @endif
</div>


<div class="row">
    @if ($inputs->get('no_akta_kelahiran'))
        <div class="col-md-6 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">No. Registrasi Akta Kelahiran</label>
                <div class="input-group modern-input-group">
                    <input type="text" name="no_akta_kelahiran"
                        value="{{ old('no_akta_kelahiran', @$ppdbUser->no_akta_kelahiran) }}"
                        class="form-control uppercase-input required" placeholder="No Akta Kelahiran">
                </div>
            </div>
        </div>
    @endif


    <div class="col-md-6 mb-4">
        <div class="form-group custom-form-group">
            <label class="form-label fw-bold text-muted mb-2">NIK Orang Tua / Wali</label>
            <div class="input-group modern-input-group">
                <input type="number" name="nik_ortu" value="{{ old('nik_ortu', @$ppdbUser->nik_ortu) }}"
                    class="form-control uppercase-input required" placeholder="NIK Orang Tua / Wali">
            </div>
        </div>
    </div>
</div>


<div class="row">
    @if ($inputs->get('bahasa'))
        <div class="col-md-6 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Bahasa Sehari-hari</label>
                <div class="input-group modern-input-group">
                    <select name="bahasa" class="form-control required">
                        <option value=""></option>
                        <option value="Bahasa Indonesia" {!! old('bahasa', @$ppdbUser->bahasa) == 'Bahasa Indonesia' ? 'selected="true"' : null !!}>
                            BAHASA INDONESIA
                        </option>
                        <option value="Bahasa Inggris" {!! old('bahasa', @$ppdbUser->bahasa) == 'Bahasa Inggris' ? 'selected="true"' : null !!}>
                            BAHASA INGGRIS
                        </option>
                        @if (old('bahasa', @$ppdbUser->bahasa) &&
                                !in_array(old('bahasa', @$ppdbUser->bahasa), ['Bahasa Inggris', 'Bahasa Indonesia']))
                            <option value="{{ old('bahasa', @$ppdbUser->bahasa) }}">
                                {{ old('bahasa', @$ppdbUser->bahasa) }}</option>
                        @endif
                    </select>
                </div>
            </div>
        </div>
    @endif

    @if ($inputs->get('jumlah_saudara_kandung'))
        <div class="col-md-6 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Jumlah Suadara Kandung</label>
                <div class="input-group modern-input-group">
                    <input type="number" name="jumlah_saudara_kandung"
                        value="{{ old('jumlah_saudara_kandung', @$ppdbUser->jumlah_saudara_kandung) }}"
                        class="form-control uppercase-input required" placeholder="Jumlah Saudara Kandung">
                </div>
            </div>
        </div>
    @endif
</div>

<div class="row">
    @if ($inputs->get('anak_ke'))
        <div class="col-md-6 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Anak Ke</label>
                <div class="input-group modern-input-group">
                    <input type="number" name="anak_ke" value="{{ old('anak_ke', @$ppdbUser->anak_ke) }}"
                        class="form-control required" placeholder="Anak Ke-">
                </div>
            </div>
        </div>
    @endif

    @if ($inputs->get('jumlah_saudara_tiri'))
        <div class="col-md-6 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Jumlah Saudara Tiri</label>
                <div class="input-group modern-input-group">
                    <input type="number" name="jumlah_saudara_tiri"
                        value="{{ old('jumlah_saudara_tiri', @$ppdbUser->jumlah_saudara_tiri) }}"
                        class="form-control required" placeholder="Jumlah Saudara Tiri">
                </div>
            </div>
        </div>
    @endif
</div>

<div class="row">
    @if ($inputs->get('alamat_sesuai_kk'))
        <div class="col-md-12 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Alamat Sesuai KK (Kartu Keluarga)</label>
                <div class="input-group modern-input-group">
                    <textarea class="form-control uppercase-input required" name="alamat_sesuai_kk" rows="3"
                        placeholder="Alamat Sesuai Kartu Keluarga">{{ old('alamat_sesuai_kk', @$ppdbUser->alamat_sesuai_kk) }}</textarea>
                </div>
            </div>
        </div>
    @endif
</div>

<div class="row">
    @if ($inputs->get('alamat_tempat_tinggal'))
        <div class="col-md-12 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Alamat Tempat Tinggal / Domisili</label>
                <div class="input-group modern-input-group">
                    <textarea class="form-control uppercase-input required" name="alamat_tempat_tinggal" rows="3"
                        placeholder="Alamat Tempat Tinggal / Domisili">{{ old('alamat_tempat_tinggal', @$ppdbUser->alamat_tempat_tinggal) }}</textarea>
                </div>
            </div>
        </div>
    @endif
</div>

<div class="row">
    @if ($inputs->get('status_orangtua'))
        <div class="col-md-12 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Status Orang Tua & Anak</label>
                <select class="form-control required" placeholder="Status Orang Tua" name="status_orangtua">
                    <option value=""></option>
                    <option value="yatim"
                        {{ old('status_orangtua', @$ppdbUser->status_orangtua) === 'yatim' ? 'selected' : null }}>
                        YATIM
                    </option>
                    <option value="piatu"
                        {{ old('status_orangtua', @$ppdbUser->status_orangtua) === 'piatu' ? 'selected' : null }}>
                        PIATU
                    </option>
                    <option value="yatim piatu"
                        {{ old('status_orangtua', @$ppdbUser->status_orangtua) === 'yatim piatu' ? 'selected' : null }}>
                        YATIM PIATU
                    </option>
                    <option value="bukan yatim piatu"
                        {{ old('status_orangtua', @$ppdbUser->status_orangtua) === 'bukan yatim piatu' ? 'selected' : null }}>
                        BUKAN YATIM PIATU
                    </option>

                </select>
            </div>
        </div>
    @endif
</div>

<div class="row">
    @if ($inputs->get('tinggal_dengan'))
        <div class="col-md-4 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Bertempat Tinggal Pada</label>
                <div class="input-group modern-input-group">
                    <select class="form-control required" placeholder="Tinggal Dengan" name="tinggal_dengan">
                        <option value=""></option>
                        <option value="orang tua"
                            {{ old('tinggal_dengan', @$ppdbUser->tinggal_dengan) === 'orang tua' ? 'selected' : null }}>
                            ORANG TUA
                        </option>
                        <option value="wali"
                            {{ old('tinggal_dengan', @$ppdbUser->tinggal_dengan) === 'wali' ? 'selected' : null }}>
                            WALI
                        </option>
                        <option value="saudara"
                            {{ old('tinggal_dengan', @$ppdbUser->tinggal_dengan) === 'saudara' ? 'selected' : null }}>
                            SAUDARA
                        </option>
                        <option value="asrama"
                            {{ old('tinggal_dengan', @$ppdbUser->tinggal_dengan) === 'asrama' ? 'selected' : null }}>
                            ASRAMA
                        </option>
                        <option value="kost"
                            {{ old('tinggal_dengan', @$ppdbUser->tinggal_dengan) === 'kost' ? 'selected' : null }}>
                            KOST
                        </option>
                        <option value="panti asuhan"
                            {{ old('tinggal_dengan', @$ppdbUser->tinggal_dengan) === 'panti asuhan' ? 'selected' : null }}>
                            PANTI ASUHAN
                        </option>
                        <option value="lainnya"
                            {{ old('tinggal_dengan', @$ppdbUser->tinggal_dengan) === 'lainnya' ? 'selected' : null }}>
                            LAINNYA
                        </option>
                    </select>
                </div>
            </div>
        </div>
    @endif

    @if ($inputs->get('jarak_tempat_tinggal'))
        <div class="col-md-4 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Jarak Tempat Tinggal ke Sekolah</label>
                <div class="input-group modern-input-group">
                    <div class="d-flex">
                        <input type="number" name="jarak_tempat_tinggal"
                            value="{{ old('jarak_tempat_tinggal', @$ppdbUser->jarak_tempat_tinggal) }}"
                            class="form-control required" placeholder="Jarak Tempat Tinggal">
                        <div class="input-group-append">
                            <span class="input-group-text">km</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($inputs->get('waktu_tempuh'))
        <div class="col-md-4 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Waktu Tempuh ke Sekolah</label>
                <div class="input-group modern-input-group">
                    <div class="d-flex">
                        <input type="number" name="waktu_tempuh"
                            value="{{ old('waktu_tempuh', @$ppdbUser->waktu_tempuh) }}" class="form-control required"
                            placeholder="Waktu Tempuh">
                        <div class="input-group-append">
                            <span class="input-group-text">Menit</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<div class="row">
    @if ($inputs->get('nik_ayah'))
        <div class="col-md-6 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">NIK Orang Tua (Ayah)</label>
                <input type="text" name="nik_ayah" value="{{ old('nik_ayah', @$ppdbUser->nik_ayah) }}"
                    class="form-control required" placeholder="NIK Orang Tua (Ayah)">
            </div>
        </div>
    @endif

    @if ($inputs->get('nik_ibu'))
        <div class="col-md-6 mb-4">
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">NIK Orang Tua (Ibu)</label>
                <input type="text" name="nik_ibu" value="{{ old('nik_ibu', @$ppdbUser->nik_ibu) }}"
                    class="form-control required" placeholder="NIK Orang Tua (Ibu)">
            </div>
        </div>
    @endif
</div>


<div class="row">
    <div class="col-md-12 mb-4">
        @if ($inputs->get('penanggungjawab_biaya'))
            <div class="form-group custom-form-group">
                <label class="form-label fw-bold text-muted mb-2">Penanggunjawab Biaya Pendidikan</label>
                <div class="input-group modern-input-group">
                    <input type="text" name="penanggungjawab_biaya"
                        value="{{ old('penanggungjawab_biaya', @$ppdbUser->penanggungjawab_biaya) }}"
                        class="form-control uppercase-input required" placeholder="Penanggungjawab Biaya">
                </div>
            </div>
        @endif
    </div>
</div>
