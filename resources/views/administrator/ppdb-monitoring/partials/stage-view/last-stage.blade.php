<div class="selection-card">
    <div class="form-group" style="padding: 3em;">
        <div class="alert alert-info mt-4 mb-0 border-0 shadow-sm">
            <i class="fa fa-exclamation-triangle me-2"></i>
            <strong>Penting:</strong> Calon siswa hanya dianggap <strong>Resmi</strong> menjadi Siswa
            <strong>{{$period->unit->name}}</strong> jika sudah dilakukan <strong>Verifikasi (Diterima)</strong> oleh
            admin.
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <br class="card-body p-4">

            <div class="row text-center justify-content-center">
                <div class="col-md-3 mb-3">
                    <div class="p-3 border rounded bg-light">
                        <div class="h4 text-primary fw-bold">
                            <div id="total-student"></div>
                        </div>
                        <h6 class="fw-bold">Total Pendaftar</h6>
                        <small class="text-muted">Total pendaftar di <strong> {{ $period->unit->name }}</strong>.
                        </small>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="p-3 border rounded bg-light">
                        <div class="h4 text-warning fw-bold">
                            <div id="total-accepted"></div>
                        </div>
                        <h6 class="fw-bold">Diterima</h6>
                        <small class="text-muted">Total siswa yang diterima menjadi siswa
                            <strong>{{ $period->unit->name }}</strong>.
                        </small>
                    </div>
                </div>


                <div class="col-md-3 mb-3">
                    <div class="p-3 border border-primary rounded bg-white shadow-sm">
                        <div class="h4 text-success fw-bold">
                            <div id="total-not-selected"></div>
                        </div>
                        <h6 class="fw-bold">Tidak Diterima</h6>
                        <small class="text-muted">Total siswa yang tidak diterima</small>
                    </div>
                </div>


                <div class="col-md-3 mb-3">
                    <div class="p-3 border border-primary rounded bg-white shadow-sm">
                        <div class="h4 text-success fw-bold">
                            <div id="total-null"></div>
                        </div>
                        <h6 class="fw-bold">Belum Ditentukan</h6>
                        <small class="text-muted">Total siswa yang belum ditentukan</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="selection-header">
    <ul class="nav nav-pills custom-pills" id="selection">
        <li class="active">
            <a href="#seleksi" data-toggle="pill">
                <i class="fa fa-users mr-2"></i> Seleksi Pendaftar
            </a>
        </li>
        <li>
            <a href="#manual" data-toggle="pill">
                <i class="fa fa-upload mr-2"></i> Import Data
            </a>
        </li>
    </ul>
</div>
<div class="tab-content selection-body">
    <input type="hidden" id="unit_id" value="{{$period->unit->id}}">
    <input type="hidden" id="periode" value="{{$period->id}}">

    <div id="seleksi" class="tab-pane fade in active">
        <div class="widget-header">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <h3>Seleksi Pendaftar</h3>
        </div> <!-- /widget-header -->
        <div class="widget-content seleksi-wrapper">
            <div class="action-bar row align-items-center mb-4">
                <div class="col-md-5">
                    <div class="search-wrapper">
                        <i class="fa fa-search"></i>
                        <input type="text" name="pencarian" class="form-control shadow-none"
                               placeholder="Cari nama atau nomor pendaftaran...">
                    </div>
                </div>
                <div class="col-md-7 text-right">
                    <div class="total-badge total shadow-sm">
                    </div>
                </div>
            </div>
            <br>

            <div class="table-responsive">
                <table class="table table-custom table-hover align-middle">
                    <thead>
                    <tr>
                        <th rowspan="2" class="text-center" width="60">NO</th>
                        <th rowspan="2">INFORMASI PENDAFTAR</th>
                        <th colspan="3" class="text-center border-bottom-0">STATUS SELEKSI</th>
                    </tr>
                    <tr class="sub-header">
                        <th class="text-center" width="25%">BELUM DITENTUKAN</th>
                        <th class="text-center" width="25%">DITERIMA</th>
                        <th class="text-center" width="25%">TIDAK DITERIMA</th>
                    </tr>
                    </thead>
                    <tbody style="height: 300px; overflow-y: auto;">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="manual" class="tab-pane fade">
        <div class="widget-header">
            <h3>Pengumuman Privasi</h3>
        </div> <!-- /widget-header -->
        <div class="widget-content">
            <form action="{{ route('admin.ppdb-monitoring.import-users-last-stage', ['stage' => @$stage['id']]) }}"
                  method="post"
                  enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <input type="file" name="file" class="form-control form-control-radius"
                           accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/>
                </div>
                <div class="form-group">
                    <div class="checkbox checkbox-circle checkbox-info">
                        <input id="overwrite" name="type" type="checkbox" checked="true" value="overwrite"/>
                        <label for="overwrite">Overwrite</label>
                    </div>
                </div>
                <div class="form-group">
                    download template dengan <a href="{{ route('admin.stage.export-student', [
                            'stage'=>@$stage['id'],
                            'unit'=>@$period->unit->id,
                            'period'=>@$period->id,
                         ]) }}" target="_blank" download>klik disini</a>
                </div>

                <div class="form-group">
                    <div class="alert alert-info border-0 shadow-sm" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-info-circle me-3 fa-lg"> Panduan Pengisian Template</i>
                            <div>
                                <h6 class="alert-heading fw-bold mb-1"></h6>
                                <span>Silahkan kolom <strong>Status</strong> diisi dengan:
                                        <span class="badge bg-success">diterima</span>, atau
                                        <span class="badge bg-danger">tidak diterima</span>.
                                    </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success btn-sm btn-insert">import</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

@push('scripts')
    <script src="{{asset('js/summernote/summernote.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-select/bootstrap-select.js')}}"></script>
    <script src="{{asset('js/sweet-alert/sweet-alert.min.js')}}"></script>
    <script>
        var transfer;
        var nullTotal = notSelectedTotal = accepetedTotal = nullTotal = 0;
        $(document).ready(function () {
            $('.selectpicker').selectpicker({
                liveSearch: true,
                dropupAuto: false,
                title: "No Value"
            });

            $(".nav-tabs a").click(function () {
                $(this).tab('show');
            });

            $('textarea[name=information]').summernote({
                height: 400
            });

            initTable();
        });

        $(document).on('change', '#unit_id,#periode,#is_opening_development_feature,#is_opening_shop_feature', function () {
            if ($('.seleksi-wrapper').length) {
                $('.seleksi-wrapper').html('harap simpan data terlebih dahulu')
            }
            if ($(this).attr('id') == 'unit_id') {
                $.get('{{ route('admin.stage.get-periods') }}/' + $(this).val(), function (data, status) {
                    $('#periode').html(`<option data-hidden="true"></option>`);
                    if (data.length) {
                        $.each(data, function (index, value) {
                            $('#periode').append(`
                                    <option value="${value.id}">${value.name}</option>
                                `);
                        });
                        $('#periode').selectpicker('refresh');
                    }
                });
            }
        });

        // $(document).on('click', 'tbody .radio label', function () {
        //     let id = $(this).parent().parent().parent().data('id');
        //     if ($('input[name="status[' + id + ']"]:checked').val() === '2') {
        //         pendingTotal--;
        //     } else if ($('input[name="status[' + id + ']"]:checked').val() === '1') {
        //         passedTotal--;
        //     } else if ($('input[name="status[' + id + ']"]:checked').val() === '0') {
        //         notpassedTotal--;
        //     } else {
        //         nullTotal--;
        //     }
        // });

        // $(document).on('click', 'tbody input[type=radio]', function () {
        //     let val = $(this).val();
        //     if (val === '1') {
        //         $(this).parent().parent().parent().find('textarea').removeAttr('readonly');
        //         passedTotal++;
        //     } else if (val === '2') {
        //         $(this).parent().parent().parent().find('textarea').attr('readonly', 'true');
        //         pendingTotal++;
        //     } else if (val === '0') {
        //         $(this).parent().parent().parent().find('textarea').attr('readonly', 'true');
        //         notpassedTotal++;
        //     } else {
        //         $(this).parent().parent().parent().find('textarea').attr('readonly', 'true');
        //         nullTotal++;
        //     }
        //
        //     showTotal();
        // })

        $(document).on('input', 'input[name=pencarian]', function () {
            let val = $(this).val();
            if (!val) {
                $('tr').show();
                return;
            }
            $("tbody tr[data-name*='" + val + "' i]").show();
            $("tbody tr:not([data-name*='" + val + "' i])").hide();
        });

        $(document).on('click', '.btn-konfirmasi', function (e) {
            console.log("test")
            e.preventDefault();
            $(this).attr('readonly', 'true');
            swal({
                title: "PERHATIAN",
                text: "Siswa ini akan dinyatakan diterima menjadi siswa reguler?",
                icon: "warning",
                buttons: [
                    'tidak!',
                    'Ya, Saya yakin!'
                ],
                dangerMode: false,
            }).then(function (isConfirm) {
                if (isConfirm) {
                    let statuses = {};
                    let notes = {};
                    let passed_all = false;

                    if ($('#passed_all').is(':checked')) {
                        passed_all = true;
                    }

                    $('input[name^="status"]:checked').each(function () {
                        statuses[$(this).data('id')] = $(this).val();
                    });

                    // $('textarea[name^="note"').each(function () {
                    //     notes[$(this).data('id')] = $(this).val();
                    // }

                    var period = $('#periode').val();
                    var url = "{{ route('admin.ppdb-monitoring.post-users', ['id' => ':id']) }}";
                    url = url.replace(':id', period);

                    $.post(url, {
                        statuses: statuses,
                        notes: notes,
                        passed_all: passed_all,

                        _token: "{{ csrf_token() }}"
                    }, function (data, status) {
                        if (data.status == 'success') {
                            swal({
                                title: 'Sukses!',
                                text: 'data Pendaftar berhasil disimpan!',
                                icon: 'success',
                                timer: 2000
                            });

                            setTimeout(function () {
                                location.reload();
                            }, 2000);
                        } else {
                            swal("Terjadi kesalahan", "Penyimpanan gagal, silahkan ulangi lagi !", "error");
                        }
                    });
                } else {
                    swal("Dibatalkan", "Silahkan periksa kembali data yang akan disimpan", "error");
                }
            })
        });

        function initTable() {
            $('.seleksi-wrapper tbody').html('');
            var unit = $('#unit_id').val();
            var period = $('#periode').val();
            var shopFeatureChecked = false;

            if ($('#is_opening_shop_feature').is(':checked')) {
                shopFeatureChecked = true;
            }

            if (unit && period) {
                var url = "{{ route('admin.ppdb-monitoring.users-last-stage', ['id' => ':id']) }}";
                url = url.replace(':id', period);

                $.get(url, function (data, status) {
                    if (data.length) {
                        $.each(data, function (index, value) {
                            nullChecked = acceptedChecked = notSelectedChecked = '';

                            if (value.note == null) {
                                value.note = '';
                            }

                            let no = index + 1;
                            if (value.status === 'submitted') {
                                nullTotal++;
                                nullChecked = 'checked="true"';
                            } else if (value.status === 'accepted') {
                                accepetedTotal++;
                                acceptedChecked = 'checked="true"';
                            } else if (value.status === 'not_selected') {
                                notSelectedTotal++;
                                notSelectedChecked = 'checked="true"';
                            } else {
                                nullTotal++;
                                nullChecked = 'checked="true"';
                            }

                            $('.seleksi-wrapper tbody').append(`
                                    <tr data-name="[${value.register_number}] ${value.name}" data-id="${value.id}">
                                        <td style="width: 50px;" class="text-center">${no}</td>
                                        <td><b>[${value.register_number}]</b> ${value.name}</td>
                                        <td style="width: 75px;" class="text-center">
                                            <div class="radio">
                                                <input id="status_null_${value.id}" ${nullChecked} type="radio" data-id="${value.id}" name="status[${value.id}]" value="submitted" /><label for="status_null_${value.id}"></label>
                                            </div>
                                        </td>
                                        <td style="width: 75px;" class="text-center">
                                            <div class="radio radio-warning">
                                                <input id="status_accepeted_${value.id}" ${acceptedChecked} data-id="${value.id}" type="radio" name="status[${value.id}]" value="accepted" /><label for="status_accepeted_${value.id}"></label>
                                            </div>
                                        </td>
                                        <td style="width: 75px;" class="text-center">
                                            <div class="radio radio-warning">
                                                <input id="status_not_selected_${value.id}" ${notSelectedChecked} data-id="${value.id}" type="radio" name="status[${value.id}]" value="not_selected" /><label for="status_not_selected_${value.id}"></label>
                                            </div>
                                        </td>



                                    </tr>
                                `);
                        });

                        if (shopFeatureChecked) {
                            $('.seleksi-wrapper').append(`
                                    <div class="form-group">
                                        <div class="checkbox checkbox-success">
                                            <input id="passed_all" name="passed_all" type="checkbox" value="1" />
                                            <label for="passed_all">Lolos semua</label>
                                        </div>
                                    </div>
                                `);
                        }

                        $('.seleksi-wrapper').append('<br><button class="btn btn-konfirmasi btn-success"><i class="fa fa-save"></i> simpan</button>');
                        showTotal();
                    } else {
                        $('#seleksi').html('tidak ada data pendaftar');
                    }
                });
            } else {
                $('#seleksi').html('tidak ada data pendaftar');
            }
        }

        function showTotal() {
            let total = nullTotal + notSelectedTotal + accepetedTotal;
            $('#total-student').html(total);
            $('#total-accepted').html(accepetedTotal);
            $('#total-not-selected').html(notSelectedTotal);
            $('#total-null').html(nullTotal);
        }
    </script>
@endpush
