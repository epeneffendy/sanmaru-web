<div class="bill-row border-bottom border-light">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <!-- Info Tagihan -->
        <div class="d-flex align-items-start w-100">
            <div class="ms-3 flex-grow-1">
                <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                    <h6 class="mb-0 fw-bold text-dark me-1">Uang
                        Pengembangan
                    </h6>
                </div>

                <span class="text-muted x-small d-block pe-md-4 mt-1">Pembangunan
                    sarana, prasarana, dan fasilitas gedung</span>
                @if ($item['payment_method'] == 'closed')
                    <div class="row">
                        <div style="margin-bottom: 4px; padding-left: 12px;">
                            <span class="badge-modern badge-soft-danger" style="border-radius: 20px;">
                                <i class="fa fa-times-circle"></i> Tagihan
                                Dihentikan
                            </span>
                        </div>
                    </div>
                @else
                    <div class="row">
                        @if ($item['payment_method'] == 'paid')
                            <div style="margin-bottom: 4px; padding-left: 12px;">
                                <span class="badge-modern badge-soft-success" style="border-radius: 20px;">
                                    <i class="fa fa-check-circle"></i>
                                    Sudah
                                    Terbayarkan
                                </span>
                            </div>
                        @else
                            <div style="margin-bottom: 4px; padding-left: 12px;">
                                <span class="badge-modern badge-soft-warning" style="border-radius: 20px;">
                                    <i class="fa fa-check-circle"></i>
                                    Belum
                                    Terbayarkan
                                </span>
                            </div>
                        @endif

                        @if ($item['payment_term'] == 'full_payment')
                            <div style="margin-bottom: 4px; padding-left: 12px;">
                                <span class="badge-modern badge-soft-info" style="border-radius: 20px;">
                                    <i class="fa fa-check-circle"></i>
                                    Lunas
                                </span>
                            </div>
                        @endif
                        @if ($item['payment_term'] == 'installment_payment')
                            <div style="margin-bottom: 4px; padding-left: 12px;">
                                <span class="badge-modern badge-soft-secondary" style="border-radius: 20px;">
                                    <i class="fa fa-check-circle"></i>
                                    Cicilan
                                </span>
                            </div>
                        @endif

                        @if ($is_dispensation)
                            <div style="margin-bottom: 4px; padding-left: 12px;">
                                <span class="badge-modern badge-soft-success" style="border-radius: 20px;">
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
            @if (isset($arr_dispensation['development']))
                @if ($arr_dispensation['development']['is_dispensation'] == true)
                    <div class="fw-bold text-dark mb-md-2" style="font-size: 1.15rem;">
                        Rp
                        {{ number_format($arr_dispensation['development']['total_final_fee'], 0, ',', '.') }}
                    </div>
                    <div class="fw-bold text-muted mb-md-2" style="font-size: 0.95rem; text-decoration: line-through;">
                        Rp {{ number_format($item['amount'], 0, ',', '.') }}
                    </div>
                @else
                    <div class="fw-bold text-dark mb-md-2" style="font-size: 1.15rem;">
                        Rp {{ number_format($item['amount'], 0, ',', '.') }}
                    </div>
                @endif
            @else
                <div class="fw-bold text-dark mb-md-2" style="font-size: 1.15rem;">
                    Rp {{ number_format($item['amount'], 0, ',', '.') }}
                </div>
            @endif

            @if ($item['payment_method'] == 'paid')
                <a href={{ route('ppdb.bills.payment-paid-receipt', ['id' => $item['id']]) }}
                    class="btn btn-sm btn-light text-muted border px-3" style="font-size: 0.75rem; font-weight: 600;">
                    <i class="fa fa-file-text-o me-1"></i> Bukti Lunas
                </a>
            @else
                @if ($is_show)
                    @if ($item['payment_method'] == 'unpaid' || $item['payment_method'] == 'partial')
                        <a href="{{ route('ppdb.bills.choise-payment', ['type' => 'development']) }}"
                            class="btn btn-sm btn-outline-green px-3" style="font-size: 0.75rem; font-weight: 600;">
                            Cara Bayar <i class="fa fa-chevron-right ms-1"></i>
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
                        <h6 class="fw-bold small text-dark mb-3"><i class="fa fa-university text-success me-2"></i>
                            Instruksi Transfer BCA</h6>
                        <div class="mb-2">
                            <span class="d-block text-muted" style="font-size: 0.7rem;">Nomor Virtual
                                Account
                                (VA)
                            </span>
                            <div class="d-flex align-items-center gap-2">
                                <strong class="text-dark"
                                    style="font-size: 1.1rem; letter-spacing: 1px;">{{ $item['va'] }}</strong>
                                <button class="copy-btn"><i class="fa fa-files-o"></i>
                                    Salin</button>
                            </div>
                        </div>
                        <div>
                            <span class="d-block text-muted" style="font-size: 0.7rem;">Nominal
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
        <div class="mt-3 p-3 rounded" style="background-color: #fff3cd; border: 1px solid #ffe69c;">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-2">
                    <div>
                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Upload Surat
                            Pernyataan</h6>
                        <span class="text-muted" style="font-size: 0.8rem;">Penting: Setelah
                            pembayaran, Anda <b>wajib</b> mengunggah
                            surat pernyataan. Format PDF.</span>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('download-biaya-pengembangan', ['type' => $item['payment_term'] == 'full_payment' ? 'lunas' : 'cicilan']) }}"
                        target="_blank" class="btn btn-sm btn-warning text-dark fw-bold px-3">
                        <i class="fa fa-download me-1"></i> Unduh Form
                    </a>
                    <button type="button" class="btn btn-sm btn-success fw-bold px-3 upload-statement-trigger"
                        data-type="{{ $item['payment_term'] == 'full_payment' ? 'lunas' : 'cicilan' }}">
                        <i class="fa fa-upload me-1"></i> Upload
                    </button>
                </div>
            </div>
        </div>
    @else
        <div class="mt-3 p-3 rounded" style="background-color: #d1e7dd; border: 1px solid #badbcc;">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-2">
                    <div>
                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Surat Pernyataan
                            Terunggah</h6>
                        <span class="text-muted" style="font-size: 0.8rem;">Terima kasih,
                            Anda telah berhasil mengunggah surat
                            pernyataan biaya pengembangan.</span>
                    </div>
                </div>
                <div>
                    <a href="{{ route('download-development-statement-letter') }}" target="_blank"
                        class="btn btn-sm btn-outline-success fw-bold px-3">
                        <i class="fa fa-eye me-1"></i> Lihat File
                    </a>
                </div>
            </div>
        </div>
    @endif
@endif
