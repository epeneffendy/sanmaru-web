<div class="bill-row border-bottom border-light">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
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