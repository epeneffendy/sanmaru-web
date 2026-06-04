@extends('layouts.ppdb-online.main')
@section('content')
    @push('styles')
        <style>
            /* Typography & Body */
            body {
                background-color: #f8f9fa;
                font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
                color: #333;
            }

            /* Colors */
            .text-dark-green {
                color: #155736;
            }

            .bg-dark-green {
                background-color: #1a5632;
            }

            .bg-light-green {
                background-color: #ebfcf1;
            }

            /* Custom Alert */
            .alert-custom {
                background-color: #ebfcf1;
                border: 1px solid #c3e6cb;
                color: #155736;
                border-radius: 8px;
                font-weight: 600;
            }

            /* Total Card */
            .total-card {
                border-radius: 12px;
                position: relative;
                overflow: hidden;
                border: none;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            }

            .total-card-watermark {
                position: absolute;
                right: -20px;
                top: -20px;
                font-size: 8rem;
                color: rgba(255, 255, 255, 0.1);
                transform: rotate(-15deg);
            }

            /* Options Cards */
            .card-option {
                border-radius: 12px;
                border: 1px solid #eaeaea;
                height: 100%;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            }

            /* Buttons */
            .btn-dark-green {
                background-color: #1a5632;
                color: #fff;
                border: none;
                border-radius: 6px;
                font-weight: 600;
                transition: all 0.3s;
            }

            .btn-dark-green:hover {
                background-color: #124024;
                color: #fff;
            }

            .btn-outline-dark-green {
                background-color: transparent;
                color: #1a5632;
                border: 1px solid #1a5632;
                border-radius: 6px;
                font-weight: 600;
                transition: all 0.3s;
            }

            .btn-outline-dark-green:hover {
                background-color: #ebfcf1;
                color: #1a5632;
            }

            /* Badges */
            .badge-recommendation {
                background-color: #e2e8f0;
                color: #475569;
                font-weight: normal;
                padding: 4px 10px;
                border-radius: 20px;
            }

            /* Table Customization */
            .table-custom th {
                border-top: none;
                border-bottom: none;
                color: #155736;
                font-weight: 600;
                font-size: 0.9rem;
            }

            .table-custom td {
                vertical-align: middle;
                font-size: 0.9rem;
                border-top: 1px solid #f1f1f1;
            }

            .status-unpaid {
                color: #d97706;
                font-weight: 600;
                font-size: 0.85rem;
            }

            .btn-pay-sm {
                background-color: #a7f3d0;
                color: #065f46;
                border: none;
                border-radius: 4px;
                padding: 4px 16px;
                font-weight: 600;
                font-size: 0.85rem;
            }

            .btn-pay-sm:hover {
                background-color: #6ee7b7;
            }

            .va-text {
                font-size: 1.3rem;
                font-weight: 700;
                letter-spacing: 1px;
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

            /* Deadline Box */
            .deadline-box {
                background-color: #fff3cd;
                border: 1px solid #ffe69c;
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
        </style>
    @endpush

    <div class="container py-5">

        @if ($dispensation->dispensation_mode != 'real_payment')
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
        @endif

        @if (isset($virtual_account_unpaid))
            <div class="deadline-box mb-4 shadow-sm">
                <div class="deadline-icon flex-shrink-0">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="text-dark fw-bold" style="font-size: 1rem;">Ada tagihan yang sedang menunggu pembayaran!
                        Segera Lakukan Pembayaran Sebelum</div>
                    <div class="fw-bold text-danger" id="deadline-time">
                        {{ \Carbon\Carbon::parse($virtual_account_unpaid->expired_at)->translatedFormat('l, d F Y - H:i') }}
                        WIB
                    </div>
                    <div class="fw-bold text-danger mt-1" id="countdown-timer"></div>
                </div>
                <div class="ml-auto pl-3 flex-shrink-0">
                    <a href="{{ route('ppdb.bills.payment-now', ['id' => $dispensation->id, 'type' => 'full_statement']) }}"
                        class="btn btn-warning font-weight-bold text-dark shadow-sm">Cek Pembayaran</a>
                </div>
            </div>
        @endif

        @if ($dispensation->remaining_balance > 0)
            <div class="card bg-dark-green text-white total-card p-4 mb-5">
                <i class="fas fa-wallet total-card-watermark"></i>
                <div class="position-relative d-flex flex-column flex-md-row justify-content-between align-items-md-center"
                    style="z-index: 1;">
                    <div>
                        <p class="mb-1" style="font-size: 0.85rem; font-weight: 600; letter-spacing: 0.5px; color:white">
                            TOTAL
                            YANG HARUS DIBAYAR</p>
                        <h2 class="mb-0 font-weight-bold" style="color:white">Rp
                            {{ number_format($dispensation['total_final_fee'] ?? 0, 0, ',', '.') }}</h2>
                    </div>

                    @if (isset($dispensation['actual_cost']) && $dispensation['actual_cost'] > ($dispensation['total_final_fee'] ?? 0))
                        <div class="mt-3 mt-md-0 text-start text-md-end">
                            <p class="mb-1" style="font-size: 0.70rem; color: white;">Nominal Uang Pengembangan</p>
                            <h4 class="mb-0 text-decoration-line-through"
                                style="color: rgba(255,255,255,0.7);font-size: 1rem">
                                Rp {{ number_format($dispensation['actual_cost'], 0, ',', '.') }}
                            </h4>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="p-4 rounded-3 mb-5 shadow-sm text-center"
                style="background-color: #ebfcf1; border: 1px solid #bbf7d0;">
                <i class="fas fa-check-circle mb-3" style="font-size: 3rem; color: #166534;"></i>
                <h4 class="fw-bold mb-1" style="color: #166534;">Pembayaran Anda Telah Lunas</h4>
                <p class="mb-0" style="color: #15803d;">Terima kasih, seluruh tagihan Uang Pengembangan Anda sudah
                    diselesaikan.</p>
                <div class="mt-4">
                    <a href="{{ route('ppdb.bills.development-receipt', ['id' => $dispensation->id]) }}"
                        class="btn btn-dark-green text-white"><i class="fa fa-receipt me-2"></i>Lihat Bukti Pembayaran</a>
                </div>
            </div>
        @endif

        {{-- @if ($dispensation->dispensation_mode > 0) --}}
        <h5 class="font-weight-bold text-secondary mb-3">Alternatif Pembayaran</h5>
        <div class="row mb-5">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="card card-option p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="font-weight-bold mb-0">Full Settlement (Bayar Lunas)</h6>
                        <span class="badge badge-recommendation">Rekomendasi</span>
                    </div>

                    <p class="text-muted mb-1" style="font-size: 0.85rem;">Nomor Virtual Account</p>
                    <div class="d-flex align-items-center mb-3">
                        <span class="va-text va-number mr-2 text-dark">{{ $va_full ?? 'Nomor VA Lunas' }}</span>
                        {{-- <i class="copy-icon text-muted" style="cursor: pointer;" title="Salin">Salin</i> --}}
                    </div>

                    <p class="text-muted mb-1" style="font-size: 0.85rem;">Total Nominal</p>
                    <h5 class="font-weight-bold mb-4">Rp
                        {{ number_format($dispensation['remaining_balance'] ?? 0, 0, ',', '.') }}</h5>

                    <a href="{{ route('ppdb.bills.payment-now', ['id' => $dispensation->id, 'type' => 'full_statement']) }}"
                        class="btn btn-dark-green btn-block py-2 text-white">Bayar
                        Lunas Sekarang</a>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-option p-4">
                    <h6 class="font-weight-bold mb-2">Partial Settlement (Bayar Sebagian)</h6>
                    <p class="text-muted mb-3" style="font-size: 0.85rem; line-height: 1.4;">
                        Jika anda akan melakukan pembayaran dengan nominal bebas untuk mengurangi beban cicilan
                    </p>

                    <div class="form-group mb-4">
                        <label class="text-muted" style="font-size: 0.85rem;">Nominal yang akan dibayarkan</label>
                        <input type="text" id="input-nominal-partial" class="form-control"
                            placeholder="Contoh: 1.000.000" min="100000">
                        <input type="hidden" id="input-nominal-remaining-balance" class="form-control"
                            value="{{ $dispensation['remaining_balance'] ?? 0 }}">
                    </div>

                    <a href="{{ route('ppdb.bills.payment-now', ['id' => $dispensation->id, 'type' => 'partial']) }}"
                        id="btn-generate-partial"
                        class="btn btn-outline-dark-green text-success btn-block py-2 mt-auto">Generate Virtual
                        Account</a>
                </div>
            </div>
        </div>
        {{-- @endif --}}

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="font-weight-bold text-secondary mb-0">Rencana Cicilan / Setup Pembayaran</h5>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <div class="table-responsive">
                <table class="table table-custom mb-0">
                    <thead class="bg-light-green">
                        <tr>
                            <th class="py-3">Keterangan</th>
                            <th class="py-3">Total Tagihan</th>
                            <th class="py-3">Terbayar</th>
                            <th class="py-3">Tanggal Bayar</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $isPreviousPaid = true; @endphp
                        @foreach ($dispensation->details as $detail)
                            <tr>
                                <td class="font-weight-bold">
                                    {{ $detail->installment_number == 0 ? 'Uang Muka (DP)' : 'Cicilan Ke-' . $detail->installment_number }}
                                </td>
                                <td class="font-weight-bold">Rp {{ number_format($detail['nominal'] ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="text-muted">Rp {{ number_format($detail['amount_paid'] ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="text-muted">
                                    {{ !empty($detail->date) ? \Carbon\Carbon::parse($detail->date)->format('d M Y') : '-' }}
                                </td>
                                <td>
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
                                </td>
                                <td>
                                    @if ($detail->status != 'paid' && $isPreviousPaid)
                                        <a href="{{ route('ppdb.bills.payment-now', ['id' => $detail->id, 'type' => 'installment']) }}"
                                            class="btn btn-sm btn-dark-green btn-block py-2 text-white">Bayar</a>
                                    @endif
                                </td>
                            </tr>
                            @php $isPreviousPaid = ($detail->status == 'paid'); @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
@push('scripts')
    <script>
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
                $icon.attr('class', 'fas fa-check text-success copy-icon');
                $icon.attr('title', 'Tersalin!');

                // Mengembalikan ikon ke bentuk semula setelah 2 detik
                setTimeout(function() {
                    $icon.attr('class', originalClass);
                    $icon.attr('title', 'Salin');
                }, 2000);
            });

            // Format Number Input
            $('#input-nominal-partial').on('input', function() {
                let value = $(this).val().replace(/\D/g, "");
                if (value !== "") {
                    $(this).val(parseInt(value, 10).toLocaleString('id-ID'));
                } else {
                    $(this).val("");
                }
            });

            // Validasi Input Nominal (Bayar Sebagian)
            $('#btn-generate-partial').on('click', function(e) {
                e.preventDefault(); // Mencegah pindah halaman langsung

                // Hapus format ribuan sebelum divalidasi dan dikirim ke parameter URL
                const nominalStr = $('#input-nominal-partial').val().replace(/\D/g, "");
                const nominal = parseInt(nominalStr, 10);
                const maxNominal = parseInt($('#input-nominal-remaining-balance').val(), 10);

                if (!nominalStr || nominalStr.trim() === '') {
                    alert('Nominal pembayaran belum diisi!');
                    $('#input-nominal-partial').focus();
                    return;
                }

                if (nominal <= 100000) {
                    alert('Nominal pembayaran harus di atas Rp 100.000!');
                    $('#input-nominal-partial').focus();
                    return;
                }

                if (nominal > maxNominal) {
                    alert('Nominal pembayaran tidak boleh melebihi sisa tagihan (Rp ' + maxNominal
                        .toLocaleString('id-ID') + ')!');
                    $('#input-nominal-partial').focus();
                    return;
                }

                // Lanjut ke halaman pembayaran dengan mengirimkan nominal yang diinputkan
                const url = new URL($(this).attr('href'), window.location.origin);
                url.searchParams.set('nominal', nominal);
                window.location.href = url.toString();
            });
        });
    </script>
@endpush
