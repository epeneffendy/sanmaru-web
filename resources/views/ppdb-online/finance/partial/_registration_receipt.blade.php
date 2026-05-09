<style>
    .payment-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin: 0 auto;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }

    .payment-header {
        background: #f8fafc;
        padding: 1.25rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px dashed #cbd5e1;
    }

    .method-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #475569;
        background: #e2e8f0;
        padding: 0.35rem 0.85rem;
        border-radius: 20px;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        font-size: 0.875rem;
        font-weight: 600;
        padding: 0.375rem 1rem;
        border-radius: 20px;
    }

    .status-pill.success {
        background: #dcfce7;
        color: #166534;
    }

    .status-pill.danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .payment-body {
        padding: 1.5rem;
    }

    .info-row {
        margin-bottom: 1.25rem;
    }

    .info-row:last-child {
        margin-bottom: 0;
    }

    .info-row label {
        display: block;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        margin-bottom: 0.35rem;
        font-weight: 600;
    }

    .info-row .value {
        font-size: 1rem;
        color: #1e293b;
    }

    .main-info {
        background: #f1f5f9;
        padding: 1.25rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .main-info label {
        color: #475569;
    }

    .main-info .value {
        font-size: 1.15rem;
        color: #0f172a;
    }

    .payment-details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }

    .va-number {
        font-family: 'Courier New', Courier, monospace;
        font-size: 1.15rem !important;
        letter-spacing: 0.05em;
        color: #0f172a !important;
        font-weight: 600;
    }

    .price {
        font-size: 1.25rem !important;
        font-weight: 700;
    }

    /* Media Queries */
    @media (max-width: 576px) {
        .payment-details-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .payment-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }
    }

    /* Print Styles */
    @media print {
        body * {
            visibility: hidden;
        }

        .modal-content,
        .modal-dialog,
        .modal {
            border: none !important;
            box-shadow: none !important;
            background: transparent !important;
        }

        .payment-card,
        .payment-card * {
            visibility: visible;
        }

        .payment-card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: 2px solid #e2e8f0 !important;
            margin: 0;
        }

        .modal-header,
        .modal-footer {
            display: none !important;
        }
    }
</style>

<div class="modal-header">
    <h5 class="modal-title" id="receiptModalLabel">Bukti Pembayaran Formulir </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <!-- Tampilkan data yang dikirim dari controller -->
    @if ($data->payment_date != '')
        <div class="payment-card shadow-sm">
            <div class="payment-header">
                <div class="bank-info">
                    <span class="method-label">Virtual Account</span>
                </div>
                <div class="status-badge-wrapper">
                    <span class="status-pill success">
                        <i class="fa fa-check-circle"></i> Pembayaran Terkonfirmasi
                    </span>
                </div>
            </div>

            <div class="payment-body">
                <div class="info-row main-info">
                    <label>Nama Peserta</label>
                    <div class="value fw-bold text-uppercase">{{ @$data->name }}</div>
                </div>

                <div class="payment-details-grid">
                    <div class="info-row">
                        <label>Nomor Virtual Account</label>
                        <div class="value va-number">{{ @$data->virtual_account_number }}</div>
                    </div>
                    <div class="info-row">
                        <label>Total Pembayaran</label>
                        <div class="value price text-success">Rp. {{ number_format($data->total_payment_form) }}</div>
                    </div>
                    <div class="info-row">
                        <label>Tanggal Transaksi</label>
                        <div class="value">{{ @$data->payment_date }}</div>
                    </div>
                    <div class="info-row">
                        <label>Metode Pembayaran</label>
                        <div class="value">Automatic Verification (BCA)</div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="status-badge-wrapper" style="text-align: center">
            <span class="status-pill danger">
                <i class="fa fa-times"></i> Belum Melakukan Pembayaran
            </span>
        </div>
    @endif

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
    {{-- <button type="button" class="btn btn-primary" onclick="window.print()">Cetak</button> --}}
</div>
