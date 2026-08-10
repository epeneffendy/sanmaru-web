@extends('layouts.ppdb-online.main')
@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    @push('styles')
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

    <div class="container py-5">
        <div class="card registration-card border-0 shadow-lg">
            <div class="card-header bg-white pt-5 pb-4 border-0">
                <h3 class="fw-bold text-success text-center mb-5">Identitas Orang Tua Calon Siswa</h3>

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
                <form id="wrapped" method="POST" autocomplete="off" action="{{route('ppdb.form-parent.submit')}}"
                      novalidate>
                    @csrf
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="father" role="tabpanel">
                            <div class="row g-4">
                                <div class="col-12">
                                    <h5 class="section-title"><i class="bi bi-person-badge me-2"></i>Data Ayah</h5>
                                </div>
                                @include('ppdb-online.partials.form_registration._father_form')
                            </div>
                        </div>

                        <div class="tab-pane fade" id="mother" role="tabpanel">
                            <div class="col-12">
                                <h5 class="section-title"><i class="bi bi-person-badge me-2"></i>Data Ibu</h5>
                            </div>

                            @include('ppdb-online.partials.form_registration._mother_form')
                        </div>
                    </div>

                    <div class="form-footer mt-4 mt-md-5 pt-3 pt-md-4 mb-4 mb-md-0 d-flex justify-content-between align-items-center gap-2">
                        <button type="button" class="btn btn-light btn-sm rounded-pill px-3 px-sm-5 shadow" id="prevBtn">Sebelumnya</button>
                        <button type="button" class="btn btn-info btn-sm rounded-pill px-3 px-sm-5 shadow ms-auto" id="nextBtn">Selanjutnya</button>
                        <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 px-sm-5 shadow ms-auto d-none"
                                id="simpan-pendaftaran">
                            Simpan <i class="bi bi-check-circle me-1 me-sm-2"></i>
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
            isSaving: false,
            tabs: @json($ppdbUser->isWaliRequired ?? false) ? ['father', 'mother', 'wali'] : ['father', 'mother'],

            init() {
                this.updateUI();
                $('#nextBtn').off('click').on('click', () => this.moveTab(1));
                $('#prevBtn').off('click').on('click', () => this.moveTab(-1));

                this.checkingPhoneNumber();

                $('.uppercase-input').on('input', function () {
                    this.value = this.value.toUpperCase();
                });

                // Submit handler untuk tab terakhir (tombol "Simpan")
                $('#wrapped').on('submit', (e) => {
                    e.preventDefault();

                    if (this.isSaving) return;

                    // Validasi tab terakhir
                    if (!this.validateCurrentTab()) {
                        swal({
                            icon: 'warning',
                            title: 'Informasi!',
                            text: 'Mohon lengkapi atau perbaiki data pada form sebelum menyimpan!',
                        });
                        return;
                    }

                    // Save tab terakhir via AJAX, lalu redirect
                    this.saveTabData(this.tabs[this.currentTab], true);
                });

                $('.select2-provinces').select2({
                    theme: 'default',
                    width: '100%',
                    placeholder: "Pilih Provinsi",
                });

                $('.select2-cities').select2({
                    theme: "bootstrap4",
                    width: '100%',
                    placeholder: "Pilih Kota"
                });

                this.setupProvinceChangeEvent();

                const f_initialProvince = $('#f_region').val();
                if (f_initialProvince) {
                    this.fetchFatherCities(f_initialProvince, "{!! old('f_city', @$dad->city) !!}");
                }
                const m_initialProvince = $('#m_region').val();
                if (m_initialProvince) {
                    this.fetchMotherCities(m_initialProvince, "{!! old('m_city', @$mom->city) !!}");
                }
                if(this.tabs.includes('wali')) {
                    const w_initialProvince = $('#w_region').val();
                    if(w_initialProvince) {
                        this.fetchWaliCities(w_initialProvince, "{!! old('w_city', @$wali->city) !!}");
                    }
                }

            },

            /**
             * Validasi client-side untuk semua field .required di tab aktif.
             */
            validateCurrentTab() {
                const currentTabId = this.tabs[this.currentTab];
                const $tabPane = $(`#${currentTabId}`);
                let isValid = true;

                $tabPane.find('.required').each(function () {
                    const $el = $(this);
                    const tagName = this.tagName.toLowerCase();
                    let value = $el.val();

                    // Hapus feedback lama
                    $el.siblings('.auto-validation-feedback').remove();
                    $el.closest('.input-group, .modern-input-group, .d-flex')
                       .siblings('.auto-validation-feedback').remove();

                    if (!value || (typeof value === 'string' && value.trim() === '')) {
                        $el.addClass('is-invalid');

                        const label = $el.closest('.form-group, .custom-form-group')
                                        .find('label').first().text().trim();

                        const feedbackEl = document.createElement('div');
                        feedbackEl.className = 'auto-validation-feedback text-danger small mt-1';
                        feedbackEl.textContent = (label || 'Field ini') + ' wajib diisi.';

                        const $inputGroup = $el.closest('.input-group, .modern-input-group, .d-flex');
                        if ($inputGroup.length) {
                            $inputGroup.after(feedbackEl);
                        } else {
                            $el.after(feedbackEl);
                        }

                        isValid = false;
                    } else {
                        if (!$el.siblings('.invalid-feedback').length) {
                            $el.removeClass('is-invalid');
                        }
                    }
                });

                if ($tabPane.find('.is-invalid').length > 0) {
                    isValid = false;
                }

                return isValid;
            },

            /**
             * Simpan data tab aktif ke server via AJAX.
             */
            saveTabData(tabName, isFinalSave = false) {
                if (this.isSaving) return;

                this.isSaving = true;

                const $nextBtn = $('#nextBtn');
                const $saveBtn = $('#simpan-pendaftaran');
                const originalNextText = $nextBtn.text();
                const originalSaveText = $saveBtn.html();

                if (isFinalSave) {
                    $saveBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...');
                } else {
                    $nextBtn.prop('disabled', true).text('Menyimpan...');
                }
                $('#prevBtn').prop('disabled', true);

                const formData = $('#wrapped').serialize() + '&tab=' + encodeURIComponent(tabName);

                $.ajax({
                    url: "{{ route('ppdb.form-parent.partial-save') }}",
                    type: "POST",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val()
                    },
                    success: (response) => {
                        this.isSaving = false;

                        if (isFinalSave) {
                            swal({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Data berhasil disimpan.',
                                timer: 1500,
                                buttons: false,
                            });
                            setTimeout(() => {
                                @if($ppdbUser->isWaliRequired ?? false)
                                     window.location.href = "{{ route('ppdb.welcome') }}";
                                @else
                                    // Sesuai permintaan: redirect ke welcome sama seperti form siswa
                                    window.location.href = "{{ route('ppdb.welcome') }}";
                                @endif
                            }, 1500);
                        } else {
                            this.performTabSwitch();
                            this.showAutoSaveNotification('Data berhasil disimpan.');
                        }

                        $nextBtn.prop('disabled', false).text(originalNextText);
                        $saveBtn.prop('disabled', false).html(originalSaveText);
                        $('#prevBtn').prop('disabled', false);
                    },
                    error: (xhr) => {
                        this.isSaving = false;

                        $nextBtn.prop('disabled', false).text(originalNextText);
                        $saveBtn.prop('disabled', false).html(originalSaveText);
                        $('#prevBtn').prop('disabled', false);

                        if (xhr.status === 419) {
                            swal({
                                icon: 'error',
                                title: 'Sesi Berakhir',
                                text: 'Sesi Anda telah berakhir karena terlalu lama diam (Page Expired). Halaman akan dimuat ulang...',
                                timer: 3000,
                                buttons: false,
                            });
                            setTimeout(() => window.location.reload(), 3000);
                            return;
                        }

                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            this.displayServerErrors(xhr.responseJSON.errors);
                            swal({
                                icon: 'warning',
                                title: 'Validasi Gagal',
                                text: 'Mohon periksa kembali data yang diisi.',
                            });
                        } else {
                            const message = (xhr.responseJSON && xhr.responseJSON.message)
                                ? xhr.responseJSON.message
                                : 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.';
                            swal({
                                icon: 'error',
                                title: 'Gagal Simpan',
                                text: message,
                            });
                        }
                    }
                });
            },

            displayServerErrors(errors) {
                $('.server-validation-feedback').remove();

                for (const [fieldName, messages] of Object.entries(errors)) {
                    const $field = $(`[name="${fieldName}"]`);
                    if ($field.length) {
                        $field.addClass('is-invalid');

                        const feedbackEl = document.createElement('div');
                        feedbackEl.className = 'server-validation-feedback text-danger small mt-1';
                        feedbackEl.textContent = Array.isArray(messages) ? messages[0] : messages;

                        const $inputGroup = $field.closest('.input-group, .modern-input-group, .d-flex');
                        if ($inputGroup.length) {
                            $inputGroup.after(feedbackEl);
                        } else {
                            $field.after(feedbackEl);
                        }
                    }
                }
            },

            showAutoSaveNotification(message) {
                const existing = document.getElementById('auto-save-toast');
                if (existing) existing.remove();

                const toast = document.createElement('div');
                toast.id = 'auto-save-toast';
                toast.style.cssText = 'position:fixed;bottom:20px;right:20px;z-index:9999;background:#198754;color:white;padding:12px 20px;border-radius:10px;box-shadow:0 4px 15px rgba(0,0,0,0.15);font-size:0.875rem;display:flex;align-items:center;gap:8px;animation:fadeInUp 0.3s ease;';

                const icon = document.createElement('i');
                icon.className = 'bi bi-check-circle-fill';

                const text = document.createElement('span');
                text.textContent = message;

                toast.appendChild(icon);
                toast.appendChild(text);
                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transition = 'opacity 0.3s ease';
                    setTimeout(() => toast.remove(), 300);
                }, 2500);
            },

            performTabSwitch() {
                $(`#${this.tabs[this.currentTab]}`).removeClass('show active');
                this.currentTab++;
                $(`#${this.tabs[this.currentTab]}`).addClass('show active');
                this.updateUI();
                window.scrollTo({top: 0, behavior: 'smooth'});
            },

            hasInvalidFieldsInAnyTab() {
                return $('.tab-pane').find('.is-invalid').length > 0;
            },

            hasInvalidFields() {
                return $(`#${this.tabs[this.currentTab]}`).find('.is-invalid').length > 0;
            },

            moveTab(step) {
                if (step > 0) {
                    $('.auto-validation-feedback').remove();
                    $('.server-validation-feedback').remove();

                    if (!this.validateCurrentTab()) {
                        swal({
                            icon: 'warning',
                            title: "Informasi!",
                            text: 'Mohon lengkapi atau perbaiki data pada form sebelum melanjutkan!',
                        });
                        return;
                    }

                    const nextTabIndex = this.currentTab + 1;
                    if (nextTabIndex >= this.tabs.length) return;

                    this.saveTabData(this.tabs[this.currentTab]);
                } else {
                    const prevTabIndex = this.currentTab - 1;
                    if (prevTabIndex < 0) return;

                    $(`#${this.tabs[this.currentTab]}`).removeClass('show active');
                    this.currentTab = prevTabIndex;
                    $(`#${this.tabs[this.currentTab]}`).addClass('show active');

                    this.updateUI();
                    window.scrollTo({top: 0, behavior: 'smooth'});
                }
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

            checkingPhoneNumber() {
                const validate = (el) => {
                    const val = el.val();
                    const feedbackId = `${el.attr('name')}-feedback`;
                    $(`#${feedbackId}`).remove();
                    el.removeClass('is-invalid is-valid');

                    if (val.length === 0) return;

                    // Regex HP: Awalan 08, 628, atau +628, panjang 10-13 digit (setelah prefix)
                    const regex = /^(?:\+62|62|0)8[1-9][0-9]{7,10}$/;
                    const errorMessage = "Format No. HP tidak valid (Gunakan awalan 08, 628, 10-13 digit).";

                    if (!regex.test(val)) {
                        el.addClass('is-invalid');
                        const feedbackEl = document.createElement('div');
                        feedbackEl.id = feedbackId;
                        feedbackEl.className = 'invalid-feedback';
                        feedbackEl.textContent = errorMessage;
                        el[0].parentNode.insertBefore(feedbackEl, el[0].nextSibling);
                    } else {
                        el.addClass('is-valid');
                    }
                };

                const f_phone = $('input[name="f_phone"]');
                const m_phone = $('input[name="m_phone"]');

                f_phone.on('input change', function () { validate($(this)); });
                m_phone.on('input change', function () { validate($(this)); });

                if (f_phone.val()) validate(f_phone);
                if (m_phone.val()) validate(m_phone);
                
                if(this.tabs.includes('wali')) {
                    const w_phone = $('input[name="w_phone"]');
                    w_phone.on('input change', function () { validate($(this)); });
                    if (w_phone.val()) validate(w_phone);
                }
            },

            setupProvinceChangeEvent() {
                const self = this;
                $('#f_region').on('change', function() {
                    self.fetchFatherCities($(this).val());
                });

                $('#m_region').on('change', function() {
                    self.fetchMotherCities($(this).val());
                });
                
                if(this.tabs.includes('wali')) {
                    $('#w_region').on('change', function() {
                        self.fetchWaliCities($(this).val());
                    });
                }
            },

            fetchFatherCities(provinceId, selectedCityId = null) {
                this.fetchCitiesCore(provinceId, selectedCityId, '#f_city');
            },

            fetchMotherCities(provinceId, selectedCityId = null) {
                this.fetchCitiesCore(provinceId, selectedCityId, '#m_city');
            },

            fetchWaliCities(provinceId, selectedCityId = null) {
                this.fetchCitiesCore(provinceId, selectedCityId, '#w_city');
            },

            fetchCitiesCore(provinceId, selectedCityId, selectSelector) {
                let citySelect = $(selectSelector);

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
                            const isSelected = (selectedCityId && (city.name == selectedCityId || city.id == selectedCityId));
                            citySelect.append(new Option(city.name, city.name, isSelected, isSelected));
                        });

                        citySelect.prop('disabled', false).trigger('change');
                    },
                    error: () => {
                        swal({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal mengambil data kota.',
                        });
                        citySelect.prop('disabled', false);
                    }
                });
            }
        };

        $(document).ready(() => RegistrationWizard.init());
    </script>
@endpush
