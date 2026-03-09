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
                        @foreach(['Identitas Siswa', 'Data Tambahan', 'Asal Sekolah', 'Riwayat Kesehatan', 'Prestasi & Potensi'] as $index => $label)
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
                <form action="#" method="POST" id="mainRegistrationForm">
                    @csrf
                    <div class="tab-content">
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

                        <div class="tab-pane fade" id="additional" role="tabpanel">
                            <div class="col-12">
                                <h5 class="section-title"><i class="bi bi-person-badge me-2"></i>Data Calon Peserta
                                    Didik</h5>
                            </div>

                            @include('ppdb-online.partials.form_registration._additional_data')
                        </div>

                        <div class="tab-pane fade" id="school" role="tabpanel">
                            <div class="col-12">
                                <h5 class="section-title"><i class="bi bi-person-badge me-2"></i>Asal Sekolah Calon Peserta Didik</h5>
                            </div>

                            @include('ppdb-online.partials.form_registration._school_form')
                        </div>

                    </div>

                    <div class="form-footer mt-5 pt-4 d-flex justify-content-between">
                        <button type="button" class="btn btn-light btn-lg rounded-pill px-4 d-none" id="prevBtn">
                            <i class="bi bi-arrow-left me-2"></i>Sebelumnya
                        </button>
                        <button type="button" class="btn btn-success btn-lg rounded-pill px-5 shadow" id="nextBtn">
                            Selanjutnya <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        const RegistrationWizard = {
            currentTab: 0,
            tabs: ['identitas', 'additional', 'school', 'healt', 'potential'],

            init() {
                this.updateUI();
                $('#nextBtn').click(() => this.moveTab(1));
                $('#prevBtn').click(() => this.moveTab(-1));
            },

            moveTab(step) {
                const nextTab = this.currentTab + step;
                if (nextTab < 0 || nextTab >= this.tabs.length) return;

                // Hide current & Show next
                $(`#${this.tabs[this.currentTab]}`).removeClass('show active');
                this.currentTab = nextTab;
                $(`#${this.tabs[this.currentTab]}`).addClass('show active');

                this.updateUI();
                window.scrollTo({top: 0, behavior: 'smooth'});
            },

            updateUI() {
                const progress = (this.currentTab / (this.tabs.length - 1)) * 100;
                $('#formProgressBar').css('width', `${progress}%`);

                $('.step-dot').each((i, el) => $(el).toggleClass('active', i <= this.currentTab));
                $('#prevBtn').toggleClass('d-none', this.currentTab === 0);
                $('#nextBtn').text(this.currentTab === this.tabs.length - 1 ? 'Kirim Pendaftaran' : 'Selanjutnya');
            }
        };

        $(document).ready(() => RegistrationWizard.init());

    </script>
@endpush
