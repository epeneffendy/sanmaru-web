<div class="col-md-12 mb-4">
    <div class="form-group custom-form-group">
        <label class="form-label fw-bold text-muted mb-2">Nama Lengkap Calon Siswa</label>
        <div class="input-group modern-input-group">
            <input type="text" name="name"
                   value="{{ old('name', @$ppdbUser->name) }}"
                   class="form-control border-start-0 ps-0 shadow-none required"
                   placeholder="Masukkan Nama Lengkap Sesuai Ijazah">
        </div>
        <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">*Pastikan ejaan nama sudah benar.</small>
    </div>
</div>

<div class="col-md-12 mb-4">
    <div class="form-group custom-form-group">
        <label class="form-label fw-bold text-muted mb-2">NIK Siswa</label>
        <div class="input-group modern-input-group">
            <input type="text" name="nik_siswa"
                   value="{{ old('nik_siswa', @$ppdbUser->nik_siswa) }}"
                   class="form-control border-start-0 ps-0 shadow-none required"
                   placeholder="Masukkan NIK Siswa">
        </div>
    </div>
</div>


<div class="col-md-6 mb-4">
    <div class="form-group custom-form-group">
        <label class="form-label fw-bold text-muted mb-2">Jenis Kelamin</label>
        <div class="input-group modern-input-group">
            <select class="form-control required" placeholder="Jenis Kelamin" name="gender">
                <option value="male" {{ old('gender', @$ppdbUser->gender) === 'male' ? 'selected' : NULL }}>Laki-Laki
                </option>
                <option value="female" {{ old('gender', @$ppdbUser->gender) === 'female' ? 'selected' : NULL }}>
                    Perempuan
                </option>
            </select>
        </div>
    </div>
</div>

<div class="col-md-6 mb-4">
    <div class="form-group custom-form-group">
        <label class="form-label fw-bold text-muted mb-2">Agama</label>
        <div class="input-group modern-input-group">
            <select class="form-control required" placeholder="Agama" name="religion">
                <option value="Katolik" {{ old('religion', @$ppdbUser->religion) === 'Katolik' ? 'selected' : NULL }}>
                    Katolik
                </option>
                <option
                    value="Protestan" {{ old('religion', @$ppdbUser->religion) === 'Protestan' ? 'selected' : NULL }}>
                    Protestan
                </option>
                <option value="Islam" {{ old('religion', @$ppdbUser->religion) === 'Islam' ? 'selected' : NULL }}>Islam
                </option>
                <option value="Hindu" {{ old('religion', @$ppdbUser->religion) === 'Hindu' ? 'selected' : NULL }}>Hindu
                </option>
                <option value="Buddha" {{ old('religion', @$ppdbUser->religion) === 'Buddha' ? 'selected' : NULL }}>
                    Buddha
                </option>
                <option
                    value="Khonghucu" {{ old('religion', @$ppdbUser->religion) === 'Khonghucu' ? 'selected' : NULL }}>
                    Khonghucu
                </option>
            </select>
        </div>
    </div>
</div>

<div class="col-md-6 mb-4">
    <div class="form-group custom-form-group">
        <label class="form-label fw-bold text-muted mb-2">Tempat Lahir</label>
        <div class="input-group modern-input-group">
            <input type="text" name="place_of_birth"
                   value="{{ old('place_of_birth', @$ppdbUser->city_name) }}"
                   class="form-control border-start-0 ps-0 shadow-none required"
                   placeholder="Masukkan Tempat Lahir">
        </div>
    </div>
</div>

<div class="col-md-6 mb-4">
    <div class="form-group custom-form-group">
        <label class="form-label fw-bold text-muted mb-2">Tanggal Lahir</label>
        <div class="input-group modern-input-group">
            <input type="date" name="date_of_birth"
                   value="{{ old('date_of_birth', @$ppdbUser->date_of_birth) }}"
                   class="form-control required" id="datepicker"/>
        </div>
    </div>
</div>

<div class="col-md-12 mb-4">
    <div class="form-group custom-form-group">
        <label class="form-label fw-bold text-muted mb-2">Alamat</label>
        <div class="input-group modern-input-group">
            <textarea class="form-control required" name="alamat_sesuai_kk" rows="3" placeholder="Alamat Sesuai KK">{{ old('alamat_sesuai_kk', @$ppdbUser->alamat_sesuai_kk) }}</textarea>
        </div>
    </div>
</div>

<div class="col-md-4 mb-4">
    <div class="form-group custom-form-group">
        <label class="form-label fw-bold text-muted mb-2">Provinsi</label>
        <div class="input-group modern-input-group">
            <select class="form-control required" placeholder="Kewarganegaraan" name="country">
                <option value="WNI" {{ old('country', @$ppdbUser->country) === 'WNI' ? 'selected' : NULL }}>WNI
                </option>
                <option value="WNA" {{ old('country', @$ppdbUser->country) === 'WNA' ? 'selected' : NULL }}>WNA
                </option>
            </select>
        </div>
    </div>
</div>


<div class="col-md-4 mb-4">
    <div class="form-group custom-form-group">
        <label class="form-label fw-bold text-muted mb-2">Kota</label>
        <div class="input-group modern-input-group">
            <select class="form-control required" placeholder="Kewarganegaraan" name="country">
                <option value="WNI" {{ old('country', @$ppdbUser->country) === 'WNI' ? 'selected' : NULL }}>WNI
                </option>
                <option value="WNA" {{ old('country', @$ppdbUser->country) === 'WNA' ? 'selected' : NULL }}>WNA
                </option>
            </select>
        </div>
    </div>
</div>

<div class="col-md-4 mb-4">
    <div class="form-group custom-form-group">
        <label class="form-label fw-bold text-muted mb-2">Kewarganegaraan</label>
        <div class="input-group modern-input-group">
            <select class="form-control required" placeholder="Kewarganegaraan" name="country">
                <option value="WNI" {{ old('country', @$ppdbUser->country) === 'WNI' ? 'selected' : NULL }}>WNI
                </option>
                <option value="WNA" {{ old('country', @$ppdbUser->country) === 'WNA' ? 'selected' : NULL }}>WNA
                </option>
            </select>
        </div>
    </div>
</div>


