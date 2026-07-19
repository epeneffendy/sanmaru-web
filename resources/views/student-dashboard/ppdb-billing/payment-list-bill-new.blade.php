@extends('layouts.welcome-page.main')
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
                    <a href="{{ route('bills.payment-now', ['id' => $dispensation->id, 'type' => 'full_statement']) }}"
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
                    {{-- <a href="{{ route('bills.development-receipt', ['id' => $dispensation->id]) }}"
                        class="btn btn-dark-green text-white"><i class="fa fa-receipt me-2"></i>Lihat Bukti Pembayaran</a> --}}
                </div>
            </div>
        @endif

        @if ($type == 'development')
            <div class="alert mt-2 p-3" style="background-color: #e0f2fe; border: 1px solid #bae6fd; border-radius: 8px;">
                <h6 class="fw-bold mb-2" style="color: #075985; font-size: 13px;">
                    <i class="fa-solid fa-gift me-1"></i> Informasi Penting:
                </h6>
                <ul class="mb-0 ps-3" style="color: #075985; font-size: 13px;">
                    <li>1. Silahkan tentukan rencana bayar pada <b>Cicilan ke-1 dst</b>.</li>
                    <li>2. Tanggal rencana bayar harus beda bulan dari tanggal sebelumnya</li>
                    <li>3. Periode bulan pada rencana bayar harus berurutan dari bulan sebelumnya</li>
                    <li>4. Setelah simpan tanggal cicilan lakukan donwload dan upload <b>Surat Pernyataan</b></li>
                </ul>
            </div>
        @endif

        @if ($dispensation->status_payment != 'paid' && $ppdb['is_upload_development_statement'] == 1)
            <h5 class="font-weight-bold text-secondary mb-3">Alternatif Pembayaran</h5>
            <div class="row mb-5">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="card card-option p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="font-weight-bold mb-0">Full Settlement (Bayar Lunas)</h6>
                            <span class="badge badge-recommendation">Rekomendasi</span>
                        </div>

                        <p class="text-muted mb-3" style="font-size: 0.85rem; line-height: 1.4;">
                            Anda dapat melunasi sisa pembayaran uang pengembangan secara langsung
                        </p>

                        <p class="text-muted mb-1" style="font-size: 0.85rem;">Total Nominal</p>
                        <h5 class="font-weight-bold mb-4">Rp
                            {{ number_format($dispensation['remaining_balance'] ?? 0) }}
                        </h5>

                        @if (!empty($dispensation->ppdb->development_statement))
                            <a href="{{ route('bills.payment-now', ['id' => $dispensation->id, 'type' => 'full_statement', 'dispensation_type' => $type]) }}"
                                class="btn btn-dark-green btn-block py-2 text-white">Bayar
                                Lunas Sekarang</a>
                        @endif
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
                        @if (!empty($dispensation->ppdb->development_statement))
                            <a href="{{ route('bills.payment-now', ['id' => $dispensation->id, 'type' => 'partial', 'dispensation_type' => $type]) }}"
                                id="btn-generate-partial"
                                class="btn btn-outline-dark-green text-success btn-block py-2 mt-auto">Generate Virtual
                                Account</a>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @if ($dispensation->total_final_fee == $dispensation->remaining_balance)
            @if ($dispensation->dispensation_mode == 'real_payment')
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="font-weight-bold text-secondary mb-0">Rencana Cicilan / Setup Pembayaran</h5>
                    <a href="{{ route('bills.change-payment-method', ['id' => $dispensation->id, 'dispensation_type' => $type]) }}"
                        class=" font-weight-bold">
                        Ubah Cara Bayar
                    </a>
                </div>
            @endif
        @endif

        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <div class="table-responsive">
                <form action="{{ route('bills.payment-plan-date') }}" method="POST" id="form-installment-dates">
                    @csrf
                    <input type="hidden" name="dispensation_type" value="{{ $type }}">
                    <div id="alert-dates" class="px-3 pt-3"></div>
                    <table class="table table-custom mb-0">
                        <thead class="bg-light-green">
                            <tr>
                                <th class="py-3">Keterangan</th>
                                <th class="py-3">Total Tagihan</th>
                                <th class="py-3">Terbayar</th>
                                <th class="py-3">Tanggal Bayar</th>
                                <th class="py-3">Rencana Bayar</th>
                                <th class="py-3">Status</th>
                                <th class="py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $isPreviousPaid = true;
                                $hasEmptyDate = false;
                                $installmentIndex = 0;
                                $startDateAngsuran = \App\Helpers\PriceHelper::getDevelopmentStartDateFinance(
                                    $dispensation->ppdb,
                                );
                            @endphp
                            @foreach ($dispensation->details as $index => $detail)
                                <tr>
                                    <td class="font-weight-bold">
                                        {{ $detail->installment_number == 0 ? 'Uang Muka (DP)' : 'Cicilan Ke-' . $detail->installment_number }}
                                    </td>
                                    <td class="font-weight-bold">Rp
                                        {{ number_format($detail['nominal'] ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="text-muted">Rp
                                        {{ number_format($detail['amount_paid'] ?? 0, 0, ',', '.') }}
                                        </br>
                                        @if ($detail['amount_paid'] > 0 && $detail['amount_paid'] != $detail['nominal'])
                                            <span class="text-muted" style="font-size: 0.8rem; font-style: italic">Kurang
                                                Bayar : Rp
                                                {{ number_format($detail['nominal'] - $detail['amount_paid'], 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-muted">
                                        {{ !empty($detail->date) ? \Carbon\Carbon::parse($detail->date)->format('d M Y') : '-' }}
                                    </td>
                                    <td class="text-muted">
                                        @if($detail->installment_number > 0)
                                            @if (empty($detail->plan_date))
                                                @php $hasEmptyDate = true; @endphp
                                                <input type="date" name="dates[{{ $detail->id }}]" class="form-control"
                                                    onchange="handler(this.value, {{ $installmentIndex }}, '{{ $type }}')"
                                                    id="installment_date_{{ $installmentIndex }}"
                                                    @if($type == 'development')
                                                        value="{{ $detail->installment_number == 1 ? \App\Helpers\Helper::tanggalCicilan($startDateAngsuran) : '' }}"
                                                        {{ $detail->installment_number == 1 ? 'readonly' : '' }} 
                                                    @elseif($type == 'activity')
                                                        value="{{ $detail->installment_number == 1 ? \Carbon\Carbon::now()->addMonth()->format('Y-m-d') : '' }}"
                                                        {{ $detail->installment_number == 1 ? 'readonly' : '' }} 
                                                    @endif
                                                    required>
                                            @else
                                                {{ \Carbon\Carbon::parse($detail->plan_date)->format('d M Y') }}
                                                <input type="hidden" id="installment_date_{{ $installmentIndex }}"
                                                    value="{{ \Carbon\Carbon::parse($detail->plan_date)->format('Y-m-d') }}">
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if ($detail->status == 'paid')
                                            <span
                                                class="badge status-badge status-bayar rounded-pill px-3 py-2 w-100">Sudah
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
                                        @if ($detail->status != 'paid' && $isPreviousPaid && !empty($detail->plan_date))
                                            @if ($type == 'development')
                                                @if (!empty($dispensation->ppdb->development_statement))
                                                    <a href="{{ route('bills.payment-now', ['id' => $detail->id, 'type' => 'installment', 'dispensation_type' => $type]) }}"
                                                        class="btn btn-sm btn-dark-green btn-block py-2 text-white">Bayar</a>
                                                @endif
                                            @else
                                                <a href="{{ route('bills.payment-now', ['id' => $detail->id, 'type' => 'installment', 'dispensation_type' => $type]) }}"
                                                    class="btn btn-sm btn-dark-green btn-block py-2 text-white">Bayar</a>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @php
                                    $isPreviousPaid = $detail->status == 'paid';
                                    $installmentIndex++;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                    @if ($hasEmptyDate)
                        <div class="p-3 text-right text-end">
                            <button type="submit" class="btn btn-dark-green text-white px-4 py-2"
                                id="simpan-cicilan">Simpan Tanggal Cicilan</button>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        @if (!$hasEmptyDate)
            @if ($type == 'development')
                <div class="d-flex justify-content-between align-items-center mt-5 mb-3">
                    <h5 class="font-weight-bold text-secondary mb-0">Upload Surat Pernyataan</h5>
                </div>
                <div class="card border-0 shadow-sm p-4" style="border-radius: 12px;">
                    <form id="form-development">
                        <input type="hidden" name="development_fee_option" value="cicilan" />
                        <div class="row">
                            <div class="col-md-12">
                                @if (!empty($dispensation->ppdb->development_statement))
                                    <div class="alert alert-success d-flex align-items-center border-0 shadow-sm mb-3"
                                        role="alert" style="background-color: #ebfcf1; color: #155736;">
                                        <i class="fas fa-check-circle fa-2x mr-3"></i>
                                        <div>
                                            <h6 class="fw-bold mb-1">Dokumen Berhasil Diunggah!</h6>
                                            <p class="mb-0">Surat pernyataan Anda telah berhasil disimpan di sistem.</p>
                                        </div>
                                    </div>
                                    <div>
                                        <a target="_blank" class="btn btn-outline-dark-green font-weight-bold"
                                            href="{{ route('download-development-statement-letter') }}">
                                            <i class="fas fa-file-pdf mr-2"></i>Lihat Surat Pernyataan
                                        </a>
                                    </div>
                                @else
                                    <div id="upload-instruction">
                                        <p class="text-muted">Silahkan download form surat pernyataan terlebih dahulu <a
                                                href="{{ route('download-biaya-pengembangan', ['type' => 'cicilan']) }}"
                                                target="_blank" class="font-weight-bold text-success">disini</a></p>
                                    </div>

                                    <div class="upload-image-desktop mt-4" id="upload-box">
                                        <div class="btn-upload p-4 text-center"
                                            style="border: 2px dashed #a7f3d0; border-radius: 12px; background-color: #f0fdf4;">
                                            <div class="row justify-content-center align-items-center flex-column">
                                                <i class="fas fa-cloud-upload-alt fa-3x mb-3" style="color: #166534;"></i>
                                                <span class="d-block font-weight-bold mb-2" style="color: #166534;">Pilih
                                                    file
                                                    dari perangkat komputer Anda</span>
                                                <span class="text-muted d-block mb-3">Support: PDF</span>
                                                <span class="btn btn-dark-green text-white position-relative">
                                                    Browse
                                                    <input type="file" name="development_statement"
                                                        accept="application/pdf" class="position-absolute w-100 h-100"
                                                        id="development_statement"
                                                        style="left: 0; top: 0; opacity: 0; cursor: pointer;" />
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-column mt-4" id="message_development_statement_container">
                                        <div class="text-danger font-weight-bold" id="message_development_statement">
                                            <i class="fas fa-times-circle me-2"></i>
                                            <span>Belum Terupload</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        @endif

    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/sweet-alert/sweet-alert.min.js') }}"></script>
    <script>
        var isMonthLess =
            '<div class="alert alert-danger mb-3 p-2 rounded border-0" style="background-color: #f8d7da; color: #842029;">Tanggal harus lebih besar dari sebelumnya</div>';
        var isMonthBeforeUndef =
            '<div class="alert alert-danger mb-3 p-2 rounded border-0" style="background-color: #f8d7da; color: #842029;">Tanggal sebelumnya isi terlebih dahulu</div>';
        var isDefferentMonth =
            '<div class="alert alert-danger mb-3 p-2 rounded border-0" style="background-color: #f8d7da; color: #842029;">Tanggal pembayaran harus beda bulan dari tanggal sebelumnya</div>';
        var isNotSequential =
            '<div class="alert alert-danger mb-3 p-2 rounded border-0" style="background-color: #f8d7da; color: #842029;">Periode bulan harus berurutan dari bulan sebelumnya</div>';

        function handler(value, month) {
            var date = new Date(value);

            var today = new Date();
            today.setHours(0, 0, 0, 0);
            if (date.getTime() < today.getTime() && month == 0) {
                document.getElementById("alert-dates").innerHTML =
                    '<div class="alert alert-danger mb-3 p-2 rounded border-0" style="background-color: #f8d7da; color: #842029;">Tanggal pembayaran tidak boleh kurang dari hari ini</div>';
                document.getElementById("installment_date_" + month).value = "";
                return;
            }

            if (month > 0) {
                var prevDateEl = document.getElementById("installment_date_" + (month - 1));
                if (prevDateEl && prevDateEl.value) {
                    var beforeMonth = new Date(prevDateEl.value);

                    // Case 1: Tanggal tidak boleh lebih kecil atau sama dengan tanggal sebelumnya
                    if (date.getTime() <= beforeMonth.getTime()) {
                        document.getElementById("alert-dates").innerHTML = isMonthLess;
                        document.getElementById("installment_date_" + month).value = "";
                        return;
                    }

                    // Case 2: Tanggal yang dipilih tidak boleh sama pada bulan sebelumnya
                    if (date.getMonth() == beforeMonth.getMonth() && date.getFullYear() == beforeMonth.getFullYear()) {
                        document.getElementById("alert-dates").innerHTML = isDefferentMonth;
                        document.getElementById("installment_date_" + month).value = "";
                        return;
                    }

                    // Case 3: Periode bulan harus berurutan (increment 1 bulan dari bulan sebelumnya)
                    var expectedMonth = beforeMonth.getMonth() + 1;
                    var expectedYear = beforeMonth.getFullYear();
                    if (expectedMonth > 11) {
                        expectedMonth = 0;
                        expectedYear++;
                    }
                    if (date.getMonth() !== expectedMonth || date.getFullYear() !== expectedYear) {
                        document.getElementById("alert-dates").innerHTML = isNotSequential;
                        document.getElementById("installment_date_" + month).value = "";
                        return;
                    }
                } else {
                    document.getElementById("alert-dates").innerHTML = isMonthBeforeUndef;
                    document.getElementById("installment_date_" + month).value = "";
                    return;
                }
            }

            document.getElementById("alert-dates").innerHTML = "";
        }

        $(document).on('change', "#development_statement", function() {
            if ($(this).val()) {
                var self = $(this);
                var formData = new FormData($('#form-development')[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    type: "POST",
                    url: "{{ route('upload-development-fee') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#message_development_statement').html(
                            '<span class="text-warning font-weight-bold"><i class="fas fa-spinner fa-spin me-2"></i>Uploading...</span>'
                        );
                    },
                    error: function(data) {
                        $('#message_development_statement').html(
                            '<span class="text-danger font-weight-bold"><i class="fas fa-times-circle me-2"></i>Gagal Upload</span>'
                        );
                    },
                    success: function(data) {
                        $('#upload-instruction').hide();
                        $('#upload-box').hide();
                        var html =
                            '<div class="alert alert-success d-flex align-items-center border-0 shadow-sm w-100 mb-3" role="alert" style="background-color: #ebfcf1; color: #155736;">' +
                            '<i class="fas fa-check-circle fa-2x mr-3"></i>' +
                            '<div>' +
                            '<h6 class="fw-bold mb-1">Dokumen Berhasil Diunggah!</h6>' +
                            '<p class="mb-0">Surat pernyataan Anda telah berhasil disimpan di sistem.</p>' +
                            '</div>' +
                            '</div>' +
                            '<div>' +
                            '<a target="_blank" class="btn btn-outline-dark-green font-weight-bold" href="' +
                            data.preview + '">' +
                            '<i class="fas fa-file-pdf mr-2"></i>Lihat Surat Pernyataan' +
                            '</a>' +
                            '</div>';
                        $('#message_development_statement_container').html(html);
                        swal({
                            icon: 'success',
                            title: "Sukses!",
                            text: 'Upload Dokumen Berhasil!',
                        });
                        setTimeout(function() {
                            location.reload()
                        }, 2000);
                    }
                });
                return false;
            }
        });

        $(document).on('click', '#simpan-cicilan', function(e) {
            e.preventDefault();
            var form = $('#form-installment-dates');
            var inputs = form.find('input[type="date"]');
            var isEmpty = false;

            inputs.each(function() {
                if ($(this).val() === "") {
                    isEmpty = true;
                }
            });

            if (isEmpty) {
                swal({
                    icon: 'warning',
                    title: "Gagal",
                    text: 'Pastikan Angsuran terisi semua!',
                });
            } else {
                swal({
                        title: 'Konfirmasi Pembayaran Cicilan',
                        text: 'Skema pembayaran yang anda pilih adalah cicilan, silahkan konfirmasi tanggal angsuran anda dan unduh surat pernyataan bermaterai, unggah kembali melalui sistem dan tunggu proses validasi dari admin',
                        buttons: [
                            'Tidak',
                            'Ya'
                        ],
                        icon: "warning"
                    })
                    .then((value) => {
                        if (value) {
                            form.submit();
                        }
                    });
            }
        });

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
                    swal({
                        icon: 'error',
                        title: "Informasi!",
                        text: 'Nominal pembayaran belum diisi!',
                    });
                    $('#input-nominal-partial').focus();
                    return;
                }

                if (nominal <= 100000) {
                    swal({
                        icon: 'error',
                        title: "Informasi!",
                        text: 'Nominal pembayaran harus di atas Rp 100.000!',
                    });
                    $('#input-nominal-partial').focus();
                    return;
                }

                if (nominal > maxNominal) {
                    swal({
                        icon: 'error',
                        title: "Informasi!",
                        text: 'Nominal pembayaran tidak boleh melebihi sisa tagihan (Rp ' +
                            maxNominal
                            .toLocaleString('id-ID') + ')!',
                    });
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
