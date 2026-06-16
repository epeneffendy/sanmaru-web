@extends('layouts.ppdb-online.main')

@section('content')
    @push('styles')
        <style>
            /* --- Base & Color Variables --- */
            :root {
                --primary-green: #1a4d2e;
                --primary-green-hover: #143d24;
                --light-green: #f0f7f2;
                --text-muted: #6c757d;
                --border-color: #eaeaea;
            }

            /* --- Typography & Accents --- */
            .header-accent {
                width: 50px;
                height: 4px;
                background-color: var(--primary-green);
                border-radius: 4px;
                margin-top: 8px;
            }

            .x-small {
                font-size: 0.75rem;
                line-height: 1.4;
            }

            .rounded-4 {
                border-radius: 1rem !important;
            }

            /* --- Bill Items --- */
            .bill-row {
                padding: 1.5rem 0;
                transition: background 0.2s ease;
            }

            .bill-row:last-child {
                border-bottom: none !important;
            }

            .bill-row:hover {
                background-color: #fcfcfc;
            }

            .icon-square {
                width: 48px;
                height: 48px;
                background-color: rgba(26, 77, 46, 0.08);
                color: var(--primary-green);
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.25rem;
                flex-shrink: 0;
            }

            /* --- Status Badges (Diperbarui agar lebih rapi) --- */
            .status-badge {
                padding: 5px 12px;
                font-size: 0.7rem;
                font-weight: 600;
                letter-spacing: 0.3px;
                border-radius: 20px;
                /* Diubah menjadi bentuk pill agar lebih modern */
                text-transform: uppercase;
                display: inline-flex;
                align-items: center;
                gap: 5px;
            }

            .status-lunas {
                background-color: #e8f5e9;
                color: #2e7d32;
                border: 1px solid #c8e6c9;
            }

            .status-belum {
                background-color: #fff8e1;
                color: #f57f17;
                border: 1px solid #ffecb3;
            }

            /* --- Payment Type Badge (Cicilan/Lunas) --- */
            .tipe-badge {
                padding: 4px 10px;
                font-size: 0.65rem;
                font-weight: 500;
                border-radius: 6px;
                background-color: #f1f3f5;
                color: #495057;
                border: 1px solid #e9ecef;
                display: inline-flex;
                align-items: center;
                gap: 4px;
            }

            /* --- Buttons --- */
            .btn-outline-green {
                color: var(--primary-green);
                border: 1px solid var(--primary-green);
                background-color: transparent;
                transition: all 0.2s ease;
            }

            .btn-outline-green:hover,
            .btn-outline-green[aria-expanded="true"] {
                background-color: var(--primary-green);
                color: white;
            }

            .btn-pay {
                background-color: var(--primary-green);
                color: white;
                border: none;
                box-shadow: 0 4px 12px rgba(26, 77, 46, 0.15);
                transition: all 0.3s ease;
            }

            .btn-pay:hover {
                background-color: var(--primary-green-hover);
                color: white;
                transform: translateY(-2px);
                box-shadow: 0 6px 15px rgba(26, 77, 46, 0.25);
            }

            /* --- Summary & Instructions --- */
            .summary-box {
                background-color: var(--light-green);
                border: 1px solid rgba(26, 77, 46, 0.15);
                position: relative;
                overflow: hidden;
            }

            .instruction-panel {
                background-color: #f8f9fa;
                border: 1px dashed #ced4da;
                border-radius: 0.75rem;
                position: relative;
            }

            .instruction-panel::before {
                content: '';
                position: absolute;
                top: -8px;
                right: 40px;
                width: 16px;
                height: 16px;
                background-color: #f8f9fa;
                border-top: 1px dashed #ced4da;
                border-left: 1px dashed #ced4da;
                transform: rotate(45deg);
            }

            .copy-btn {
                background: rgba(26, 77, 46, 0.1);
                color: var(--primary-green);
                border: none;
                border-radius: 4px;
                padding: 2px 8px;
                font-size: 0.7rem;
                transition: background 0.2s;
            }

            .copy-btn:hover {
                background: rgba(26, 77, 46, 0.2);
            }

            /* --- Responsive --- */
            @media (max-width: 767.98px) {
                .responsive-total {
                    font-size: 1.5rem;
                }

                .border-top-mobile {
                    border-top: 1px dashed var(--border-color);
                    padding-top: 15px;
                    margin-top: 15px;
                }

                .instruction-panel::before {
                    right: auto;
                    left: 40px;
                }
            }

            /* Soft Badges */
            .badge-modern {
                padding: 4px 10px;
                border-radius: 6px;
                font-size: 11px;
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                gap: 4px;
                margin-right: 4px;
                margin-bottom: 4px;
            }

            .badge-modern i {
                font-size: 10px;
            }

            .badge-soft-success {
                background-color: #dcfce7;
                color: #166534;
                border: 1px solid #bbf7d0;
            }

            .badge-soft-danger {
                background-color: #fee2e2;
                color: #991b1b;
                border: 1px solid #fecaca;
            }

            .badge-soft-warning {
                background-color: #fef3c7;
                color: #92400e;
                border: 1px solid #fde68a;
            }

            .badge-soft-info {
                background-color: #e0f2fe;
                color: #075985;
                border: 1px solid #bae6fd;
            }

            .badge-soft-secondary {
                background-color: #f1f5f9;
                color: #475569;
                border: 1px solid #e2e8f0;
            }
        </style>
    @endpush

    <div class="row-height bg-light" style="min-height: 100vh;">
        @if ($ppdbUser->status == 'confirmed')
            <div class="container py-4 px-2 px-md-4">
                <div class="row justify-content-center">
                    <div class="form-group" style="padding:3em">
                        <div class="alert border-0 shadow-sm" role="alert"
                            style="background-color: #f8f9fa; border-left: 5px solid #17a2b8 !important; border-radius: 8px; padding: 20px;">
                            <div class="d-flex align-items-center">
                                <div style="margin-right: 20px;">
                                    <i class="fa fa-info-circle text-info" style="font-size: 2.5rem;"></i>
                                </div>
                                <div>
                                    <h5 class="font-weight-bold text-dark mb-2" style="font-size: 16px; margin-top: 0;">
                                        Informasi </h5>
                                    <span class="text-muted" style="font-size: 14px;">
                                        Halaman ini belum tersedia untuk akun Anda. <br>
                                        Silahkan lengkapi terlebih dahulu data <strong>Administrasi Siswa</strong> untuk
                                        mendapatkan informasi lebih lanjut.
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="container py-4 px-2 px-md-4">
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-9">

                        @if (count($bills) > 0)
                            <!-- Header Title -->
                            <div class="text-center mb-4">
                                <h4 class="fw-bold px-3 mb-1" style="color: #1a4d2e; font-size: calc(1.2rem + 0.5vw);">
                                    Rincian Biaya Pendidikan
                                </h4>
                                <p class="text-muted small mb-2">Tahun Ajaran
                                    {{ $ppdb['school_year'] . ' - ' . ($ppdb['school_year'] + 1) }}</p>
                                <div class="header-accent mx-auto"></div>
                            </div>

                            @if (!$is_show)
                                <div class="alert mt-2 p-3"
                                    style="background-color: #e0f2fe; border: 1px solid #bae6fd; border-radius: 8px;">
                                    <h6 class="fw-bold mb-2" style="color: #075985; font-size: 13px;">
                                        <i class="fa-solid fa-gift me-1"></i> Pembayaran Masih Belum Aktif, Silahkan
                                        Selesaikan
                                        Tahapan Penerimaan Terlebih Dahulu!
                                    </h6>
                                </div>
                            @endif

                            <!-- Main Card -->
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

                                <!-- Card Header -->
                                <div class="card-header border-0 p-3 p-md-4 d-flex justify-content-between align-items-center"
                                    style="background-color: #1a4d2e;">
                                    <h5 class="mb-0 text-white fw-bold h6-mobile">
                                        <i class="fa fa-list-alt me-2"></i> Daftar Tagihan Anda
                                    </h5>
                                </div>

                                <div class="card-body p-0 bg-white">

                                    <!-- Invoice Meta Info -->
                                    <div
                                        class="p-4 border-bottom bg-white d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                                        <div>
                                            <span class="text-muted x-small text-uppercase d-block mb-1">No Register</span>
                                            <span class="fw-bold text-dark">{{ $ppdb['register_number'] }}</span>
                                        </div>
                                        <div>
                                            {{-- <span class="text-muted x-small text-uppercase d-block mb-1">Nama Siswa</span>
                                            <span class="fw-bold text-dark">{{ $ppdb['name'] }}</span> --}}
                                        </div>
                                        <div class="text-md-end">
                                            <span class="text-muted x-small text-uppercase d-block mb-1">Nama Siswa</span>
                                            <span class="fw-bold text-dark">{{ $ppdb['name'] }}</span>
                                        </div>
                                    </div>

                                    <!-- Loop Daftar Tagihan -->
                                    <div class="px-3 px-md-4">
                                        @php
                                            $total = 0;
                                        @endphp
                                        @foreach ($bills as $item)
                                            @if ($item['type'] == 'registrasi')
                                                <div class="bill-row border-bottom border-light">
                                                    <div
                                                        class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                                                        <!-- Info Tagihan -->
                                                        <div class="d-flex align-items-start w-100">
                                                            <div class="ms-3 flex-grow-1">
                                                                <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                                                                    <h6 class="mb-0 fw-bold text-dark me-1">Uang Pendaftaran
                                                                    </h6>
                                                                </div>

                                                                <span class="text-muted x-small d-block pe-md-4 mt-1">Biaya
                                                                    administrasi, tes seleksi, dan formulir
                                                                    pendaftaran</span>

                                                                <div class="row">
                                                                    @if ($item['payment_method'] == 'paid')
                                                                        <div
                                                                            style="margin-bottom: 4px; padding-left: 12px;">
                                                                            <span class="badge-modern badge-soft-success"
                                                                                style="border-radius: 20px;">
                                                                                <i class="fa fa-check-circle"></i> Sudah
                                                                                Terbayarkan
                                                                            </span>
                                                                        </div>
                                                                    @else
                                                                        <div
                                                                            style="margin-bottom: 4px; padding-left: 12px;">
                                                                            <span class="badge-modern badge-soft-warning"
                                                                                style="border-radius: 20px;">
                                                                                <i class="fa fa-check-circle"></i> Belum
                                                                                Terbayarkan
                                                                            </span>
                                                                        </div>
                                                                    @endif

                                                                    @if ($item['payment_term'] == 'full_payment')
                                                                        <div
                                                                            style="margin-bottom: 4px; padding-left: 12px;">
                                                                            <span class="badge-modern badge-soft-info"
                                                                                style="border-radius: 20px;">
                                                                                <i class="fa fa-check-circle"></i> Lunas
                                                                            </span>
                                                                        </div>
                                                                    @elseif($item['payment_term'] == 'partial_payment')
                                                                        <div
                                                                            style="margin-bottom: 4px; padding-left: 12px;">
                                                                            <span class="badge-modern badge-soft-secondary"
                                                                                style="border-radius: 20px;">
                                                                                <i class="fa fa-check-circle"></i> Cicilan
                                                                            </span>
                                                                        </div>
                                                                    @endif

                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Nominal & Tombol Aksi -->
                                                        <div
                                                            class="text-md-end w-100 w-md-auto mt-2 mt-md-0 border-top-mobile pt-md-0 pt-2 d-flex flex-row flex-md-column justify-content-between align-items-center align-items-md-end">
                                                            <div class="fw-bold text-dark mb-md-2"
                                                                style="font-size: 1.15rem;">
                                                                Rp {{ number_format($item['amount'], 0, ',', '.') }}
                                                            </div>

                                                            @if ($item['payment_method'] == 'paid')
                                                                {{-- <a href="{{ route('ppdb.registration-payment-receipt', $item['ppdb_user_id']) }}"
                                                                    class="btn btn-sm btn-light text-muted border px-3"
                                                                    style="font-size: 0.75rem; font-weight: 600;">
                                                                    <i class="fa fa-file-text-o me-1"></i> Bukti Lunas
                                                                </a> --}}
                                                                <button type="button"
                                                                    class="btn btn-sm btn-primary btn-receipt"
                                                                    data-id="{{ $item['ppdb_user_id'] }}">
                                                                    Bukti Lunas
                                                                </button>
                                                            @else
                                                                {{-- <a href="{{ route('ppdb.bills.choise-payment') }}"
                                                                    class="btn btn-sm btn-outline-green px-3"
                                                                    style="font-size: 0.75rem; font-weight: 600;">
                                                                    Cara Bayar <i class="fa fa-chevron-right ms-1"></i>
                                                                </a> --}}
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Expandable / Accordion Cara Bayar Khusus Item Ini -->
                                                    @if ($item['status'] == 'belum')
                                                        <div class="collapse mt-3" id="caraBayar{{ $item['id'] }}">
                                                            <div class="instruction-panel p-3 p-md-4">
                                                                <div class="row g-3 align-items-center">
                                                                    <div class="col-12 col-md-6 border-end-md">
                                                                        <h6 class="fw-bold small text-dark mb-3"><i
                                                                                class="fa fa-university text-success me-2"></i>
                                                                            Instruksi Transfer BCA</h6>
                                                                        <div class="mb-2">
                                                                            <span class="d-block text-muted"
                                                                                style="font-size: 0.7rem;">Nomor Virtual
                                                                                Account
                                                                                (VA)
                                                                            </span>
                                                                            <div class="d-flex align-items-center gap-2">
                                                                                <strong class="text-dark"
                                                                                    style="font-size: 1.1rem; letter-spacing: 1px;">{{ $item['va'] }}</strong>
                                                                                <button class="copy-btn"><i
                                                                                        class="fa fa-files-o"></i>
                                                                                    Salin</button>
                                                                            </div>
                                                                        </div>
                                                                        <div>
                                                                            <span class="d-block text-muted"
                                                                                style="font-size: 0.7rem;">Nominal
                                                                                Pembayaran
                                                                                ({{ $item['tipe_bayar'] }})</span>
                                                                            <strong class="text-danger">Rp
                                                                                {{ number_format($item['jumlah'], 0, ',', '.') }}</strong>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-12 col-md-6">
                                                                        <div class="ps-md-2 text-muted x-small">
                                                                            <p class="mb-1 fw-bold text-dark">Langkah
                                                                                Pembayaran:
                                                                            </p>
                                                                            <ol class="ps-3 mb-0">
                                                                                <li class="mb-1">Masuk ke m-BCA / ATM
                                                                                    BCA.
                                                                                </li>
                                                                                <li class="mb-1">Pilih
                                                                                    <strong>m-Transfer</strong>
                                                                                    >
                                                                                    <strong>BCA Virtual Account</strong>.
                                                                                </li>
                                                                                <li class="mb-1">Masukkan Nomor VA di
                                                                                    samping.
                                                                                </li>
                                                                                <li>Pastikan nominal tepat dan selesaikan
                                                                                    transaksi.
                                                                                    Sistem otomatis update 5-10 menit.</li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            @if ($item['type'] == 'development')
                                                <div class="bill-row border-bottom border-light">
                                                    <div
                                                        class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                                                        <!-- Info Tagihan -->
                                                        <div class="d-flex align-items-start w-100">
                                                            <div class="ms-3 flex-grow-1">
                                                                <div
                                                                    class="d-flex align-items-center flex-wrap gap-2 mb-1">
                                                                    <h6 class="mb-0 fw-bold text-dark me-1">Uang
                                                                        Pengembangan
                                                                    </h6>
                                                                </div>

                                                                <span
                                                                    class="text-muted x-small d-block pe-md-4 mt-1">Pembangunan
                                                                    sarana, prasarana, dan fasilitas gedung</span>
                                                                @if ($item['payment_method'] == 'closed')
                                                                    <div class="row">
                                                                        <div
                                                                            style="margin-bottom: 4px; padding-left: 12px;">
                                                                            <span class="badge-modern badge-soft-danger"
                                                                                style="border-radius: 20px;">
                                                                                <i class="fa fa-times-circle"></i> Tagihan
                                                                                Dihentikan
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div class="row">
                                                                        @if ($item['payment_method'] == 'paid')
                                                                            <div
                                                                                style="margin-bottom: 4px; padding-left: 12px;">
                                                                                <span
                                                                                    class="badge-modern badge-soft-success"
                                                                                    style="border-radius: 20px;">
                                                                                    <i class="fa fa-check-circle"></i>
                                                                                    Sudah
                                                                                    Terbayarkan
                                                                                </span>
                                                                            </div>
                                                                        @else
                                                                            <div
                                                                                style="margin-bottom: 4px; padding-left: 12px;">
                                                                                <span
                                                                                    class="badge-modern badge-soft-warning"
                                                                                    style="border-radius: 20px;">
                                                                                    <i class="fa fa-check-circle"></i>
                                                                                    Belum
                                                                                    Terbayarkan
                                                                                </span>
                                                                            </div>
                                                                        @endif

                                                                        @if ($item['payment_term'] == 'full_payment')
                                                                            <div
                                                                                style="margin-bottom: 4px; padding-left: 12px;">
                                                                                <span class="badge-modern badge-soft-info"
                                                                                    style="border-radius: 20px;">
                                                                                    <i class="fa fa-check-circle"></i>
                                                                                    Lunas
                                                                                </span>
                                                                            </div>
                                                                        @endif
                                                                        @if ($item['payment_term'] == 'installment_payment')
                                                                            <div
                                                                                style="margin-bottom: 4px; padding-left: 12px;">
                                                                                <span
                                                                                    class="badge-modern badge-soft-secondary"
                                                                                    style="border-radius: 20px;">
                                                                                    <i class="fa fa-check-circle"></i>
                                                                                    Cicilan
                                                                                </span>
                                                                            </div>
                                                                        @endif

                                                                        @if ($is_dispensation)
                                                                            <div
                                                                                style="margin-bottom: 4px; padding-left: 12px;">
                                                                                <span
                                                                                    class="badge-modern badge-soft-success"
                                                                                    style="border-radius: 20px;">
                                                                                    <i class="fa fa-check-circle"></i> Anda
                                                                                    menerima potongan pembayaran </span>
                                                                            </div>
                                                                        @endif

                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <!-- Nominal & Tombol Aksi -->
                                                        <div
                                                            class="text-md-end w-100 w-md-auto mt-2 mt-md-0 border-top-mobile pt-md-0 pt-2 d-flex flex-row flex-md-column justify-content-between align-items-center align-items-md-end">
                                                            @if ($is_dispensation)
                                                                <div class="fw-bold text-dark mb-md-2"
                                                                    style="font-size: 1.15rem;">
                                                                    Rp
                                                                    {{ number_format($dispensation->total_final_fee, 0, ',', '.') }}
                                                                </div>
                                                                <div class="fw-bold text-muted mb-md-2"
                                                                    style="font-size: 0.95rem; text-decoration: line-through;">
                                                                    Rp {{ number_format($item['amount'], 0, ',', '.') }}
                                                                </div>
                                                            @else
                                                                <div class="fw-bold text-dark mb-md-2"
                                                                    style="font-size: 1.15rem;">
                                                                    Rp {{ number_format($item['amount'], 0, ',', '.') }}
                                                                </div>
                                                            @endif

                                                            @if ($item['payment_method'] == 'paid')
                                                                <a href={{ route('ppdb.bills.payment-paid-receipt', ['id' => 89]) }}
                                                                    class="btn btn-sm btn-light text-muted border px-3"
                                                                    style="font-size: 0.75rem; font-weight: 600;">
                                                                    <i class="fa fa-file-text-o me-1"></i> Bukti Lunas
                                                                </a>
                                                            @else
                                                                @if ($is_show)
                                                                    @if ($item['payment_method'] == 'unpaid' || $item['payment_method'] == 'partial')
                                                                        <a href="{{ route('ppdb.bills.choise-payment', ['type' => 'development']) }}"
                                                                            class="btn btn-sm btn-outline-green px-3"
                                                                            style="font-size: 0.75rem; font-weight: 600;">
                                                                            Cara Bayar <i
                                                                                class="fa fa-chevron-right ms-1"></i>
                                                                        </a>
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Expandable / Accordion Cara Bayar Khusus Item Ini -->
                                                    @if ($item['status'] == 'belum')
                                                        <div class="collapse mt-3" id="caraBayar{{ $item['id'] }}">
                                                            <div class="instruction-panel p-3 p-md-4">
                                                                <div class="row g-3 align-items-center">
                                                                    <div class="col-12 col-md-6 border-end-md">
                                                                        <h6 class="fw-bold small text-dark mb-3"><i
                                                                                class="fa fa-university text-success me-2"></i>
                                                                            Instruksi Transfer BCA</h6>
                                                                        <div class="mb-2">
                                                                            <span class="d-block text-muted"
                                                                                style="font-size: 0.7rem;">Nomor Virtual
                                                                                Account
                                                                                (VA)
                                                                            </span>
                                                                            <div class="d-flex align-items-center gap-2">
                                                                                <strong class="text-dark"
                                                                                    style="font-size: 1.1rem; letter-spacing: 1px;">{{ $item['va'] }}</strong>
                                                                                <button class="copy-btn"><i
                                                                                        class="fa fa-files-o"></i>
                                                                                    Salin</button>
                                                                            </div>
                                                                        </div>
                                                                        <div>
                                                                            <span class="d-block text-muted"
                                                                                style="font-size: 0.7rem;">Nominal
                                                                                Pembayaran
                                                                                ({{ $item['tipe_bayar'] }})</span>
                                                                            <strong class="text-danger">Rp
                                                                                {{ number_format($item['jumlah'], 0, ',', '.') }}</strong>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-12 col-md-6">
                                                                        <div class="ps-md-2 text-muted x-small">
                                                                            <p class="mb-1 fw-bold text-dark">Langkah
                                                                                Pembayaran:
                                                                            </p>
                                                                            <ol class="ps-3 mb-0">
                                                                                <li class="mb-1">Masuk ke m-BCA / ATM
                                                                                    BCA.
                                                                                </li>
                                                                                <li class="mb-1">Pilih
                                                                                    <strong>m-Transfer</strong>
                                                                                    >
                                                                                    <strong>BCA Virtual Account</strong>.
                                                                                </li>
                                                                                <li class="mb-1">Masukkan Nomor VA di
                                                                                    samping.
                                                                                </li>
                                                                                <li>Pastikan nominal tepat dan selesaikan
                                                                                    transaksi.
                                                                                    Sistem otomatis update 5-10 menit.</li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Alert Upload Surat Pernyataan Jika Sudah Lunas -->
                                                @if ($item['payment_method'] == 'paid' && $item['payment_term'] == 'full_payment')
                                                    @if (empty($item->ppdb->development_statement))
                                                        <div class="mt-3 p-3 rounded"
                                                            style="background-color: #fff3cd; border: 1px solid #ffe69c;">
                                                            <div
                                                                class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <div>
                                                                        <h6 class="mb-0 fw-bold text-dark"
                                                                            style="font-size: 0.9rem;">Upload Surat
                                                                            Pernyataan</h6>
                                                                        <span class="text-muted"
                                                                            style="font-size: 0.8rem;">Penting: Setelah
                                                                            pembayaran, Anda <b>wajib</b> mengunggah
                                                                            surat pernyataan. Format PDF.</span>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex gap-2">
                                                                    <a href="{{ route('ppdb.download-biaya-pengembangan', ['type' => $item['payment_term'] == 'full_payment' ? 'lunas' : 'cicilan']) }}"
                                                                        target="_blank"
                                                                        class="btn btn-sm btn-warning text-dark fw-bold px-3">
                                                                        <i class="fa fa-download me-1"></i> Unduh Form
                                                                    </a>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-success fw-bold px-3 upload-statement-trigger"
                                                                        data-type="{{ $item['payment_term'] == 'full_payment' ? 'lunas' : 'cicilan' }}">
                                                                        <i class="fa fa-upload me-1"></i> Upload
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="mt-3 p-3 rounded"
                                                            style="background-color: #d1e7dd; border: 1px solid #badbcc;">
                                                            <div
                                                                class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <div>
                                                                        <h6 class="mb-0 fw-bold text-dark"
                                                                            style="font-size: 0.9rem;">Surat Pernyataan
                                                                            Terunggah</h6>
                                                                        <span class="text-muted"
                                                                            style="font-size: 0.8rem;">Terima kasih,
                                                                            Anda telah berhasil mengunggah surat
                                                                            pernyataan biaya pengembangan.</span>
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <a href="{{ route('ppdb.download-development-statement-letter') }}"
                                                                        target="_blank"
                                                                        class="btn btn-sm btn-outline-success fw-bold px-3">
                                                                        <i class="fa fa-eye me-1"></i> Lihat File
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endif


                                            @if ($item['type'] == 'activity')
                                                <div class="bill-row border-bottom border-light">
                                                    <div
                                                        class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                                                        <!-- Info Tagihan -->
                                                        <div class="d-flex align-items-start w-100">
                                                            <div class="ms-3 flex-grow-1">
                                                                <div
                                                                    class="d-flex align-items-center flex-wrap gap-2 mb-1">
                                                                    <h6 class="mb-0 fw-bold text-dark me-1">Uang Kegiatan
                                                                    </h6>
                                                                </div>

                                                                <span
                                                                    class="text-muted x-small d-block pe-md-4 mt-1">Kegiatan
                                                                    siswa selama 1 tahun (Study Tour, Ekskul, dll)</span>

                                                                <div class="row">
                                                                    @if ($item['payment_method'] == 'paid')
                                                                        <div
                                                                            style="margin-bottom: 4px; padding-left: 12px;">
                                                                            <span class="badge-modern badge-soft-success"
                                                                                style="border-radius: 20px;">
                                                                                <i class="fa fa-check-circle"></i> Sudah
                                                                                Terbayarkan
                                                                            </span>
                                                                        </div>
                                                                    @else
                                                                        <div
                                                                            style="margin-bottom: 4px; padding-left: 12px;">
                                                                            <span class="badge-modern badge-soft-warning"
                                                                                style="border-radius: 20px;">
                                                                                <i class="fa fa-check-circle"></i> Belum
                                                                                Terbayarkan
                                                                            </span>
                                                                        </div>
                                                                    @endif

                                                                    @if ($item['payment_term'] == 'full_payment')
                                                                        <div
                                                                            style="margin-bottom: 4px; padding-left: 12px;">
                                                                            <span class="badge-modern badge-soft-info"
                                                                                style="border-radius: 20px;">
                                                                                <i class="fa fa-check-circle"></i> Lunas
                                                                            </span>
                                                                        </div>
                                                                    @elseif($item['payment_term'] == 'partial_payment')
                                                                        <div
                                                                            style="margin-bottom: 4px; padding-left: 12px;">
                                                                            <span class="badge-modern badge-soft-secondary"
                                                                                style="border-radius: 20px;">
                                                                                <i class="fa fa-check-circle"></i> Cicilan
                                                                            </span>
                                                                        </div>
                                                                    @endif

                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Nominal & Tombol Aksi -->
                                                        <div
                                                            class="text-md-end w-100 w-md-auto mt-2 mt-md-0 border-top-mobile pt-md-0 pt-2 d-flex flex-row flex-md-column justify-content-between align-items-center align-items-md-end">
                                                            <div class="fw-bold text-dark mb-md-2"
                                                                style="font-size: 1.15rem;">
                                                                Rp {{ number_format($item['amount'], 0, ',', '.') }}
                                                            </div>


                                                            @if ($item['payment_method'] == 'paid')
                                                                <button class="btn btn-sm btn-light text-muted border px-3"
                                                                    style="font-size: 0.75rem; font-weight: 600;">
                                                                    <i class="fa fa-file-text-o me-1"></i> Bukti Lunas
                                                                </button>
                                                            @else
                                                                @if ($is_show)
                                                                    <a href="{{ route('ppdb.bills.choise-payment', ['type' => 'activity']) }}"
                                                                        class="btn btn-sm btn-outline-green px-3"
                                                                        style="font-size: 0.75rem; font-weight: 600;">
                                                                        Cara Bayar <i class="fa fa-chevron-right ms-1"></i>
                                                                    </a>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Expandable / Accordion Cara Bayar Khusus Item Ini -->
                                                    @if ($item['status'] == 'belum')
                                                        <div class="collapse mt-3" id="caraBayar{{ $item['id'] }}">
                                                            <div class="instruction-panel p-3 p-md-4">
                                                                <div class="row g-3 align-items-center">
                                                                    <div class="col-12 col-md-6 border-end-md">
                                                                        <h6 class="fw-bold small text-dark mb-3"><i
                                                                                class="fa fa-university text-success me-2"></i>
                                                                            Instruksi Transfer BCA</h6>
                                                                        <div class="mb-2">
                                                                            <span class="d-block text-muted"
                                                                                style="font-size: 0.7rem;">Nomor Virtual
                                                                                Account
                                                                                (VA)
                                                                            </span>
                                                                            <div class="d-flex align-items-center gap-2">
                                                                                <strong class="text-dark"
                                                                                    style="font-size: 1.1rem; letter-spacing: 1px;">{{ $item['va'] }}</strong>
                                                                                <button class="copy-btn"><i
                                                                                        class="fa fa-files-o"></i>
                                                                                    Salin</button>
                                                                            </div>
                                                                        </div>
                                                                        <div>
                                                                            <span class="d-block text-muted"
                                                                                style="font-size: 0.7rem;">Nominal
                                                                                Pembayaran
                                                                                ({{ $item['tipe_bayar'] }})</span>
                                                                            <strong class="text-danger">Rp
                                                                                {{ number_format($item['jumlah'], 0, ',', '.') }}</strong>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-12 col-md-6">
                                                                        <div class="ps-md-2 text-muted x-small">
                                                                            <p class="mb-1 fw-bold text-dark">Langkah
                                                                                Pembayaran:
                                                                            </p>
                                                                            <ol class="ps-3 mb-0">
                                                                                <li class="mb-1">Masuk ke m-BCA / ATM
                                                                                    BCA.
                                                                                </li>
                                                                                <li class="mb-1">Pilih
                                                                                    <strong>m-Transfer</strong>
                                                                                    >
                                                                                    <strong>BCA Virtual Account</strong>.
                                                                                </li>
                                                                                <li class="mb-1">Masukkan Nomor VA di
                                                                                    samping.
                                                                                </li>
                                                                                <li>Pastikan nominal tepat dan selesaikan
                                                                                    transaksi.
                                                                                    Sistem otomatis update 5-10 menit.</li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            @if ($item['type'] == 'tuition')
                                                <div class="bill-row border-bottom border-light">
                                                    <div
                                                        class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                                                        <!-- Info Tagihan -->
                                                        <div class="d-flex align-items-start w-100">
                                                            <div class="ms-3 flex-grow-1">
                                                                <div
                                                                    class="d-flex align-items-center flex-wrap gap-2 mb-1">
                                                                    <h6 class="mb-0 fw-bold text-dark me-1">Uang SPP
                                                                    </h6>
                                                                </div>

                                                                <span
                                                                    class="text-muted x-small d-block pe-md-4 mt-1">Sumbangan
                                                                    Pendidikan setiap bulan</span>

                                                                <div class="row">
                                                                    @if ($item['payment_method'] == 'paid')
                                                                        <div
                                                                            style="margin-bottom: 4px; padding-left: 12px;">
                                                                            <span class="badge-modern badge-soft-success"
                                                                                style="border-radius: 20px;">
                                                                                <i class="fa fa-check-circle"></i> Sudah
                                                                                Terbayarkan
                                                                            </span>
                                                                        </div>
                                                                    @else
                                                                        <div
                                                                            style="margin-bottom: 4px; padding-left: 12px;">
                                                                            <span class="badge-modern badge-soft-warning"
                                                                                style="border-radius: 20px;">
                                                                                <i class="fa fa-check-circle"></i> Belum
                                                                                Terbayarkan
                                                                            </span>
                                                                        </div>
                                                                    @endif

                                                                    @if ($item['payment_term'] == 'full_payment')
                                                                        <div
                                                                            style="margin-bottom: 4px; padding-left: 12px;">
                                                                            <span class="badge-modern badge-soft-info"
                                                                                style="border-radius: 20px;">
                                                                                <i class="fa fa-check-circle"></i> Lunas
                                                                            </span>
                                                                        </div>
                                                                    @elseif($item['payment_term'] == 'partial_payment')
                                                                        <div
                                                                            style="margin-bottom: 4px; padding-left: 12px;">
                                                                            <span class="badge-modern badge-soft-secondary"
                                                                                style="border-radius: 20px;">
                                                                                <i class="fa fa-check-circle"></i> Cicilan
                                                                            </span>
                                                                        </div>
                                                                    @endif

                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Nominal & Tombol Aksi -->
                                                        <div
                                                            class="text-md-end w-100 w-md-auto mt-2 mt-md-0 border-top-mobile pt-md-0 pt-2 d-flex flex-row flex-md-column justify-content-between align-items-center align-items-md-end">
                                                            <div class="fw-bold text-dark mb-md-2"
                                                                style="font-size: 1.15rem;">
                                                                Rp {{ number_format($item['amount'], 0, ',', '.') }}
                                                            </div>


                                                            {{-- @if ($item['payment_method'] == 'paid')
                                                                <button class="btn btn-sm btn-light text-muted border px-3"
                                                                    style="font-size: 0.75rem; font-weight: 600;">
                                                                    <i class="fa fa-file-text-o me-1"></i> Bukti Lunas
                                                                </button>
                                                            @else
                                                                <a href="{{ route('ppdb.bills.choise-payment') }}"
                                                                    class="btn btn-sm btn-outline-green px-3"
                                                                    style="font-size: 0.75rem; font-weight: 600;">
                                                                    Cara Bayar <i class="fa fa-chevron-right ms-1"></i>
                                                                </a>
                                                            @endif --}}
                                                        </div>
                                                    </div>

                                                    <!-- Expandable / Accordion Cara Bayar Khusus Item Ini -->
                                                    @if ($item['status'] == 'belum')
                                                        <div class="collapse mt-3" id="caraBayar{{ $item['id'] }}">
                                                            <div class="instruction-panel p-3 p-md-4">
                                                                <div class="row g-3 align-items-center">
                                                                    <div class="col-12 col-md-6 border-end-md">
                                                                        <h6 class="fw-bold small text-dark mb-3"><i
                                                                                class="fa fa-university text-success me-2"></i>
                                                                            Instruksi Transfer BCA</h6>
                                                                        <div class="mb-2">
                                                                            <span class="d-block text-muted"
                                                                                style="font-size: 0.7rem;">Nomor Virtual
                                                                                Account
                                                                                (VA)
                                                                            </span>
                                                                            <div class="d-flex align-items-center gap-2">
                                                                                <strong class="text-dark"
                                                                                    style="font-size: 1.1rem; letter-spacing: 1px;">{{ $item['va'] }}</strong>
                                                                                <button class="copy-btn"><i
                                                                                        class="fa fa-files-o"></i>
                                                                                    Salin</button>
                                                                            </div>
                                                                        </div>
                                                                        <div>
                                                                            <span class="d-block text-muted"
                                                                                style="font-size: 0.7rem;">Nominal
                                                                                Pembayaran
                                                                                ({{ $item['tipe_bayar'] }})</span>
                                                                            <strong class="text-danger">Rp
                                                                                {{ number_format($item['jumlah'], 0, ',', '.') }}</strong>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-12 col-md-6">
                                                                        <div class="ps-md-2 text-muted x-small">
                                                                            <p class="mb-1 fw-bold text-dark">Langkah
                                                                                Pembayaran:
                                                                            </p>
                                                                            <ol class="ps-3 mb-0">
                                                                                <li class="mb-1">Masuk ke m-BCA / ATM
                                                                                    BCA.
                                                                                </li>
                                                                                <li class="mb-1">Pilih
                                                                                    <strong>m-Transfer</strong>
                                                                                    >
                                                                                    <strong>BCA Virtual Account</strong>.
                                                                                </li>
                                                                                <li class="mb-1">Masukkan Nomor VA di
                                                                                    samping.
                                                                                </li>
                                                                                <li>Pastikan nominal tepat dan selesaikan
                                                                                    transaksi.
                                                                                    Sistem otomatis update 5-10 menit.</li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            @php
                                                $total += $item['amount'];
                                            @endphp
                                        @endforeach
                                    </div>

                                    <!-- Summary / Rekapitulasi Pembayaran -->
                                    {{-- <div class="p-3 p-md-4 mt-2">
                                        <div class="summary-box p-4 rounded-4 shadow-sm">
                                            <div class="row align-items-center">
                                                <div class="col-12 col-md-7 mb-3 mb-md-0 border-end-md">
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="text-muted x-small fw-bold text-uppercase">Total
                                                            Seluruh
                                                            Tagihan</span>
                                                        <span class="fw-bold">Rp
                                                            {{ number_format($bill_amount['total_bill'], 0, ',', '.') }}</span>
                                                    </div>
                                                    <div
                                                        class="d-flex justify-content-between pt-2 border-top border-success border-opacity-25">
                                                        <span class="text-danger x-small fw-bold text-uppercase">Sisa Belum
                                                            Dibayar</span>
                                                        <h3 class="fw-bolder mb-0 responsive-total"
                                                            style="color: #1a4d2e;">Rp
                                                            {{ number_format($bill_amount['total_bill'] - $bill_amount['billed_full'], 0, ',', '.') }}
                                                        </h3>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-5 ps-md-4">
                                                    @if ($bill_amount['total_bill'] - $bill_amount['billed_full'] > 0)
                                                        <p class="text-muted x-small mb-2"><i
                                                                class="fa fa-info-circle me-1"></i>
                                                            Anda dapat membayar tagihan satu per satu sesuai Nomor Virtual
                                                            Account
                                                            masing-masing.</p>
                                                    @else
                                                        <div class="text-center text-success py-2">
                                                            <i class="fa fa-check-circle fa-2x mb-2"></i>
                                                            <p class="mb-0 fw-bold">Semua Tagihan Lunas!</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}

                                </div>
                            </div>
                        @else
                            <div class="form-group" style="padding:3em">
                                <div class="alert border-0 shadow-sm" role="alert"
                                    style="background-color: #f8f9fa; border-left: 5px solid #17a2b8 !important; border-radius: 8px; padding: 20px;">
                                    <div class="d-flex align-items-center">
                                        <div style="margin-right: 20px;">
                                            <i class="fa fa-info-circle text-info" style="font-size: 2.5rem;"></i>
                                        </div>
                                        <div>
                                            <h5 class="font-weight-bold text-dark mb-2"
                                                style="font-size: 16px; margin-top: 0;">
                                                Informasi Billing</h5>
                                            <span class="text-muted" style="font-size: 14px;">
                                                Rincian biaya pendidikan saat ini belum tersedia untuk akun
                                                Anda. <br>
                                                Silakan hubungi <strong>Admin</strong> atau <strong>Tata Usaha</strong>
                                                sekolah untuk
                                                mendapatkan informasi lebih lanjut.
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

    </div>

    <div class="modal fade" id="receiptModal" tabindex="-1" role="dialog" aria-labelledby="receiptModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <!-- Konten _registration_receipt.blade.php akan dimuat di sini menggunakan AJAX -->
            </div>
        </div>
    </div>

    <!-- Hidden Form For Development Statement Upload -->
    <form id="form-development" style="display: none;">
        <input type="hidden" name="development_fee_option" id="development_fee_option" value="lunas" />
        <input type="file" name="development_statement" accept="application/pdf" id="development_statement" />
    </form>

    @push('scripts')
        <script src="{{ asset('js/sweet-alert/sweet-alert.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                // Ketika tombol Bukti Lunas diklik
                $('.btn-receipt').on('click', function(e) {
                    e.preventDefault();

                    // Ambil ID dari atribut data-id
                    var id = $(this).data('id');

                    // Siapkan URL untuk request AJAX
                    var url = "{{ route('ppdb.registration-payment-receipt', ':id') }}";
                    url = url.replace(':id', id);

                    // Fetch data via AJAX
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(response) {
                            // Masukkan response HTML dari view ke dalam konten modal
                            $('#receiptModal .modal-content').html(response);

                            // Tampilkan modal
                            $('#receiptModal').modal('show');
                        },
                        error: function(xhr) {
                            alert('Terjadi kesalahan saat mengambil data.');
                        }
                    });
                });

                // Trigger klik untuk unggah surat pernyataan
                $('.upload-statement-trigger').on('click', function(e) {
                    e.preventDefault();
                    var optionType = $(this).data('type') || 'lunas';
                    $('#development_fee_option').val(optionType);
                    $('#development_statement').trigger('click');
                });

                // Proses unggah saat file dipilih
                $('#development_statement').on('change', function() {
                    if ($(this).val()) {
                        var formData = new FormData($('#form-development')[0]);
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                            type: "POST",
                            url: "{{ route('ppdb.upload-development-fee') }}",
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            beforeSend: function() {
                                $('.upload-statement-trigger').html(
                                        '<i class="fa fa-spinner fa-spin me-1"></i> Mengunggah...')
                                    .prop('disabled', true);
                            },
                            error: function(data) {
                                alert('Gagal mengunggah surat pernyataan. Silakan coba lagi.');
                                $('.upload-statement-trigger').html(
                                    '<i class="fa fa-upload me-1"></i> Upload').prop('disabled',
                                    false);
                                $('#development_statement').val('');
                            },
                            success: function(data) {
                                if (typeof swal !== 'undefined') {
                                    swal({
                                        icon: 'success',
                                        title: "Sukses!",
                                        text: 'Upload Dokumen Berhasil!',
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    swal({
                                        icon: 'success',
                                        title: "Sukses!",
                                        text: 'Surat pernyataan berhasil diunggah!',
                                    });
                                    location.reload();
                                }
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
@endsection
