<div class="row">
    <div class="col-md-12 mb-4">
        <div class="form-group custom-form-group">
            <label class="form-label fw-bold text-muted mb-2">Nama Ibu</label>
            <div class="input-group modern-input-group">
                <input type="text" name="mother_name"
                       value="{{ (!empty(@$mom['name']))?$mom['name']:old('mother_name') }}"
                       class="form-control uppercase-input required" placeholder="Nama Ibu">
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="form-group custom-form-group">
            <label class="form-label fw-bold text-muted mb-2">Tempat Lahir</label>
            <div class="input-group modern-input-group">
                <input name="m_place_of_birth" class="form-control uppercase-input required" placeholder="Tempat Lahir"
                       value="{{ old('m_place_of_birth', @$mom->place_of_birth) }}"/>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="form-group custom-form-group">
            <label class="form-label fw-bold text-muted mb-2">Tanggal Lahir</label>
            <div class="input-group modern-input-group">
                <input type="date" class="form-control required" name="m_date_of_birth" placeholder="Tanggal Lahir"
                       value="{{ old('m_date_of_birth', @$mom->date_of_birth) }}"
                       id="datepicker-mother"/>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-4">
        <label class="form-label fw-bold text-muted mb-2">Alamat Ibu</label>
        <div class="input-group modern-input-group">
        <textarea class="form-control uppercase-input required" rows="3" name="m_address"
                  placeholder="Alamat">{{ (!empty(@$mom['address']))?$mom['address']:old('m_address') }}</textarea>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <label class="form-label fw-bold text-muted mb-2">Provinsi</label>
        <div class="input-group modern-input-group">
            <select name="m_region" id="m_region" class="form-control select2-provinces required">
                <option value=""></option>
                @foreach($provinces as $province)
                    <option value="{{ $province->name }}" {{ old('m_region',@$mom->region) == $province->name ? 'selected' : '' }}>
                        {{ $province->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <label class="form-label fw-bold text-muted mb-2">Kota</label>
        <div class="input-group modern-input-group">
            <select name="m_city" id="m_city" class="form-control select2-cities required" data-placeholder="Pilih Kota">
                <option value=""></option>
            </select>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-6 mb-4">
        <label class="form-label fw-bold text-muted mb-2">Kewarganegaraan Ibu</label>
        <div class="input-group modern-input-group">
            <select class="form-control required" placeholder="Kewarganegaraan" name="m_country">
                <option value="">--Silahkan Pilih--</option>
                <option value="WNI" {{ old('m_country', @$mom->country) === 'WNI' ? 'selected' : NULL }}>WNI
                </option>
                <option value="WNA" {{ old('m_country', @$mom->country) === 'WNA' ? 'selected' : NULL }}>WNA
                </option>
            </select>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <label class="form-label fw-bold text-muted mb-2">Agama Ibu</label>
        <div class="input-group modern-input-group">
            <select class="form-control required" placeholder="Agama" name="m_religion">
                <option value="">--Silahkan Pilih--</option>
                <option value="Katolik" {{ old('m_religion', @$mom->religion) === 'Katolik' ? 'selected' : NULL }}>
                    Katolik
                </option>
                <option value="Protestan" {{ old('m_religion', @$mom->religion) === 'Protestan' ? 'selected' : NULL }}>
                    Protestan
                </option>
                <option value="Islam" {{ old('m_religion', @$mom->religion) === 'Islam' ? 'selected' : NULL }}>Islam
                </option>
                <option value="Hindu" {{ old('m_religion', @$mom->religion) === 'Hindu' ? 'selected' : NULL }}>Hindu
                </option>
                <option value="Buddha" {{ old('m_religion', @$mom->religion) === 'Buddha' ? 'selected' : NULL }}>Buddha
                </option>
                <option value="Khonghucu" {{ old('m_religion', @$mom->religion) === 'Khonghucu' ? 'selected' : NULL }}>
                    Khonghucu
                </option>
            </select>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-6 mb-4">
        <label class="form-label fw-bold text-muted mb-2">No. Telp Ibu</label>
        <div class="input-group modern-input-group">
            <input type="text" pattern="[0-9]{7,13}" name="m_phone"
                   value="{{ (!empty(@$mom['phone']))?$mom['phone']:old('m_phone') }}"
                   class="form-control required" placeholder="Telepon">
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <label class="form-label fw-bold text-muted mb-2">Pendidikan Ibu</label>
        <div class="input-group modern-input-group">
            <select class="form-control required" placeholder="Pendidikan Ibu" name="m_education">
                <option value="">--Silahkan Pilih--</option>
                <option value="sd" {{ old('m_education', @$mom->education) === 'sd' ? 'selected' : NULL }}>SD</option>
                <option value="smp" {{ old('m_education', @$mom->education) === 'smp' ? 'selected' : NULL }}>SMP
                </option>
                <option value="sma" {{ old('m_education', @$mom->education) === 'sma' ? 'selected' : NULL }}>SMA
                </option>
                <option value="d1" {{ old('m_education', @$mom->education) === 'd1' ? 'selected' : NULL }}>D-1</option>
                <option value="d2" {{ old('m_education', @$mom->education) === 'd2' ? 'selected' : NULL }}>D-2</option>
                <option value="d3" {{ old('m_education', @$mom->education) === 'd3' ? 'selected' : NULL }}>D-3</option>
                <option value="s1" {{ old('m_education', @$mom->education) === 's1' ? 'selected' : NULL }}>S-1</option>
                <option value="s2" {{ old('m_education', @$mom->education) === 's2' ? 'selected' : NULL }}>S-2</option>
                <option value="s3" {{ old('m_education', @$mom->education) === 's3' ? 'selected' : NULL }}>S-3</option>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <label class="form-label fw-bold text-muted mb-2">Pekerjaan Ibu</label>
        <div class="input-group modern-input-group">
            <select class="form-control required" placeholder="Pekerjaan Ibu" name="m_job">
                <option value="">--Silahkan Pilih--</option>
                <option value="tidak bekerja" {{ old('m_job', @$mom->job) === 'tidak bekerja' ? 'selected' : NULL }}>
                    Tidak Bekerja
                </option>
                <option value="pns" {{ old('m_job', @$mom->job) === 'pns' ? 'selected' : NULL }}>PNS</option>
                <option value="tni" {{ old('m_job', @$mom->job) === 'tni' ? 'selected' : NULL }}>TNI</option>
                <option value="polri" {{ old('m_job', @$mom->job) === 'polri' ? 'selected' : NULL }}>Polri</option>
                <option
                    value="karyawan swasta" {{ old('m_job', @$mom->job) === 'karyawan swasta' ? 'selected' : NULL }}>
                    Karyawan Swasta
                </option>
                <option value="wiraswasta" {{ old('m_job', @$mom->job) === 'wiraswasta' ? 'selected' : NULL }}>
                    Wiraswasta
                </option>
                <option value="pensiunan" {{ old('m_job', @$mom->job) === 'pensiunan' ? 'selected' : NULL }}>Pensiunan
                </option>
                <option value="lainnya" {{ old('m_job', @$mom->job) === 'lainnya' ? 'selected' : NULL }}>Lainnya
                </option>
            </select>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <label class="form-label fw-bold text-muted mb-2">Penghasilan Ibu</label>
        <div class="input-group modern-input-group">
            <select class="form-control required" placeholder="Pekerjaan Ibu" name="m_salary">
                <option value="">--Silahkan Pilih--</option>
                <option
                    value="kurang dari Rp 500.000" {{ old('m_salary', @$mom->salary) === 'kurang dari Rp 500.000' ? 'selected' : NULL }}>
                    kurang dari Rp 500.000
                </option>
                <option
                    value="Rp 500.000 - Rp 999.999" {{ old('m_salary', @$mom->salary) === 'Rp 500.000 - Rp 999.999' ? 'selected': NULL }}>
                    Rp 500.000 - Rp 999.999
                </option>
                <option
                    value="Rp 1.000.000 - Rp 1.999.999" {{ old('m_salary', @$mom->salary) === 'Rp 1.000.000 - Rp 1.999.999' ? 'selected' : NULL }}>
                    Rp 1.000.000 - Rp 1.999.999
                </option>
                <option
                    value="Rp 2.000.000 - Rp 4.999.999" {{ old('m_salary', @$mom->salary) === 'Rp 2.000.000 - Rp 4.999.999' ? 'selected' : NULL }}>
                    Rp 2.000.000 - Rp 4.999.999
                </option>
                <option
                    value="Rp 5.000.000 - Rp 20.000.000" {{ old('m_salary', @$mom->salary) === 'Rp 5.000.000 - Rp 20.000.000' ? 'selected' : NULL }}>
                    Rp 5.000.000 - Rp 20.000.0000
                </option>
                <option
                    value="lebih dari Rp 20.000.000" {{ old('m_salary', @$mom->salary) === 'lebih dari Rp 20.000.000' ? 'selected' : NULL }}>
                    lebih dari Rp 20.000.000
                </option>
            </select>
        </div>
    </div>
</div>
