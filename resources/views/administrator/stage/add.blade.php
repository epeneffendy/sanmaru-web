@extends('layouts.admin.main')
@section('content')
    @if(@$status=="edit")
        @php($action=route('admin.stage.update',array($stage['id'])))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.stage.insert'))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif

    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Setting Stage</h1>
        <ol class="breadcrumb">
            <li>PPDB</li>
            <li><a href="{{route('admin.stage.index')}}">Setting Stage</a></li>
            <li class="active">{{$status_header}}</li>
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
                        <h3>{{$status_header}} Setting Stage</h3>
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
                        <form role="form" method="POST" action="{{$action}}"  class="form-horizontal" enctype="multipart/form-data">
                            @if (@$status == 'Update')
                                @method('PATCH')
                            @endif
                            <input type="hidden" name="id" value="{{@$stage->id}}" />
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Name:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name" value="{{old('name', @$stage['name'])}}" placeholder="Input a name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="unit">Unit:</label>
                                <div class="col-sm-10">
                                    <select name="unit_id" id="unit_id" class="form-control selectpicker" data-style="btn-success">
                                        <option data-hidden="true"></option>
                                        @foreach($unitOption as $value => $label)
                                            <option value="{{ $value }}" {{ @$stage['unit_id'] == $value ? 'selected' : null }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="unit">Periode:</label>
                                <div class="col-sm-10">
                                    <select name="periode" id="periode" data-style="btn-primary" class="form-control selectpicker">
                                        <option data-hidden="true"></option>
                                        @foreach($periodOption as $value => $label)
                                            <option value="{{ $value }}" {{ @$stage['periode'] == $value ? 'selected' : null }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Fitur:</label>
                                <div class="col-sm-10">
                                    <select name="opening_feature" id="opening_feature" class="form-control">
                                        <option value="">Tanpa fitur</option>
                                        <option value="is_opening_development_feature" {{ (old('opening_feature') == 'is_opening_development_feature') || @$stage['is_opening_development_feature'] ? 'selected' : null }}>Fitur uang pengembangan</option>
                                        <option value="is_opening_shop_feature" {{ (old('opening_feature') == 'is_opening_shop_feature') || @$stage['is_opening_shop_feature'] ? 'selected' : null }}>Fitur shop</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Status:</label>
                                <div class="col-sm-10">
                                    <div class="radio radio-success radio-inline">
                                        <input type="radio" id="status_active" value="true" name="active" {{ old('active', @$stage['active'] ? 'checked' : '') }}>
                                        <label for="status_active"> Active </label>
                                    </div>
                                    <div class="radio radio-danger radio-inline">
                                        <input type="radio" id="status_inactive" value="false" name="active" {{ old('active', @!$stage['active'] ? 'checked' : '') }}>
                                        <label for="status_inactive"> Inactive</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="information">Keterangan:</label>
                                <div class="col-sm-10">
                                    <textarea name="information" rows="5" class="form-control" placeholder="Keterangan">{{old('information', @$stage['information'])}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-default">{{$status}}</button>
                                </div>
                            </div>
                            <!-- /bottom-wizard -->
                            @csrf
                        </form>
                    </div>
                    @if($status=="Update" && !$stage->is_opening_development_feature)
                    <ul class="nav nav-tabs" id="selection">
                        <li class="active"><a href="#seleksi">Seleksi Pendaftar</a></li>
                        <li><a href="#manual">Import</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="seleksi" class="tab-pane fade in active">
                            <div class="widget-header">
                                <h3>Seleksi Pendaftar</h3>
                            </div> <!-- /widget-header -->
                            <div class="widget-content seleksi-wrapper">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-search   "></i></div>
                                            <input type="text" name="pencarian" class="form-control" placeholder="Cari ...">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-md-offset-2 pull-right total">
                                    </div>
                                </div>
                                <table class="table table-responsive table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="text-center" style="width: 50px;">No</th>
                                            <th rowspan="2" class="text-center">Nama</th>
                                            <th colspan="4" class="text-center" style="width: 300px; ">Status</th>
                                            <th rowspan="2" class="text-center" style="width: 250px;">Keterangan</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center" style="width: 75px">-</th>
                                            <th class="text-center" style="width: 75px">Pending</th>
                                            <th class="text-center" style="width: 75px">Tdk Lolos</th>
                                            <th class="text-center" style="width: 75px">Lolos</th>
                                        </tr>
                                    </thead>
                                    <tbody style="height: 300px; overflow-y: auto;">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id="manual" class="tab-pane fade">
                            <div class="widget-header">
                                <h3>Pengumuman Privasi</h3>
                            </div> <!-- /widget-header -->
                            <div class="widget-content">
                                <form action="{{ route('admin.stage.import-users', ['stage' => @$stage['id']]) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <input type="file" name="file" class="form-control form-control-radius" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                                </div>
                                <div class="form-group">
                                    <div class="checkbox checkbox-circle checkbox-info">
                                        <input id="overwrite" name="type" type="checkbox" checked="true" value="overwrite" />
                                        <label for="overwrite">Overwrite</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    download template dengan <a href="{{ route('admin.stage.export-users') }}" target="_blank" download>klik disini</a>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success btn-sm btn-insert">import</button>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div> <!-- /widget-content -->
            </div>
            <!-- End Panel -->
        </div>
        <!-- End Row -->
    </div>
    <!-- END CONTAINER -->
@endsection
@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugin/jquery-transfer/icon_font.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugin/sweet-alert/sweet-alert.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugin/bootstrap-select/bootstrap-select.css') }}" />
    <style>
    table {
        display: flex;
        flex-flow: column;
        height: 100%;
        width: 100%;
    }
    table thead {
        /* head takes the height it requires,
        and it's not scaled when table is resized */
        flex: 0 0 auto;
        width: calc(100% - 0.9em);
    }
    table thead tr th,
    table tbody tr td {
        padding: 3px !important;
    }
    table tbody {
        /* body takes all the remaining available space */
        flex: 1 1 auto;
        display: block;
        overflow-y: scroll;
    }
    table tbody tr {
        width: 100%;
    }
    table thead,
    table tbody tr {
        display: table;
        table-layout: fixed;
    }

    #selection {
        margin-top: 80px;
    }
    </style>
@endpush
@push('scripts')
    <script src="{{asset('js/summernote/summernote.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-select/bootstrap-select.js')}}"></script>
    <script src="{{asset('js/sweet-alert/sweet-alert.min.js')}}"></script>
    <script>
        var transfer;
        var nullTotal = pendingTotal = notpassedTotal = passedTotal = 0;
        $(document).ready(function () {
            $('.selectpicker').selectpicker({
                liveSearch: true,
                dropupAuto: false,
                title: "No Value"
            });

            $(".nav-tabs a").click(function(){
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
                $.get('{{ route('admin.stage.get-periods') }}/'+$(this).val(), function(data, status) {
                    $('#periode').html(`<option data-hidden="true"></option>`);
                    if (data.length) {
                        $.each(data, function(index, value) {
                            $('#periode').append(`
                                <option value="${value.id}">${value.name}</option>
                            `);
                        });
                        $('#periode').selectpicker('refresh');
                    }
                });
            }
        });

        $(document).on('click', 'tbody .radio label', function() {
            let id = $(this).parent().parent().parent().data('id');
            if ($('input[name="status['+id+']"]:checked').val() === '2') {
                pendingTotal--;
            } else if ($('input[name="status['+id+']"]:checked').val() === '1') {
                passedTotal--;
            } else if ($('input[name="status['+id+']"]:checked').val() === '0') {
                notpassedTotal--;
            } else {
                nullTotal--;
            }
        });

        $(document).on('click', 'tbody input[type=radio]', function() {
            let val = $(this).val();
            if (val === '1') {
                $(this).parent().parent().parent().find('textarea').removeAttr('readonly');
                passedTotal++;
            } else if (val === '2') {
                $(this).parent().parent().parent().find('textarea').attr('readonly', 'true');
                pendingTotal++;
            } else if (val === '0') {
                $(this).parent().parent().parent().find('textarea').attr('readonly', 'true');
                notpassedTotal++;
            } else {
                $(this).parent().parent().parent().find('textarea').attr('readonly', 'true');
                nullTotal++;
            }

            showTotal();
        })

        $(document).on('input', 'input[name=pencarian]', function() {
            let val = $(this).val();
            if (!val) {
                $('tr').show(); return;
            }
            $("tbody tr[data-name*='"+val+"' i]").show();
            $("tbody tr:not([data-name*='"+val+"' i])").hide();
        });

        $(document).on('click', '.btn-konfirmasi', function(e) {
            e.preventDefault();
            $(this).attr('readonly', 'true');
            swal({
                title: "PERHATIAN",
                text: "Pastikan kembali siswa sudah lolos di tahap sebelumnya",
                icon: "warning",
                buttons: [
                    'tidak!',
                    'Ya, Saya yakin!'
                ],
                dangerMode: false,
            }).then(function(isConfirm) {
                if (isConfirm) {
                    let statuses = {};
                    let notes = {};
                    let passed_all = false;

                    if ($('#passed_all').is(':checked')) {
                        passed_all = true;
                    }

                    $('input[name^="status"]:checked').each(function() {
                        statuses[$(this).data('id')] = $(this).val();
                    });
                    $('textarea[name^="note"').each(function() {
                        notes[$(this).data('id')] = $(this).val();
                    });

                    $.post('{{ route('admin.stage.post-users', ['stage' => @$stage['id']]) }}', {
                        statuses: statuses,
                        notes: notes,
                        passed_all: passed_all,

                        _token: "{{ csrf_token() }}"
                    }, function (data, status) {
                        if (data.status == 'success') {
                            swal({
                                title: 'Sukses!',
                                text: 'data Pendaftar berhasil disimpan!',
                                icon: 'success'
                            });
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
                $.get('{{ route('admin.stage.users-json', ['stage' => @$stage['id']]) }}/'+ unit + '/' + period, function (data, status) {
                    if (data.length) {
                        $.each(data, function(index, value) {
                            nullChecked = pendingChecked = notpassedChecked = passedChecked = textareaReadonly = '';

                            if (value.note == null) {
                                value.note = '';
                            }

                            let no = index + 1;
                            if (value.passed === 1) {
                                passedTotal++;
                                passedChecked = 'checked="true"';
                            } else if (value.passed === 0) {
                                notpassedTotal++;
                                notpassedChecked = 'checked="true"';
                                textareaReadonly = 'readonly="true"';
                            } else if (value.passed === 2) {
                                pendingTotal++;
                                pendingChecked = 'checked="true"';
                                textareaReadonly = 'readonly="true"';
                            } else {
                                nullTotal++;
                                nullChecked = 'checked="true"';
                                textareaReadonly = 'readonly="true"';
                            }

                            $('.seleksi-wrapper tbody').append(`
                                <tr data-name="[${value.register_number}] ${value.name}" data-id="${value.id}">
                                    <td style="width: 50px;" class="text-center">${no}</td>
                                    <td><b>[${value.register_number}]</b> ${value.name}</td>
                                    <td style="width: 75px;" class="text-center"><div class="radio"><input id="status_null_${value.id}" ${nullChecked} type="radio" data-id="${value.id}" name="status[${value.id}]" value="" /><label for="status_null_${value.id}"></label></div></td>
                                    <td style="width: 75px;" class="text-center"><div class="radio radio-warning"><input id="status_pending_${value.id}" ${pendingChecked} data-id="${value.id}" type="radio" name="status[${value.id}]" value="2" /><label for="status_pending_${value.id}"></label></div></td>
                                    <td style="width: 75px;" class="text-center"><div class="radio radio-danger"><input id="status_notpassed_${value.id}" ${notpassedChecked} type="radio" data-id="${value.id}" name="status[${value.id}]" value="0" /><label for="status_notpassed_${value.id}"></label></div></td>
                                    <td style="width: 75px;" class="text-center"><div class="radio radio-success"><input id="status_passed_${value.id}" ${passedChecked} type="radio" name="status[${value.id}]" data-id="${value.id}" value="1" /><label for="status_passed_${value.id}"></label></div></td>
                                    <td style="width: 250px;"><textarea class="form-control" ${textareaReadonly} name="note[${value.id}]" data-id="${value.id}">${value.note.replace('\\n', '&#013;')}</textarea></td>
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

                        $('.seleksi-wrapper').append('<button class="btn btn-konfirmasi btn-success"><i class="fa fa-save"></i> simpan</button>');
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
            let total = nullTotal+pendingTotal+notpassedTotal+passedTotal;
            $('.total').html(`Total [ -: ${nullTotal}, pending: ${pendingTotal}, tdk lolos: ${notpassedTotal}, lolos: ${passedTotal}] = ${total} pendaftar`);
        }
    </script>
@endpush
