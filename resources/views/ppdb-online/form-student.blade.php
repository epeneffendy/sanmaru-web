@extends('layouts.ppdb-online.main')
@section('content')
@php
// $cities = [
//   "Banda Aceh", "Langsa", "Lhokseumawe", "Sabang", "Subulussalam", "Denpasar", "Pangkalpinang", "Cilegon", "Serang", "Tangerang Selatan", "Tangerang", "Bengkulu", "Gorontalo", "Kota Administrasi Jakarta Barat", "Kota Administrasi Jakarta Pusat", "Kota Administrasi Jakarta Selatan", "Kota Administrasi Jakarta Timur", "Kota Administrasi Jakarta Utara", "Sungai Penuh", "Jambi", "Bandung", "Bekasi", "Bogor", "Cimahi", "Cirebon", "Depok", "Sukabumi", "Tasikmalaya", "Banjar", "Magelang", "Pekalongan", "Salatiga", "Semarang", "Surakarta", "Tegal", "Batu", "Blitar", "Kediri", "Madiun", "Malang", "Mojokerto", "Pasuruan", "Probolinggo", "Sidoarjo", "Surabaya", "Pontianak", "Singkawang", "Banjarbaru", "Banjarmasin", "Palangkaraya", "Balikpapan", "Bontang", "Samarinda", "Tarakan", "Batam", "Tanjungpinang", "Bandar Lampung", "Metro", "Ternate", "Tidore Kepulauan", "Ambon", "Tual", "Bima", "Mataram", "Kupang", "Sorong", "Jayapura", "Dumai", "Pekanbaru", "Makassar", "Palopo", "Parepare", "Palu", "Bau-Bau", "Kendari", "Bitung", "Kotamobagu", "Manado", "Tomohon", "Bukittinggi", "Padang", "Padangpanjang", "Pariaman", "Payakumbuh", "Sawahlunto", "Solok", "Lubuklinggau", "Pagaralam", "Palembang", "Prabumulih", "Binjai", "Medan", "Padang Sidempuan", "Pematangsiantar", "Sibolga", "Tanjungbalai", "Tebingtinggi", "Yogyakarta"
// ];
// $provinces = [
//     "Banda Aceh", "Sumatera Utara", "Sumatera Barat", "Riau", "Kepulauan Riau", "Jambi", "Sumatera Selatan", "Kepulauan Bangka Belitung", "Bengkulu", "Lampung", "DKI Jakarta", "Banten", "Jawa Barat", "Jawa Tengah", "DI Yogyakarta", "Jawa Timur", "Bali", "Nusa Tenggara Barat", "Nusa Tenggara Timur", "Kalimantan Barat", "Kalimantan Tengah", "Kalimantan Selatan", "Kalimantan Timur", "Kalimantan Utara", "Sulawesi Utara", "Gorontalo", "Sulawesi Tengah", "Sulawesi Barat", "Sulawesi Selatan", "Sulawesi Tenggara", "Maluku", "Maluku Utara", "Papua Barat", "Papua"
//     ];
@endphp
@php($inputs = \App\Helpers\InputCollectionHelper::additionalData($ppdbUser->unit))
<div class="row row-height">
        <div class="col content-top" id="start">
            <div id="wizard_container">
                <form id="wrapped" method="POST" autocomplete="off" action="{{route('ppdb.form-student.submit')}}">
                        <input autocomplete="false" name="hidden" type="text" style="display:none;">
                        <div>
                            <h2>Identitas Calon Siswa</h2>
                            <br>
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
                                <label class="control-label">Nama Lengkap Calon Siswa</label>
                                <input type="text" name="name"
                                    value="{{ old('name', @$ppdbUser->name) }}"
                                    class="form-control required" placeholder="Nama Lengkap Calon Siswa">
                            </div>

                            <div class="form-group">
                                <label class="control-label">NIK Siswa</label>
                                <input type="text" name="nik_siswa" value="{{ old('nik_siswa', @$ppdbUser->nik_siswa) }}" class="form-control required"
                                    placeholder="NIK Siswa">
                            </div>

                            <div class="form-group">
                                <label class="control-label">Jenis Kelamin</label>
                                <select class="form-control required" placeholder="Jenis Kelamin" name="gender">
                                    <option value="male" {{ old('gender', @$ppdbUser->gender) === 'male' ? 'selected' : NULL }}>Laki-Laki
                                    </option>
                                    <option value="female" {{ old('gender', @$ppdbUser->gender) === 'female' ? 'selected' : NULL }}>Perempuan
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Tempat Lahir</label>
                                <select name="place_of_birth" id="place_of_birth" class="form-control required selectpicker" data-live-search="true" data-live-search-placeholder="Cari Kota" title="Pilih Kota">
                                    <option value="another_city">Kota Lainnya</option>
                                    @foreach ($cities as $key => $city)
                                    <option value="{{$city->city_name}}" {{ Str::lower(old('place_of_birth', @$ppdbUser->place_of_birth)) === $city->city_name ?
                                        'selected' : NULL }}>{{ ucwords($city->city_name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" id="another_city" style="display: none">
                                <label class="control-label">Kota Lainnya</label>
                                <input type="text" id="input_another_city" name="another_city" class="form-control" placeholder="Kota Lainnya"
                                    value="{{ old('city', ucwords(Str::lower(@$ppdbUser->place_of_birth))) }}" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Tanggal Lahir</label>
                                <input type="date" name="date_of_birth"
                                        value="{{ old('date_of_birth', @$ppdbUser->date_of_birth) }}"
                                        class="form-control required" id="datepicker"/>
                            </div>
                            <br>
                            <div class="form-group">
                                <label class="control-label">Alamat</label>
                                    <textarea class="form-control required" name="address" rows="3"
                                            placeholder="Alamat">{{ old('address', @$ppdbUser->address) }}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Kota</label>
                                {{-- <select name="city" class="form-control required" placeholder="Kota">
                                    @foreach ($cities as $city)
                                    <option value="{{$city}}" {{ old('city', @$ppdbUser->city) === $city ? 'selected' : NULL }}>{{ $city }}</option>
                                    @endforeach
                                </select> --}}
                                <input name="city" class="form-control required" placeholder="Kota" value="{{ old('city', @$ppdbUser->city) }}" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Provinsi</label>
                                {{-- <select name="region" class="form-control required" placeholder="Provinsi">
                                    @foreach ($provinces as $province)
                                    <option value="{{$province}}" {{ old('region', @$ppdbUser->region) === $province? 'selected' : NULL }}>{{ $province }}</option>
                                    @endforeach
                                </select> --}}
                                <input name="region" class="form-control required" placeholder="Provinsi" value="{{ old('region', @$ppdbUser->region) }}" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Kewarganegaraan</label>
                                <select class="form-control required" placeholder="Kewarganegaraan" name="country">
                                    <option value="WNI" {{ old('country', @$ppdbUser->country) === 'WNI' ? 'selected' : NULL }}>WNI
                                    </option>
                                    <option value="WNA" {{ old('country', @$ppdbUser->country) === 'WNA' ? 'selected' : NULL }}>WNA
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Agama</label>
                                <select class="form-control required" placeholder="Agama" name="religion">
                                    <option value="Katolik" {{ old('religion', @$ppdbUser->religion) === 'Katolik' ? 'selected' : NULL }}>Katolik
                                    </option>
                                    <option value="Protestan" {{ old('religion', @$ppdbUser->religion) === 'Protestan' ? 'selected' : NULL }}>Protestan
                                    </option>
                                    <option value="Islam" {{ old('religion', @$ppdbUser->religion) === 'Islam' ? 'selected' : NULL }}>Islam
                                    </option>
                                    <option value="Hindu" {{ old('religion', @$ppdbUser->religion) === 'Hindu' ? 'selected' : NULL }}>Hindu
                                    </option>
                                    <option value="Buddha" {{ old('religion', @$ppdbUser->religion) === 'Buddha' ? 'selected' : NULL }}>Buddha
                                    </option>
                                    <option value="Khonghucu" {{ old('religion', @$ppdbUser->religion) === 'Khonghucu' ? 'selected' : NULL }}>Khonghucu
                                    </option>
                                </select>
                            </div>
                            <hr class="clear-50" />
                            <h1>Data Tambahan</h1>
                            <fieldset>
                                <legend>Data Calon Peserta Didik</legend>
                                @if ($inputs->get('nama_siswa'))
                                <div class="form-group">
                                    <label class="control-label">Nama Lengkap (Sesuai Akta Kelahiran)</label>
                                    <input type="text" name="nama_siswa"
                                        value="{{ old('nama_siswa', @$ppdbUser->nama_siswa) }}"
                                        class="form-control required" placeholder="Nama Siswa (Sesuai Akta Kelahiran)">
                                </div>
                                @endif

                                @if ($inputs->get('nama_panggilan'))
                                <div class="form-group">
                                    <label class="control-label">Nama Panggilan</label>
                                    <input type="text" name="nama_panggilan"
                                        value="{{ old('nama_panggilan', @$ppdbUser->nama_panggilan) }}"
                                        class="form-control required" placeholder="Nama Panggilan">
                                </div>
                                @endif

                                @if ($inputs->get('jumlah_saudara_kandung'))
                                <div class="form-group">
                                    <label class="control-label">Jumlah Saudara Kandung</label>
                                    <input type="number" name="jumlah_saudara_kandung"
                                        value="{{ old('jumlah_saudara_kandung', @$ppdbUser->jumlah_saudara_kandung) }}"
                                        class="form-control required" placeholder="Jumlah Saudara Kandung">
                                </div>
                                @endif

                                @if ($inputs->get('anak_ke'))
                                <div class="form-group">
                                    <label class="control-label">Anak Ke</label>
                                    <input type="text" name="anak_ke"
                                        value="{{ old('anak_ke', @$ppdbUser->anak_ke) }}"
                                        class="form-control required" placeholder="Anak Ke">
                                </div>
                                @endif

                                @if ($inputs->get('jumlah_saudara_tiri'))
                                <div class="form-group">
                                    <label class="control-label">Jumlah Saudara Tiri</label>
                                    <input type="text" name="jumlah_saudara_tiri"
                                        value="{{ old('jumlah_saudara_tiri', @$ppdbUser->jumlah_saudara_tiri) }}"
                                        class="form-control required" placeholder="Jumlah Saudara Tiri">
                                </div>
                                @endif

                                @if ($inputs->get('nama_saudara_se_sekolah'))
                                <div class="form-group">
                                    <label class="control-label">Nama Adik / Kakak / Saudara yang sekolah di Santa Maria (Nama + Unit)</label>
                                    <input type="text" name="nama_saudara_se_sekolah"
                                        value="{{ old('nama_saudara_se_sekolah', @$ppdbUser->nama_saudara_se_sekolah) }}"
                                        class="form-control required" placeholder="Nama Saudara">
                                </div>
                                @endif

                                @if ($inputs->get('status_orangtua'))
                                <div class="form-group required">
                                    <label class="form-label" style="display: block">
                                        Status Anak & Orangtua
                                    </label>
                                    <label><input type="radio" name="status_orangtua" {{ old('status_orangtua', @$ppdbUser->status_orangtua) == 'yatim' ? 'checked' : null }} value="yatim" /> Yatim</label>
                                    <label><input type="radio" name="status_orangtua" {{ old('status_orangtua', @$ppdbUser->status_orangtua) == 'piatu' ? 'checked' : null }} value="piatu" /> Piatu</label>
                                    <label><input type="radio" name="status_orangtua" {{ old('status_orangtua', @$ppdbUser->status_orangtua) == 'yatim piatu' ? 'checked' : null }} value="yatim piatu" /> Yatim Piatu</label>
                                    <label><input type="radio" name="status_orangtua" {{ old('status_orangtua', @$ppdbUser->status_orangtua) == 'bukan yatim piatu' ? 'checked' : null }} value="bukan yatim piatu" /> Bukan Yatim Piatu</label>
                                </div>
                                @endif

                                @if ($inputs->get('bahasa'))
                                <div class="form-group">
                                    <label class="control-label">Bahasa Sehari-hari</label>
                                    <select name="bahasa" class="form-control required">
                                        <option value=""></option>
                                        <option value="Bahasa Indonesia" {!! old('bahasa', @$ppdbUser->bahasa) == 'Bahasa Indonesia' ? 'selected="true"' : null !!}>Bahasa Indonesia</option>
                                        <option value="Bahasa Inggris" {!! old('bahasa', @$ppdbUser->bahasa) == 'Bahasa Inggris' ? 'selected="true"' : null !!}>Bahasa Inggris</option>
                                        @if (old('bahasa', @$ppdbUser->bahasa) && !in_array(old('bahasa', @$ppdbUser->bahasa), ['Bahasa Inggris', 'Bahasa Indonesia']))
                                            <option value="{{ old('bahasa', @$ppdbUser->bahasa) }}">{{ old('bahasa', @$ppdbUser->bahasa) }}</option>
                                        @endif
                                    </select>
                                </div>
                                @endif

                                @if ($inputs->get('alamat_sesuai_kk'))
                                <div class="form-group">
                                    <label class="control-label">Alamat Sesuai KK</label>
                                    <textarea class="form-control required" name="alamat_sesuai_kk" rows="3" placeholder="Alamat Sesuai KK">{{ old('alamat_sesuai_kk', @$ppdbUser->alamat_sesuai_kk) }}</textarea>
                                </div>
                                @endif

                                @if ($inputs->get('alamat_tempat_tinggal'))
                                <div class="form-group">
                                    <label class="control-label">Alamat Tempat Tinggal</label>
                                        <textarea class="form-control required" name="alamat_tempat_tinggal" rows="3"
                                                placeholder="Alamat Tempat Tinggal">{{ old('alamat_tempat_tinggal', @$ppdbUser->alamat_tempat_tinggal) }}</textarea>
                                </div>
                                @endif

                                @if ($inputs->get('tinggal_dengan'))
                                <div class="form-group">
                                    <label class="control-label">Bertempat Tinggal pada</label>
                                    <select class="form-control required" placeholder="Tinggal Dengan" name="tinggal_dengan">
                                        <option value=""></option>
                                        <option value="orang tua" {{ old('tinggal_dengan', @$ppdbUser->tinggal_dengan) === 'orang tua' ? 'selected' : NULL }}>Orang Tua
                                        </option>
                                        <option value="wali" {{ old('tinggal_dengan', @$ppdbUser->tinggal_dengan) === 'wali' ? 'selected' : NULL }}>Wali
                                        </option>
                                        <option value="saudara" {{ old('tinggal_dengan', @$ppdbUser->tinggal_dengan) === 'saudara' ? 'selected' : NULL }}>Saudara</option>
                                        <option value="asrama" {{ old('tinggal_dengan', @$ppdbUser->tinggal_dengan) === 'asrama' ? 'selected' : NULL }}>Asrama</option>
                                        <option value="kost" {{ old('tinggal_dengan', @$ppdbUser->tinggal_dengan) === 'kost' ? 'selected' : NULL }}>Kost</option>
                                        <option value="panti asuhan" {{ old('tinggal_dengan', @$ppdbUser->tinggal_dengan) === 'panti asuhan' ? 'selected' : NULL }}>Panti Asuhan</option>
                                        <option value="lainnya" {{ old('tinggal_dengan', @$ppdbUser->tinggal_dengan) === 'lainnya' ? 'selected' : NULL }}>Lainnya</option>
                                    </select>
                                </div>
                                @endif

                                @if ($inputs->get('jarak_tempat_tinggal'))
                                <div class="form-group">
                                    <label class="control-label">Jarak Tempat Tinggal ke Sekolah</label>
                                    <div class="d-flex">
                                        <input type="number" name="jarak_tempat_tinggal"
                                            value="{{ old('jarak_tempat_tinggal', @$ppdbUser->jarak_tempat_tinggal) }}"
                                            class="form-control required" placeholder="Jarak Tempat Tinggal">
                                        <div class="input-group-append">
                                            <span class="input-group-text">km</span>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if ($inputs->get('waktu_tempuh'))
                                <div class="form-group">
                                    <label class="control-label">Waktu Tempuh Berangkat ke Sekolah</label>
                                    <div class="d-flex">
                                        <input type="number" name="waktu_tempuh"
                                            value="{{ old('waktu_tempuh', @$ppdbUser->waktu_tempuh) }}"
                                            class="form-control required" placeholder="Waktu Tempuh">
                                        <div class="input-group-append">
                                            <span class="input-group-text">menit</span>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if ($inputs->get('no_akta_kelahiran'))
                                <div class="form-group">
                                    <label class="control-label">No. Registrasi Akta Kelahiran</label>
                                    <input type="text" name="no_akta_kelahiran"
                                        value="{{ old('no_akta_kelahiran', @$ppdbUser->no_akta_kelahiran) }}"
                                        class="form-control required" placeholder="No Akta Kelahiran">
                                </div>
                                @endif

                                @if ($inputs->get('nik_ayah'))
                                <div class="form-group">
                                    <label class="control-label">NIK Orang Tua (Ayah)</label>
                                    <input type="text" name="nik_ayah"
                                        value="{{ old('nik_ayah', @$ppdbUser->nik_ayah) }}"
                                        class="form-control required" placeholder="NIK AYAH">
                                </div>
                                @endif

                                @if ($inputs->get('nik_ibu'))
                                <div class="form-group">
                                    <label class="control-label">NIK Orang Tua (Ibu)</label>
                                    <input type="text" name="nik_ibu"
                                        value="{{ old('nik_ibu', @$ppdbUser->nik_ibu) }}"
                                        class="form-control required" placeholder="NIK IBU">
                                </div>
                                @endif

                                {{-- Bekas NPWP, nanti tambah case jika yg diminta kedua NIK Ortu   --}}
                                {{-- agar tidak redundant                                           --}}
                                {{-- @if ($inputs->get('nik_ortu')) --}}
                                <div class="form-group">
                                    <label class="control-label">NIK Orang Tua atau Wali</label>
                                    <input type="text" name="nik_ortu" value="{{ old('nik_ortu', @$ppdbUser->nik_ortu) }}"
                                    class="form-control required" placeholder="NIK Orang Tua atau Wali">
                                </div>
                                {{-- @endif --}}

                                @if ($inputs->get('penanggungjawab_biaya'))
                                <div class="form-group">
                                    <label class="control-label">Penanggungjawab Biaya Pendidikan</label>
                                    <input type="text" name="penanggungjawab_biaya"
                                        value="{{ old('penanggungjawab_biaya', @$ppdbUser->penanggungjawab_biaya) }}"
                                        class="form-control required" placeholder="Penanggungjawab Biaya">
                                </div>
                                @endif
                            </fieldset>

                            <fieldset>
                                <legend>Asal Sekolah Calon Peserta Didik</legend>
                                @if ($inputs->get('asal_sekolah'))
                                <div class="form-group">
                                    <label class="control-label">Asal Sekolah</label>
                                    <input type="text" name="asal_sekolah"
                                        value="{{ old('asal_sekolah', @$ppdbUser->asal_sekolah) }}"
                                        class="form-control required" placeholder="Asal Sekolah">
                                </div>
                                @endif

                                @if ($inputs->get('alamat_asal_sekolah'))
                                <div class="form-group">
                                    <label class="control-label">Alamat Asal Sekolah</label>
                                    <input type="text" name="alamat_asal_sekolah"
                                        value="{{ old('alamat_asal_sekolah', @$ppdbUser->alamat_asal_sekolah) }}"
                                        class="form-control required" placeholder="Alamat Asal Sekolah">
                                </div>
                                @endif

                                @if ($inputs->get('kabupaten_asal_sekolah'))
                                <div class="form-group">
                                    <label class="control-label">Kabupaten Asal Sekolah</label>
                                    <input type="text" name="kabupaten_asal_sekolah"
                                        value="{{ old('kabupaten_asal_sekolah', @$ppdbUser->kabupaten_asal_sekolah) }}"
                                        class="form-control required" placeholder="Kabupaten Asal Sekolah">
                                </div>
                                @endif

                                @if ($inputs->get('kecamatan_asal_sekolah'))
                                <div class="form-group">
                                    <label class="control-label">Kecamatan Asal Sekolah</label>
                                    <input type="text" name="kecamatan_asal_sekolah"
                                        value="{{ old('kecamatan_asal_sekolah', @$ppdbUser->kecamatan_asal_sekolah) }}"
                                        class="form-control required" placeholder="Kecamatan Asal Sekolah">
                                </div>
                                @endif

                                @if ($inputs->get('kelurahan_asal_sekolah'))
                                <div class="form-group">
                                    <label class="control-label">Kelurahan Asal Sekolah</label>
                                    <input type="text" name="kelurahan_asal_sekolah"
                                        value="{{ old('kelurahan_asal_sekolah', @$ppdbUser->kelurahan_asal_sekolah) }}"
                                        class="form-control required" placeholder="Kelurahan Asal Sekolah">
                                </div>
                                @endif

                                @if ($inputs->get('kota_asal_sekolah'))
                                <div class="form-group">
                                    <label class="control-label">Kota Asal Sekolah</label>
                                    {{-- <select name="kota_asal_sekolah" class="form-control required" placeholder="Kota Asal Sekolah">
                                        @foreach ($cities as $city)
                                            <option value="{{$city}}" {{ old('kota_asal_sekolah', @$ppdbUser->kota_asal_sekolah) === $city ? 'selected' : NULL }}>{{ $city }}</option>
                                        @endforeach
                                    </select> --}}
                                    <input name="kota_asal_sekolah" class="form-control required" placeholder="Kota Asal Sekolah" value="{{ old('kota_asal_sekolah', @$ppdbUser->kota_asal_sekolah) }}" />
                                </div>
                                @endif

                                @if ($inputs->get('provinsi_asal_sekolah'))
                                <div class="form-group">
                                    <label class="control-label">Provinsi Asal Sekolah</label>
                                    {{-- <select name="provinsi_asal_sekolah" class="form-control required" placeholder="Provinsi Asal Sekolah">
                                        @foreach ($provinces as $province)
                                        <option value="{{$province}}" {{ old('provinsi_asal_sekolah', @$ppdbUser->provinsi_asal_sekolah) === $province? 'selected' : NULL }}>{{ $province }}</option>
                                        @endforeach
                                    </select> --}}
                                    <input name="provinsi_asal_sekolah" class="form-control required" placeholder="Provinsi Asal Sekolah" value="{{ old('provinsi_asal_sekolah', @$ppdbUser->provinsi_asal_sekolah) }}" />
                                </div>
                                @endif

                                @if ($inputs->get('nomor_telepon_asal_sekolah'))
                                <div class="form-group">
                                    <label class="control-label">Nomor Telepon Asal Sekolah</label>
                                    <input type="text" pattern="0-9" name="nomor_telepon_asal_sekolah"
                                        value="{{ old('nomor_telepon_asal_sekolah', @$ppdbUser->nomor_telepon_asal_sekolah) }}"
                                        class="form-control required" placeholder="Nomor Telepon Asal Sekolah">
                                </div>
                                @endif

                                @if ($inputs->get('transportasi_ke_sekolah'))
                                <div class="form-group">
                                    <label class="control-label">Transportasi Ke Sekolah</label>
                                    <input type="text" name="transportasi_ke_sekolah"
                                        value="{{ old('transportasi_ke_sekolah', @$ppdbUser->transportasi_ke_sekolah) }}"
                                        class="form-control required" placeholder="Transportasi ke Sekolah">
                                </div>
                                @endif

                                @if ($inputs->get('nisn'))
                                <div class="form-group">
                                    <label class="control-label">NISN (Nomor Induk Siswa Nasional)</label>
                                    <input type="text" name="nisn"
                                        value="{{ old('nisn', @$ppdbUser->nisn) }}"
                                        class="form-control required" placeholder="NISN">
                                </div>
                                @endif

                                @if ($inputs->get('tahun_lulus'))
                                <div class="form-group">
                                    <label class="control-label">Tahun Lulus</label>
                                    <input type="number" min="1900" max="2099" step="1" name="tahun_lulus" value="{{ old('tahun_lulus', @$ppdbUser->tahun_lulus) }}" class="form-control required" placeholder="Tahun Lulus">
                                </div>
                                @endif

                                @if ($inputs->get('nomor_seri_shun'))
                                <div class="form-group">
                                    <label class="control-label">Nomor Seri SHUN</label>
                                    <input type="text" name="nomor_seri_shun" value="{{ old('nomor_seri_shun', @$ppdbUser->nomor_seri_shun) }}" class="form-control required" placeholder="Nomor Seri SHUN">
                                </div>
                                @endif

                                @if ($inputs->get('nomor_seri_ijazah'))
                                <div class="form-group">
                                    <label class="control-label">Nomor Seri Ijazah</label>
                                    <input type="text" name="nomor_seri_ijazah" value="{{ old('nomor_seri_ijazah', @$ppdbUser->nomor_seri_ijazah) }}" class="form-control required" placeholder="Nomor Seri Ijazah">
                                </div>
                                @endif

                                @if ($inputs->get('nomor_ujian_nasional'))
                                <div class="form-group">
                                    <label class="control-label">Nomor Ujian Nasional</label>
                                    <input type="text" name="nomor_ujian_nasional" value="{{ old('nomor_ujian_nasional', @$ppdbUser->nomor_ujian_nasional) }}" class="form-control required" placeholder="Nomor Ujian Nasional">
                                </div>
                                @endif

                            </fieldset>

                            <fieldset>
                                <legend>Riwayat Kesehatan Calon Peserta Didik</legend>

                                @if ($inputs->get('tinggi'))
                                <div class="form-group">
                                    <label class="control-label">Tinggi Badan</label>
                                    <div class="d-flex">
                                        <input type="number" name="tinggi"
                                            value="{{ old('tinggi', @$ppdbUser->tinggi) }}"
                                            class="form-control required" placeholder="Tinggi">
                                        <div class="input-group-append">
                                            <span class="input-group-text">cm</span>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if ($inputs->get('berat'))
                                <div class="form-group">
                                    <label class="control-label">Berat Badan</label>
                                    <div class="d-flex">
                                        <input type="number" name="berat"
                                            value="{{ old('berat', @$ppdbUser->berat) }}"
                                            class="form-control required" placeholder="Berat">
                                        <div class="input-group-append">
                                            <span class="input-group-text">kg</span>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if ($inputs->get('golongan_darah'))
                                <div class="form-group">
                                    <label class="control-label">Golongan Darah</label>
                                    <select class="form-control required" placeholder="Golongan Darah" name="golongan_darah">
                                        <option value="A" {{ old('golongan_darah', @$ppdbUser->golongan_darah) === 'A' ? 'selected' : NULL }}>A
                                        </option>
                                        <option value="AB" {{ old('golongan_darah', @$ppdbUser->golongan_darah) === 'AB' ? 'selected' : NULL }}>AB
                                        </option>
                                        <option value="B" {{ old('golongan_darah', @$ppdbUser->golongan_darah) === 'B' ? 'selected' : NULL }}>B
                                        </option>
                                        <option value="O" {{ old('golongan_darah', @$ppdbUser->golongan_darah) === 'O' ? 'selected' : NULL }}>O
                                        </option>
                                    </select>
                                </div>
                                @endif

                                @if ($inputs->get('pernah_dirawat'))
                                <div class="form-group required">
                                    <label class="form-label" style="display: block">
                                        Pernah dirawat dirumah sakit?
                                    </label>
                                    <label><input type="radio" name="pernah_dirawat" {{ old('pernah_dirawat', @$ppdbUser->pernah_dirawat) == 'ya' ? 'checked' : null }} value="ya" /> Ya</label>
                                    <label><input type="radio" name="pernah_dirawat" {{ old('pernah_dirawat', @$ppdbUser->pernah_dirawat) == 'tidak' ? 'checked' : null }} value="tidak" /> Tidak</label>
                                </div>
                                @endif

                                @if ($inputs->get('kapan_dirawat'))
                                <div class="form-group">
                                    <label class="control-label">Jika iya, kapan dirawat</label>
                                    <input type="text" name="kapan_dirawat" value="{{ old('kapan_dirawat', @$ppdbUser->kapan_dirawat) }}" class="form-control" placeholder="kapan dirawat">
                                </div>
                                @endif

                                @if ($inputs->get('penyakit'))
                                <div class="form-group">
                                    <label class="control-label">Penyakit</label>
                                    <input type="text" name="penyakit"
                                        value="{{ old('penyakit', @$ppdbUser->penyakit) }}"
                                        class="form-control required" placeholder="Penyakit">
                                </div>
                                @endif

                                @if ($inputs->get('alergi'))
                                <div class="form-group">
                                    <label class="control-label">Jenis Alergi yang diderita</label>
                                    <input type="text" name="alergi" value="{{ old('alergi', @$ppdbUser->alergi) }}" class="form-control required" placeholder="Alergi">
                                </div>
                                @endif

                            @if ($inputs->get('kontak_darurat_keluarga'))
                            <div class="form-group">
                                <label class="control-label">Keluarga yang bisa dihubungi bila dalam keadaan darurat (Nama + Nomor Telepon)</label>
                                <input type="text" name="kontak_darurat_keluarga" value="{{ old('kontak_darurat_keluarga', @$ppdbUser->kontak_darurat_keluarga) }}" class="form-control required" placeholder="Kontak darurat keluarga">
                            </div>
                            @endif

                                @if ($inputs->get('kelainan'))
                                <div class="form-group">
                                    <label class="control-label">Kelainan</label>
                                    <input type="text" name="kelainan"
                                        value="{{ old('kelainan', @$ppdbUser->kelainan) }}"
                                        class="form-control required" placeholder="Kelainan">
                                </div>
                                @endif
                            </fieldset>

                            <fieldset>
                                <legend>Prestasi Calon Peserta Didik</legend>
                                @if ($inputs->get('prestasi_akademik'))
                                <div class="form-group">
                                    <label class="control-label">Prestasi Akademik</label>
                                    <textarea name="prestasi_akademik" class="form-control required" placeholder="Prestasi Akademik">{{ old('prestasi_akademik', @$ppdbUser->prestasi_akademik) }}</textarea>
                                </div>
                                @endif

                                @if ($inputs->get('prestasi_nonakademik'))
                                <div class="form-group">
                                    <label class="control-label">Prestasi Non Akademik</label>
                                    <textarea name="prestasi_nonakademik" class="form-control required" placeholder="Prestasi Non Akademik">{{ old('prestasi_nonakademik', @$ppdbUser->prestasi_nonakademik) }}</textarea>
                                </div>
                                @endif

                                @if ($inputs->get('prestasi_lainnya'))
                                <div class="form-group">
                                    <label class="control-label">Prestasi Lainnya</label>
                                    <textarea name="prestasi_lainnya" class="form-control required" placeholder="Prestasi Lainnya">{{ old('prestasi_lainnya', @$ppdbUser->prestasi_lainnya) }}</textarea>
                                </div>
                                @endif
                            </fieldset>

                            <fieldset>
                                <legend>Potensi Calon Peserta Didik</legend>
                                @if ($inputs->get('potensi_dan_bakat_sains'))
                                <div class="form-group">
                                    <label class="control-label">Potensi dan bakat Sains</label>
                                    <textarea name="potensi_dan_bakat_sains" class="form-control required" placeholder="Potensi dan bakat Sains">{{ old('potensi_dan_bakat_sains', @$ppdbUser->potensi_dan_bakat_sains) }}</textarea>
                                </div>
                                @endif

                                @if ($inputs->get('potensi_dan_bakat_seni'))
                                <div class="form-group">
                                    <label class="control-label">Potensi dan bakat Seni</label>
                                    <textarea name="potensi_dan_bakat_seni" class="form-control required" placeholder="Potensi dan bakat Seni">{{ old('potensi_dan_bakat_seni', @$ppdbUser->potensi_dan_bakat_seni) }}</textarea>
                                </div>
                                @endif

                                @if ($inputs->get('potensi_dan_bakat_olahraga'))
                                <div class="form-group">
                                    <label class="control-label">Potensi dan bakat Olahraga</label>
                                    <textarea name="potensi_dan_bakat_olahraga" class="form-control required" placeholder="Potensi dan bakat Olahraga">{{ old('potensi_dan_bakat_olahraga', @$ppdbUser->potensi_dan_bakat_olahraga) }}</textarea>
                                </div>
                                @endif

                                @if ($inputs->get('potensi_dan_bakat_lainnya'))
                                <div class="form-group">
                                    <label class="control-label">Potensi dan bakat Lainnya</label>
                                    <textarea name="potensi_dan_bakat_lainnya" class="form-control required" placeholder="Potensi dan bakat Lainnya">{{ old('potensi_dan_bakat_lainnya', @$ppdbUser->potensi_dan_bakat_lainnya) }}</textarea>
                                </div>
                                @endif
                            </fieldset>

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
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
<style>
    /* .btn {
        margin: 0;
        min-width: 45px;
        max-width: fit-content;
    } */
</style>
@endpush
@push('scripts')
    <!-- Wizard script -->
    <script src="{{asset('frontend-ppdb-online/js/moment.min.js')}}"></script>
    <script src="{{asset('frontend-ppdb-online/js/bootstrap.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script src="{{asset('frontend-ppdb-online/js/registration_func.js')}}"></script>
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <script>
        $('#datepicker').datepicker({
            uiLibrary: 'bootstrap4',
            format: 'yyyy-mm-dd'
        });
        $(document).ready(function(){

            $('.selectpicker').selectpicker({
                noneResultsText: 'Silahkan tambahkan kota dengan memilih "Kota Lainnya"'
            });

            $('.selectpicker').on('change', function() {
                var option = $('option:selected', this).attr('value');
                if (option != 'another_city') {
                    $("#another_city").hide();
                    $("#input_another_city").removeClass('required');
                } else {
                    $("#another_city").show();
                    $("#input_another_city").addClass('required');
                }
                $('.selectpicker').selectpicker('refresh');
            });
        });
    </script>
@endpush
