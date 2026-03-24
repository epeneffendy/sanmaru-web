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
                <h3 class="fw-bold text-success text-center mb-5">Identitas Wali Orang Tua Calon Siswa</h3>
                <div class="row g-4">
                    <div class="col-12">
                        <h5 class="section-title"><i class="bi bi-person-badge me-2"></i>Data Wali</h5>
                    </div>
                </div>


                <form id="wrapped" method="POST" autocomplete="off"
                      action="{{route('ppdb.form-parent.submit')}}">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group custom-form-group">
                                <label class="form-label fw-bold text-muted mb-2">Nama Wali</label>
                                <div class="input-group modern-input-group">
                                    <input type="text" name="wali_name"
                                           value="{{ (!empty(@$wali['name']))?$wali['name']:old('wali_name') }}"
                                           class="form-control uppercase-input required" placeholder="Nama Wali">
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group custom-form-group">
                                <label class="form-label fw-bold text-muted mb-2">Tempat Lahir Wali</label>
                                <div class="input-group modern-input-group">
                                    <input name="w_place_of_birth" class="form-control uppercase-input required"
                                           placeholder="Tempat Lahir"
                                           value="{{ old('w_place_of_birth', @$wali->place_of_birth) }}"/>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group custom-form-group">
                                <label class="form-label fw-bold text-muted mb-2">Tanggal Lahir Wali</label>
                                <div class="input-group modern-input-group">
                                    <input type="date" class="form-control required" name="w_date_of_birth"
                                           value="{{ old('w_date_of_birth', @$wali->date_of_birth) }}"
                                           id="datepicker-wali"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <label class="form-label fw-bold text-muted mb-2">Alamat Wali</label>
                            <div class="input-group modern-input-group">
                                <textarea class="form-control uppercase-input required" rows="3" name="w_address"
                                          placeholder="Alamat">{{ (!empty(@$wali['address']))?$wali['address']:old('w_address') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold text-muted mb-2">Provinsi</label>
                            <div class="input-group modern-input-group">
                                <input name="w_region" class="form-control required" placeholder="Provinsi"
                                       value="{{ old('w_region', @$wali->region) }}"/>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold text-muted mb-2">Kota</label>
                            <div class="input-group modern-input-group">
                                <input name="w_city" class="form-control required" placeholder="Kota"
                                       value="{{ old('w_city', @$wali->city) }}"/>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold text-muted mb-2">Kewarganegaraan Wali</label>
                            <div class="input-group modern-input-group">
                                <select class="form-control required" placeholder="Kewarganegaraan" name="w_country">
                                    <option value="">--Silahkan Pilih--</option>
                                    <option
                                        value="WNI" {{ old('w_country', @$wali->country) === 'WNI' ? 'selected' : NULL }}>
                                        WNI
                                    </option>
                                    <option
                                        value="WNA" {{ old('w_country', @$wali->country) === 'WNA' ? 'selected' : NULL }}>
                                        WNA
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold text-muted mb-2">Agama Wali</label>
                            <div class="input-group modern-input-group">
                                <select class="form-control required" placeholder="Agama" name="w_religion">
                                    <option value="">--Silahkan Pilih--</option>
                                    <option
                                        value="Katolik" {{ old('w_religion', @$wali->religion) === 'Katolik' ? 'selected' : NULL }}>
                                        Katolik
                                    </option>
                                    <option
                                        value="Protestan" {{ old('w_religion', @$wali->religion) === 'Protestan' ? 'selected' : NULL }}>
                                        Protestan
                                    </option>
                                    <option
                                        value="Islam" {{ old('w_religion', @$wali->religion) === 'Islam' ? 'selected' : NULL }}>
                                        Islam
                                    </option>
                                    <option
                                        value="Hindu" {{ old('w_religion', @$wali->religion) === 'Hindu' ? 'selected' : NULL }}>
                                        Hindu
                                    </option>
                                    <option
                                        value="Buddha" {{ old('w_religion', @$wali->religion) === 'Buddha' ? 'selected' : NULL }}>
                                        Buddha
                                    </option>
                                    <option
                                        value="Khonghucu" {{ old('w_religion', @$wali->religion) === 'Khonghucu' ? 'selected' : NULL }}>
                                        Khonghucu
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold text-muted mb-2">No. Telp Wali</label>
                            <div class="input-group modern-input-group">
                                <input type="text" pattern="0-9" name="w_phone"
                                       value="{{ (!empty(@$wali['phone']))?$wali['phone']:old('w_phone') }}"
                                       class="form-control required" placeholder="Telepon">
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold text-muted mb-2">Pendidikan Wali</label>
                            <div class="input-group modern-input-group">
                                <select class="form-control required" placeholder="Pendidikan Wali" name="w_education">
                                    <option value="">--Silahkan Pilih--</option>
                                    <option
                                        value="sd" {{ old('w_education', @$wali->education) === 'sd' ? 'selected' : NULL }}>
                                        SD
                                    </option>
                                    <option
                                        value="smp" {{ old('w_education', @$wali->education) === 'smp' ? 'selected' : NULL }}>
                                        SMP
                                    </option>
                                    <option
                                        value="sma" {{ old('w_education', @$wali->education) === 'sma' ? 'selected' : NULL }}>
                                        SMA
                                    </option>
                                    <option
                                        value="d1" {{ old('w_education', @$wali->education) === 'd1' ? 'selected' : NULL }}>
                                        D-1
                                    </option>
                                    <option
                                        value="d2" {{ old('w_education', @$wali->education) === 'd2' ? 'selected' : NULL }}>
                                        D-2
                                    </option>
                                    <option
                                        value="d3" {{ old('w_education', @$wali->education) === 'd3' ? 'selected' : NULL }}>
                                        D-3
                                    </option>
                                    <option
                                        value="s1" {{ old('w_education', @$wali->education) === 's1' ? 'selected' : NULL }}>
                                        S-1
                                    </option>
                                    <option
                                        value="s2" {{ old('w_education', @$wali->education) === 's2' ? 'selected' : NULL }}>
                                        S-2
                                    </option>
                                    <option
                                        value="s3" {{ old('w_education', @$wali->education) === 's3' ? 'selected' : NULL }}>
                                        S-3
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold text-muted mb-2">Pekerjaan Wali</label>
                            <div class="input-group modern-input-group">
                                <select class="form-control required" placeholder="Pekerjaan Wali" name="w_job">
                                    <option value="">--Silahkan Pilih--</option>
                                    <option
                                        value="tidak bekerja" {{ old('w_job', @$wali->job) === 'tidak bekerja' ? 'selected' : NULL }}>
                                        Tidak Bekerja
                                    </option>
                                    <option value="pns" {{ old('w_job', @$wali->job) === 'pns' ? 'selected' : NULL }}>
                                        PNS
                                    </option>
                                    <option value="tni" {{ old('w_job', @$wali->job) === 'tni' ? 'selected' : NULL }}>
                                        TNI
                                    </option>
                                    <option
                                        value="polri" {{ old('w_job', @$wali->job) === 'polri' ? 'selected' : NULL }}>
                                        Polri
                                    </option>
                                    <option
                                        value="karyawan swasta" {{ old('w_job', @$wali->job) === 'karyawan swasta' ? 'selected' : NULL }}>
                                        Karyawan Swasta
                                    </option>
                                    <option
                                        value="wiraswasta" {{ old('w_job', @$wali->job) === 'wiraswasta' ? 'selected' : NULL }}>
                                        Wiraswasta
                                    </option>
                                    <option
                                        value="pensiunan" {{ old('w_job', @$wali->job) === 'pensiunan' ? 'selected' : NULL }}>
                                        Pensiunan
                                    </option>
                                    <option
                                        value="lainnya" {{ old('w_job', @$wali->job) === 'lainnya' ? 'selected' : NULL }}>
                                        Lainnya
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold text-muted mb-2">Penghasilan Wali</label>
                            <div class="input-group modern-input-group">
                                <select class="form-control required" placeholder="Pekerjaan Wali" name="w_salary">
                                    <option value="">--Silahkan Pilih--</option>
                                    <option
                                        value="kurang dari Rp 500.000" {{ old('w_salary', @$wali->salary) === 'kurang dari Rp 500.000' ? 'selected' : NULL }}>
                                        kurang dari Rp 500.000
                                    </option>
                                    <option
                                        value="Rp 500.000 - Rp 999.999" {{ old('w_salary', @$wali->salary) === 'Rp 500.000 - Rp 999.999' ? 'selected': NULL }}>
                                        Rp 500.000 - Rp 999.999
                                    </option>
                                    <option
                                        value="Rp 1.000.000 - Rp 1.999.999" {{ old('w_salary', @$wali->salary) === 'Rp 1.000.000 - Rp 1.999.999' ? 'selected' : NULL }}>
                                        Rp 1.000.000 - Rp 1.999.999
                                    </option>
                                    <option
                                        value="Rp 2.000.000 - Rp 4.999.999" {{ old('w_salary', @$wali->salary) === 'Rp 2.000.000 - Rp 4.999.999' ? 'selected' : NULL }}>
                                        Rp 2.000.000 - Rp 4.999.999
                                    </option>
                                    <option
                                        value="Rp 5.000.000 - Rp 20.000.000" {{ old('w_salary', @$wali->salary) === 'Rp 5.000.000 - Rp 20.000.000' ? 'selected' : NULL }}>
                                        Rp 5.000.000 - Rp 20.000.0000
                                    </option>
                                    <option
                                        value="lebih dari Rp 20.000" {{ old('w_salary', @$wali->salary) === 'lebih dari Rp 20.000' ? 'selected' : NULL }}>
                                        lebih dari Rp 20.000.000
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-footer mt-5 pt-4 d-flex justify-content-between">
                        <button type="submit" class="btn btn-success btn-lg rounded-pill px-5 shadow">
                            Simpan
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
            init() {
                $('.uppercase-input').on('input', function () {
                    this.value = this.value.toUpperCase();
                });
                this.checkingPhoneNumber();
            },

            checkingPhoneNumber() {
                const telpWali = $('input[name="w_phone"]');

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

                telpWali.on('input change', function () {
                    validate($(this), 'hp');
                });

                if (telpWali.val()) {
                    validate(telpWali, 'hp');
                }


            }


        };


        $(document).ready(() => RegistrationWizard.init());

    </script>
@endpush
