<div class="col-md-12 mb-4">
    <div class="form-group custom-form-group">
        <label class="form-label fw-bold text-muted mb-2">Nama Ayah</label>
        <div class="input-group modern-input-group">
            <input type="text" name="father_name"
                   value="{{ (!empty(@$dad->name))?$dad->name:old('father_name') }}"
                   class="form-control uppercase-input required" placeholder="Nama Ayah">
        </div>
    </div>
</div>


<div class="col-md-6 mb-4">
    <div class="form-group custom-form-group">
        <label class="form-label fw-bold text-muted mb-2">Tempat Lahir</label>
        <div class="input-group modern-input-group">
            <input name="f_place_of_birth" class="form-control uppercase-input required" placeholder="Tempat Lahir"
                   value="{{ old('f_place_of_birth', @$dad->place_of_birth) }}"/>
        </div>
    </div>
</div>

<div class="col-md-6 mb-4">
    <div class="form-group custom-form-group">
        <label class="form-label fw-bold text-muted mb-2">Tanggal Lahir</label>
        <div class="input-group modern-input-group">
            <input type="date" class="form-control required" name="f_date_of_birth" placeholder="Tanggal Lahir"
                   value="{{ old('f_date_of_birth', @$dad->date_of_birth) }}"
                   id="datepicker-father"/>
        </div>
    </div>
</div>

<div class="col-md-12 mb-4">
    <label class="form-label fw-bold text-muted mb-2">Alamat Ayah</label>
    <div class="input-group modern-input-group">
        <textarea class="form-control uppercase-input required" rows="3" name="f_address"
                  placeholder="Alamat">{{ (!empty(@$dad['address']))?$dad['address']:old('f_address') }}</textarea>
    </div>
</div>

<div class="col-md-6 mb-4">
    <label class="form-label fw-bold text-muted mb-2">Provinsi</label>
    <div class="input-group modern-input-group">
        <select name="f_region" id="f_region" class="form-control select2-provinces required">
            <option value=""></option>
            @foreach($provinces as $province)
                <option value="{{ $province->name }}" {{ old('f_region', @$dad['region']) == $province->name ? 'selected' : '' }}>
                    {{ $province->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="col-md-6 mb-4">
    <label class="form-label fw-bold text-muted mb-2">Kota/Kabupaten</label>
    <div class="input-group modern-input-group">
        <select name="f_city" id="f_city" class="form-control select2-cities required" data-placeholder="Pilih Kota">
            <option value=""></option>
        </select>
    </div>
</div>

<div class="col-md-6 mb-4">
    <label class="form-label fw-bold text-muted mb-2">Kewarganegaraan Ayah</label>
    <div class="input-group modern-input-group">
        <select class="form-control required" placeholder="Kewarganegaraan" name="f_country">
            <option value="">--Silahkan Pilih--</option>
            <option value="WNI" {{ old('f_country', @$dad->country) === 'WNI' ? 'selected' : NULL }}>WNI
            </option>
            <option value="WNA" {{ old('f_country', @$dad->country) === 'WNA' ? 'selected' : NULL }}>WNA
            </option>
        </select>
    </div>
</div>

<div class="col-md-6 mb-4">
    <label class="form-label fw-bold text-muted mb-2">Agama Ayah</label>
    <div class="input-group modern-input-group">
        <select class="form-control required" placeholder="Agama" name="f_religion">
            <option value="">--Silahkan Pilih--</option>
            <option value="Katolik" {{ old('f_religion', @$dad->religion) === 'Katolik' ? 'selected' : NULL }}>
                KATHOLIK
            </option>
            <option value="Protestan" {{ old('f_religion', @$dad->religion) === 'Protestan' ? 'selected' : NULL }}>
                PROTESTAN
            </option>
            <option value="Islam" {{ old('f_religion', @$dad->religion) === 'Islam' ? 'selected' : NULL }}>ISLAM
            </option>
            <option value="Hindu" {{ old('f_religion', @$dad->religion) === 'Hindu' ? 'selected' : NULL }}>HINDU
            </option>
            <option value="Buddha" {{ old('f_religion', @$dad->religion) === 'Buddha' ? 'selected' : NULL }}>BUDDHA
            </option>
            <option value="Khonghucu" {{ old('f_religion', @$dad->religion) === 'Khonghucu' ? 'selected' : NULL }}>
                KHONGHUCU
            </option>
        </select>
    </div>
</div>


<div class="col-md-6 mb-4">
    <label class="form-label fw-bold text-muted mb-2">No. Telp Ayah</label>
    <div class="input-group modern-input-group">
        <input type="text" pattern="[0-9]{7,13}" name="f_phone"
               value="{{ (!empty(@$dad['phone']))?$dad['phone']:old('f_phone') }}"
               class="form-control required" placeholder="Telepon">
    </div>
</div>

<div class="col-md-6 mb-4">
    <label class="form-label fw-bold text-muted mb-2">Pendidikan Ayah</label>
    <div class="input-group modern-input-group">
        <select class="form-control required" placeholder="Pendidikan Ayah" name="f_education">
            <option value="">--Silahkan Pilih--</option>
            <option value="sd" {{ old('f_education', @$dad->education) === 'sd' ? 'selected' : NULL }}>SD</option>
            <option value="smp" {{ old('f_education', @$dad->education) === 'smp' ? 'selected' : NULL }}>SMP
            </option>
            <option value="sma" {{ old('f_education', @$dad->education) === 'sma' ? 'selected' : NULL }}>SMA
            </option>
            <option value="d1" {{ old('f_education', @$dad->education) === 'd1' ? 'selected' : NULL }}>D-1</option>
            <option value="d2" {{ old('f_education', @$dad->education) === 'd2' ? 'selected' : NULL }}>D-2</option>
            <option value="d3" {{ old('f_education', @$dad->education) === 'd3' ? 'selected' : NULL }}>D-3</option>
            <option value="s1" {{ old('f_education', @$dad->education) === 's1' ? 'selected' : NULL }}>S-1</option>
            <option value="s2" {{ old('f_education', @$dad->education) === 's2' ? 'selected' : NULL }}>S-2</option>
            <option value="s3" {{ old('f_education', @$dad->education) === 's3' ? 'selected' : NULL }}>S-3</option>
        </select>
    </div>
</div>

<div class="col-md-6 mb-4">
    <label class="form-label fw-bold text-muted mb-2">Pekerjaan Ayah</label>
    <div class="input-group modern-input-group">
        <select class="form-control required" placeholder="Pekerjaan Ayah" name="f_job">
            <option value="">--Silahkan Pilih--</option>
            <option value="tidak bekerja" {{ old('f_job', @$dad->job) === 'tidak bekerja' ? 'selected' : NULL }}>
                Tidak Bekerja
            </option>
            <option value="pns" {{ old('f_job', @$dad->job) === 'pns' ? 'selected' : NULL }}>PNS</option>
            <option value="tni" {{ old('f_job', @$dad->job) === 'tni' ? 'selected' : NULL }}>TNI</option>
            <option value="polri" {{ old('f_job', @$dad->job) === 'polri' ? 'selected' : NULL }}>Polri</option>
            <option
                value="karyawan swasta" {{ old('f_job', @$dad->job) === 'karyawan swasta' ? 'selected' : NULL }}>
                Karyawan Swasta
            </option>
            <option value="wiraswasta" {{ old('f_job', @$dad->job) === 'wiraswasta' ? 'selected' : NULL }}>
                Wiraswasta
            </option>
            <option value="pensiunan" {{ old('f_job', @$dad->job) === 'pensiunan' ? 'selected' : NULL }}>Pensiunan
            </option>
            <option value="lainnya" {{ old('f_job', @$dad->job) === 'lainnya' ? 'selected' : NULL }}>Lainnya
            </option>
        </select>
    </div>
</div>

<div class="col-md-6 mb-4">
    <label class="form-label fw-bold text-muted mb-2">Penghasilan Ayah</label>
    <div class="input-group modern-input-group">
        <select class="form-control required" placeholder="Pekerjaan Ayah" name="f_salary">
            <option value="">--Silahkan Pilih--</option>
            <option
                value="kurang dari Rp 500.000" {{ old('f_salary', @$dad->salary) === 'kurang dari Rp 500.000' ? 'selected' : NULL }}>
                kurang dari Rp 500.000
            </option>
            <option
                value="Rp 500.000 - Rp 999.999" {{ old('f_salary', @$dad->salary) === 'Rp 500.000 - Rp 999.999' ? 'selected': NULL }}>
                Rp 500.000 - Rp 999.999
            </option>
            <option
                value="Rp 1.000.000 - Rp 1.999.999" {{ old('f_salary', @$dad->salary) === 'Rp 1.000.000 - Rp 1.999.999' ? 'selected' : NULL }}>
                Rp 1.000.000 - Rp 1.999.999
            </option>
            <option
                value="Rp 2.000.000 - Rp 4.999.999" {{ old('f_salary', @$dad->salary) === 'Rp 2.000.000 - Rp 4.999.999' ? 'selected' : NULL }}>
                Rp 2.000.000 - Rp 4.999.999
            </option>
            <option
                value="Rp 5.000.000 - Rp 20.000.000" {{ old('f_salary', @$dad->salary) === 'Rp 5.000.000 - Rp 20.000.000' ? 'selected' : NULL }}>
                Rp 5.000.000 - Rp 20.000.0000
            </option>
            <option
                value="lebih dari Rp 20.000.000" {{ old('f_salary', @$dad->salary) === 'lebih dari Rp 20.000.000' ? 'selected' : NULL }}>
                lebih dari Rp 20.000.000
            </option>
        </select>
    </div>
</div>

