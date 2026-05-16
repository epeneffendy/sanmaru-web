@extends('layouts.ppdb-online.main')
@section('content')
    @push('styles')
        <style>
            .page-header {
                color: #1e293b;
                /* Navy gelap profesional */
            }

            .main-card {
                border: none;
                border-radius: 16px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
                overflow: hidden;
            }

            .auto-allocation-pill {
                background-color: transparent;
                color: #64748b;
                border: 1px solid #e2e8f0;
                font-weight: 500;
                font-size: 0.85rem;
            }

            /* Kustomisasi Payment Item */
            .payment-item {
                border-bottom: 1px solid #f1f5f9;
                transition: background-color 0.2s ease;
            }

            .payment-item:last-child {
                border-bottom: none;
            }

            .payment-item:hover {
                background-color: #f8fafc;
            }

            /* Ikon Bagian Kiri */
            .payment-icon-container {
                width: 48px;
                height: 48px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }

            .icon-dp {
                background-color: #e0e7ff;
                /* Soft Indigo */
                color: #4f46e5;
            }

            .icon-cicilan {
                background-color: #f1f5f9;
                /* Soft Grey */
                color: #64748b;
            }

            /* Teks Identitas Payment */
            .payment-title {
                color: #1e293b;
                font-weight: 700;
            }

            .va-label {
                color: #64748b;
                font-size: 0.85rem;
            }

            .va-number {
                font-family: 'Roboto Mono', monospace;
                /* Font monospace untuk angka */
                color: #1e293b;
                font-weight: 600;
                letter-spacing: 0.5px;
            }

            .copy-icon {
                cursor: pointer;
                color: #94a3b8;
                transition: color 0.2s;
            }

            .copy-icon:hover {
                color: #4f46e5;
            }

            /* Pemisah Informasi Keuangan */
            .financial-divider {
                width: 1px;
                height: 40px;
                background-color: #e2e8f0;
                margin: 0 24px;
            }

            /* Teks Keuangan */
            .finance-label {
                color: #64748b;
                font-size: 0.8rem;
                text-transform: uppercase;
                letter-spacing: 1px;
                margin-bottom: 4px;
            }

            .finance-value {
                color: #1e293b;
                font-weight: 600;
                font-size: 1.1rem;
            }

            .finance-paid {
                color: #94a3b8;
                font-size: 1rem;
            }

            /* Badge Status Refined */
            .status-badge {
                font-size: 0.85rem;
                font-weight: 500;
                padding: 8px 16px;
            }

            .status-sebagian {
                background-color: #fef9c3;
                /* Soft Yellow */
                color: #854d0e;
                border: 1px solid #fde047;
            }

            .status-bayar {
                background-color: #0e722c;
                /* Soft Yellow */
                color: #ffffff;
                border: 1px solid #0e722c;
            }

            .status-belum-dibayar {
                background-color: #f1f5f9;
                /* Soft Grey */
                color: #475569;
                border: 1px solid #e2e8f0;
            }

            /* Kustomisasi Scroll View List Pembayaran */
            .payment-list-container {
                max-height: 500px;
                overflow-y: auto;
            }

            .payment-list-container::-webkit-scrollbar {
                width: 8px;
            }

            .payment-list-container::-webkit-scrollbar-track {
                background: #f1f5f9;
            }

            .payment-list-container::-webkit-scrollbar-thumb {
                background-color: #cbd5e1;
                border-radius: 10px;
            }
        </style>
    @endpush

    <div class="container-fluid max-w-100">
        <div class="card main-card overflow-hidden">
            <div class="card-header bg-white pt-4 pb-3 px-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2"
                style="border-bottom: 1px solid #f1f5f9;">
                <h5 class="mb-0 fw-semibold text-dark">Rincian Tagihan dan Status</h5>
            </div>

            <div class="card-body p-4 border-bottom">
                <!-- Alert Kotak Keringanan -->
                <div class="p-3 rounded-3 mb-4 shadow-sm" style="background-color: #f0fdf4; border: 1px solid #bbf7d0;">
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

                <div class="p-3 p-md-4 rounded-4 shadow-sm" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                    <div
                        class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">

                        <div>
                            <span class="d-block small fw-semibold text-uppercase mb-1"
                                style="color: #64748b; letter-spacing: 0.5px;">
                                Total Yang Harus Dibayar
                            </span>
                            <h3 class="mb-0 fw-bold" style="color: #0f2b5b;">
                                Rp {{ number_format($dispensation['total_final_fee'] ?? 0, 0, ',', '.') }}
                            </h3>
                        </div>

                        @if (isset($dispensation['actual_cost']) && $dispensation['actual_cost'] > ($dispensation['total_final_fee'] ?? 0))
                            <div class="text-start text-sm-end">
                                <div class="text-muted" style="font-size: 0.85rem;">
                                    Nominal Uang Pengembangan:
                                    <span class="text-danger text-decoration-line-through fw-medium ms-1">
                                        Rp {{ number_format($dispensation['actual_cost'], 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                <!-- Opsi Pembayaran Lunas & Sebagian -->
                <div class="mt-4 pt-2">
                    <h6 class="fw-bold mb-3" style="color: #334155;">Alternatif Pembayaran Bebas</h6>
                    <div class="row g-3">
                        <!-- Card Full Settlement -->
                        <div class="col-md-6">
                            <div class="p-3 rounded-4 shadow-sm h-100 d-flex flex-column"
                                style="background-color: #ffffff; border: 1px solid #e2e8f0; position: relative; overflow: hidden;">
                                <div
                                    style="position: absolute; top: 0; left: 0; width: 4px; height: 100%; background-color: #059669;">
                                </div>
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark">Full Settlement (Bayar Lunas)</h6>
                                        <small class="text-muted">Jika anda akan melakukan pelunasan seluruh total
                                            tagihan</small>
                                    </div>
                                </div>
                                <div class="mt-auto">
                                    <div class="va-label">Nomor Virtual Account</div>
                                    <div class="d-flex align-items-center mt-1 mb-2">
                                        <span class="va-number fs-5">{{ $dispensation->va_full ?? 'Nomor VA Lunas' }}</span>
                                        <i class="fa fa-clipboard copy-icon ms-2" title="Salin Nomor VA"
                                            style="font-size: 1.1rem;"></i>
                                    </div>
                                    <div class="finance-label">Nominal</div>
                                    <div class="finance-value text-success">Rp
                                        {{ number_format($dispensation['remaining_balance'] ?? 0, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Partial Settlement -->
                        <div class="col-md-6">
                            <div class="p-3 rounded-4 shadow-sm h-100 d-flex flex-column"
                                style="background-color: #ffffff; border: 1px solid #e2e8f0; position: relative; overflow: hidden;">
                                <div
                                    style="position: absolute; top: 0; left: 0; width: 4px; height: 100%; background-color: #f59e0b;">
                                </div>
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark">Partial Settlement (Bayar Sebagian)</h6>
                                        <small class="text-muted">Jika anda akan melakukan pembayaran dengan nominal bebas
                                            untuk mengurangi beban cicilan</small>
                                    </div>
                                </div>
                                <div class="mt-auto">
                                    <div class="va-label">Nomor Virtual Account</div>
                                    <div class="d-flex align-items-center mt-1 mb-2">
                                        <span
                                            class="va-number fs-5">{{ $dispensation->va_partial ?? 'Nomor VA Sebagian' }}</span>
                                        <i class="fa fa-clipboard copy-icon ms-2" title="Salin Nomor VA"
                                            style="font-size: 1.1rem;"></i>
                                    </div>
                                    <div class="finance-label">Nominal</div>
                                    <div class="finance-value" style="color: #d97706;">Bebas (Sesuai Kesepakatan)</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Header Pembatas List Setup/Cicilan -->
            <div class="bg-light px-4 py-3 border-bottom">
                <h6 class="mb-0 fw-semibold text-dark">Rencana Cicilan / Setup Pembayaran</h6>
            </div>

            <div class="list-group list-group-flush payment-list-container">

                @foreach ($dispensation->details as $detail)
                    <div class="list-group-item payment-item p-4">
                        <div class="d-flex flex-column flex-lg-row align-items-lg-center">

                            <div class="d-flex align-items-center flex-grow-1 mb-3 mb-lg-0">
                                <div>
                                    <h6 class="payment-title mb-1 fs-5">
                                        {{ $detail->installment_number == 0 ? 'Uang Muka (DP)' : 'Cicilan Ke-' . $detail->installment_number }}
                                    </h6>
                                    <div class="va-label">Nomor Virtual Account</div>
                                    <div class="d-flex align-items-center mt-1">
                                        <span class="va-number"> {{ $detail->virtual_account }}</span>
                                        <i class="fa fa-clipboard copy-icon ms-2" title="Salin Nomor VA"
                                            style="font-size: 1.1rem;"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="financial-divider d-none d-lg-block"></div>

                            <div
                                class="d-flex flex-column flex-sm-row gap-3 gap-sm-5 align-items-sm-center justify-content-lg-end flex-wrap">
                                <div class="text-start text-sm-end" style="min-width: 140px;">
                                    <div class="finance-label">Total Tagihan</div>
                                    <div class="finance-value"> Rp
                                        {{ number_format($detail['nominal'] ?? 0, 0, ',', '.') }}</div>
                                </div>
                                <div class="text-start text-sm-end" style="min-width: 140px;">
                                    <div class="finance-label">Terbayar</div>
                                    <div class="finance-paid">Rp
                                        {{ number_format($detail['amount_paid'] ?? 0, 0, ',', '.') }}
                                    </div>
                                </div>
                                <div class="text-start text-sm-end" style="min-width: 130px;">
                                    <div class="finance-label">Tanggal Bayar</div>
                                    <div class="finance-paid">
                                        {{ !empty($detail->date) ? \Carbon\Carbon::parse($detail->date)->format('d M Y') : '-' }}
                                    </div>
                                </div>
                                <div class="mt-2 mt-sm-0 text-start" style="min-width: 160px;">
                                    @if ($detail->status == 'paid')
                                        <span class="badge status-badge status-bayar rounded-pill px-3 py-2 w-100">Sudah
                                            Dibayar</span>
                                    @elseif ($detail->status == 'partial')
                                        <span
                                            class="badge status-badge status-sebagian rounded-pill px-3 py-2 w-100">Pembayaran
                                            Sebagian</span>
                                    @else
                                        <span
                                            class="badge status-badge status-belum-dibayar rounded-pill px-3 py-2 w-100">Belum
                                            Dibayar</span>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Fitur Copy Virtual Account
                $('.copy-icon').on('click', function() {
                    const $icon = $(this);
                    const vaNumber = $icon.siblings('.va-number').text().trim();

                    // Membuat elemen textarea sementara untuk menyalin teks (Kompatibilitas Lintas Browser)
                    const $temp = $("<textarea>");
                    $("body").append($temp);
                    $temp.val(vaNumber).select();
                    document.execCommand("copy");
                    $temp.remove();

                    // Mengubah ikon menjadi centang hijau sebagai feedback visual
                    const originalClass = $icon.attr('class');
                    $icon.removeClass('fa-clipboard copy-icon').addClass('fa fa-check text-success');
                    $icon.attr('title', 'Tersalin!');

                    // Mengembalikan ikon ke bentuk semula setelah 2 detik
                    setTimeout(function() {
                        $icon.attr('class', originalClass);
                        $icon.attr('title', 'Salin Nomor VA');
                    }, 2000);
                });
            });
        </script>
    @endpush
@endsection
