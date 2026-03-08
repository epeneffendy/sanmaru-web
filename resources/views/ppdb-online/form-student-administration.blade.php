@extends('layouts.ppdb-online.main')
@section('content')
@push('styles')
<style>
    .step-btn {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        border: 2px solid #dee2e6;
        background: white;
        color: #adb5bd;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.4s ease;
        z-index: 2;
    }

    .step-btn.active {
        background: #198754;
        color: white;
        border-color: #198754;
        box-shadow: 0 0 0 5px rgba(25, 135, 84, 0.2);
    }

    .step-btn.completed {
        background: #198754;
        color: white;
        border-color: #198754;
    }

    .step-label {
        width: 80px;
        text-align: center;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Form Design */
    .form-control-lg, .form-select-lg {
        font-size: 1rem;
        border-radius: 10px;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .tab-pane.active {
        animation: slideUp 0.5s ease-out forwards;
    }

    /* Efek transisi halus untuk progress bar */
    #formProgressBar {
        transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Mengatur agar perpindahan tidak 'melompat' */
    .tab-content {
        min-height: 300px; /* Menjaga tinggi konten tetap stabil */
        position: relative;
    }
</style>
@endpush

<div class="card border-0 overflow-hidden" style="border-radius: 15px;">
    <div class="card-header bg-white py-4 border-0">
        <h4 class="fw-bold text-success text-center mb-4">Formulir Pendaftaran Siswa Baru</h4>

        <div class="position-relative mb-5">
            <div class="progress" style="height: 2px;">
                <div id="formProgressBar" class="progress-bar bg-success" role="progressbar" style="width: 0%;"></div>
            </div>
            <div class="d-flex justify-content-between position-absolute top-50 start-0 translate-middle-y w-100">
                <button class="step-btn active" id="identitas-step" data-index="0">1</button>
                <button class="step-btn" id="tambahan-step" data-index="1">2</button>
                <button class="step-btn" id="asal-sekolah-step" data-index="2">3</button>
                <button class="step-btn" id="kesehatan-step" data-index="3">4</button>
                <button class="step-btn" id="prestasi-step" data-index="4">5</button>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-3 px-2">
            <small class="step-label text-success fw-bold"></small>
            <small class="step-label text-muted"></small>
            <small class="step-label text-muted"></small>
            <small class="step-label text-muted"></small>
            <small class="step-label text-muted"></small>
        </div>
    </div>

    <div class="card-body p-4 p-md-5 bg-light">
        <ul class="nav nav-tabs d-none" id="studentFormTab">
            <li class="nav-item">
                <button class="nav-link active" id="identitas-tab" data-bs-toggle="tab"
                        data-bs-target="#identitas"></button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tambahan-tab" data-bs-toggle="tab" data-bs-target="#tambahan"></button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="asal-sekolah-tab" data-bs-toggle="tab"
                        data-bs-target="#asal-sekolah"></button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="kesehatan-tab" data-bs-toggle="tab" data-bs-target="#kesehatan"></button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="prestasi-tab" data-bs-toggle="tab" data-bs-target="#prestasi"></button>
            </li>
        </ul>

        <form action="#" method="POST">
            <div class="tab-content mt-4" id="studentFormTabContent">
                <div class="tab-pane fade show active" id="identitas" role="tabpanel">
                    <div class="row g-4">
                        <h4 class="fw-bold mb-4 text-dark">Identitas Calon Siswa</h4>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Nama Lengkap Calon Siswa</label>
                            <input type="text" name="name"
                                   value="{{ old('name', @$ppdbUser->name) }}"
                                   class="form-control required" placeholder="Nama Lengkap Calon Siswa">
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">NIK Siswa</label>
                                <input type="text" name="nik_siswa"
                                       value="{{ old('nik_siswa', @$ppdbUser->nik_siswa) }}"
                                       class="form-control required"
                                       placeholder="NIK Siswa">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Jenis Kelamin</label>
                                <select class="form-control required" placeholder="Jenis Kelamin" name="gender">
                                    <option value="male" {{ old(
                                    'gender', @$ppdbUser->gender) === 'male' ? 'selected' : NULL }}>Laki-Laki
                                    </option>
                                    <option value="female" {{ old(
                                    'gender', @$ppdbUser->gender) === 'female' ? 'selected' : NULL }}>Perempuan
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
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
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Tanggal Lahir</label>
                                <input type="date" name="date_of_birth"
                                       value="{{ old('date_of_birth', @$ppdbUser->date_of_birth) }}"
                                       class="form-control required" id="datepicker"/>
                            </div>
                        </div>


                    </div>
                </div>

            </div>

            <div class="mt-5 d-flex justify-content-between border-top pt-4">
                <button type="button" class="btn btn-light btn-lg px-4 fw-bold text-muted" id="prevBtn"
                        onclick="changeTab(-1)">
                    <i class="bi bi-arrow-left me-2"></i>Sebelumnya
                </button>
                <button type="button" class="btn btn-success btn-lg px-5 fw-bold shadow" id="nextBtn"
                        onclick="changeTab(1)">
                    Selanjutnya<i class="bi bi-arrow-right ms-2"></i>
                </button>
                <button type="submit" class="btn btn-primary btn-lg px-5 fw-bold shadow d-none" id="submitBtn">
                    Kirim Pendaftaran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
    let currentTab = 0;
    const tabIDs = ['identitas', 'tambahan', 'asal-sekolah', 'kesehatan', 'prestasi'];

    function changeTab(step) {
        const nextTabIdx = currentTab + step;
        if (nextTabIdx < 0 || nextTabIdx >= tabIDs.length) return;

        // Berikan efek visual 'loading' tipis pada kontainer jika diperlukan
        const container = document.getElementById('studentFormTabContent');
        container.style.opacity = '0.5';

        setTimeout(() => {
            currentTab = nextTabIdx;

            // Trigger Tab Bootstrap
            const tabTrigger = new bootstrap.Tab(document.getElementById(tabIDs[currentTab] + '-tab'));
            tabTrigger.show();

            // Kembalikan opacity dan update UI Stepper
            container.style.opacity = '1';
            updateUI();

            // Scroll otomatis ke atas card jika form sangat panjang
            document.querySelector('.card').scrollIntoView({behavior: 'smooth', block: 'start'});
        }, 150); // Jeda 150ms agar transisi terasa natural
    }

    function updateUI() {
        const percentage = (currentTab / (tabIDs.length - 1)) * 100;
        document.getElementById('formProgressBar').style.width = percentage + '%';

        // Update Step Buttons & Labels
        const btns = document.querySelectorAll('.step-btn');
        const labels = document.querySelectorAll('.step-label');

        btns.forEach((btn, idx) => {
            if (idx <= currentTab) {
                btn.classList.add('active');
                labels[idx].classList.replace('text-muted', 'text-success');
                labels[idx].classList.add('fw-bold');
            } else {
                btn.classList.remove('active');
                labels[idx].classList.replace('text-success', 'text-muted');
                labels[idx].classList.remove('fw-bold');
            }
        });

        // Toggle Buttons
        document.getElementById('prevBtn').classList.toggle('invisible', currentTab === 0);
        if (currentTab === tabIDs.length - 1) {
            document.getElementById('nextBtn').classList.add('d-none');
            document.getElementById('submitBtn').classList.remove('d-none');
        } else {
            document.getElementById('nextBtn').classList.remove('d-none');
            document.getElementById('submitBtn').classList.add('d-none');
        }
    }
</script>
@endpush
