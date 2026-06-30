<style>
    /* Modern UI Custom Styles */
    .table-modern {
        border-collapse: separate !important;
        border-spacing: 0 8px !important;
        background-color: transparent !important;
    }

    .table-modern thead th {
        border: none !important;
        color: #64748b;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        padding-bottom: 10px;
    }

    .table-modern tbody tr {
        background-color: #ffffff;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px 0 rgba(0, 0, 0, 0.03);
        transition: all 0.2s ease;
    }

    .table-modern tbody tr:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transform: translateY(-1px);
    }

    .table-modern tbody td {
        vertical-align: middle !important;
        padding: 16px 12px;
        border-top: 1px solid #f1f5f9 !important;
        border-bottom: 1px solid #f1f5f9 !important;
        color: #334155;
    }

    .table-modern tbody td:first-child {
        border-left: 1px solid #f1f5f9 !important;
        border-top-left-radius: 8px;
        border-bottom-left-radius: 8px;
    }

    .table-modern tbody td:last-child {
        border-right: 1px solid #f1f5f9 !important;
        border-top-right-radius: 8px;
        border-bottom-right-radius: 8px;
    }

    /* Typography & Info Layout */
    .student-name {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 2px;
        text-transform: uppercase;
    }

    .student-username {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .student-contact {
        font-size: 12px;
        color: #475569;
        display: flex;
        flex-direction: column;
        gap: 4px;
        margin-bottom: 10px;
    }

    .student-contact div {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .student-contact i {
        color: #94a3b8;
        width: 14px;
        text-align: center;
    }

    /* Soft Badges */
    .badge-modern {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-right: 4px;
        margin-bottom: 4px;
    }

    .badge-modern i {
        font-size: 10px;
    }

    .badge-soft-success {
        background-color: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .badge-soft-danger {
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .badge-soft-warning {
        background-color: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .badge-soft-info {
        background-color: #e0f2fe;
        color: #075985;
        border: 1px solid #bae6fd;
    }

    .badge-soft-secondary {
        background-color: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    /* Action Buttons */
    .btn-modern {
        border-radius: 6px;
        font-weight: 500;
        font-size: 12px;
        padding: 5px 12px;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        transition: all 0.2s;
    }

    .btn-modern:hover {
        opacity: 0.9;
    }

    .btn-icon-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }

    /* Container Utama Button */
    .btn-modern-circle {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        /* Menggunakan rounded-square agar lebih modern dari circle sempurna */
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        color: #ffffff;
        font-size: 14px;
    }

    .btn-modern-circle:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .btn-modern-circle:active {
        transform: scale(0.95);
    }

    /* Variasi Warna Modern (Pastel-Bold) */
    .btn-modern-success {
        background: linear-gradient(135deg, #2ecc71, #27ae60);
    }

    .btn-modern-warning {
        background: linear-gradient(135deg, #f1c40f, #f39c12);
    }

    .btn-modern-danger {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
    }

    /* Icon Styling */
    .btn-modern-circle i {
        transition: transform 0.2s ease;
    }

    .btn-modern-circle:hover i {
        transform: scale(1.1);
    }
</style>

<div class="panel panel-default" style="border-radius: 10px; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.02); margin-bottom: 20px;">
    <div class="panel-body" style="padding: 15px 20px; background-color: #f8fafc; border-radius: 10px;">
        <form role="form" autocomplete="off" method="GET"
            action="{{ route('admin.ppdb-monitoring.show-detail-stage', [$period->id, $type, $stage->id ?? 'xx']) }}">
            <input autocomplete="false" name="hidden" disabled type="text" style="display:none;">
            <div class="row" style="display: flex; flex-wrap: wrap; align-items: flex-end; gap: 10px;">
                <div class="col-md-4" style="padding-left: 15px; padding-right: 0;">
                    <label for="search" style="font-weight: 600; color: #475569; margin-bottom: 5px; font-size: 13px;">Pencarian</label>
                    <input type="text" name="name" placeholder="Ketik kata pencarian..." value="{{ @$params['name'] }}"
                        class="form-control" style="border-radius: 8px; height: 38px; border: 1px solid #cbd5e1; box-shadow: inset 0 1px 2px rgba(0,0,0,0.01);" />
                </div>
                
                <div class="col-md-3" style="padding-left: 15px; padding-right: 0;">
                    <label for="scope" style="font-weight: 600; color: #475569; margin-bottom: 5px; font-size: 13px;">Berdasarkan</label>
                    <select name="scope" class="form-control" style="border-radius: 8px; height: 38px; border: 1px solid #cbd5e1;">
                        <option value="name"
                            {{ (@$params['scope'] == 'name' || !isset($params['scope'])) ? 'selected' : null }}>
                            Nama Siswa
                        </option>
                        <option value="register_number"
                            {{ @$params['scope'] == 'register_number' ? 'selected' : null }}>
                            Nomor Registrasi
                        </option>
                    </select>
                </div>
                
                <div class="col-md-4" style="padding-left: 15px;">
                    <button type="submit" class="btn btn-success" style="border-radius: 8px; height: 38px; padding: 0 20px; font-weight: 600; margin-right: 5px; display: inline-flex; align-items: center; gap: 5px;">
                        <i class="fa fa-search"></i> Terapkan
                    </button>
                    <a href="{{ route('admin.ppdb-monitoring.show-detail-stage', [$period->id, $type, $stage->id ?? 'xx']) }}" class="btn btn-default" style="border-radius: 8px; height: 38px; padding: 0 20px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; border: 1px solid #cbd5e1; background: #fff; color: #475569;">
                        <i class="fa fa-refresh"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="fixed-table-head" style="max-height: calc(100vh - 280px);">
    <table id="datatables-master-ppdb" class="table table-modern display" style="width: 100%;">
        <thead>
            <tr>
                <th rowspan="2" class="text-center" style="width: 40px;">No</th>
                <th rowspan="2" class="text-left">Calon Siswa</th>
                <th rowspan="2" class="text-center">Status Pendaftaran</th>
                <th colspan="5" class="text-center">Status Administrasi</th>
                <th rowspan="2" class="text-center">Option</th>
            </tr>
            <tr>
                <th class="text-center">Verified</th>
                <th class="text-center">Biaya Formulir</th>
                <th class="text-center">Data</th>
                <th class="text-center">Parent</th>
                <th class="text-center">Surat Pernyataan</th>
            </tr>
        </thead>
        <tbody>
            @php($number = 1)
            @foreach ($data as $item)
                <tr>
                    <td class="text-center font-weight-bold">{{ $number++ }}</td>
                    <td class="text-left" style="min-width: 280px;">
                        <div class="student-name">{{ $item['name'] }}</div>
                        <div class="student-username">
                            <i class="fa fa-user"></i> {{ $item['username'] }}
                        </div>

                        <div class="student-contact">
                            <div><i class="fa fa-envelope"></i> {{ $item['email'] }}</div>
                            <div><i class="fa fa-phone"></i> {{ $item['mobile_phone'] }}</div>
                            <div><i class="fa fa-venus-mars"></i> {{ $item['gender'] }}</div>
                        </div>

                        <div style="margin-top: 8px;">
                            <span class="badge-modern badge-soft-warning" title="No Registrasi">
                                Reg: {{ $item['register_number'] }}
                            </span>
                            <span class="badge-modern badge-soft-danger" title="Unit">
                                {{ $item['unit_name'] }}
                            </span>
                            <span class="badge-modern badge-soft-info" title="Periode">
                                {{ $item['periode_name'] }}
                            </span>
                            <span class="badge-modern badge-soft-secondary" title="Asal Sekolah">
                                {{ $item['origin_school'] }}
                            </span>
                        </div>
                    </td>

                    <td class="text-center">
                        <div style="margin-bottom: 8px;">
                            {!! $item['status_confirm'] !!}<br>
                            {!! $item['status_period'] !!}
                        </div>
                        <div>
                            {!! $item['status_stage'] !!}
                        </div>

                        @if ($item['status_student'] == 'siswa')
                            <div style="margin-top: 8px;">
                                <span class="badge-modern badge-soft-success" title="No Registrasi">
                                    Telah diterima menjadi siswa
                                </span>
                                <span class="badge-modern badge-soft-warning" title="No Registrasi">
                                    NISN: {{ isset($item['nis']) ? $item['nis'] : '' }}
                                </span>
                                <span class="badge-modern badge-soft-info" title="Unit">
                                    Kelas: {{ isset($item['class_name']) ? $item['class_name'] : '' }}
                                </span>
                            </div>
                        @endif
                    </td>

                    <td class="text-center">
                        <div style="margin-bottom: 8px;">
                            <span
                                class="badge-modern {{ $item['isEmailVerified'] ? 'badge-soft-success' : 'badge-soft-danger' }}"
                                style="border-radius: 20px;">
                                <i
                                    class="fa {{ $item['isEmailVerified'] ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                {{ $item['isEmailVerified'] ? 'Email Verified' : 'Belum Verified' }}
                            </span>
                        </div>
                        @if (!$item['isEmailVerified'])
                            <button class="btn btn-sm btn-outline-secondary btn-modern send-confirmation"
                                data-id="{{ $item['id'] }}" title="Kirim Ulang Email Konfirmasi">
                                <i class="fa fa-paper-plane text-primary me-1"></i> Kirim Ulang
                            </button>
                        @endif
                    </td>

                    <td class="text-center">
                        @if ($item['payment_date'] == '')
                            <div style="margin-bottom: 8px;">
                                <span class="badge-modern badge-soft-danger" style="border-radius: 20px;">
                                    <i class="fa fa-times-circle"></i> Belum Bayar
                                </span>
                            </div>
                        @else
                            <div style="margin-bottom: 4px;">
                                <span class="badge-modern badge-soft-success" style="border-radius: 20px;">
                                    <i class="fa fa-check-circle"></i> Terkonfirmasi
                                </span>
                            </div>
                            <div style="font-weight: 700; color: #16a34a; font-size: 13px;">
                                Rp {{ number_format($item['total_payment_form']) }}
                            </div>
                        @endif
                    </td>

                    <td class="text-center">
                        <span
                            class="badge-modern {{ $item['isComplite'] ? 'badge-soft-success' : 'badge-soft-danger' }}"
                            style="border-radius: 20px;">
                            <i class="fa {{ $item['isComplite'] ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                            {{ $item['isComplite'] ? 'Lengkap' : 'Belum Lengkap' }}
                        </span>
                    </td>

                    <td class="text-center">
                        <span class="badge-modern {{ $item['isParent'] ? 'badge-soft-success' : 'badge-soft-danger' }}"
                            style="border-radius: 20px;">
                            <i class="fa {{ $item['isParent'] ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                            {{ $item['isParent'] ? 'Lengkap' : 'Belum Lengkap' }}
                        </span>
                    </td>

                    <td class="text-center">

                        <button
                            class="btn btn-modern btn-icon-circle {{ $item['IsStatementLetterUploaded'] ? ($item['IsStatementLetterConfirmed'] ? 'btn-success btn-modal-statement-letter-success' : 'btn-warning btn-modal-statement-letter') : 'btn-danger' }}"
                            data-id="{{ $item['id'] }}" data-name="{{ $item['name'] }}"
                            data-register_number="{{ $item['register_number'] }}"
                            data-unit_id="{{ $item['unit_id'] }}" data-unit_name="{{ $item['unit_name'] }}"
                            title="Detail Surat Pernyataan">
                            @if ($item['IsStatementLetterConfirmed'])
                                <i class="fa fa-check"></i>
                            @elseif ($item['IsStatementLetterUploaded'])
                                <i class="fa fa-question"></i>
                            @else
                                <i class="fa fa-times"></i>
                            @endif
                        </button>

                        {{-- ACTION MODAL --}}
                        @if ($item['development_fee_option'] && !$item['isOrderConfirmed'])
                            <div class="mt-2">
                                <button data-toggle="modal"
                                    data-target="#reset-development-payment-modal-{{ $item['id'] }}"
                                    class="btn btn-sm btn-outline-warning btn-modern"
                                    onclick="return confirm('Apakah anda yakin akan mereset tahapan ini? Surat pernyataan akan terhapus');">
                                    <i class="fa fa-sync-alt"></i> Reset
                                </button>
                            </div>
                        @endif

                        @if ($item['development_fee_option'] != null)
                            <div style="margin-top: 10px;">
                                @if ($item['development_fee_option'] == 'lunas')
                                    <span class="badge-modern badge-soft-info d-block text-center mb-1">Pembayaran
                                        Lunas</span>
                                    <div class="text-center">{!! $item['voucher'] !!}</div>
                                @else
                                    <span class="badge-modern badge-soft-warning d-block text-center">Pembayaran
                                        Cicilan</span>
                                @endif
                            </div>
                        @endif

                        @if ($item['IsStageDevelopment'])
                            <div style="margin-top: 10px;">
                                <button type="button"
                                    data-url="{{ route('admin.ppdb-monitoring.sync-stage-development', ['id' => $item['id'], 'stage_id' => $item['stage_id']]) }}"
                                    title="Detail Data Siswa"
                                    class="btn btn-light btn-modern btn-block border btn-sync-data">
                                    <i class="fa fa-refresh text-info"></i> Sync Data
                                </button>
                            </div>
                        @endif

                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.ppdb.show', $item['id']) }}" title="Detail Data Siswa"
                            class="btn btn-primary btn-modern text-white">
                            <i class="fa fa-eye"></i> View
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

</div>
{{ $data->appends(request()->except('page'))->links() }}

@foreach ($data as $item)
    @if ($item['development_fee_option'] && !$item['isOrderConfirmed'])
        <!-- Modal -->
        <div id="reset-development-payment-modal-{{ $item['id'] }}" class="modal fade text-left" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content" style="border-radius: 10px; border: none;">
                    <div class="modal-header bg-light" style="border-radius: 10px 10px 0 0;">
                        <h5 class="modal-title font-weight-bold">Reset Surat Pernyataan</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body p-4">
                        <form action="{{ route('admin.ppdb.reset-development-payment-method', $item) }}"
                            method="POST">
                            @csrf
                            <p class="text-muted mb-3">Silakan isi alasan mereset surat pernyataan
                                untuk <strong class="text-dark">{{ $item['name'] }}</strong>.</p>

                            <input type="hidden" id="year" name="year" value="{{ $item['school_year'] }}">
                            <input type="hidden" id="unit" name="unit" value="{{ $item['unit_id'] }}">
                            <input type="hidden" id="periode" name="periode" value="{{ $item['periode'] }}">
                            <input type="hidden" id="ppdb_user_id" name="ppdb_user_id[]"
                                value="{{ $item['id'] }}">
                            <input type="hidden" id="title" name="title"
                                value="[RESET] Surat Pernyataan {{ $item['name'] }}">

                            <div class="form-group">
                                <label for="body" class="font-weight-bold">Alasan Reset</label>
                                <textarea class="form-control" name="body" id="body" rows="3"
                                    placeholder="Tuliskan alasan spesifik di sini...">{!! old('body') !!}</textarea>
                                @error('body')
                                    <span class="text-danger small mt-1"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="send_email"
                                        id="send_email_{{ $item['id'] }}" value="1"
                                        {{ old('send_email', 1) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="send_email_{{ $item['id'] }}">Kirim
                                        email
                                        pemberitahuan ke orang tua</label>
                                </div>
                            </div>

                            <div class="text-right mt-4">
                                <button type="button" class="btn btn-light btn-modern mr-2"
                                    data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-warning btn-modern text-dark"><i
                                        class="fa fa-sync-alt mr-1"></i> Proses Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

<!-- Modal Confirmation Standalone -->
<div id="modal-confirmation" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 10px; border: none;">
            <div class="modal-header bg-light" style="border-radius: 10px 10px 0 0;">
                <h5 class="modal-title font-weight-bold">&nbsp;</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
            </div>
            <div class="modal-footer bg-white" style="border-radius: 0 0 10px 10px;">
                <button type="button" class="btn btn-light btn-modern" data-dismiss="modal">Batal</button>
                <button type="button" id="btn-confirm-modal" class="btn btn-success btn-modern">&nbsp;</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('js/sweet-alert/sweet-alert.min.js') }}"></script>
    <script>
        // Membungkus script dengan DOMContentLoaded agar berjalan setelah DOM dan script selesai dimuat
        document.addEventListener('DOMContentLoaded', function() {
            $(document).on('click', '.btn-modal-statement-letter, .btn-modal-statement-letter-success', function(
                e) {
                e.preventDefault();
                var id = $(this).data('id'),
                    unitId = $(this).data('unit_id'),
                    isConfirmed = $(this).hasClass('btn-modal-statement-letter-success');

                var html =
                    `<form role="form" action="{{ route('admin.ppdb.confirm-development-statement', ['id' => null]) }}/` +
                    id + `" method="POST" id="statement-letter-confirmation-form">
                            @csrf
                            <input type="hidden" name="id" value="` + id + `" />

                            <div class="text-center mb-4">
                                <h5 class="text-primary font-weight-bold mb-1">` + $(this).data('name') + `</h5>
                                <span class="badge badge-secondary mr-1">` + $(this).data('register_number') + `</span>
                                <span class="badge badge-info">` + $(this).data('unit_name') + `</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong class="text-dark">Preview Dokumen</strong>
                                <a href="{{ route('admin.ppdb.get-development-file', ['id' => null]) }}/` + $(this)
                    .data('id') + `" target="_blank" class="btn btn-sm btn-outline-primary btn-modern">
                                    <i class="fa fa-external-link-alt"></i> Buka di Tab Baru
                                </a>
                            </div>

                            <div class="border rounded overflow-hidden shadow-sm" style="background:#f8f9fa;">
                                <iframe src="{{ route('admin.ppdb.get-development-file', ['id' => null]) }}/` + $(
                        this).data('id') + `" width="100%" height="350" style="border:none;"></iframe>
                            </div>
                        </form>`;

                $('#modal-confirmation .modal-title').html('Detail Surat Pernyataan');
                $('#modal-confirmation .modal-body').html(html);

                if (isConfirmed) {
                    $('#btn-confirm-modal').hide();
                } else {
                    $('#btn-confirm-modal').show();
                    $('#btn-confirm-modal').attr('data-id', id);
                    $('#btn-confirm-modal').html('<i class="fa fa-check mr-1"></i> Setujui Dokumen');
                }

                $('#modal-confirmation').modal('show');
            });

            $(document).on('click', "#btn-confirm-modal", function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                // Tambahkan loading state pada tombol
                $(this).html('<i class="fa fa-spinner fa-spin mr-1"></i> Memproses...').prop('disabled',
                    true);
                $('#statement-letter-confirmation-form').submit();
            });

            $(document).on('click', '.btn-sync-data', function(e) {
                e.preventDefault();
                var url = $(this).data('url');
                var $btn = $(this);
                var originalHtml = $btn.html();

                $btn.html('<i class="fa fa-spinner fa-spin text-info"></i> Syncing...').prop('disabled',
                    true);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        console.log(response);
                        if (response === true || response.success === true || response
                            .status === 'success') {
                            window.location.reload();
                        } else {
                            swal("Gagal!", response.message ||
                                "Gagal melakukan sinkronisasi data.", "error");
                            $btn.html(originalHtml).prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        swal("Error!", "Terjadi kesalahan pada server saat sinkronisasi data.",
                            "error");
                        $btn.html(originalHtml).prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush
