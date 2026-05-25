@extends('layouts.ppdb-online.main')
@section('content')
    @push('styles')
        <style>
            body {
                background-color: #f5f7fa;
                /* Warna latar modern yang lembut */
                font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
                color: #333;
            }

            .payment-container {
                max-width: 500px;
                margin: 0 auto;
            }

            .custom-card {
                border: none;
                border-radius: 12px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
                background-color: #ffffff;
                margin-bottom: 1rem;
            }

            .deadline-box {
                background-color: #23bb44;
                border-radius: 12px;
                padding: 15px;
                display: flex;
                align-items: center;
            }

            .deadline-icon {
                background-color: #ffe0b2;
                color: #f57c00;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.2rem;
                margin-right: 15px;
            }

            .va-number {
                font-size: 1.5rem;
                font-weight: 700;
                letter-spacing: 1.5px;
                color: #212529;
            }

            .total-amount {
                font-size: 1.3rem;
                font-weight: 700;
                color: #0d6efd;
            }

            .btn-copy {
                background: none;
                border: none;
                color: #0d6efd;
                font-weight: 600;
                display: flex;
                align-items: center;
                gap: 5px;
                padding: 0;
                cursor: pointer;
                transition: color 0.2s;
            }

            .btn-copy:hover {
                color: #0a58ca;
            }

            .accordion-button:not(.collapsed) {
                background-color: transparent;
                color: #212529;
                box-shadow: none;
            }

            .accordion-button:focus {
                box-shadow: none;
                border-color: rgba(0, 0, 0, .125);
            }

            .btn-primary-custom {
                background-color: #0d6efd;
                border: none;
                border-radius: 8px;
                padding: 12px;
                font-weight: 600;
                width: 100%;
                transition: background-color 0.3s;
            }

            .btn-primary-custom:hover {
                background-color: #0b5ed7;
            }

            .custom-toast {
                background-color: #ffffff;
                border-radius: 12px;
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
                border-left: 5px solid #198754;
                /* Warna hijau untuk indikator sukses */
            }

            /* --- Responsive --- */
            @media (max-width: 575.98px) {
                .va-number {
                    font-size: 1.25rem;
                    letter-spacing: 1px;
                }

                .total-amount {
                    font-size: 1.15rem;
                }

                .payment-container {
                    padding-left: 1rem !important;
                    padding-right: 1rem !important;
                }
            }
        </style>
    @endpush

    <div class="container py-5 payment-container">

        <!-- Header -->
        <div class="text-center mb-4">
            <h4 class="fw-bold">Informasi Pembayaran</h4>
            <p class="text-muted">Selesaikan pembayaran Anda sebelum batas waktu</p>
        </div>

        <!-- Batas Waktu Pembayaran -->
        @if ($dispensation->status_payment == 'paid')
            <div class="custom-card p-3">
                <div class="deadline-box">
                    <div>
                        <div class="text-white fw-bold" style="font-size: 1rem;">Pembayaran Anda Telah Kami Terima <i
                                class="bi bi-check-circle ms-1"></i></div>
                        {{-- <div class="fw-bold text-danger" id="deadline-time">Sabtu, 16 Mei 2026 - 21:08 WIB</div> --}}
                    </div>
                </div>
            </div>
        @endif

        @if ($dispensation->status_payment == 'unpaid')
            <div class="custom-card p-3">
                <div class="deadline-box" style="background-color: #fff3cd;">
                    <div class="deadline-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <div class="text-dark fw-bold" style="font-size: 1rem;">Segera Lakukan Pembayaran Sebelum</div>
                        <div class="fw-bold text-danger" id="deadline-time">
                            {{ isset($virtual_account_unpaid) ? \Carbon\Carbon::parse($virtual_account_unpaid->expired_at)->translatedFormat('l, d F Y - H:i') : '' }}
                            WIB
                        </div>
                        <div class="fw-bold text-danger mt-1" id="countdown-timer"></div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Detail Virtual Account -->
        <div class="custom-card p-3 p-md-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Bank Tujuan</span>
                <span class="fw-bold">Bank BCA</span>
            </div>

            <hr class="text-muted opacity-25">

            <div class="mb-3">
                <span class="text-muted d-block mb-1">Nomor Virtual Account</span>
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <span class="va-number text-break" id="va-number">{{ $virtual_account_number }}</span>
                    <button class="btn-copy flex-shrink-0"
                        onclick="copyText('va-number', 'Nomor Virtual Account disalin!')">
                        Salin <i class="bi bi-files"></i>
                    </button>
                </div>
            </div>

            <hr class="text-muted opacity-25">

            <div>
                <span class="text-muted d-block mb-1">Total Tagihan</span>
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <span class="total-amount text-break" id="total-amount">Rp
                        {{ number_format($virtual_account_unpaid->total_payment, 0, '.', ',') }}</span>
                    <button class="btn-copy flex-shrink-0" onclick="copyText('total-amount', 'Nominal disalin!')">
                        Salin <i class="bi bi-files"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-4">
            <a href={{ route('ppdb.bills.choise-payment') }}
                class="btn btn-primary btn-block text-white font-weight-bold py-2 shadow-sm"
                style="background-color: #0d6efd; border-radius: 8px; border: none;">
                Saya Sudah Bayar </a>

            <a href="{{ route('ppdb.bills.payment-cancel', ['virtual_account_number' => $virtual_account_number]) }}"
                class="btn btn-outline-danger btn-block mt-3 font-weight-bold py-2 shadow-sm" style="border-radius: 8px;"
                onclick="return confirm('Apakah Anda yakin ingin membatalkan pembayaran ini?');">
                Batalkan Pembayaran </a>

            <a href={{ route('ppdb.finance-bills') }} class="btn btn-block text-secondary mt-3"
                style="background: transparent; border: none; font-weight: 500;">
                Kembali ke Beranda </a>

        </div>

    </div>

    <!-- Toast Alert untuk Notifikasi Copy -->
    {{-- <div class="toast-container position-fixed bottom-0 end-0 p-4" style="z-index: 1055;">
        <div id="copyToast" class="toast align-items-center border-0 custom-toast" role="alert" aria-live="assertive"
            aria-atomic="true">
            <div class="d-flex align-items-center p-2">
                <div class="ms-2 me-3">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 1.5rem;"></i>
                </div>
                <div class="toast-body fw-semibold text-dark p-0" id="toastMessage" style="font-size: 1rem;">
                    Berhasil disalin!
                </div>
                <button type="button" class="btn-close ms-auto me-2" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div> --}}


    @push('scripts')
        <script>
            function copyText(elementId, successMessage) {
                // Mengambil teks dari elemen
                let textToCopy = document.getElementById(elementId).innerText;

                // Membersihkan teks jika itu adalah nominal (menghapus "Rp" dan titik jika diperlukan untuk sistem)
                if (elementId === 'total-amount') {
                    textToCopy = textToCopy.replace(/[^0-9]/g, '');
                }

                // Menyalin ke clipboard
                navigator.clipboard.writeText(textToCopy).then(() => {
                    // Menampilkan Toast notifikasi Bootstrap
                    document.getElementById('toastMessage').innerText = successMessage;
                    const toast = new bootstrap.Toast(document.getElementById('copyToast'));
                    toast.show();
                }).catch(err => {
                    console.error('Gagal menyalin teks: ', err);
                });
            }

            @if (isset($virtual_account_unpaid))
                // Countdown Timer
                const expiredAt = new Date("{{ $virtual_account_unpaid->expired_at }}").getTime();

                const countdownInterval = setInterval(function() {
                    const now = new Date().getTime();
                    const distance = expiredAt - now;

                    if (distance < 0) {
                        clearInterval(countdownInterval);
                        const countdownEl = document.getElementById("countdown-timer");
                        if (countdownEl) {
                            countdownEl.innerHTML = "Waktu pembayaran telah habis";
                        }
                        return;
                    }

                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    let countdownText = "";
                    if (days > 0) {
                        countdownText += days + " Hari ";
                    }

                    document.getElementById("countdown-timer").innerHTML = "Sisa Waktu: " + countdownText +
                        hours.toString().padStart(2, '0') + ":" +
                        minutes.toString().padStart(2, '0') + ":" +
                        seconds.toString().padStart(2, '0');
                }, 1000);
            @endif
        </script>
    @endpush
@endsection
