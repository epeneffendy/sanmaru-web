@extends('layouts.admin.main')
@section('content')
    @if (@$status == 'edit')
        @php($action = route('admin.dispensation.update', [@$dispensation['id']]))
        @php($status_btn = 'Update')
        @php($status_header = 'Edit')
    @else
        @php($action = route('admin.dispensation.store'))
        @php($status_btn = 'Save')
        @php($status_header = 'Tambah')
    @endif
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Kelola Dispensasi Pembayaran Siswa</h1>
        <ol class="breadcrumb">
            <li>Keuangan</li>
            <li><a href="{{ route('admin.dispensation.index') }}">Kelola Dispensasi Pembayaran Siswa</a></li>
            <li class="active">{{ $status_header }}</li>
        </ol>
    </div>
    <!-- End Page Header -->

    <!-- START CONTAINER -->
    <div class="container-padding">
        <!-- Start Row -->
        <div class="row">
            <!-- Start Panel -->
            <div class="col-md-12">
                <div class="widget ">
                    <div class="widget-header">
                        <h3>{{ $status_header }} Data</h3>
                    </div> <!-- /widget-header -->
                    <div class="widget-content">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form role="form" method="POST" action="{{ $action }}" class="form-horizontal"
                            enctype="multipart/form-data">
                            <input type="hidden" value="{{ @$dispensation['id'] }}" name="id" />

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Jenis Dispensasi</label>
                                <div class="col-sm-10">
                                    <select class="form-control selectpicker" name="dispensation_type"
                                        id="dispensation_type" data-style="btn-success" data-live-search="true" required>
                                        <option value="" selected>Pilih Jenis Dispensasi</option>
                                        @foreach ($dispensation_type as $type)
                                            <option value="{{ $type['value'] }}"
                                                {{ @$dispensation['dispensation_type'] == $type['value'] ? 'selected' : '' }}>
                                                {{ $type['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Tahun Ajaran</label>
                                <div class="col-sm-10">
                                    <select name="school_year" id="school_year" class="form-control selectpicker"
                                        data-style="btn-success">
                                        <option value="">Pilih Tahun Ajaran</option>
                                        @foreach ($school_year ?? [] as $year)
                                            <option value="{{ $year }}"
                                                {{ @$dispensation['school_year'] == $year ? 'selected' : '' }}>
                                                {{ $year }} - {{ $year + 1 }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Unit</label>
                                <div class="col-sm-10">
                                    <select class="form-control selectpicker" name="unit_id" id="unit_id"
                                        data-style="btn-success">
                                        <option value="">Pilih Unit</option>
                                        @foreach ($units ?? [] as $unit)
                                            <option value="{{ $unit->id }}"
                                                {{ @$dispensation['unit_id'] == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Siswa</label>
                                <div class="col-sm-10">
                                    <select class="form-control selectpicker" name="ppdb_user_id" id="ppdb_user_id"
                                        data-style="btn-success" data-live-search="true" required>
                                        <option value="">Pilih Siswa</option>
                                    </select>
                                </div>

                            </div>



                            <div class="form-group">
                                <label class="col-sm-2 control-label">Biaya Sebenarnya</label>
                                <div class="col-sm-7">
                                    <input type="hidden" name="actual_cost" id="actual_cost"
                                        value="{{ @$dispensation['actual_cost'] }}">
                                    <input type="text" class="form-control" id="actual_cost_display"
                                        value="{{ @$dispensation['actual_cost'] ? number_format(@$dispensation['actual_cost'], 0, ',', '.') : '' }}"
                                        required placeholder="0" readonly>
                                    <small id="actual_cost_info" class="help-block text-warning font-weight-bold"
                                        style="display: none;"></small>
                                </div>
                                <div class="col-md-3" id="attachment-container" style="display: none;">
                                    <button type="button" class="btn btn-info" id="show-attachment-btn">Lihat
                                        Lampiran</button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Total Akhir</label>
                                <div class="col-sm-7">
                                    <input type="hidden" name="total_final_fee" id="total_final_fee"
                                        value="{{ @$dispensation['total_final_fee'] }}">
                                    <input type="text" class="form-control" id="total_final_fee_display"
                                        value="{{ @$dispensation['total_final_fee'] ? number_format(@$dispensation['total_final_fee'], 0, ',', '.') : '' }}"
                                        required placeholder="0">
                                </div>
                                <div class="col-sm-3" id="is_discount_accepted_container" style="display: none; padding-top: 7px;">
                                    <label>
                                        <input type="checkbox" name="is_discount_accepted" id="is_discount_accepted" value="1" {{ @$dispensation['is_discount_accepted'] ? 'checked' : '' }}> Terima Potongan
                                    </label>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-2 control-label">Mode Dispensasi</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="dispensation_mode" id="dispensation_mode" required>
                                        <option value="">Pilih Mode Dispensasi</option>
                                        <option value="full_setup"
                                            {{ @$dispensation['dispensation_mode'] == 'full_setup' ? 'selected' : '' }}>
                                            Full Setup (Admin Tentukan Cicilan)</option>
                                        <option value="only_discount"
                                            {{ @$dispensation['dispensation_mode'] == 'only_discount' ? 'selected' : '' }}>
                                            Hanya Potongan (Siswa Pilih Skema)</option>
                                    </select>
                                </div>
                            </div>

                            <div id="form-full-setup"
                                style="display: {{ @$dispensation['dispensation_mode'] == 'full_setup' ? 'block' : 'none' }}">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Nominal DP (Bisa 0)</label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="down_payment" id="down_payment"
                                            value="{{ @$dispensation['down_payment'] }}">
                                        <input type="text" class="form-control" id="down_payment_display"
                                            value="{{ @$dispensation['down_payment'] ? number_format(@$dispensation['down_payment'], 0, ',', '.') : '' }}"
                                            placeholder="0">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Jumlah Cicilan (Tenor)</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" name="tenor" id="tenor"
                                            value="{{ @$dispensation['tenor'] }}" placeholder="0">
                                    </div>
                                </div>

                                <div class="form-group" id="simulation-container" style="display: none;">
                                    <label class="col-sm-2 control-label">Simulasi Cicilan</label>
                                    <div class="col-sm-10">
                                        <div class="alert alert-info" id="simulation-result" style="margin-bottom: 0;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-success">{{ $status_btn }}</button>
                                    <a href="{{ route('admin.dispensation.index') }}" class="btn btn-default">Batal</a>
                                </div>
                            </div>
                            <!-- /bottom-wizard -->
                            @csrf
                        </form>
                    </div>
                </div> <!-- /widget-content -->
            </div>
            <!-- End Panel -->
        </div>
        <!-- End Row -->
    </div>

    <div id="show-image-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;
                    </button>
                    <h4 class="modal-title">Lampiran Dispensasi</h4>
                </div>
                <div class="modal-body">
                    <img class="header-image" id="lightbox-img" src="" width="500" height="500"
                        alt="Zoom">
                </div>
            </div>
        </div>
    </div>
    <!-- END CONTAINER -->
@endsection

@push('styles')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('css/plugin/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugin/bootstrap-select/bootstrap-select.css') }}" />
@endpush
@push('scripts')
    <script src="{{ asset('js/bootstrap-select/bootstrap-select.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.selectpicker').selectpicker({
                liveSearch: true,
                dropupAuto: false,
                title: "No Value"
            });
            calculateSimulation();
            initDispensationTypeLogic();
        });

        function isUangKegiatan() {
            let text = $('#dispensation_type option:selected').text().trim().toUpperCase();
            return text === 'UANG KEGIATAN';
        }

        function initDispensationTypeLogic() {
            if (isUangKegiatan()) {
                $('#is_discount_accepted_container').show();
                let isChecked = $('#is_discount_accepted').is(':checked');
                if (!isChecked) {
                    $('#total_final_fee_display').prop('readonly', true);
                } else {
                    $('#total_final_fee_display').prop('readonly', false);
                }
            } else {
                $('#is_discount_accepted_container').hide();
                $('#is_discount_accepted').prop('checked', false);
                $('#total_final_fee_display').prop('readonly', false);
            }
        }

        $(document).on('change', '#dispensation_type', function(e) {
            initDispensationTypeLogic();
            if (isUangKegiatan()) {
                if (!$('#is_discount_accepted').is(':checked')) {
                    let actualCost = parseInt($('#actual_cost').val() || 0, 10);
                    if (actualCost > 0) {
                        $('#total_final_fee').val(actualCost);
                        $('#total_final_fee_display').val(formatRupiah(actualCost));
                    }
                }
            }
        });

        $(document).on('change', '#is_discount_accepted', function(e) {
            if ($(this).is(':checked')) {
                $('#total_final_fee_display').prop('readonly', false);
            } else {
                $('#total_final_fee_display').prop('readonly', true);
                let actualCost = parseInt($('#actual_cost').val() || 0, 10);
                $('#total_final_fee').val(actualCost);
                $('#total_final_fee_display').val(actualCost ? formatRupiah(actualCost) : '');
                
                // Validate down payment with new total final fee
                let downPayment = parseInt($('#down_payment').val() || 0, 10);
                if (downPayment > actualCost) {
                    $('#down_payment').val(actualCost);
                    $('#down_payment_display').val(actualCost ? formatRupiah(actualCost) : '');
                }
                calculateSimulation();
            }
        });

        $(document).on('change', '#dispensation_mode', function(e) {
            var mode = $('#dispensation_mode').val();
            if (mode === 'full_setup') {
                $('#form-full-setup').show();
                calculateSimulation();
            } else {
                $('#form-full-setup').hide();
            }
        });

        $(document).on('change', '#unit_id', function(e) {
            e.preventDefault();
            var unit_id = $('#unit_id').val();
            var school_year = $('#school_year').val();
            var dispensation_type = $('#dispensation_type').val();
            fetchStudent(unit_id, school_year, dispensation_type)
        });

        $(document).on('change', '#ppdb_user_id', function(e) {
            e.preventDefault();
            var ppdb_user_id = $('#ppdb_user_id').val();
            var type = $('#dispensation_type').val();
            fetchAnualCost(ppdb_user_id, type)
        });

        $(document).on('click', '#show-attachment-btn', function(e) {
            let imageUrl = $(this).data('url');
            showImage(imageUrl);
        });

        $(document).on('keyup', '#total_final_fee_display', function(e) {
            let val = $(this).val().replace(/[^0-9]/g, '');

            if (val === '') {
                $('#total_final_fee').val('');
                $(this).val('');
                return;
            }

            let parsedVal = parseInt(val, 10);
            let actualCost = parseInt($('#actual_cost').val() || 0, 10);

            if ($('#actual_cost').val() !== '' && parsedVal > actualCost) {
                alert('Total Akhir tidak boleh lebih besar dari Biaya Sebenarnya.');
                parsedVal = actualCost;
            }

            $('#total_final_fee').val(parsedVal);
            $(this).val(formatRupiah(parsedVal));

            let downPayment = parseInt($('#down_payment').val() || 0, 10);
            if (downPayment > parsedVal) {
                $('#down_payment').val(parsedVal);
                $('#down_payment_display').val(formatRupiah(parsedVal));
            }
            calculateSimulation();
        });

        $(document).on('keyup', '#down_payment_display', function(e) {
            let val = $(this).val().replace(/[^0-9]/g, '');

            if (val === '') {
                $('#down_payment').val('');
                $(this).val('');
                return;
            }

            let parsedVal = parseInt(val, 10);
            let totalFinalFee = parseInt($('#total_final_fee').val() || 0, 10);

            if (parsedVal > totalFinalFee) {
                alert('Nominal DP tidak boleh lebih besar dari Total Akhir.');
                parsedVal = totalFinalFee;
            }

            $('#down_payment').val(parsedVal);
            $(this).val(formatRupiah(parsedVal));
            calculateSimulation();
        });

        $(document).on('keyup change', '#tenor', function(e) {
            calculateSimulation();
        });

        function calculateSimulation() {
            let mode = $('#dispensation_mode').val();
            let totalFinalFee = parseInt($('#total_final_fee').val() || 0, 10);
            let downPayment = parseInt($('#down_payment').val() || 0, 10);
            let tenor = parseInt($('#tenor').val() || 0, 10);

            if (mode === 'full_setup' && totalFinalFee > 0 && tenor > 0) {
                let remaining = totalFinalFee - downPayment;
                let installment = Math.round(remaining / tenor);

                let html = '<ul class="list-unstyled" style="margin-bottom: 0;">';
                html += '<li><strong>Total Akhir:</strong> Rp ' + formatRupiah(totalFinalFee) + '</li>';
                html += '<li><strong>DP:</strong> Rp ' + formatRupiah(downPayment) + '</li>';
                html += '<li><strong>Sisa Tagihan:</strong> Rp ' + formatRupiah(remaining) + '</li>';
                html +=
                    '<li style="margin-top: 10px; border-top: 1px dashed #bce8f1; padding-top: 10px;"><strong>Cicilan Per Bulan (' +
                    tenor + 'x):</strong> <span class="text-danger" style="font-weight: bold; font-size: 1.1em;">Rp ' +
                    formatRupiah(installment) + '</span> / bln</li>';
                html += '</ul>';

                $('#simulation-result').html(html);
                $('#simulation-container').slideDown();
            } else {
                $('#simulation-container').slideUp();
                $('#simulation-result').html('');
            }
        }

        function fetchStudent(unit_id, school_year, type) {
            $("#ppdb_user_id").empty();
            $("#ppdb_user_id").append('<option value="">Pilih Siswa</option>');

            $.get("{{ route('admin.dispensation.fetch-student-approved') }}", {
                unit_id: unit_id,
                school_year: school_year,
                type: type
            }, function(students) {
                var selectedStudent = '{{ @$arr_student ?? '' }}';
                var arrStudent = [];
                selectedStudent.split(',').forEach(function(v) {
                    arrStudent.push(v)
                });

                $.each(students, function(index, student) {
                    var element = '';
                    if (jQuery.inArray(index.toString(), arrStudent) >= 0) {
                        element = '<option value="' + index + '" selected >' + student + '</option>'
                    } else {
                        element = '<option value="' + index + '"  >' + student + '</option>'
                    }

                    $("#ppdb_user_id").append(element)
                })
                $("#ppdb_user_id").selectpicker("refresh")
            }, 'json')
        }

        function fetchAnualCost(ppdb_user_id, type) {
            if (!ppdb_user_id) {
                $('#actual_cost').val('');
                $('#actual_cost_display').val('');
                $('#actual_cost_info').hide().text('');
                $('#attachment-container').hide();
                $('#show-attachment-btn').data('url', '');
                return;
            }

            $.get("{{ route('admin.dispensation.fetch-anual-cost') }}", {
                ppdb_user_id: ppdb_user_id,
                type: type
            }, function(data) {
                if (data.status === 'success') {
                    let costValue = data.actual_cost.toString();
                    let parsedCost = parseFloat(costValue);
                    $('#actual_cost').val(parsedCost);
                    $('#actual_cost_display').val(formatRupiah(parsedCost));

                    if (data.message !== '') {
                        $('#actual_cost_info').text(data.message).show();
                    } else {
                        $('#actual_cost_info').hide().text('');
                    }

                    if (isUangKegiatan() && !$('#is_discount_accepted').is(':checked')) {
                        $('#total_final_fee').val(parsedCost);
                        $('#total_final_fee_display').val(formatRupiah(parsedCost));
                        
                        let downPayment = parseInt($('#down_payment').val() || 0, 10);
                        if (downPayment > parsedCost) {
                            $('#down_payment').val(parsedCost);
                            $('#down_payment_display').val(formatRupiah(parsedCost));
                        }
                        calculateSimulation();
                    }


                    if (data.attachment_url) {
                        $('#show-attachment-btn').data('url', data.attachment_url);
                        $('#attachment-container').show();
                    } else {
                        $('#attachment-container').hide();
                        $('#show-attachment-btn').data('url', '');
                    }
                } else {
                    $('#actual_cost').val('');
                    $('#actual_cost_display').val('');
                    $('#actual_cost_info').hide().text('');
                    $('#attachment-container').hide();
                    $('#show-attachment-btn').data('url', '');
                    alert(data.message);
                }
            }, 'json').fail(function() {
                $('#actual_cost').val('');
                $('#actual_cost_display').val('');
                $('#actual_cost_info').hide().text('');
                $('#attachment-container').hide();
                $('#show-attachment-btn').data('url', '');
                alert('Terjadi kesalahan pada server saat mengambil data biaya.');
            });
        }

        /**
         * Fungsi untuk memformat angka menjadi format ribuan standar Indonesia (id-ID)
         * @param {number} number Angka yang akan diformat
         */
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        function showImage(imageSrc) {
            $('#lightbox-img').attr("src", '');
            $('#show-image-modal').modal();
            $('#lightbox-img').attr("src", imageSrc)
        }
    </script>
@endpush
