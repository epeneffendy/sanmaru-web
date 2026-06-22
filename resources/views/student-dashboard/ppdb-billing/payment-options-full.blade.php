@extends('layouts.welcome-page.main')
@section('content')
    @push('styles')
        <style>
            body {
                font-family: 'Inter', sans-serif;
                background-color: #f4f7f6;
                color: #333;
            }

            .payment-container {
                max-width: 600px;
                margin: 40px auto;
            }

            .card-custom {
                border: none;
                border-radius: 16px;
                box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
                background: #fff;
                overflow: hidden;
            }

            .card-header-custom {
                background-color: #fff;
                border-bottom: 1px solid #eaeaea;
                padding: 24px;
                text-align: center;
            }

            .card-body-custom {
                padding: 32px 24px;
            }

            /* Custom Radio Buttons as Cards */
            .payment-option {
                display: none;
            }

            .payment-option-label {
                display: block;
                border: 2px solid #e0e0e0;
                border-radius: 12px;
                padding: 16px;
                cursor: pointer;
                transition: all 0.3s ease;
                position: relative;
                background: #fff;
            }

            .payment-option-label:hover {
                border-color: #b3d4ff;
                background-color: #f8fbff;
            }

            .payment-option:checked+.payment-option-label {
                border-color: #0d6efd;
                background-color: #f0f7ff;
                box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
            }

            .payment-option-label .icon-box {
                font-size: 24px;
                color: #6c757d;
                margin-bottom: 8px;
                transition: color 0.3s ease;
            }

            .payment-option:checked+.payment-option-label .icon-box {
                color: #0d6efd;
            }

            .payment-option-label .title {
                font-weight: 600;
                font-size: 16px;
                margin-bottom: 4px;
                color: #212529;
            }

            .payment-option-label .desc {
                font-size: 13px;
                color: #6c757d;
                margin: 0;
            }

            .check-icon {
                position: absolute;
                top: 16px;
                right: 16px;
                color: #0d6efd;
                opacity: 0;
                transition: opacity 0.3s ease;
                font-size: 18px;
            }

            .payment-option:checked+.payment-option-label .check-icon {
                opacity: 1;
            }

            /* Summary Section */
            .summary-box {
                background-color: #f8f9fa;
                border-radius: 12px;
                padding: 20px;
                margin-top: 24px;
            }

            .summary-item {
                display: flex;
                justify-content: space-between;
                margin-bottom: 12px;
                font-size: 14px;
                color: #555;
            }

            .summary-item:last-child {
                margin-bottom: 0;
            }

            .summary-total {
                display: flex;
                justify-content: space-between;
                margin-top: 16px;
                padding-top: 16px;
                border-top: 2px dashed #dee2e6;
                font-weight: 700;
                font-size: 18px;
                color: #212529;
            }

            .btn-pay {
                padding: 14px 24px;
                font-weight: 600;
                border-radius: 10px;
                width: 100%;
                margin-top: 24px;
                font-size: 16px;
            }

            /* Form Controls */
            .form-select-custom {
                border-radius: 8px;
                border: 1px solid #ced4da;
                padding: 10px 14px;
                font-size: 14px;
            }

            .section-title {
                font-size: 15px;
                font-weight: 600;
                margin-bottom: 12px;
                color: #333;
            }
        </style>
    @endpush

    <div class="container payment-container">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h4 class="m-0 fw-bold">Detail Pembayaran</h4>
                {{-- <p class="text-muted small m-0 mt-1">Selesaikan pembayaran untuk pesanan Anda</p> --}}
            </div>

            <div class="card-body card-body-custom">
                <form action="{{ route('bills.store') }}" method="POST" id="paymentForm">
                    @csrf
                    <div class="mb-4 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center px-1">
                            <div>
                                <span class="text-muted small d-block mb-1">Total yang harus dibayar</span>
                                <h5 class="mb-0 fw-bold" style="color: #0f2b5b;">Rp
                                    {{ number_format($total_bill ?? 0, 0, ',', '.') }}</h5>
                                <input type="hidden" name="total_bill" value="{{ $total_bill ?? 0 }}">
                                <input type="hidden" name="ppdb_user_id" value="{{ $ppdb['id'] }}">
                                <input type="hidden" name="type" value="{{ $type }}">
                            </div>

                        </div>
                    </div>

                    <h5 class="section-title">Pilih Metode Pembayaran</h5>

                    <!-- Payment Options -->
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <input type="radio" name="paymentType" id="bayarLunas" class="payment-option" value="lunas"
                                checked>
                            <label for="bayarLunas" class="payment-option-label h-100">
                                <i class="fa-solid fa-wallet icon-box"></i>
                                <div class="title">Bayar Lunas</div>
                                <p class="desc">Bayar penuh sekarang</p>
                                <i class="fa-solid fa-circle-check check-icon"></i>
                            </label>
                        </div>
                    </div>

                    <!-- Payment Summary -->
                    <div class="summary-box">
                        <h6 class="fw-bold mb-3">Rincian Pembayaran</h6>

                        <div class="summary-item">
                            <span>Total Tagihan</span>
                            <span id="textHargaBarang">Rp {{ number_format($total_bill ?? 0, 0, ',', '.') }}</span>
                        </div>

                        <!-- Only visible when Lunas is selected -->
                        @if (($discount ?? 0) > 0)
                            <div id="lunasSummary" style="display: none;">
                                <div class="alert mt-2 p-3"
                                    style="background-color: #e0f2fe; border: 1px solid #bae6fd; border-radius: 8px;">
                                    <h6 class="fw-bold mb-2" style="color: #075985; font-size: 13px;">
                                        <i class="fa-solid fa-gift me-1"></i> Keuntungan Bayar Lunas 100%:
                                    </h6>
                                    <ul class="mb-0 ps-3" style="color: #075985; font-size: 13px;">
                                        <li>Voucher {{ $discount }}% dari nominal uang pengembangan yang sudah
                                            ditentukan.
                                        </li>
                                        <li>Mendapat voucher free seragam olahraga siswa.</li>
                                    </ul>
                                </div>
                                <div class="summary-item text-success fw-medium mt-3">
                                    <span>Diskon Pelunasan ({{ $discount }}%)</span>
                                    <span id="textDiskonLunas">- Rp 0</span>
                                </div>
                            </div>
                        @endif
                        <input type="hidden" id="nominal_diskon_lunas" name="nominal_diskon_lunas" value="0">

                        <div class="summary-total">
                            <span>Total Bayar Saat Ini</span>
                            <span id="textTotalBayarSekarang" class="text-primary">Rp
                                {{ number_format($total_bill ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <button type="submit" class="btn btn-primary btn-pay shadow-sm" id="btnSubmit">
                        Bayar Sekarang <i class="fa-solid fa-arrow-right ms-2"></i>
                    </button>
                </form>

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {

                // --- 1. Constants & Variables ---
                const TOTAL_BILL = {{ $total_bill ?? 0 }};
                const DISCOUNT_PERCENTAGE = {{ $discount ?? 0 }};

                // --- 2. DOM Elements Caching ---
                const $paymentOptions = $('.payment-option');
                const $lunasSummary = $('#lunasSummary');

                // Summary Elements
                const $textDiskonLunas = $('#textDiskonLunas');
                const $textTotalBayarSekarang = $('#textTotalBayarSekarang');
                const $btnSubmit = $('#btnSubmit');
                const $summaryTotal = $('.summary-total');

                // --- 3. Helper Functions ---
                const formatRupiah = (number) => {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(number);
                };

                // --- 4. Core Logic Functions ---
                const calculatePayment = () => {
                    const paymentType = $('input[name="paymentType"]:checked').val();

                    if (paymentType === 'lunas') {

                        // Tampilkan rincian pelunasan
                        const diskonLunas = TOTAL_BILL * (DISCOUNT_PERCENTAGE / 100);
                        const totalBayarSekarang = TOTAL_BILL - diskonLunas;

                        if ($textDiskonLunas.length) {
                            $textDiskonLunas.text('- ' + formatRupiah(diskonLunas));
                        }
                        $('#nominal_diskon_lunas').val(diskonLunas);
                        if (DISCOUNT_PERCENTAGE > 0) {
                            $lunasSummary.slideDown(300);
                        }

                        $summaryTotal.slideDown(300);

                        // Total yang harus dibayar saat ini adalah total penuh dikurangi diskon
                        $textTotalBayarSekarang.text(formatRupiah(totalBayarSekarang));

                        $btnSubmit.html('Bayar Sekarang <i class="fa-solid fa-arrow-right ms-2"></i>');
                    }
                };

                // --- 5. Event Listeners ---
                const bindEvents = () => {
                    // Picu kalkulasi saat input radio atau dropdown berubah
                    $paymentOptions.on('change', calculatePayment);

                    $btnSubmit.on('click', function(e) {
                        e.preventDefault();

                        // Tambahkan state loading
                        const $btn = $(this);
                        $btn.html('<i class="fa-solid fa-spinner fa-spin"></i> Memproses...');
                        $btn.addClass('disabled').prop('disabled', true);

                        $('#paymentForm').submit();
                    });
                };

                // --- 6. Initialization ---
                const init = () => {
                    bindEvents();
                    calculatePayment(); // Jalankan sekali di awal untuk reset nilai saat halaman diload
                };

                init();
            });
        </script>
    @endpush
@endsection
