@extends('layouts.ppdb-online.main')
@section('content')
@php
$cities = [
  "Banda Aceh", "Langsa", "Lhokseumawe", "Sabang", "Subulussalam", "Denpasar", "Pangkalpinang", "Cilegon", "Serang", "Tangerang Selatan", "Tangerang", "Bengkulu", "Gorontalo", "Kota Administrasi Jakarta Barat", "Kota Administrasi Jakarta Pusat", "Kota Administrasi Jakarta Selatan", "Kota Administrasi Jakarta Timur", "Kota Administrasi Jakarta Utara", "Sungai Penuh", "Jambi", "Bandung", "Bekasi", "Bogor", "Cimahi", "Cirebon", "Depok", "Sukabumi", "Tasikmalaya", "Banjar", "Magelang", "Pekalongan", "Salatiga", "Semarang", "Surakarta", "Tegal", "Batu", "Blitar", "Kediri", "Madiun", "Malang", "Mojokerto", "Pasuruan", "Probolinggo", "Sidoarjo", "Surabaya", "Pontianak", "Singkawang", "Banjarbaru", "Banjarmasin", "Palangkaraya", "Balikpapan", "Bontang", "Samarinda", "Tarakan", "Batam", "Tanjungpinang", "Bandar Lampung", "Metro", "Ternate", "Tidore Kepulauan", "Ambon", "Tual", "Bima", "Mataram", "Kupang", "Sorong", "Jayapura", "Dumai", "Pekanbaru", "Makassar", "Palopo", "Parepare", "Palu", "Bau-Bau", "Kendari", "Bitung", "Kotamobagu", "Manado", "Tomohon", "Bukittinggi", "Padang", "Padangpanjang", "Pariaman", "Payakumbuh", "Sawahlunto", "Solok", "Lubuklinggau", "Pagaralam", "Palembang", "Prabumulih", "Binjai", "Medan", "Padang Sidempuan", "Pematangsiantar", "Sibolga", "Tanjungbalai", "Tebingtinggi", "Yogyakarta"
];
$provinces = [
    "Banda Aceh", "Sumatera Utara", "Sumatera Barat", "Riau", "Kepulauan Riau", "Jambi", "Sumatera Selatan", "Kepulauan Bangka Belitung", "Bengkulu", "Lampung", "DKI Jakarta", "Banten", "Jawa Barat", "Jawa Tengah", "DI Yogyakarta", "Jawa Timur", "Bali", "Nusa Tenggara Barat", "Nusa Tenggara Timur", "Kalimantan Barat", "Kalimantan Tengah", "Kalimantan Selatan", "Kalimantan Timur", "Kalimantan Utara", "Sulawesi Utara", "Gorontalo", "Sulawesi Tengah", "Sulawesi Barat", "Sulawesi Selatan", "Sulawesi Tenggara", "Maluku", "Maluku Utara", "Papua Barat", "Papua" 
    ];
@endphp
    <div class="row row-height">
        <div class="col content-top" id="start">
            <div id="wizard_container">
                <form id="wrapped" method="POST" autocomplete="off" action="{{route('ppdb.form-parent.submit')}}">
                    <input autocomplete="false" name="hidden" type="text" style="display:none;">
                    <div>
                        <h2>Identitas Orang Tua Calon Siswa</h2>
                        <br>
                        @if (!$ppdb->isWaliRequired)
                        <div class="form-group"><label><strong>Data Ayah</strong></label></div>
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul style="margin: 0 0 0 0;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="form-group">
                            <label class="control-label">Nama Ayah</label>
                            <input type="text" name="father_name"
                                   value="{{ (!empty(@$dad->name))?$dad->name:old('father_name') }}"
                                   class="form-control required" placeholder="Nama Ayah">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Tempat Lahir Ayah</label>
                            {{-- <select name="f_place_of_birth" class="form-control required" placeholder="Tempat Lahir">
                                @foreach ($cities as $city)
                                    <option value="{{$city}}" {{ old('f_place_of_birth', @$dad->place_of_birth) === $city ? 'selected' : NULL }}>{{ $city }}</option>
                                @endforeach
                            </select> --}}
                            <input name="f_place_of_birth" class="form-control required" placeholder="Tempat Lahir" value="{{ old('f_place_of_birth', @$dad->place_of_birth) }}" />
                        </div>
                        <div class="form-group">
                            <label class="control-label">Tanggal Lahir Ayah</label>
                            <input type="date" class="form-control required" name="f_date_of_birth" placeholder="Tanggal Lahir"
                                   value="{{ old('f_date_of_birth', @$dad->date_of_birth) }}"
                                   id="datepicker-father"/>
                        </div>
                        <br>
                        <div class="form-group">
                            <label class="control-label">Alamat Ayah</label>
                            <textarea class="form-control required" rows="3" name="f_address"
                                      placeholder="Alamat">{{ (!empty(@$dad['address']))?$dad['address']:old('f_address') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Kota</label>
                            {{-- <select name="f_city" class="form-control required" placeholder="Kota">
                                @foreach ($cities as $city)
                                    <option value="{{$city}}" {{ old('f_city', @$dad->city) === $city ? 'selected' : NULL }}> {{ $city }}</option>
                                @endforeach
                            </select> --}}
                            <input name="f_city" class="form-control required" placeholder="Kota" value="{{ old('f_city', @$dad->city) }}" />
                        </div>
                        <div class="form-group">
                            <label class="control-label">Provinsi</label>
                            {{-- <select name="f_region" class="form-control required" placeholder="Provinsi">
                                @foreach ($provinces as $province)
                                    <option value="{{$province}}" {{ old('f_region', @$dad->region) === $province ? 'selected' : NULL }}> {{ $province }}</option>
                                @endforeach
                            </select> --}}
                            <input name="f_region" class="form-control required" placeholder="Provinsi" value="{{ old('f_region', @$dad->region) }}" />
                        </div>
                        <div class="form-group">
                            <label class="control-label">Kewarganegaraan Ayah</label>
                            <select class="form-control required" placeholder="Kewarganegaraan" name="f_country">
                                <option value="WNI" {{ old('f_country', @$dad->country) === 'WNI' ? 'selected' : NULL }}>WNI
                                </option>
                                <option value="WNA" {{ old('f_country', @$dad->country) === 'WNA' ? 'selected' : NULL }}>WNA
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Agama Ayah</label>
                            <select class="form-control required" placeholder="Agama" name="f_religion">
                                <option value="Katolik" {{ old('f_religion', @$dad->religion) === 'Katolik' ? 'selected' : NULL }}>Katolik
                                </option>
                                <option value="Protestan" {{ old('f_religion', @$dad->religion) === 'Protestan' ? 'selected' : NULL }}>Protestan
                                </option>
                                <option value="Islam" {{ old('f_religion', @$dad->religion) === 'Islam' ? 'selected' : NULL }}>Islam
                                </option>
                                <option value="Hindu" {{ old('f_religion', @$dad->religion) === 'Hindu' ? 'selected' : NULL }}>Hindu
                                </option>
                                <option value="Buddha" {{ old('f_religion', @$dad->religion) === 'Buddha' ? 'selected' : NULL }}>Buddha
                                </option>
                                <option value="Khonghucu" {{ old('f_religion', @$dad->religion) === 'Khonghucu' ? 'selected' : NULL }}>Khonghucu
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Nomor Telepon Ayah</label>
                            <input type="text" pattern="0-9" name="f_phone"
                                   value="{{ (!empty(@$dad['phone']))?$dad['phone']:old('f_phone') }}"
                                   class="form-control required" placeholder="Telepon">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Pendidikan Ayah</label>
                            <select class="form-control required" placeholder="Pendidikan Ayah" name="f_education">
                                <option value="sd" {{ old('f_education', @$dad->education) === 'sd' ? 'selected' : NULL }}>SD</option>
                                <option value="smp" {{ old('f_education', @$dad->education) === 'smp' ? 'selected' : NULL }}>SMP</option>
                                <option value="sma" {{ old('f_education', @$dad->education) === 'sma' ? 'selected' : NULL }}>SMA</option>
                                <option value="d1" {{ old('f_education', @$dad->education) === 'd1' ? 'selected' : NULL }}>D-1</option>
                                <option value="d2" {{ old('f_education', @$dad->education) === 'd2' ? 'selected' : NULL }}>D-2</option>
                                <option value="d3" {{ old('f_education', @$dad->education) === 'd3' ? 'selected' : NULL }}>D-3</option>
                                <option value="s1" {{ old('f_education', @$dad->education) === 's1' ? 'selected' : NULL }}>S-1</option>
                                <option value="s2" {{ old('f_education', @$dad->education) === 's2' ? 'selected' : NULL }}>S-2</option>
                                <option value="s3" {{ old('f_education', @$dad->education) === 's3' ? 'selected' : NULL }}>S-3</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Pekerjaan Ayah</label>
                            <select class="form-control required" placeholder="Pekerjaan Ayah" name="f_job">
                                <option value="tidak bekerja" {{ old('f_job', @$dad->job) === 'tidak bekerja' ? 'selected' : NULL }}>Tidak Bekerja</option>
                                <option value="pns" {{ old('f_job', @$dad->job) === 'pns' ? 'selected' : NULL }}>PNS</option>
                                <option value="tni" {{ old('f_job', @$dad->job) === 'tni' ? 'selected' : NULL }}>TNI</option>
                                <option value="polri" {{ old('f_job', @$dad->job) === 'polri' ? 'selected' : NULL }}>Polri</option>
                                <option value="karyawan swasta" {{ old('f_job', @$dad->job) === 'karyawan swasta' ? 'selected' : NULL }}>Karyawan Swasta</option>
                                <option value="wiraswasta" {{ old('f_job', @$dad->job) === 'wiraswasta' ? 'selected' : NULL }}>Wiraswasta</option>
                                <option value="pensiunan" {{ old('f_job', @$dad->job) === 'pensiunan' ? 'selected' : NULL }}>Pensiunan</option>
                                <option value="lainnya" {{ old('f_job', @$dad->job) === 'lainnya' ? 'selected' : NULL }}>Lainnya</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Penghasilan Ayah</label>
                            <select class="form-control required" placeholder="Pekerjaan Ayah" name="f_salary">
                                <option value="">Penghasilan Ayah</option>
                                <option value="kurang dari Rp 500.000" {{ old('f_salary', @$dad->salary) === 'kurang dari Rp 500.000' ? 'selected' : NULL }}>kurang dari Rp 500.000</option>
                                <option value="Rp 500.000 - Rp 999.999" {{ old('f_salary', @$dad->salary) === 'Rp 500.000 - Rp 999.999' ? 'selected': NULL }}>Rp 500.000 - Rp 999.999</option>
                                <option value="Rp 1.000.000 - Rp 1.999.999" {{ old('f_salary', @$dad->salary) === 'Rp 1.000.000 - Rp 1.999.999' ? 'selected' : NULL }}>Rp 1.000.000 - Rp 1.999.999</option>
                                <option value="Rp 2.000.000 - Rp 4.999.999" {{ old('f_salary', @$dad->salary) === 'Rp 2.000.000 - Rp 4.999.999' ? 'selected' : NULL }}>Rp 2.000.000 - Rp 4.999.999</option>
                                <option value="Rp 5.000.000 - Rp 20.000.000" {{ old('f_salary', @$dad->salary) === 'Rp 5.000.000 - Rp 20.000.000' ? 'selected' : NULL }}>Rp 5.000.000 - Rp 20.000.0000</option>
                                <option value="lebih dari Rp 20.000.000" {{ old('f_salary', @$dad->salary) === 'lebih dari Rp 20.000.000' ? 'selected' : NULL }}>lebih dari Rp 20.000.000</option>
                            </select>
                        </div>
                        <hr>

                        <div class="form-group"><label> <strong>Data Ibu</strong></label></div>
                        <div class="form-group">
                            <label class="control-label">Nama Ibu</label>
                            <input type="text" name="mother_name"
                                   value="{{ (!empty(@$mom['name']))?$mom['name']:old('mother_name') }}"
                                   class="form-control required" placeholder="Nama Ibu">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Tempat Lahir Ibu</label>
                            {{-- <select name="m_place_of_birth" class="form-control required" placeholder="Tempat Lahir">
                                @foreach ($cities as $city)
                                    <option value="{{$city}}" {{ old('m_place_of_birth', @$mom->place_of_birth) === $city ? 'selected' : NULL }}>{{ $city }}</option>
                                @endforeach
                            </select> --}}
                            <input name="m_place_of_birth" class="form-control required" placeholder="Tempat Lahir" value="{{ old('m_place_of_birth', @$mom->place_of_birth) }}" />
                        </div>
                        <div class="form-group">
                            <label class="control-label">Tanggal Lahir Ibu</label>
                            <input type="date" class="form-control required" name="m_date_of_birth" placeholder="Tanggal Lahir"
                                   value="{{ old('m_date_of_birth', @$mom->date_of_birth) }}"
                                   id="datepicker-mother"/>
                        </div>
                        <br>
                        <div class="form-group">
                            <label class="control-label">Alamat Ibu</label>
                            <textarea class="form-control required" rows="3" name="m_address"
                                      placeholder="Alamat">{{ (!empty(@$mom['address']))?$mom['address']:old('m_address') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Kota</label>
                            {{-- <select name="m_city" class="form-control required" placeholder="Kota">
                                @foreach ($cities as $city)
                                    <option value="{{$city}}" {{ old('m_city', @$mom->city) === $city ? 'selected' : NULL }}> {{ $city }}</option>
                                @endforeach
                            </select> --}}
                            <input name="m_city" class="form-control required" placeholder="Kota" value="{{ old('m_city', @$mom->city) }}" />
                        </div>
                        <div class="form-group">
                            <label class="control-label">Provinsi</label>
                            {{-- <select name="m_region" class="form-control required" placeholder="Provinsi">
                                @foreach ($provinces as $province)
                                    <option value="{{$province}}" {{ old('m_region', @$mom->region) === $province ? 'selected' : NULL }}> {{ $province }}</option>
                                @endforeach
                            </select> --}}
                            <input name="m_region" class="form-control required" placeholder="Provinsi" value="{{ old('m_region', @$mom->region) }}" />
                        </div>
                        <div class="form-group">
                            <label class="control-label">Kewarganegaraan Ibu</label>
                            <select class="form-control required" placeholder="Kewarganegaraan" name="m_country">
                                <option value="WNI" {{ old('m_country', @$mom->country) === 'WNI' ? 'selected' : NULL }}>WNI
                                </option>
                                <option value="WNA" {{ old('m_country', @$mom->country) === 'WNA' ? 'selected' : NULL }}>WNA
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Agama Ibu</label>
                            <select class="form-control required" placeholder="Agama" name="m_religion">
                                <option value="Katolik" {{ old('m_religion', @$mom->religion) === 'Katolik' ? 'selected' : NULL }}>Katolik
                                </option>
                                <option value="Protestan" {{ old('m_religion', @$mom->religion) === 'Protestan' ? 'selected' : NULL }}>Protestan
                                </option>
                                <option value="Islam" {{ old('m_religion', @$mom->religion) === 'Islam' ? 'selected' : NULL }}>Islam
                                </option>
                                <option value="Hindu" {{ old('m_religion', @$mom->religion) === 'Hindu' ? 'selected' : NULL }}>Hindu
                                </option>
                                <option value="Buddha" {{ old('m_religion', @$mom->religion) === 'Buddha' ? 'selected' : NULL }}>Buddha
                                </option>
                                <option value="Khonghucu" {{ old('m_religion', @$mom->religion) === 'Khonghucu' ? 'selected' : NULL }}>Khonghucu
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Nomor Telepon Ibu</label>
                            <input type="text" pattern="0-9" name="m_phone"
                                   value="{{ (!empty(@$mom['phone']))?$mom['phone']:old('m_phone') }}"
                                   class="form-control required" placeholder="Telepon">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Pendidikan Ibu</label>
                            <select class="form-control required" placeholder="Pendidikan Ibu" name="m_education">
                                <option value="sd" {{ old('m_education', @$mom->education) === 'sd' ? 'selected' : NULL }}>SD</option>
                                <option value="smp" {{ old('m_education', @$mom->education) === 'smp' ? 'selected' : NULL }}>SMP</option>
                                <option value="sma" {{ old('m_education', @$mom->education) === 'sma' ? 'selected' : NULL }}>SMA</option>
                                <option value="d1" {{ old('m_education', @$mom->education) === 'd1' ? 'selected' : NULL }}>D-1</option>
                                <option value="d2" {{ old('m_education', @$mom->education) === 'd2' ? 'selected' : NULL }}>D-2</option>
                                <option value="d3" {{ old('m_education', @$mom->education) === 'd3' ? 'selected' : NULL }}>D-3</option>
                                <option value="s1" {{ old('m_education', @$mom->education) === 's1' ? 'selected' : NULL }}>S-1</option>
                                <option value="s2" {{ old('m_education', @$mom->education) === 's2' ? 'selected' : NULL }}>S-2</option>
                                <option value="s3" {{ old('m_education', @$mom->education) === 's3' ? 'selected' : NULL }}>S-3</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Pekerjaan Ibu</label>
                            <select class="form-control required" placeholder="Pekerjaan Ibu" name="m_job">
                                <option value="tidak bekerja" {{ old('m_job', @$mom->job) === 'tidak bekerja' ? 'selected' : NULL }}>Tidak Bekerja</option>
                                <option value="pns" {{ old('m_job', @$mom->job) === 'pns' ? 'selected' : NULL }}>PNS</option>
                                <option value="tni" {{ old('m_job', @$mom->job) === 'tni' ? 'selected' : NULL }}>TNI</option>
                                <option value="polri" {{ old('m_job', @$mom->job) === 'polri' ? 'selected' : NULL }}>Polri</option>
                                <option value="karyawan swasta" {{ old('m_job', @$mom->job) === 'karyawan swasta' ? 'selected' : NULL }}>Karyawan Swasta</option>
                                <option value="wiraswasta" {{ old('m_job', @$mom->job) === 'wiraswasta' ? 'selected' : NULL }}>Wiraswasta</option>
                                <option value="pensiunan" {{ old('m_job', @$mom->job) === 'pensiunan' ? 'selected' : NULL }}>Pensiunan</option>
                                <option value="lainnya" {{ old('m_job', @$mom->job) === 'lainnya' ? 'selected' : NULL }}>Lainnya</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Penghasilan Ibu</label>
                            <select class="form-control required" placeholder="Pekerjaan Ibu" name="m_salary">
                                <option value="kurang dari Rp 500.000" {{ old('m_salary', @$mom->salary) === 'kurang dari Rp 500.000' ? 'selected' : NULL }}>kurang dari Rp 500.000</option>
                                <option value="Rp 500.000 - Rp 999.999" {{ old('m_salary', @$mom->salary) === 'Rp 500.000 - Rp 999.999' ? 'selected': NULL }}>Rp 500.000 - Rp 999.999</option>
                                <option value="Rp 1.000.000 - Rp 1.999.999" {{ old('m_salary', @$mom->salary) === 'Rp 1.000.000 - Rp 1.999.999' ? 'selected' : NULL }}>Rp 1.000.000 - Rp 1.999.999</option>
                                <option value="Rp 2.000.000 - Rp 4.999.999" {{ old('m_salary', @$mom->salary) === 'Rp 2.000.000 - Rp 4.999.999' ? 'selected' : NULL }}>Rp 2.000.000 - Rp 4.999.999</option>
                                <option value="Rp 5.000.000 - Rp 20.000.000" {{ old('m_salary', @$mom->salary) === 'Rp 5.000.000 - Rp 20.000.000' ? 'selected' : NULL }}>Rp 5.000.000 - Rp 20.000.0000</option>
                                <option value="lebih dari Rp 20.000.000" {{ old('m_salary', @$mom->salary) === 'lebih dari Rp 20.000.000' ? 'selected' : NULL }}>lebih dari Rp 20.000.000</option>
                            </select>
                        </div>
                        @else
                        <hr>

                        <div class="form-group"><label> <strong>Data Wali</strong> </label></div>
                        <div class="form-group">
                            <label class="control-label">Nama Wali</label>
                            <input type="text" name="wali_name"
                                   value="{{ (!empty(@$wali['name']))?$wali['name']:old('wali_name') }}"
                                   class="form-control required" placeholder="Nama Wali">
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <label class="control-label">Tempat Lahir Wali</label>
                                <select name="w_place_of_birth" class="form-control required" placeholder="Tempat Lahir">
                                    @foreach ($cities as $city)
                                        <option value="{{$city}}" {{ old('w_place_of_birth', @$wali->place_of_birth) === $city ? 'selected' : NULL }}>{{ $city }}</option>
                                    @endforeach
                                </select>
                                <input name="w_place_of_birth" class="form-control required" placeholder="Tempat Lahir" value="{{ old('w_place_of_birth', @$wali->place_of_birth) }}" />
                            </div>
                            <div class="col">
                                <label class="control-label">Tanggal Lahir Wali</label>
                                <input type="date" class="form-control required" name="w_date_of_birth"
                                       value="{{ old('w_date_of_birth', @$wali->date_of_birth) }}"
                                       id="datepicker-wali"/>
                            </div>
                        </div>
                        <br>
                        <div class="form-group">
                            <label class="control-label">Alamat Wali</label>
                            <textarea class="form-control required" rows="3" name="w_address"
                                      placeholder="Alamat">{{ (!empty(@$wali['address']))?$wali['address']:old('w_address') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Kota</label>
                            <select name="w_city" class="form-control required" placeholder="Kota">
                                @foreach ($cities as $city)
                                    <option value="{{$city}}" {{ old('w_city', @$wali->city) === $city ? 'selected' : NULL }}> {{ $city }}</option>
                                @endforeach
                            </select>
                            <input name="w_city" class="form-control required" placeholder="Kota" value="{{ old('w_city', @$wali->city) }}" />
                        </div>
                        <div class="form-group">
                            <label class="control-label">Provinsi</label>
                            {{-- <select name="w_region" class="form-control required" placeholder="Provinsi">
                                @foreach ($provinces as $province)
                                    <option value="{{$province}}" {{ old('w_region', @$wali->region) === $province ? 'selected' : NULL }}> {{ $province }}</option>
                                @endforeach
                            </select> --}}
                            <input name="w_region" class="form-control required" placeholder="Provinsi" value="{{ old('w_region', @$wali->region) }}" />
                        </div>
                        <div class="form-group">
                            <label class="control-label">Kewarganegaraan Wali</label>
                                <select class="form-control required" placeholder="Kewarganegaraan" name="w_country">
                                    <option value="WNI" {{ old('w_country', @$wali->country) === 'WNI' ? 'selected' : NULL }}>WNI
                                    </option>
                                    <option value="WNA" {{ old('w_country', @$wali->country) === 'WNA' ? 'selected' : NULL }}>WNA
                                    </option>
                                </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Agama Wali</label>
                            <select class="form-control required" placeholder="Agama" name="w_religion">
                                <option value="Katolik" {{ old('w_religion', @$wali->religion) === 'Katolik' ? 'selected' : NULL }}>Katolik
                                </option>
                                <option value="Protestan" {{ old('w_religion', @$wali->religion) === 'Protestan' ? 'selected' : NULL }}>Protestan
                                </option>
                                <option value="Islam" {{ old('w_religion', @$wali->religion) === 'Islam' ? 'selected' : NULL }}>Islam
                                </option>
                                <option value="Hindu" {{ old('w_religion', @$wali->religion) === 'Hindu' ? 'selected' : NULL }}>Hindu
                                </option>
                                <option value="Buddha" {{ old('w_religion', @$wali->religion) === 'Buddha' ? 'selected' : NULL }}>Buddha
                                </option>
                                <option value="Khonghucu" {{ old('w_religion', @$wali->religion) === 'Khonghucu' ? 'selected' : NULL }}>Khonghucu
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Nomor Telepon Wali</label>
                            <input type="text" pattern="0-9" name="w_phone"
                                   value="{{ (!empty(@$wali['phone']))?$wali['phone']:old('w_phone') }}"
                                   class="form-control required" placeholder="Telepon">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Pendidikan Wali</label>
                            <select class="form-control required" placeholder="Pendidikan Wali" name="w_education">
                                <option value="sd" {{ old('w_education', @$wali->education) === 'sd' ? 'selected' : NULL }}>SD</option>
                                <option value="smp" {{ old('w_education', @$wali->education) === 'smp' ? 'selected' : NULL }}>SMP</option>
                                <option value="sma" {{ old('w_education', @$wali->education) === 'sma' ? 'selected' : NULL }}>SMA</option>
                                <option value="d1" {{ old('w_education', @$wali->education) === 'd1' ? 'selected' : NULL }}>D-1</option>
                                <option value="d2" {{ old('w_education', @$wali->education) === 'd2' ? 'selected' : NULL }}>D-2</option>
                                <option value="d3" {{ old('w_education', @$wali->education) === 'd3' ? 'selected' : NULL }}>D-3</option>
                                <option value="s1" {{ old('w_education', @$wali->education) === 's1' ? 'selected' : NULL }}>S-1</option>
                                <option value="s2" {{ old('w_education', @$wali->education) === 's2' ? 'selected' : NULL }}>S-2</option>
                                <option value="s3" {{ old('w_education', @$wali->education) === 's3' ? 'selected' : NULL }}>S-3</option>
                            </select>
                        </div>
                        <div class="form-group">
                        <label class="control-label">Pekerjaan Wali</label>
                                <select class="form-control required" placeholder="Pekerjaan Wali" name="w_job">
                                <option value="">Pekerjaan Wali</option>
                                <option value="tidak bekerja" {{ old('w_job', @$wali->job) === 'tidak bekerja' ? 'selected' : NULL }}>Tidak Bekerja</option>
                                <option value="pns" {{ old('w_job', @$wali->job) === 'pns' ? 'selected' : NULL }}>PNS</option>
                                <option value="tni" {{ old('w_job', @$wali->job) === 'tni' ? 'selected' : NULL }}>TNI</option>
                                <option value="polri" {{ old('w_job', @$wali->job) === 'polri' ? 'selected' : NULL }}>Polri</option>
                                <option value="karyawan swasta" {{ old('w_job', @$wali->job) === 'karyawan swasta' ? 'selected' : NULL }}>Karyawan Swasta</option>
                                <option value="wiraswasta" {{ old('w_job', @$wali->job) === 'wiraswasta' ? 'selected' : NULL }}>Wiraswasta</option>
                                <option value="pensiunan" {{ old('w_job', @$wali->job) === 'pensiunan' ? 'selected' : NULL }}>Pensiunan</option>
                                <option value="lainnya" {{ old('w_job', @$wali->job) === 'lainnya' ? 'selected' : NULL }}>Lainnya</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Penghasilan Wali</label>
                            <select class="form-control required" placeholder="Pekerjaan Wali" name="w_salary">
                                <option value="kurang dari Rp 500.000" {{ old('w_salary', @$wali->salary) === 'kurang dari Rp 500.000' ? 'selected' : NULL }}>kurang dari Rp 500.000</option>
                                <option value="Rp 500.000 - Rp 999.999" {{ old('w_salary', @$wali->salary) === 'Rp 500.000 - Rp 999.999' ? 'selected': NULL }}>Rp 500.000 - Rp 999.999</option>
                                <option value="Rp 1.000.000 - Rp 1.999.999" {{ old('w_salary', @$wali->salary) === 'Rp 1.000.000 - Rp 1.999.999' ? 'selected' : NULL }}>Rp 1.000.000 - Rp 1.999.999</option>
                                <option value="Rp 2.000.000 - Rp 4.999.999" {{ old('w_salary', @$wali->salary) === 'Rp 2.000.000 - Rp 4.999.999' ? 'selected' : NULL }}>Rp 2.000.000 - Rp 4.999.999</option>
                                <option value="Rp 5.000.000 - Rp 20.000.000" {{ old('w_salary', @$wali->salary) === 'Rp 5.000.000 - Rp 20.000.000' ? 'selected' : NULL }}>Rp 5.000.000 - Rp 20.000.0000</option>
                                <option value="lebih dari Rp 20.000" {{ old('w_salary', @$wali->salary) === 'lebih dari Rp 20.000' ? 'selected' : NULL }}>lebih dari Rp 20.000.000</option>
                            </select>
                        </div>
                        @endif
                        <div class="clear-50"></div>
                        <ul class="btn-below">
                            <li>
                                <a href="{{ route('ppdb.data-siswa-ppdb') }}">
                                    <button type="button" name="back" class="btn-back btn-disabled">Kembali</button>
                                </a>
                            </li>
                            <li>
                                <button type="submit" name="save" class="btn-save">Simpan</button>
                            </li>
                        </ul>
                    </div>
                    @csrf
                </form>
                <div class="clear-50"></div>
            </div>
            <!-- /Wizard container -->
        </div>
        <!-- /content-right-->
    </div>
    <!-- /row-->
@endsection
@push('styles')
<style>
    .btn {
        margin: 0;
        min-width: 45px;
        max-width: fit-content;
    }    
</style>    
@endpush
@push('scripts')
    <!-- Wizard script -->
    <script src="{{asset('frontend-ppdb-online/js/registration_func.js')}}"></script>
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <script>
        $('#datepicker-father').datepicker({
            uiLibrary: 'bootstrap4'
        });
        $('#datepicker-mother').datepicker({
            uiLibrary: 'bootstrap4'
        });
        $('#datepicker-wali').datepicker({
            uiLibrary: 'bootstrap4'
        });
    </script>
@endpush
