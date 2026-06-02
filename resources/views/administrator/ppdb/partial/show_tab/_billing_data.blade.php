<div class="row">
    <div class="col-md-12">
        <!-- Bagian Riwayat Pembayaran (Lunas) -->
        <div>
            @forelse($billing as $bill)
                <h4 class="border-bottom pb-2 fw-bold" style="color: #26703B;">
                    <i class="fa fa-check-circle me-2"></i> Biaya {{ $bill['type'] }}
                </h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead style="background-color: #f8fafc; color: #475569;">
                            <tr>
                                <th width="30%">Keterangan</th>
                                <th width="20%" class="text-right">Nominal</th>
                                <th width="15%" class="text-center">Cara Bayar</th>
                                <th width="15%" class="text-center">Status</th>
                                @if ($bill['type_bill'] == 'development' && ($bill['payment_status'] == 'unpaid' || $bill['payment_status'] == 'paid'))
                                    <th width="15%" class="text-center">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td>
                                    {{ $bill['desc'] }}
                                    @if (!empty($bill['note']))
                                        <br><small class="text-muted">Catatan: {{ $bill['note'] }}</small>
                                    @endif
                                </td>
                                <td class="text-right fw-bold">Rp
                                    {{ number_format($bill['amount'], 0, ',', '.') }}</td>
                                <td>{{ $bill['payment_term'] }}</td>
                                <td class="text-center">
                                    {{ $bill['payment_method'] }}
                                </td>
                                @if ($bill['type_bill'] == 'development' && ($bill['payment_status'] == 'unpaid' || $bill['payment_status'] == 'paid'))
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#closeBillingModal-{{ $loop->iteration }}">
                                            <i class="fa fa-times"></i> Close Billing
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade text-left" id="closeBillingModal-{{ $loop->iteration }}"
                                            tabindex="-1" role="dialog"
                                            aria-labelledby="closeBillingModalLabel-{{ $loop->iteration }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold"
                                                            id="closeBillingModalLabel-{{ $loop->iteration }}">Close
                                                            Billing</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('admin.ppdb.close-billing') }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="reason-{{ $loop->iteration }}"
                                                                    class="fw-bold">Alasan Close Billing <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="hidden" name="bill_id"
                                                                    value="{{ $bill['id'] }}">
                                                                <textarea class="form-control" id="reason-{{ $loop->iteration }}" name="reason" rows="4"
                                                                    placeholder="Masukkan alasan menutup billing ini..." required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Batal</button>
                                                            <button type="submit"
                                                                class="btn btn-danger">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>

    </div>
</div>
