@if($data->payment_date != '')
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
                    <div class="value va-number">{{ @$data->virtual_account_number  }}</div>
                </div>
                <div class="info-row">
                    <label>Total Pembayaran</label>
                    <div class="value price text-success">Rp. {{number_format($data->total_payment_form)}}</div>
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
