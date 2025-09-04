@extends('layouts.admin.main')
@section('content')
    @if(@$method=="edit")
        @php($action=route('admin.student.update',array($student['id'])))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.student.insert'))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data Master Student</h1>
        <ol class="breadcrumb">
            <li>Master</li>
            <li><a href="{{route('admin.student.index')}}">Student</a></li>
            <li class="active">{{$status_header}}</li>
        </ol>

    </div>
    <!-- End Page Header -->
    <!-- START CONTAINER -->
    <div class="container-padding">
        <!-- Start Row -->
        <div class="row">
            <!-- Start Panel -->
            <div class="col-md-12">
                <div class="widget ">
                    <div class="widget-header">
                        <h3>{{$status_header}} Student</h3>
                    </div> <!-- /widget-header -->
                    <div class="widget-content">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form role="form" method="POST" action="{{$action}}" class="form-horizontal"
                              enctype="multipart/form-data">
                            <div role="tabpanel">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs nav-justified tabcolor5-bg" role="tablist">
                                    <li role="presentation" class="active"><a href="#data-student" aria-controls="data-student" role="tab" data-toggle="tab" aria-expanded="true" class="">Data Siswa</a></li>
                                    <li role="presentation" class=""><a href="#data-tambahan" aria-controls="data-tambahan" role="tab" data-toggle="tab" class="" aria-expanded="false">Data Tambahan</a></li>
                                    <li role="presentation" class=""><a href="#data-orangtua" aria-controls="data-orangtua" role="tab" data-toggle="tab" class="" aria-expanded="false">Data Orangtua</a></li>
                                    <li role="presentation" class=""><a href="#data-image" aria-controls="data-image" role="tab" data-toggle="tab" class="" aria-expanded="false">Data Image</a></li>
                                </ul>
                                <hr/>
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="data-student">
                                        <input type="hidden" value="{{@$student['id']}}" name="id" />
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="nis">NIS:</label>
                                            <div class="col-sm-10">
                                                <input type="number" class="form-control" name="nis" id="nis"
                                                    value="{{old('nis') ?? @$student['nis']}}" placeholder="Enter NIS" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="name">Nama:</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="name" id="name"
                                                    value="{{old('name') ?? @$student['name']}}" placeholder="Enter name" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="email">Email:</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" name="email" id="email"
                                                    value="{{old('email') ?? @$student['email']}}" placeholder="Enter email" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="mobile_phone">Mobile Phone:</label>
                                            <div class="col-sm-10">
                                                <input type="number" class="form-control" name="mobile_phone" id="mobile_phone"
                                                    value="{{old('mobile_phone') ?? @$student['mobile_phone']}}"
                                                    placeholder="Enter mobile phone number">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="address">Alamat:</label>
                                            <div class="col-sm-10">
                                            <textarea class="form-control" name="address" id="address" placeholder="Enter address"
                                                    required>{{old('address') ?? @$student['address']}}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="class_id">Class:</label>
                                            <div class="col-sm-10">
                                                <select class="form-control{{ $errors->has('class_id') ? ' is-invalid' : '' }}"
                                                        name="class_id" id="class_id" required {{($status=="Update")?"readonly":""}}>
                                                    @foreach($classList as $key => $value)
                                                        <option value="{{ $value->id }}" data-unit-name="{{ $value->unit->name }}" {{ $value->id === @$student->class_id ? 'selected' : NULL }}>{{ $value->name_class_unit }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="school_year">Tahun</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="school_year" id="school_year"
                                                    value="{{old('school_year') ?? @$student['school_year']}}" placeholder="Enter school year">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="register_number">No Registrasi PPDB</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="register_number" id="register_number"
                                                    value="{{old('register_number') ?? @$student['register_number']}}" placeholder="Enter school register number">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="image">Status</label>
                                            <div class="col-sm-10">
                                                <select name="status" id="status" class="form-control">
                                                    @foreach ($statuses as $value => $student_status)
                                                    <option value="{{ $value }}" {{ $value == @$student->status ? 'selected' : null }}>{{ $student_status }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="image">Foto</label>
                                            <div class="col-sm-10">
                                                <input type="file" name="image" id="image" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <?php $additional = @$student->additionalData; ?>

                                    <div role="tabpanel" class="tab-pane" id="data-tambahan">
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="gender">Jenis kelamin:</label>
                                            <div class="col-sm-10">
                                                <select name="gender" id="gender" class="form-control">
                                                    <option value="male" {{ old('gender', @$additional->gender) == 'male' ? 'selected' : null }}>Laki-laki</option>
                                                    <option value="female" {{ old('gender', @$additional->gender) == 'female' ? 'selected' : null }}>Perempuan</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="place_of_birth">Tempat lahir:</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="place_of_birth" id="place_of_birth" value="{{ old('place_of_birth') ?? @$additional->place_of_birth }}" placeholder="Tempat lahir">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="date_of_birth">Tanggal lahir:</label>
                                            <div class="col-sm-10">
                                                <input type="date" class="form-control datepicker" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') ?? @$additional->date_of_birth }}" placeholder="Tanggal lahir">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="city">Kota:</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="city" id="city" value="{{ old('city') ?? @$additional->city }}" placeholder="Kota">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="region">Provinsi:</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="region" id="region" value="{{ old('region') ?? @$additional->region }}" placeholder="Provinsi">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="country">Kewarganegaraan:</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="country" id="country" value="{{ old('country') ?? @$additional->country }}" placeholder="Kewarganegaraan">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="religion">Agama:</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="religion" id="religion" value="{{ old('religion') ?? @$additional->religion }}" placeholder="Agama">
                                            </div>
                                        </div>

    <?php
        $additionalForms = [
            ['nama_siswa', 'text', 'Nama siswa', null],
            ['nama_panggilan', 'text', 'Nama panggilan', null],
            ['nama_saudara_se_sekolah', 'text', 'Nama Adik / Kakak / saudara yang sekolah di Santa Maria (Nama + Unit)', null],
            ['anak_ke', 'text', 'Anak ke', null],
            ['jumlah_saudara_kandung', 'text', 'Jumlah saudara kandung', null],
            ['jumlah_saudara_tiri', 'text', 'Jumlah saudara tiri', null],
            ['status_orangtua', 'radio', 'Status Anak & Orangtua', ['yatim', 'piatu', 'yatim piatu', 'bukan yatim piatu'], true],
            ['bahasa', 'select', 'Bahasa sehari-hari', ['Bahasa Indonesia', 'Bahasa Inggris'], true],
            ['alamat_sesuai_kk', 'textarea', 'Alamat sesuai KK', null],
            ['alamat_tempat_tinggal', 'textarea', 'Alamat tempat tinggal', null],
            ['tinggal_dengan', 'select', 'Bertempat tinggal pada', ['orang tua', 'wali', 'saudara', 'asrama', 'kost', 'panti_asuhan', 'lainnya'], true],
            ['jarak_tempat_tinggal', 'text', 'Jarak tempat tinggal ke sekolah', null],
            ['waktu_tempuh', 'text', 'Waktu tempuh berangkat ke sekolah', null],
            // ['npwp', 'text', 'NPWP Orang Tua atau Wali', null],
            ['no_akta_kelahiran', 'text', 'No. Registrasi Akta Kelahiran', null],
            ['penanggungjawab_biaya', 'text', 'Penanggungjawab Biaya Pendidikan', null],
            ['asal_sekolah', 'text', 'Asal Sekolah', null],
            ['alamat_asal_sekolah', 'text', 'Alamat asal sekolah', null],
            ['kota_asal_sekolah', 'text', 'Kota asal sekolah'],
            ['nomor_telepon_asal_sekolah', 'text', 'Nomor telepon asal sekolah', null],
            ['nisn', 'text', 'NISN', null],
            ['tahun_lulus', 'text', 'Tahun lulus', null],
            ['nomor_seri_shun', 'text', 'Nomor seri SHUN', null],
            ['nomor_seri_ijazah', 'text', 'Nomor seri ijazah', null],
            ['nomor_ujian_nasional', 'text', 'Nomor ujian nasional', null],
            ['golongan_darah', 'text', 'Golongan darah', null],
            ['pernah_dirawat', 'radio', 'Pernah dirawat di rumah sakit?', ['ya', 'tidak'], true],
            ['kapan_dirawat', 'text', 'Jika iya, kapan dirawat', null],
            ['alergi', 'text', 'Alergi', null],
            ['kontak_darurat_keluarga', 'text', 'Keluarga yang bisa dihubungi bila dalam keadaan darurat', null],
            ['prestasi_akademik', 'text', 'Prestasi Akademik', null],
            ['prestasi_nonakademik', 'text', 'Prestasi nonakademik', null],
            ['potensi_dan_bakat_sains', 'text', 'Prestasi dan bakat sains', null],
            ['potensi_dan_bakat_seni', 'text', 'Potensi dan bakt seni', null],
            ['potensi_dan_bakat_olahraga', 'text', 'Potensi dan bakat olahraga', null],
            ['penyakit', 'text', 'Penyakit', null],
            ['kelainan', 'text', 'Kelainan', null],
            ['tinggi', 'text', 'Tinggi', null],
            ['berat', 'text', 'Berat', null]
        ];

        $parents = ['f', 'm', 'w'];
        $parentForms = [
            ['name', 'text', 'Nama', null],
            ['place_of_birth', 'text', 'Tempat lahir', null],
            ['date_of_birth', 'datepicker', 'Tanggal lahir', null],
            ['address', 'textarea', 'Alamat', null],
            ['city', 'text', 'Kota', null],
            ['region', 'text', 'Provinsi', null],
            ['country', 'text', 'Kewarganegaraan', null],
            ['religion', 'text', 'Agama', null],
            ['phone', 'text', 'Telepon', null],
            ['education', 'select', 'Pendidikan', ['sd', 'smp', 'sma', 'd1', 'd2', 'd3', 's1', 's2', 's3'], true],
            ['job', 'select', 'Pekerjaan', ['tidak bekerja', 'pns', 'tni', 'polri', 'karyawan swasta', 'wiraswasta', 'pensiunan', 'lainnya'], true],
            ['salary', 'select', 'Penghasilan', ['kurang dari Rp 500.000', 'Rp 500.000 - Rp 999.999', 'Rp 1.000.000 - Rp 1.999.999', 'Rp 2.000.000 - Rp 4.999.999', 'Rp 5.000.000 - Rp 20.000.000', 'lebih dari Rp 20.000.000'], true]
        ];

        $fileForms = [
            ['payment_form', 'single', 'Bukti pembayaran'],
            ['birth_certificate', 'single', 'Akta kelahiran'],
            ['photo', 'single', 'Foto 3x4 berwarna'],
            ['family_card', 'single', 'Kartu keluarga'],
            ['parent_identity_card', 'single', 'KTP Orang tua (salah satu)'],
            ['marriage_certificate', 'single', 'Akta pernikahan orang tua'],
            ['report_cards', 'multiple', 'Raport (multiple upload)'],
            ['award_photo', 'single', 'Piagam penghargaan'],
            ['kartu_golongan_darah', 'single', 'Kartu golongan darah'],
            ['kms', 'single', 'Kartu menuju sehat (KMS)'],
            ['baptismal_certificate', 'single', 'Surat baptis'],
            ['rekomendasi_bk', 'single', 'Rekomendasi BK'],
            ['nilai_raport', 'link', 'Nilai raport'],
            ['angket_peminatan', 'single', 'Angket peminatan'],
            ['statement_letter', 'single', 'Surat Pernyataan'],
        ];
    ?>

                                        @foreach ($additionalForms as $form)
                                            @switch($form[1])
                                                @case('text')
                                                    @include('shared.input-text', [
                                                        'name' => $form[0],
                                                        'class' => 'additional-form-input',
                                                        'label' => $form[2],
                                                        'data' => @$additional,
                                                    ])
                                                    @break
                                                @case('textarea')
                                                    @include('shared.input-textarea', [
                                                        'name' => $form[0],
                                                        'data' => @$additional,
                                                        'class' => 'additional-form-input',
                                                        'label' => $form[2]
                                                    ])
                                                    @break
                                                @case('select')
                                                    @include('shared.input-select', [
                                                        'name' => $form[0],
                                                        'label' => $form[2],
                                                        'data' => @$additional,
                                                        'class' => 'additional-form-input',
                                                        'options' => $form[3],
                                                        'use_value_as_index' => $form[4]
                                                    ])
                                                    @break
                                                @case('radio')
                                                    @include('shared.input-radio', [
                                                        'name' => $form[0],
                                                        'label' => $form[2],
                                                        'data' => @$additional,
                                                        'class' => 'additional-form-input',
                                                        'options' => $form[3],
                                                        'use_value_as_index' => $form[4]
                                                    ])
                                                    @break
                                                @case('datepicker')
                                                    @include('shared.input-datepicker', [
                                                        'name' => $form[0],
                                                        'data' => @$additional,
                                                        'class' => 'additional-form-input',
                                                        'label' => $form[2],
                                                    ])
                                                    @break
                                            @endswitch
                                        @endforeach
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="data-orangtua">
                                        @foreach ($parents as $parent)
                                            <div class="form-parent-{{ $parent }}">
                                            <h5>{{ $parent == 'f' ? 'Father' : ($parent == 'm' ? 'Mother' : 'Wali') }}</h5>
                                            <?php
                                                $parentData = [];

                                                if (@$student->parents) {
                                                    $parentData = @collect($student->parents->filter(function($query) use ($parent) {
                                                        return ($parent == 'f' && $query->type == 'father') ||
                                                            ($parent == 'm' && $query->type == 'mother') ||
                                                            ($parent == 'w' && $query->type == 'wali');
                                                    })->first())->keyBy(function ($item, $key) use ($parent) {
                                                        return $parent. '_'. $key;
                                                    })->all();
                                                }
                                            ?>
                                            @foreach ($parentForms as $form)
                                                @switch($form[1])
                                                    @case('text')
                                                        @include('shared.input-text', [
                                                            'name' => $parent.'_'.$form[0],
                                                            'data' => @$parentData,
                                                            'label' => $form[2]
                                                        ])
                                                        @break
                                                    @case('textarea')
                                                        @include('shared.input-textarea', [
                                                            'name' => $parent.'_'.$form[0],
                                                            'data' => @$parentData,
                                                            'label' => $form[2]
                                                        ])
                                                        @break
                                                    @case('select')
                                                        @include('shared.input-select', [
                                                            'name' => $parent.'_'.$form[0],
                                                            'label' => $form[2],
                                                            'data' => @$parentData,
                                                            'options' => $form[3],
                                                            'use_value_as_index' => $form[4]
                                                        ])
                                                        @break
                                                    @case('radio')
                                                        @include('shared.input-radio', [
                                                            'name' => $parent.'_'.$form[0],
                                                            'label' => $form[2],
                                                            'data' => @$parentData,
                                                            'options' => $form[3],
                                                            'use_value_as_index' => $form[4]
                                                        ])
                                                        @break
                                                    @case('datepicker')
                                                        @include('shared.input-datepicker', [
                                                            'name' => $parent.'_'.$form[0],
                                                            'data' => @$parentData,
                                                            'label' => $form[2],
                                                        ])
                                                        @break
                                                @endswitch
                                            @endforeach
                                            </div>
                                        @endforeach
                                        <hr/>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="data-image">
                                        @foreach ($fileForms as $file)
                                            <div class="form-group">
                                                <label class="control-label col-sm-4">{{ $file[2] }}:</label>
                                                <div class="input-group col-md-8">
                                                    @switch($file[1])
                                                        @case('single')
                                                            @if (@$additional[$file[0]])
                                                                <div class="row">
                                                                    <img src="{{ \App\Helpers\ImageHelper::imageUrl($additional[$file[0]]) }}" style="width: 100px; height: auto"/>
                                                                </div>
                                                            @endif

                                                            @if ($file[0] == 'angket_peminatan')
                                                                <span class="input-group-addon" style="border: 1px solid #BDC4C9">
                                                                    <a href="{{ route('ppdb.download-angket-peminatan') }}" target="_blank">Unduh</a>
                                                                </span>
                                                            @endif
                                                            @if ($file[0] == 'statement_letter')
                                                                <span class="input-group-addon" style="border: 1px solid #BDC4C9">
                                                                    <a href="{{ route('ppdb.download-statement-letter') }}" target="_blank">Unduh</a>
                                                                </span>
                                                            @endif
                                                            <input type="file" class="form-control" placeholder="{{ $file[2] }}" name="{{ $file[0] }}" accept="image/x-png,image/jpeg" />
                                                            @if ($file[0] == 'angket_peminatan')
                                                                <small>
                                                                    Silakan unduh Formulir Angket Peminatan berikut dan unggah
                                                                    kembali jika sudah dilengkapi
                                                                </small>
                                                            @endif
                                                            @if ($file[0] == 'statement_letter')
                                                                <small>
                                                                    Silakan unduh Surat Pernyataan berikut dan unggah
                                                                    kembali jika sudah dilengkapi dengan ttd bermaterai
                                                                </small>
                                                            @endif
                                                            @break;
                                                        @case('multiple')
                                                            <input type="file" class="form-control" placeholder="{{ $file[2] }}" name="{{ $file[0] }}[]" multiple accept="image/x-png,image/jpeg"/>
                                                            <small class="text-muted">*bisa upload lebih dari satu file</small>
                                                            @break;
                                                        @case('link')
                                                            <a href="{{ config('form') }}" class="btn btn-success google-form" target="_blank">Google Form</a>
                                                            @break;
                                                    @endswitch
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-default">{{$status}}</button>
                                </div>
                            </div>
                            @csrf
                        </form>
                    </div>
                </div> <!-- /widget-content -->
            </div>
            <!-- End Panel -->
        </div>
        <!-- End Row -->

    </div>
    <!-- END CONTAINER -->
@endsection

@push('scripts')
<script>
    var inputCollection = {!! json_encode(\App\Helpers\InputCollectionHelper::additionalData()->toArray()) !!};

    function changeFormParents() {
        let val = $('select[name=tinggal_dengan] option:selected').val();
        if (val === 'wali') {
            $('.form-parent-f').hide();
            $('.form-parent-m').hide();
            $('.form-parent-w').show();
        } else {
            $('.form-parent-f').show();
            $('.form-parent-m').show();
            $('.form-parent-w').hide();
        }
    }

    function changeAdditionalDataForms() {
        // input hanya untuk SMA Surabaya
        if ($('select[name=class_id] option:selected').data('unit-name') == 'SMA-SURABAYA') {
            $('.additional-form-input').show();
        } else {
            $('.additional-form-input').hide();
            $.each(inputCollection, function(key, val) {
                $('.additional-form-input [name='+ key +']').parents('.additional-form-input').show();
            });
        }
    }

    $(document).on('change', 'select[name=tinggal_dengan]', function(e) {
        changeFormParents();
    });

    $(document).on('change', 'select[name=class_id]', function(e) {
        changeAdditionalDataForms();
    });

    $(document).ready(function() {
        changeFormParents();
        changeAdditionalDataForms();
    });
</script>
@endpush
