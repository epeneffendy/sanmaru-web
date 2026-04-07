<div class="col-md-12 mb-4">
    <div class="form-group custom-form-group">
        <label class="form-label fw-bold text-muted mb-2">Nama Lengkap Calon Siswa</label>
        <div class="input-group modern-input-group">
            <input type="text" name="name"
                   value="{{ old('name', @$ppdbUser->name) }}"
                   class="form-control border-start-0 ps-0 shadow-none uppercase-input required"
                   placeholder="Masukkan Nama Lengkap Sesuai Ijazah">
        </div>
        <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">*Pastikan ejaan nama sudah benar.</small>
    </div>
</div>

<div class="col-md-12 mb-4">
    <div class="form-group custom-form-group">
        <label class="form-label fw-bold text-muted mb-2">NIK Siswa</label>
        <div class="input-group modern-input-group">
            <input type="text" name="nik_siswa" id="nik_siswa"
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
                <option value="">--SILAHKAN PILIH--</option>
                <option value="male" {{ old('gender', @$ppdbUser->gender) === 'male' ? 'selected' : NULL }}>LAKI-LAKI
                </option>
                <option value="female" {{ old('gender', @$ppdbUser->gender) === 'female' ? 'selected' : NULL }}>
                    PEREMPUAN
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
                <option value="">--SILAHKAN PILIH--</option>
                <option value="Katolik" {{ old('religion', @$ppdbUser->religion) === 'Katolik' ? 'selected' : NULL }}>
                    KATOLIK
                </option>
                <option
                    value="Protestan" {{ old('religion', @$ppdbUser->religion) === 'Protestan' ? 'selected' : NULL }}>
                    PROSTESTAN
                </option>
                <option value="Islam" {{ old('religion', @$ppdbUser->religion) === 'Islam' ? 'selected' : NULL }}>ISLAM
                </option>
                <option value="Hindu" {{ old('religion', @$ppdbUser->religion) === 'Hindu' ? 'selected' : NULL }}>HINDU
                </option>
                <option value="Buddha" {{ old('religion', @$ppdbUser->religion) === 'Buddha' ? 'selected' : NULL }}>
                    BUDHA
                </option>
                <option
                    value="Khonghucu" {{ old('religion', @$ppdbUser->religion) === 'Khonghucu' ? 'selected' : NULL }}>
                    KHONGHUCU
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
                   value="{{ old('place_of_birth', @$ppdbUser->place_of_birth) }}"
                   class="form-control border-start-0 ps-0 shadow-none uppercase-input required"
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
                   class="form-control uppercase-input required" id="datepicker"/>
        </div>
    </div>
</div>

<div class="col-md-12 mb-4">
    <div class="form-group custom-form-group">
        <label class="form-label fw-bold text-muted mb-2">Alamat</label>
        <div class="input-group modern-input-group">
            <textarea class="form-control uppercase-input required" name="address" rows="3"
                      placeholder="Alamat">{{ old('address', @$ppdbUser->address) }}</textarea>
        </div>
    </div>
</div>

<div class="col-md-4 mb-4">
    <div class="form-group custom-form-group">
        <label class="form-label fw-bold text-muted mb-2">Provinsi</label>
        <div class="input-group modern-input-group">
            <select name="region" id="region" class="form-control select2-provinces required">
                <option value=""></option>
                @foreach($provinces as $province)
                    <option value="{{ $province->name }}" {{ old('region', @$ppdbUser->region) == $province->name ? 'selected' : '' }}>
                        {{ $province->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>


<div class="col-md-4 mb-4">
    <div class="form-group custom-form-group">
        <label class="form-label fw-bold text-muted mb-2">Kota/Kabupaten</label>
        <div class="input-group modern-input-group">
            <select name="city" id="city" class="form-control select2-cities required" data-placeholder="Pilih Kota">
                <option value=""></option>
            </select>
        </div>
    </div>
</div>

<div class="col-md-4 mb-4">
    <div class="form-group custom-form-group">
        <label class="form-label fw-bold text-muted mb-2">Kewarganegaraan</label>
        <div class="input-group modern-input-group">
            <select class="form-control required" placeholder="Kewarganegaraan" name="country">
                <option value="">--SILAHKAN PILIH--</option>
                <option value="WNI" {{ old('country', @$ppdbUser->country) === 'WNI' ? 'selected' : NULL }}>WNI
                </option>
                <option value="WNA" {{ old('country', @$ppdbUser->country) === 'WNA' ? 'selected' : NULL }}>WNA
                </option>
            </select>
        </div>
    </div>
</div>


