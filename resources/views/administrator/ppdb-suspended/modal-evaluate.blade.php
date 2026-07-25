<!-- Modal Evaluasi Penangguhan -->
<div class="modal fade" id="modalEvaluate" tabindex="-1" role="dialog" aria-labelledby="modalEvaluateLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form-evaluate" method="POST">
                @csrf
                <input type="hidden" name="id" id="evaluate-id" value="">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="modalEvaluateLabel">
                        <i class="fa fa-gavel"></i> Evaluasi Penangguhan PPDB
                    </h4>
                </div>
                <div class="modal-body">
                    <div id="evaluate-alert" class="alert alert-danger" style="display:none;"></div>

                    <p class="margin-b-15">Pilih tindakan evaluasi penangguhan untuk siswa ini:</p>

                    <div class="radio radio-success margin-b-15">
                        <input type="radio" name="action" id="action_tolerance" value="tolerance" checked>
                        <label for="action_tolerance" style="font-size: 14px;">
                            <strong>Toleransi Pembayaran</strong>
                            <br>
                            <small class="text-muted">Calon siswa di berikan toleransi untuk dapat melanjutkan proses pembayaran ulang.</small>
                        </label>
                    </div>

                    <div class="radio radio-danger margin-b-15">
                        <input type="radio" name="action" id="action_re_register" value="re_register">
                        <label for="action_re_register" style="font-size: 14px;">
                            <strong>Pendaftaran Ulang</strong>
                            <br>
                            <small class="text-muted">Calon siswa disarankan untuk melakukan pendaftaran ulang di periode yang tersedia.</small>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">
                        <i class="fa fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-success btn-sm" id="btn-submit-evaluate">
                        <i class="fa fa-save"></i> Proses Evaluasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $(document).on('click', '.btn-evaluate', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            $('#evaluate-id').val(id);
            $('#evaluate-alert').hide().empty();
            $('#action_tolerance').prop('checked', true);
            $('#modalEvaluate').modal('show');
        });

        $('#form-evaluate').on('submit', function(e) {
            e.preventDefault();
            var btn = $('#btn-submit-evaluate');
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
            $('#evaluate-alert').hide().empty();

            $.ajax({
                url: "{{ route('admin.ppdb-suspended.evaluate') }}",
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(res) {
                    btn.prop('disabled', false).html('<i class="fa fa-save"></i> Simpan Evaluasi');
                    if (res.status) {
                        $('#modalEvaluate').modal('hide');
                        alert(res.message);
                        location.reload();
                    } else {
                        $('#evaluate-alert').text(res.message || 'Gagal menyimpan evaluasi.').show();
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html('<i class="fa fa-save"></i> Simpan Evaluasi');
                    var msg = 'Terjadi kesalahan saat memproses evaluasi.';
                    if (xhr.status === 419) {
                        msg = 'Sesi Anda telah berakhir (CSRF token mismatch). Silakan muat ulang (refresh) halaman ini.';
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    $('#evaluate-alert').text(msg).show();
                }
            });
        });
    });
</script>
@endpush
