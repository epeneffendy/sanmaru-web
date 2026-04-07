@extends('layouts.ppdb-online.main')
@section('content')
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
        <style>
            :root {
                --primary-green: #198754;
                --soft-gray: #f8f9fa;
            }

            .registration-card {
                border-radius: 25px;
                overflow: hidden;
            }

            /* Stepper Styling */
            .stepper-container {
                position: relative;
                max-width: 800px;
                margin: 0 auto;
            }

            .progress-line {
                position: absolute;
                top: 20px;
                left: 0;
                right: 0;
                height: 3px;
                background: #e9ecef;
                z-index: 1;
            }

            .progress-fill {
                height: 100%;
                background: var(--primary-green);
                width: 0%;
                transition: 0.4s ease;
            }

            .steps-wrapper {
                position: relative;
                display: flex;
                justify-content: space-between;
                z-index: 2;
            }

            .step-dot {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                border: 2px solid #e9ecef;
                background: white;
                color: #adb5bd;
                font-weight: bold;
                transition: 0.3s;
            }

            .step-dot.active {
                background: var(--primary-green);
                border-color: var(--primary-green);
                color: white;
                box-shadow: 0 0 15px rgba(25, 135, 84, 0.3);
            }

            /* Form Fields Modernization */
            .form-control, .form-select {
                border: 2px solid #f1f3f5;
                background: var(--soft-gray);
                border-radius: 12px;
                padding: 12px 15px;
                transition: 0.3s;
            }

            .form-control:focus {
                background: white;
                border-color: var(--primary-green);
                box-shadow: none;
            }

            .section-title {
                font-weight: 700;
                color: #343a40;
                margin-bottom: 25px;
                padding-left: 10px;
                border-left: 5px solid var(--primary-green);
            }

            /* Perbaikan Tinggi dan Border Select2 */
            .select2-container--bootstrap4 .select2-selection--single,
            .select2-container--default .select2-selection--single {
                height: 45px !important; /* Sesuaikan dengan tinggi input template Anda */
                border: 1px solid #ddd !important;
                border-radius: 5px !important;
                display: flex !important;
                align-items: center !important;
            }

            /* Mengatur posisi teks di dalam Select2 agar di tengah */
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 45px !important;
                padding-left: 15px !important;
                color: #444 !important;
            }

            /* Mengatur posisi ikon panah Select2 */
            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 45px !important;
                top: 0 !important;
                right: 10px !important;
            }

            /* Mengatur dropdown agar tidak berantakan */
            .select2-dropdown {
                border: 1px solid #26703B !important; /* Gunakan warna hijau identitas Anda */
                border-radius: 0 0 10px 10px !important;
                box-shadow: 0 4px 10px rgba(0,0,0,0.1) !important;
            }

        </style>
    @endpush
    @php($inputs = \App\Helpers\InputCollectionHelper::additionalData($ppdbUser->unit))
    <div class="container py-5">
        <div class="card registration-card border-0 shadow-lg">
            <div class="card-header bg-white pt-5 pb-4 border-0">
                <h3 class="fw-bold text-success text-center mb-5">Formulir Pendaftaran Siswa Baru</h3>

                <div class="stepper-container">
                    <div class="progress-line">
                        <div class="progress-fill" id="formProgressBar"></div>
                    </div>
                    <div class="steps-wrapper">
                        @foreach($stepper as $index => $label)
                            <div class="step-item text-center">
                                <button type="button" class="step-dot {{ $index == 0 ? 'active' : '' }}"
                                        id="step-{{ $index }}">
                                    {{ $index + 1 }}
                                </button>
                                <span class="step-text d-none d-md-block">{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card-body p-4 p-md-5">
                <form id="wrapped" method="POST" autocomplete="off" action="{{route('ppdb.form-student.submit')}}" novalidate>
                    @csrf
                    <div class="tab-content">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul style="margin: 0 0 0 0;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="tab-pane fade show active" id="identitas" role="tabpanel">
                            <div class="row g-4">
                                <div class="col-12">
                                    <h5 class="section-title"><i class="bi bi-person-badge me-2"></i>Identitas Calon
                                        Siswa</h5>
                                </div>
                                @include('ppdb-online.partials.form_registration._identitas')
                            </div>
                        </div>

                        <div class="tab-pane fade" id="additional" role="tabpanel">
                            <div class="col-12">
                                <h5 class="section-title"><i class="bi bi-person-badge me-2"></i>Data Calon Peserta
                                    Didik</h5>
                            </div>

                            @include('ppdb-online.partials.form_registration._additional_data')
                        </div>

                        <div class="tab-pane fade" id="school" role="tabpanel">
                            <div class="col-12">
                                <h5 class="section-title"><i class="bi bi-person-badge me-2"></i>Asal Sekolah Calon
                                    Peserta Didik</h5>
                            </div>

                            @include('ppdb-online.partials.form_registration._school_form')
                        </div>

                        <div class="tab-pane fade" id="medical" role="tabpanel">
                            <div class="col-12">
                                <h5 class="section-title"><i class="bi bi-person-badge me-2"></i>Riwayat Kesehatan Calon
                                    Peserta Didik</h5>
                            </div>

                            @include('ppdb-online.partials.form_registration._medical_history')
                        </div>

                        @if($ppdbUser->unit->unit_code == '05')
                            <div class="tab-pane fade" id="potential" role="tabpanel">
                                <div class="col-12">
                                    <h5 class="section-title"><i class="bi bi-person-badge me-2"></i>Potensi Calon
                                        Peserta Didik</h5>
                                </div>

                                @include('ppdb-online.partials.form_registration._potential_student')
                            </div>
                        @endif


                    </div>

                    <div class="form-footer mt-5 pt-4 d-flex justify-content-between">
                        <button type="button" class="btn btn-light btn-sm rounded-pill px-5 shadow" id="prevBtn">Sebelumnya</button>
                        <button type="button" class="btn btn-info btn-sm rounded-pill px-5 shadow" id="nextBtn">Selanjutnya</button>
                        <button type="submit" class="btn btn-success btn-sm rounded-pill px-5 shadow d-none"
                                id="simpan-pendaftaran">
                            Simpan <i class="bi bi-check-circle me-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script src="{{asset('js/sweet-alert/sweet-alert.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        const RegistrationWizard = {
            currentTab: 0,
            tabs: @json($ppdbUser->unit->unit_code != '05')
                ? ['identitas', 'additional', 'school', 'medical']
                : ['identitas', 'additional', 'school', 'medical', 'potential'],

            init() {
                this.updateUI();
                $('#nextBtn').off('click').on('click', () => this.moveTab(1));
                $('#prevBtn').off('click').on('click', () => this.moveTab(-1));

                this.checkingNIK();
                this.checkingPhoneNumber();

                $('.uppercase-input').on('input', function () {
                    this.value = this.value.toUpperCase();
                });

                $('#wrapped').on('submit', (e) => {
                    if (this.hasInvalidFieldsInAnyTab()) {
                        e.preventDefault();
                        swal({
                            icon: 'error',
                            title: 'Gagal Simpan',
                            text: 'Masih ada data yang tidak valid. Mohon periksa kembali semua tab.',
                        });
                    }
                });

                $('.select2-provinces').select2({
                    theme: 'default', // atau 'bootstrap4' jika filenya ada
                    width: '100%',
                    placeholder: "Pilih Provinsi",
                    // allowClear: true
                });

                $('.select2-cities').select2({
                    theme: "bootstrap4",
                    width: '100%',
                    placeholder: "Pilih Kota"
                });

                this.setupProvinceChangeEvent();

                const initialProvince = $('.select2-provinces').val();
                if (initialProvince) {
                    this.fetchCities(initialProvince, "{{ @$ppdbUser->city }}"); // Kirim ID kota lama jika ada
                }

                // Menangani validasi warna border saat Select2 berubah
                $('.select2-provinces').on('change', function() {

                    let provinceId = $(this).val();
                    let citySelect = $('#city');

                    citySelect.empty().append('<option value=""></option>').trigger('change');

                    if (provinceId) {
                        // Tampilkan loading pada select kota
                        citySelect.prop('disabled', true);

                        // Ganti URL sesuai dengan route di Laravel Anda
                        $.ajax({
                            url: "{{ route('ppdb.get-cities') }}",
                            type: "GET",
                            data: { province_id: provinceId },
                            success: function(response) {
                                // Isi data kota ke select2
                                $.each(response, function(key, city) {
                                    citySelect.append(new Option(city.name, city.id, false, false));
                                });

                                citySelect.prop('disabled', false).trigger('change');
                            },
                            error: function() {
                                alert('Gagal mengambil data kota.');
                                citySelect.prop('disabled', false);
                            }
                        });
                    }
                });

            },

            hasInvalidFieldsInAnyTab() {
                return $('.tab-pane').find('.is-invalid').length > 0;
            },

            hasInvalidFields() {
                return $(`#${this.tabs[this.currentTab]}`).find('.is-invalid').length > 0;
            },

            moveTab(step) {
                if (step > 0 && this.hasInvalidFields()) {
                    swal({
                        icon: 'warning',
                        title: "Informasi!",
                        text: 'Mohon lengkapi atau perbaiki data pada form sebelum melanjutkan!',
                    });
                    return;
                }

                const nextTabIndex = this.currentTab + step;

                if (nextTabIndex < 0 || nextTabIndex >= this.tabs.length) return;

                $(`#${this.tabs[this.currentTab]}`).removeClass('show active');

                this.currentTab = nextTabIndex;

                $(`#${this.tabs[this.currentTab]}`).addClass('show active');

                this.updateUI();
                window.scrollTo({top: 0, behavior: 'smooth'});
            },

            updateUI() {
                const totalTabs = this.tabs.length;
                const progress = (this.currentTab / (totalTabs - 1)) * 100;
                $('#formProgressBar').css('width', `${progress}%`);

                $('.step-dot').each((i, el) => {
                    $(el).toggleClass('active', i <= this.currentTab);
                });

                const isFirstTab = this.currentTab === 0;
                const isLastTab = this.currentTab === totalTabs - 1;

                $('#prevBtn').toggleClass('d-none', isFirstTab);
                $('#nextBtn').toggleClass('d-none', isLastTab);
                $('#simpan-pendaftaran').toggleClass('d-none', !isLastTab);
            },

            checkingNIK() {
                const nikSiswa = $('input[name="nik_siswa"]');
                const nikOrtu = $('input[name="nik_ortu"]');

                const validate = (el) => {
                    const val = el.val();
                    const feedbackId = 'nik-feedback';
                    $(`#${feedbackId}`).remove();
                    el.removeClass('is-invalid is-valid');

                    if (val.length === 0) return;

                    let errorMessage = "";

                    // Validasi 1: Harus 16 digit angka
                    if (val.length !== 16 || isNaN(val)) {
                        errorMessage = "NIK harus berupa 16 digit angka.";
                    } else {
                        // Validasi 2: Bedah Tanggal Lahir (Digit ke 7 sampai 12)
                        let day = parseInt(val.substring(6, 8));
                        let month = parseInt(val.substring(8, 10));
                        let year = parseInt(val.substring(10, 12));

                        // Jika perempuan, tanggal lahir + 40
                        if (day > 40) day -= 40;

                        // Cek validitas tanggal dan bulan dasar
                        if (day < 1 || day > 31) errorMessage = "Struktur angka NIK tidak valid.";
                        if (month < 1 || month > 12) errorMessage = "Struktur angka NIK tidak valid.";

                        // Validasi 3: Kode Wilayah (6 digit pertama tidak boleh 000000)
                        if (val.substring(0, 6) === "000000") {
                            errorMessage = "Kode wilayah NIK tidak valid.";
                        }
                    }

                    if (errorMessage) {
                        el.addClass('is-invalid');
                        el.after(`<div id="${feedbackId}" class="invalid-feedback">${errorMessage}</div>`);
                        return false;
                    } else {
                        el.addClass('is-valid');
                        return true;
                    }
                };

                // 1. Jalankan validasi saat input berubah (typing/paste)
                nikSiswa.on('input change', function () {
                    validate($(this));
                });

                nikOrtu.on('input change', function () {
                    validate($(this));
                });

                if (nikSiswa.val()) {
                    validate(nikSiswa);
                }

                if (nikOrtu.val()) {
                    validate(nikOrtu);
                }
            },

            checkingPhoneNumber() {
                const telpAsalSekolah = $('input[name="nomor_telepon_asal_sekolah"]');

                const validate = (el, type) => {
                    const val = el.val();
                    const feedbackId = `${el.attr('name')}-feedback`;
                    $(`#${feedbackId}`).remove();
                    el.removeClass('is-invalid is-valid');

                    if (val.length === 0) return;

                    let regex;
                    let errorMessage = "";

                    if (type === 'hp') {
                        // Regex HP: Awalan 08 atau +628, panjang 10-13 digit
                        regex = /^(\+62|0)8[1-9][0-9]{7,10}$/;
                        errorMessage = "Format No. HP tidak valid (Gunakan awalan 08 atau +628, 10-13 digit).";
                    } else {
                        // Regex Telp Kantor: Awalan kode area (02x/03x/dst), panjang 7-11 digit
                        regex = /^0[2-9][1-9][0-9]{6,9}$/;
                        errorMessage = "Format No. Telp Kantor / Sekolah tidak valid (Gunakan kode area, contoh: 031xxxx).";
                    }

                    if (!regex.test(val)) {
                        el.addClass('is-invalid');
                        el.after(`<div id="${feedbackId}" class="invalid-feedback">${errorMessage}</div>`);
                    } else {
                        el.addClass('is-valid');
                    }
                };

                telpAsalSekolah.on('input change', function () {
                    validate($(this), 'kantor');
                });

                if (telpAsalSekolah.val()) {
                    validate(telpAsalSekolah, 'kantor');
                }
            },

            setupProvinceChangeEvent() {
                const self = this;
                $('.select2-provinces').on('change', function() {
                    self.fetchCities($(this).val());
                });
            },

            fetchCities(provinceId, selectedCityId = null) {
                let citySelect = $('#city');

                // Jangan lakukan apa-apa jika provinsi kosong
                if (!provinceId) {
                    citySelect.empty().append('<option value=""></option>').trigger('change');
                    return;
                }

                citySelect.prop('disabled', true);

                $.ajax({
                    url: "{{ route('ppdb.get-cities') }}",
                    type: "GET",
                    data: { province_id: provinceId },
                    success: (response) => {
                        citySelect.empty().append('<option value=""></option>');

                        $.each(response, (key, city) => {
                            // Cek apakah ID kota ini adalah yang harus terpilih (untuk handle edit/old data)
                            const isSelected = (selectedCityId && city.name == selectedCityId);
                            citySelect.append(new Option(city.name, city.name, isSelected, isSelected));
                        });

                        citySelect.prop('disabled', false).trigger('change');
                    },
                    error: () => {
                        alert('Gagal mengambil data kota.');
                        citySelect.prop('disabled', false);
                    }
                });
            },

        };


        $(document).ready(() => RegistrationWizard.init());

    </script>
@endpush
