@extends('layouts.ppdb-online.main')
@section('content')
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
    <script>
        const RegistrationWizard = {
            currentTab: 0,
            tabs: ['father', 'mother'],


            init() {
                this.updateUI();
                $('#nextBtn').off('click').on('click', () => this.moveTab(1));
                $('#prevBtn').off('click').on('click', () => this.moveTab(-1));

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

            checkingPhoneNumber() {
                const telpAyah = $('input[name="f_phone"]');
                const telpIbu = $('input[name="m_phone"]');

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

                telpAyah.on('input change', function () {
                    validate($(this), 'hp');
                });

                if (telpAyah.val()) {
                    validate(telpAyah, 'hp');
                }

                telpIbu.on('input change', function () {
                    validate($(this), 'hp');
                });

                if (telpIbu.val()) {
                    validate(telpIbu, 'hp');
                }


            }


        };


        $(document).ready(() => RegistrationWizard.init());

    </script>
@endpush
