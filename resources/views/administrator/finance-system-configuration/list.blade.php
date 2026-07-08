@extends('layouts.admin.main')
@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Setup Tahun Ajaran & Aturan Sistem</h1>
        <ol class="breadcrumb">
            <li>Keuangan</li>
            <li class="active">Setup Tahun Ajaran & Aturan Sistem</li>
        </ol>
    </div>
    <!-- End Page Header -->

    <!-- START CONTAINER -->
    <div class="container-padding">
        <!-- Start Row -->
        <div class="row">

            <!-- Start Panel -->
            <div class="col-md-12">
                <div class="panel panel-default table-responsive">
                    <div class="panel-title" style="margin-bottom: 1em">
                        Setup Tahun Ajaran & Aturan Sistem
                        <div class="btn-group padding-t-10 pull-right">
                            <button id="modal-periode" class="btn btn-sm btn-primary">
                                <i class="fa fa-calendar"></i> Periode Pembayaran
                            </button>
                        </div>
                    </div>

                    <div class="panel-body">
                        @if (session('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif
                        @if (session('errors'))
                            <div class="alert alert-danger">
                                {!! session('errors') !!}
                            </div>
                        @endif



                        <div class="fixed-table-head period">
                            <table id="datatables-uniform-deadline" class="table display">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">DP Minimum (%)</th>
                                        <th class="text-center">DP Kelipatan (%)</th>
                                        <th class="text-center">DP Rekomendasi (%)</th>
                                        <th class="text-center">Max Cicilan Absolut</th>
                                        <th class="text-center">Tanggal Berlaku</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($configurations as $key => $item)
                                        <tr>
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td class="text-center">{{ $item->min_down_payment }} %</td>
                                            <td class="text-center">{{ $item->down_payment_multiple }} %</td>
                                            <td class="text-center">{{ $item->recommended_down_payment }} %</td>
                                            <td class="text-center">{{ $item->max_absolute_installment }} kali cicilan</td>
                                            <td class="text-center">{{ $item->effective_date }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="btn-group padding-t-10 pull-right">
                            <a href="{{ route('admin.system-configuration.add') }}" class="btn btn-sm btn-success">
                                <i class="fa fa-plus"></i> Tambah Data
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="show-periode-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalPeriodeLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="modalPeriodeLabel" style="font-weight: 600;"><i class="fa fa-calendar"></i> Pengaturan Periode Pembayaran</h4>
                </div>
                <div class="modal-body" style="background-color: #f9f9f9;">
                    <form class="form-horizontal">
                        @foreach($periode as $item)
                            <div class="panel panel-default" style="margin-bottom: 15px; border-radius: 5px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                                <div class="panel-heading" style="background-color: #fff; border-bottom: 1px solid #f0f0f0;">
                                    <h3 class="panel-title" style="font-weight: 600; font-size: 14px;">
                                        <i class="fa fa-money text-success" style="margin-right: 5px;"></i> Pembayaran {{ ($item->type == 'activity') ? 'Uang Kegiatan' : 'Uang Pengembangan' }}
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group" style="margin-bottom: 10px;">
                                        <label class="control-label col-sm-4" for="date_start_{{$item->type}}" style="font-weight: 500;">Periode Awal</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-calendar-o"></i></span>
                                                <input type="date" class="form-control input-sm" id="date_start_{{$item->type}}"
                                                       name="date_start_{{$item->type}}" value="{{$item->start_date}}" required {{ ($item->status == 'inactive') ? 'disabled' : '' }}>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group" style="margin-bottom: 10px;">
                                        <label class="control-label col-sm-4" for="date_end_{{$item->type}}" style="font-weight: 500;">Periode Akhir</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-calendar-o"></i></span>
                                                <input type="date" class="form-control input-sm" id="date_end_{{$item->type}}"
                                                       name="date_end_{{$item->type}}" value="{{$item->end_date}}" required {{ ($item->status == 'inactive') ? 'disabled' : '' }}>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group" style="margin-bottom: 0;">
                                        <div class="col-sm-8 col-sm-offset-4">
                                            <div class="checkbox checkbox-success">
                                                <input type="checkbox" id="status_{{$item->type}}" {{ ($item->status == 'active') ? 'checked' : '' }}> 
                                                <label for="status_{{$item->type}}" style="font-weight: 600; cursor: pointer;">
                                                    Status Aktif
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </form>
                </div>
                <div class="modal-footer" style="margin-top: 0;">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
                    <button id="setting_periode" type="button" class="btn btn-success btn-sm setting_periode">
                        <i class="fa fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/plugin/datatables/datatables.css') }}">
    <style>
        .button-collection {
            margin-bottom: 5px;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{ asset('js/datatables/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#datatables-uniform-deadline').DataTable();
        });

        $(document).on('click', '#modal-periode', function (e) {
            // Inisialisasi state awal saat modal dibuka
            $('input[id^="status_"]').each(function() {
                let type = $(this).attr('id').replace('status_', '');
                if ($(this).is(':checked')) {
                    $('#date_start_' + type).prop('disabled', false);
                    $('#date_end_' + type).prop('disabled', false);
                } else {
                    $('#date_start_' + type).prop('disabled', true);
                    $('#date_end_' + type).prop('disabled', true);
                }
            });
            $('#show-periode-modal').modal();
        });

        // Trigger saat checkbox di klik/ubah
        $(document).on('change', 'input[id^="status_"]', function (e) {
            let type = $(this).attr('id').replace('status_', '');
            if ($(this).is(':checked')) {
                $('#date_start_' + type).prop('disabled', false);
                $('#date_end_' + type).prop('disabled', false);
            } else {
                $('#date_start_' + type).prop('disabled', true);
                $('#date_end_' + type).prop('disabled', true);
            }
        });

        $(document).on('click', '#setting_periode', function(e) {
            e.preventDefault();
            let data = [];
            let isValid = true;
            
            $('input[id^="status_"]').each(function() {
                let type = $(this).attr('id').replace('status_', '');
                let isChecked = $(this).is(':checked');
                let date_start = $('#date_start_' + type).val();
                let date_end = $('#date_end_' + type).val();
                
                if (isChecked && (!date_start || !date_end)) {
                    isValid = false;
                }
                
                data.push({
                    "type": type,
                    "date_start": date_start,
                    "date_end": date_end,
                    "status": isChecked ? 'active' : 'inactive'
                });
            });

            if (!isValid) {
                alert('Pastikan tanggal mulai dan tanggal akhir telah diisi untuk periode yang aktif.');
                return;
            }

            $.ajax({
                url: '{{ route('admin.system-configuration.financePeriode') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    periode: data
                },
                success: function(response) {
                    if (response.success) {
                        $('#show-periode-modal').modal('hide');
                        alert(response.message);
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan saat memproses data.');
                }
            });
        });

    </script>
@endpush
