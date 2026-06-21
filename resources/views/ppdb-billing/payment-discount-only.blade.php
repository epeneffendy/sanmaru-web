@extends('layouts.ppdb-online.main')
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
                <form action="{{ route('ppdb.bills.store') }}" method="POST" id="paymentForm">
                    @csrf
                    <!-- Product Summary -->
                    <div class="mb-4 pb-3 border-bottom">
                        <!-- Alert Kotak Keringanan -->
                        <div class="p-3 rounded-3 mb-3 shadow-sm"
                            style="background-color: #f0fdf4; border: 1px solid #bbf7d0;">
                            <div class="d-flex align-items-start">
                                <div class="me-3 mt-1" style="color: #166534;">
                                    <i class="fa-solid fa-tags fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1" style="color: #166534;">Keringanan Biaya Diterapkan</h6>
                                    <span style="color: #15803d; font-size: 13px;">Anda telah mendapatkan dispensasi atau
                                        potongan harga khusus untuk tagihan Uang Pengembangan ini.</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center px-1">
                            <div>
                                <span class="text-muted small d-block mb-1">Total yang harus dibayar</span>
                                <h5 class="mb-0 fw-bold" style="color: #0f2b5b;">Rp
                                    {{ number_format($dispensation['total_final_fee'] ?? 0, 0, ',', '.') }}</h5>
                                <input type="hidden" name="total_bill" value="{{ $dispensation['total_final_fee'] ?? 0 }}">
                                <input type="hidden" name="ppdb_user_id" value="{{ $ppdb['id'] }}">
                                <input type="hidden" name="paymentType" value="cicilan">
                                <input type="hidden" name="type" value="{{ $type }}">
                            </div>
                            @if (isset($dispensation['actual_cost']) && $dispensation['actual_cost'] > $dispensation['total_final_fee'])
                                <div class="text-end">
                                    <span class="text-muted small d-block mb-1">Nominal Uang Pengembangan</span>
                                    <span class="text-muted text-decoration-line-through fw-medium">Rp
                                        {{ number_format($dispensation['actual_cost'], 0, ',', '.') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Installment Settings (Hidden by default) -->
                    <div id="installmentSettings" style="display: block;">
                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <label for="tenorSelect" class="form-label small fw-semibold mb-2" style="color: #0f2b5b;">
                                    Pilih Tenor Cicilan
                                </label>
                                <select class="form-select form-select-custom shadow-sm w-100" id="tenorSelect"
                                    name="tenor">
                                    @if (!empty($installmentOptions))
                                        @foreach ($installmentOptions as $value => $label)
                                            <option value="{{ $value }}">
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="">Konfigurasi Cicilan tidak ditemukan</option>
                                    @endif
                                </select>
                            </div>

                            <div class="col-6">
                                <label for="dpSelect" class="form-label small fw-semibold mb-2" style="color: #0f2b5b;">
                                    Pilih Down Payment (DP)
                                </label>
                                <select class="form-select form-select-custom shadow-sm w-100" id="dpSelect"
                                    name="dp">
                                    @if (!empty($dpOptions))
                                        @foreach ($dpOptions as $value => $label)
                                            <option value="{{ $value }}"
                                                {{ $value == $configuration->recommended_down_payment ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="">Konfigurasi DP tidak ditemukan</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Summary -->
                    <div class="summary-box">
                        <h6 class="fw-bold mb-3">Rincian Pembayaran</h6>

                        <div class="summary-item">
                            <span>Total Tagihan</span>
                            <span id="textHargaBarang">Rp {{ number_format($total_bill ?? 0, 0, ',', '.') }}</span>
                        </div>

                        <!-- Only visible when Cicilan is selected -->
                        <div id="installmentSummary" style="display: none;">
                            <div class="summary-item text-primary fw-medium">
                                <span>Down Payment (DP)</span>
                                <span id="textNominalDP">Rp 0</span>
                                <input type="hidden" id="nominal_dp" name="nominal_dp" value="0">
                            </div>
                            <div class="summary-item">
                                <span>Sisa Pokok Hutang</span>
                                <span id="textSisaHutang">Rp 0</span>
                                <input type="hidden" id="sisa_hutang" name="sisa_hutang" value="0">
                            </div>
                            <div class="summary-item mt-2 pt-2 border-top">
                                <span>Cicilan Per Bulan (<span id="textTenorLabel">3x</span>)</span>
                                <span id="textCicilanPerBulan" class="fw-bold text-danger">Rp 0 / bln</span>
                                <input type="hidden" id="cicilan_per_bulan" name="cicilan_per_bulan" value="0">
                            </div>
                        </div>

                    </div>

                    <!-- CTA Button -->
                    <button class="btn btn-primary btn-pay shadow-sm" id="btnSubmit">
                        Setujui & Kunci Skema <i class="fa-solid fa-arrow-right ms-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // 1. Data Inisial
                // Mengambil nilai total biaya dari backend PHP
                const totalBiaya = {{ $dispensation['total_final_fee'] ?? 0 }};

                // 2. Cache Elemen DOM (Mencegah pemanggilan jQuery berulang yang memakan memori)
                const $tenorSelect = $('#tenorSelect');
                const $dpSelect = $('#dpSelect');

                const $textHargaBarang = $('#textHargaBarang');
                const $textNominalDP = $('#textNominalDP');
                const $textSisaHutang = $('#textSisaHutang');
                const $textTenorLabel = $('#textTenorLabel');
                const $textCicilanPerBulan = $('#textCicilanPerBulan');
                const $textTotalBayarSekarang = $('#textTotalBayarSekarang');

                const $installmentSummary = $('#installmentSummary');

                // 3. Helper: Format Angka ke Rupiah
                const formatRupiah = (angka) => {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(angka);
                };

                // 4. Fungsi Kalkulasi Utama
                const hitungCicilan = () => {
                    const nilaiDP = parseFloat($dpSelect.val()) || 0;
                    const tenor = parseInt($tenorSelect.val()) || 1;

                    // Cek jenis DP: jika value <= 100, itu adalah persentase (misal 30%), jika lebih besar itu berupa nominal mutlak (misal 1000000)
                    const nominalDP = nilaiDP <= 100 ? (totalBiaya * (nilaiDP / 100)) : nilaiDP;

                    const sisaHutang = Math.max(0, totalBiaya - nominalDP);
                    const cicilanPerBulan = tenor > 0 ? (sisaHutang / tenor) : 0;

                    // Tampilkan ringkasan tagihan pokok barang
                    $textHargaBarang.text(formatRupiah(totalBiaya));

                    if (tenor > 1 || nominalDP > 0) {
                        $installmentSummary.slideDown(200); // Animasi smooth jika menggunakan cicilan
                        $textNominalDP.text(formatRupiah(nominalDP));
                        $textSisaHutang.text(formatRupiah(sisaHutang));
                        $textTenorLabel.text(tenor + 'x');
                        $textCicilanPerBulan.text(formatRupiah(cicilanPerBulan) + ' / bln');
                        $textTotalBayarSekarang.text(formatRupiah(nominalDP));

                        $('#nominal_dp').val(nominalDP);
                        $('#sisa_hutang').val(sisaHutang);
                        $('#cicilan_per_bulan').val(cicilanPerBulan);
                    } else {
                        $installmentSummary.slideUp(200);
                        $textTotalBayarSekarang.text(formatRupiah(totalBiaya));
                    }
                };

                // 5. Daftarkan Event Listener agar kalkulasi berjalan jika ada perubahan drop-down
                $tenorSelect.add($dpSelect).on('change', hitungCicilan);

                // 6. Jalankan kalkulasi pertama kali untuk inisialisasi state UI
                hitungCicilan();
            });
        </script>
    @endpush
@endsection
