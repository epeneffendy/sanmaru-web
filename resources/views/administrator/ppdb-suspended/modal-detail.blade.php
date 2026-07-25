<!-- Modal Detail Penangguhan -->
<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalDetailLabel">
                    <i class="fa fa-info-circle"></i> Detail Penangguhan PPDB
                </h4>
            </div>
            <div class="modal-body">
                <div id="modal-detail-loading" class="text-center" style="display:none; padding: 30px;">
                    <i class="fa fa-spinner fa-spin fa-3x fa-fw text-info"></i>
                    <p style="margin-top: 10px; font-weight: bold;">Memuat data detail...</p>
                </div>
                <div id="modal-detail-content">
                    {{-- Detail content filled via AJAX --}}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">
                    <i class="fa fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $(document).on('click', '.btn-detail', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var url = "{{ route('admin.ppdb-suspended.detail', ':id') }}".replace(':id', id);

            $('#modal-detail-loading').show();
            $('#modal-detail-content').hide().empty();
            $('#modalDetail').modal('show');

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    $('#modal-detail-loading').hide();
                    if (res.status) {
                        var data = res.data;
                        var details = res.dispensation_details;

                        var paymentTypeLabel = data.type === 'activity' ? 'Uang Kegiatan' : 'Uang Pengembangan';
                        var expiredDateFormatted = data.expired_at ? new Date(data.expired_at).toLocaleDateString('id-ID') : '-';

                        var html = `
                            <div class="panel panel-default">
                                <div class="panel-heading"><strong>Informasi Siswa & Tagihan</strong></div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-striped table-bordered margin-b-0">
                                                <tr>
                                                    <th width="40%">Nama Siswa</th>
                                                    <td><strong>${data.student_name || '-'}</strong></td>
                                                </tr>
                                                <tr>
                                                    <th>Register Number</th>
                                                    <td>${data.register_number || '-'}</td>
                                                </tr>
                                                <tr>
                                                    <th>Unit</th>
                                                    <td>${data.unit_name || '-'}</td>
                                                </tr>
                                                <tr>
                                                    <th>Periode</th>
                                                    <td>${data.period_name || '-'}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tahun Ajaran</th>
                                                    <td>${data.school_year || '-'}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-striped table-bordered margin-b-0">
                                                <tr>
                                                    <th width="40%">No. Virtual Account</th>
                                                    <td><code>${data.virtual_account_number || '-'}</code></td>
                                                </tr>
                                                <tr>
                                                    <th>Tipe Tagihan</th>
                                                    <td><span class="label label-info">${paymentTypeLabel}</span></td>
                                                </tr>
                                                <tr>
                                                    <th>Tanggal Expired</th>
                                                    <td>${expiredDateFormatted}</td>
                                                </tr>
                                                <tr>
                                                    <th>Status VA</th>
                                                    <td><span class="label label-danger">${data.status ? data.status.toUpperCase() : 'EXPIRED'}</span></td>
                                                </tr>
                                                <tr>
                                                    <th>Keterlambatan</th>
                                                    <td><span class="label label-danger">Terlambat ${res.late_text}</span></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;

                        if (details && details.length > 0) {
                            html += `
                                <div class="panel panel-default margin-t-15">
                                    <div class="panel-heading"><strong>Rincian Angsuran Dispensasi</strong></div>
                                    <div class="panel-body table-responsive">
                                        <table class="table table-bordered table-striped margin-b-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" width="10%">No</th>
                                                    <th>Tahap / Angsuran</th>
                                                    <th class="text-right">Nominal</th>
                                                    <th class="text-right">Jumlah Dibayar</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Jatuh Tempo</th>
                                                </tr>
                                            </thead>
                                            <tbody>`;
                            $.each(details, function(idx, item) {
                                var badgeStatus = item.status === 'paid' 
                                    ? '<span class="label label-success">LUNAS</span>' 
                                    : '<span class="label label-warning">' + (item.status ? item.status.toUpperCase() : 'UNPAID') + '</span>';
                                
                                var dateFormatted = item.date ? new Date(item.date).toLocaleDateString('id-ID') : '-';
                                var nominalFormatted = parseFloat(item.nominal || 0).toLocaleString('id-ID');
                                var paidFormatted = parseFloat(item.amount_paid || 0).toLocaleString('id-ID');
                                var vaNum = String(item.virtual_account || data.virtual_account_number || '').trim();
                                var installmentLabel = (vaNum.length === 16) ? 'Pembayaran Lunas' : 'Pembayaran DP';

                                html += `
                                    <tr>
                                        <td class="text-center">${idx + 1}</td>
                                        <td>${installmentLabel}</td>
                                        <td class="text-right">Rp ${nominalFormatted}</td>
                                        <td class="text-right">Rp ${paidFormatted}</td>
                                        <td class="text-center">${badgeStatus}</td>
                                        <td class="text-center">${dateFormatted}</td>
                                    </tr>`;
                            });
                            html += `</tbody></table></div></div>`;
                        }

                        $('#modal-detail-content').html(html).show();
                    } else {
                        $('#modal-detail-content').html('<div class="alert alert-danger">' + (res.message || 'Gagal memuat data.') + '</div>').show();
                    }
                },
                error: function(xhr) {
                    $('#modal-detail-loading').hide();
                    $('#modal-detail-content').html('<div class="alert alert-danger">Terjadi kesalahan saat mengunduh data detail.</div>').show();
                }
            });
        });
    });
</script>
@endpush
