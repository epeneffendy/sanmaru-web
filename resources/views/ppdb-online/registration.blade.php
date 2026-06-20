@extends('layouts.ppdb-landing-page.main')

@section('content')
    @push('style')
        <style>
            .input-icon-wrapper {
                position: relative;
            }

            .input-icon-wrapper i {
                position: absolute;
                right: 12px;
                top: 50%;
                transform: translateY(-50%);
                display: none;
                z-index: 10;
                font-size: 1.1rem;
            }

            .text-success-icon {
                color: #28a745;
            }

            .text-danger-icon {
                color: #dc3545;
            }
        </style>
    @endpush
    <div class="row row-height">
        @include('ppdb-online.step-left-section')

        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
            <a class="navbar-brand" href="{{ route('ppdb.index') }}">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">KEMBALI KE HALAMAN UTAMA</button>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">

                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <a href="{{ route('ppdb.login') }}">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="button">LOGIN</button>
                    </a>
                </form>
            </div>
        </nav>

        <div class="col-lg-6 content-right" id="start" style="height: unset">
            @if ($unit->ongoingPeriods->isEmpty())
                <div class="row">
                    <div class="alert alert-danger">
                        Mohon maaf periode PPDB belum dibuka.
                        <a href="{{ url()->previous() }}" style="text-decoration: underline;">back</a>
                    </div>
                </div>
            @else
                <div id="wizard_container">
                    <div id="top-wizard">
                    </div>
                    <div class="header-form text-center">
                        <img src="{{ asset('frontend-ppdb-online/img/logo-serviam.png') }}" class="logo-serviam">
                        <div class="row">
                            <div class="col-lg-5 offset-lg-1 text-center">
                                <div class="title"><a href="{{ route('ppdb.index') }}">Register</a></div>
                            </div>
                            <div class="col-lg-5 text-center">
                                <div class="title-gray"><a href="{{ route('ppdb.login') }}">Log In</a></div>
                            </div>
                        </div>
                    </div>

                    <!-- /top-wizard -->
                    <form id="wrapped" method="POST" action="{{ route('ppdb.insert') }}" autocomplete="off"
                        enctype="multipart/form-data">
                        <input type="hidden" name="show_fieldset" value="false" />
                        <input type="hidden" name="unit_id" value="{{ $unit->id }}" />
                        <input type="hidden" name="periode" id="period" value="" />
                        <input autocomplete="false" name="hidden" type="text" style="display:none;">
                        <!-- Leave for security protection, read docs for details -->
                        <div class="text-center">
                            <p class="text-center">Selamat datang di Sistem Penerimaan Murid Baru (SPMB) Kampus Santa
                                Maria{{ $unit->ongoingPeriods->first()->school_year ? ' Tahun Ajaran ' . $unit->ongoingPeriods->first()->school_year . '/' . ($unit->ongoingPeriods->first()->school_year + 1) : '' }}
                                <br>
                                <b>{{ $unit->name }}</b>
                                <br />Silakan lengkapi form berikut terlebih dahulu untuk mendapatkan form
                                pendaftaran.
                            </p>
                            <div class="alert alert-danger" {!! count(@$errors) > 0 ? null : "style='display: none'" !!}>
                                <ul style="margin: 0 0 0 0;">
                                    @foreach (@$errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="form-group">
                                <label for="period_id">Periode Pendaftaran <span class="text-danger">*</span></label>
                                <input type="hidden" id="period_text" value="" class="form-control">
                                <div class="input-icon-wrapper">
                                    <select name="period_id" id="period_id" class="form-control required">
                                        <option value="">-- Pilih Periode Pendaftaran --</option>
                                        @foreach ($periods as $period)
                                            <option value="{{ $period->id }}">{{ $period->name }}</option>
                                        @endforeach
                                    </select>
                                    <i class="fa fa-check-circle text-success-icon icon-success"></i>
                                    <i class="fa fa-times-circle text-danger-icon icon-danger"></i>
                                </div>
                                <small id="period-hint" class="text-info" style="display: block; margin-top: 5px;">
                                    <i class="fa fa-info-circle"></i> Silakan pilih periode pendaftaran terlebih dahulu
                                    untuk melanjutkan.
                                </small>
                            </div>

                            <div id="registration-fields" style="display: none; margin-top: 20px;">
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control uppercase-input required"
                                        placeholder="Nama Siswa Sesuai Akta kelahiran" value="{{ old('name') }}"
                                        onchange="getVals(this, 'name');">
                                </div>
                                @if (!\Illuminate\Support\Str::startsWith($unit->name, ['KB', 'TK']))
                                    @if ($unit->ongoingPeriods->first()->is_feeder_school)
                                        <div class="form-group">
                                            <select name="origin_school" class="form-control required">
                                                @foreach ($unit->ongoingPeriods->first()->origin_school_options as $value)
                                                    <option value="{{ $value }}"
                                                        {{ old('origin_school') == $value ? 'selected' : null }}>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <input type="text" name="origin_school"
                                                class="form-control uppercase-input required" placeholder="Sekolah Asal"
                                                value="{{ old('origin_school') }}"
                                                onchange="getVals(this, 'origin_school');">
                                        </div>
                                    @endif

                                @endif

                                @if ($unit->isAgeLimitApplied)
                                    <div class="form-group">
                                        <input type="text" readonly="readonly" name="date_of_birth"
                                            class="form-control required" placeholder="Tanggal Lahir"
                                            value="{{ old('date_of_birth') }}"
                                            onchange="getVals(this, 'date_of_birth');">
                                    </div>
                                @endif
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control required"
                                        placeholder="Email Orang Tua" value="{{ old('email') }}"
                                        onchange="getVals(this, 'email');">
                                </div>
                                <div class="form-group">
                                    <input type="number" name="mobile_phone" class="form-control required"
                                        placeholder="Nomor HP / Whatsapp Orang Tua" value="{{ old('mobile_phone') }}"
                                        onchange="getVals(this, 'mobile_phone');">
                                </div>

                                <!-- https://aimsis.atlassian.net/browse/AIMSIS-10448 -->
                                <div class="form-group">
                                    <div class="input-icon-wrapper">
                                        <input type="text" name="nik_siswa" id="nik_siswa"
                                            class="form-control required" placeholder="NIK Siswa"
                                            value="{{ old('nik_siswa') }}" oninput="validateNIKSiswa(this)"
                                            maxlength="16">

                                        <i class="fa fa-check-circle text-success-icon" id="icon-success-nik"></i>
                                        <i class="fa fa-times-circle text-danger-icon" id="icon-danger-nik"></i>
                                    </div>
                                    <small id="nik-hint" class="text-danger"
                                        style="display:none; text-align: left"></small>
                                </div>
                                <div class="form-group">
                                    <div class="input-icon-wrapper">
                                        <input type="text" name="nik_ortu" id="nik_ortu"
                                            class="form-control required" placeholder="NIK Orang Tua"
                                            value="{{ old('nik_ortu') }}" oninput="validateNIKOrtu(this)"
                                            maxlength="16">

                                        <i class="fa fa-check-circle text-success-icon" id="icon-success-nik"></i>
                                        <i class="fa fa-times-circle text-danger-icon" id="icon-danger-nik"></i>
                                    </div>
                                    <small id="nik-ortu-hint" class="text-danger"
                                        style="display:none; text-align: left"></small>
                                </div>

                                @if (in_array($unit->name, ['KB-SURABAYA', 'TK-SURABAYA', 'TK-SIDOARJO']))
                                    <div class="form-group">
                                        <select name="class_option" class="form-control">
                                            <option value="" hidden selected disabled>PILIH KELAS</option>
                                            @if ($unit->name === 'KB-SURABAYA')
                                                <option value="KB A">KB A</option>
                                                <option value="KB B">KB B</option>
                                            @else
                                                <option value="TK A">TK A</option>
                                                <option value="TK B">TK B</option>
                                            @endif
                                        </select>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <input type="password" name="password" class="form-control required"
                                        placeholder="Password" value="{{ old('password') }}"
                                        onchange="getVals(this, 'password');">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password_confirmation" class="form-control required"
                                        placeholder="Ulangi Password" value="{{ old('password_confirmation') }}"
                                        onchange="getVals(this, 'retype-password');">
                                </div>

                                <fieldset {!! old('show_fieldset', 'false') == 'false' ? 'style="display: none"' : null !!}>
                                    <div class="alert alert-info">
                                        Usia anak Anda dibawah batas usia namun masih dapat mendaftar dengan melampirkan
                                        bukti potensi
                                        kecerdasan dan/atau bakat istimewa dan kesiapan psikis dari psikolog profesional.
                                        Informasi
                                        selengkapnya silakan menghubungi admin kami di nomor:
                                        {{ \App\Helpers\Helper::phoneWithLeadingZero($unit->phone) }}
                                    </div>
                                </fieldset>
                                <br>
                                <button type="submit" name="register" class="btn btn-register">Daftar</button>
                            </div>
                        </div>
                        @csrf
                    </form>
                </div>
            @endif
            <!-- Button trigger modal -->
            <!-- Modal -->
            <div class="modal fade" id="popup_modal" tabindex="-1" role="dialog" aria-labelledby="popup_modal_title"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-warning" id="popup_modal_title">Perhatian</h5>
                        </div>
                        <div class="modal-body">
                            {!! $unit->ongoingPeriods->first() ? $unit->ongoingPeriods->first()->popup_content : null !!}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Wizard container -->
        </div>
        <!-- /content-right-->
    </div>
@endsection
@push('scripts')
    <!-- Wizard script -->
    <script>
        var ageLimitByMonths = {{ $ageLimit ? $ageLimit->months : 0 }};
    </script>
    <script src="{{ asset('frontend-ppdb-online/js/registration_func.js') }}"></script>
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <script src="{{ asset('js/sweet-alert/sweet-alert.min.js') }}"></script>
    @if ($unit->ongoingPeriods->first() && $unit->ongoingPeriods->first()->show_registration_popup)
        <script>
            $(document).ready(function() {
                $('#popup_modal').modal('show');

            })
        </script>
    @endif
    <script>
        $(document).ready(function() {

            $('.uppercase-input').on('input', function() {
                this.value = this.value.toUpperCase();
            });

        })
        $('input[name=date_of_birth]').datepicker({
            uiLibrary: 'bootstrap4',
            format: 'yyyy-mm-dd',
            maxDate: new Date()
        });

        $('input[type=file]').change(function() {
            var parent = this;
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $(parent).parent().parent().find('.alert-success a').attr('href', e.target.result).parent()
                        .show();
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        $('.alert-success a').click(function(e) {
            let data = $(this).attr('href');
            let w = window.open('about:blank');
            let image = new Image();
            image.src = data;
            setTimeout(function() {
                w.document.write(image.outerHTML);
            }, 0);
        });

        $('xinput[name=date_of_birth]').change(function(e) {
            e.preventDefault();
            $('.alert-danger').hide();
            $('input[name=show_fieldset]').val('false');
            $('fieldset').hide();
            if ($(this).val()) {
                var userInput = new Date($(this).val());
                var today = new Date("2021-07-01");
                var difMonths = monthDiff(userInput, today);
                var systemLimit = ageLimitByMonths;

                if (systemLimit > 0 && difMonths >= (systemLimit - 2) && difMonths < systemLimit) {
                    $('fieldset').show();
                    $('input[name=show_fieldset]').val('true');
                    document.querySelector('fieldset').scrollIntoView({
                        behavior: 'smooth'
                    });
                } else if (systemLimit > 0 && difMonths < systemLimit) {
                    $('.alert-danger ul').html(
                            '<li>Mohon maaf usia Anak Anda masih dibawah batas usia yang ditetapkan.</li>').parent()
                        .show();
                    document.querySelector('.alert-danger').scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            }
            return;
        });

        function monthDiff(d1, d2) {
            var months;
            months = (d2.getFullYear() - d1.getFullYear()) * 12;
            months -= d1.getMonth();
            months += d2.getMonth();
            return months <= 0 ? 0 : months;
        }

        function validateNIKSiswa(el) {
            // 1. Bersihkan input agar hanya angka
            el.value = el.value.replace(/[^0-9]/g, '');

            let nik = el.value;
            let iconSuccess = $('#icon-success-nik');
            let iconDanger = $('#icon-danger-nik');
            let hint = $('#nik-hint');

            // Reset status awal
            iconSuccess.hide();
            iconDanger.hide();
            hint.hide();
            $(el).css('border-color', '');

            if (nik.length === 0) return;

            if (nik.length < 16) {
                // Tampilkan silang jika belum 16 digit
                iconDanger.show();
                $(el).css('border-color', '#dc3545');
                hint.text('NIK harus 16 digit.').show();
            } else if (nik.length === 16) {
                let tgl = parseInt(nik.substring(6, 8));
                let bln = parseInt(nik.substring(8, 10));
                let realTgl = tgl > 40 ? tgl - 40 : tgl;

                if (realTgl <= 31 && realTgl > 0 && bln <= 12 && bln > 0) {
                    // --- KONDISI VALID ---
                    iconSuccess.fadeIn();
                    $(el).css('border-color', '#28a745');

                    // Jalankan fungsi getVals hanya saat valid
                    getVals(el, 'nik_siswa');
                } else {
                    // --- KONDISI FORMAT SALAH ---
                    iconDanger.show();
                    $(el).css('border-color', '#dc3545');
                    hint.text('Struktur angka NIK tidak valid.').show();
                }
            }
        }

        function validateNIKOrtu(el) {
            // 1. Bersihkan input agar hanya angka
            el.value = el.value.replace(/[^0-9]/g, '');

            let nik = el.value;
            let iconSuccess = $('#icon-success-nik');
            let iconDanger = $('#icon-danger-nik');
            let hint = $('#nik-ortu-hint');

            // Reset status awal
            iconSuccess.hide();
            iconDanger.hide();
            hint.hide();
            $(el).css('border-color', '');

            if (nik.length === 0) return;

            if (nik.length < 16) {
                // Tampilkan silang jika belum 16 digit
                iconDanger.show();
                $(el).css('border-color', '#dc3545');
                hint.text('NIK harus 16 digit.').show();
            } else if (nik.length === 16) {
                let tgl = parseInt(nik.substring(6, 8));
                let bln = parseInt(nik.substring(8, 10));
                let realTgl = tgl > 40 ? tgl - 40 : tgl;

                if (realTgl <= 31 && realTgl > 0 && bln <= 12 && bln > 0) {
                    // --- KONDISI VALID ---
                    iconSuccess.fadeIn();
                    $(el).css('border-color', '#28a745');

                    // Jalankan fungsi getVals hanya saat valid
                    getVals(el, 'nik_siswa');
                } else {
                    // --- KONDISI FORMAT SALAH ---
                    iconDanger.show();
                    $(el).css('border-color', '#dc3545');
                    hint.text('Struktur angka NIK tidak valid.').show();
                }
            }
        }

        function getVals(el, type) {
            let value = $(el).val();

            // Proteksi khusus untuk tipe NIK
            if (type === 'nik_siswa') {
                if (value.length !== 16) {
                    console.warn('getVals dibatalkan: NIK belum lengkap.');
                    return false;
                }
            }

            if (type === 'nik_ortu') {
                if (value.length !== 16) {
                    console.warn('getVals dibatalkan: NIK belum lengkap.');
                    return false;
                }
            }


        }


        $('#period_id').change(function(e) {

            let val = $('#period_id').val();
            let wrapper = $('#period_id').closest('.input-icon-wrapper');
            let iconSuccess = wrapper.find('.icon-success');
            let iconDanger = wrapper.find('.icon-danger');
            let hint = $('#period-hint');
            let periodText = $('#period_id option:selected').text();
            let fieldsContainer = $('#registration-fields');
            let spanContent = document.createElement("span");
            spanContent.innerHTML = "Periode yang anda pilih adalah <b>" + periodText + "</b>, pastikan periode tersebut sesuai dengan pilihan anda";
            if (val !== "") {
                swal({
                    title: "PERHATIAN",
                    content: spanContent,
                    icon: "warning",
                    buttons: [
                        'tidak!',
                        'Ya, Saya yakin!'
                    ],
                    dangerMode: false,
                }).then(function(isConfirm) {
                    if (isConfirm) {
                        iconSuccess.fadeIn();
                        iconDanger.hide();
                        $('#period_id').css('border-color', '#28a745');
                        $('#period').val(val)
                        hint.html('<i class="fa fa-check"></i> Periode terpilih.').removeClass(
                            'text-info').addClass(
                            'text-success');
                        fieldsContainer.fadeIn(500);
                    } else {
                        $('#period_id').val('')
                        $('#period_id').css('border-color', '#dc3545');
                        $('#period').val('')
                        hint.html(
                                '<i class="fa fa-exclamation-triangle"></i> Wajib memilih periode pendaftaran!'
                            )
                            .removeClass('text-info').addClass('text-danger');
                    }
                });
            } else {
                // Jika kembali ke default
                fieldsContainer.fadeOut(300);
                iconSuccess.hide();
                iconDanger.fadeIn();
                $('#period_id').css('border-color', '#dc3545');
                $('#period').val('')
                hint.html('<i class="fa fa-exclamation-triangle"></i> Wajib memilih periode pendaftaran!')
                    .removeClass('text-info').addClass('text-danger');
            }
        });
    </script>
@endpush
