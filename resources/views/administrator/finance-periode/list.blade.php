@extends('layouts.admin.main')
@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Periode Pembayaran</h1>
        <ol class="breadcrumb">
            <li>Keuangan</li>
            <li class="active">Periode Pembayaran</li>
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
                        Periode Pembayaran
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
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif



                        <div class="fixed-table-head period">
                            <table id="datatables-uniform-deadline" class="table display">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Pembayaran</th>
                                        <th class="text-center">Unit</th>
                                        <th class="text-center">Periode Awal</th>
                                        <th class="text-center">Periode Akhir</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $item)
                                        <tr>
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td class="text-center">{{ ($item->type == 'activity') ? 'Uang Kegiatan' : 'Uang Pengembangan' }}</td>
                                            <td class="text-center">{{ $item->unit->name }}</td>
                                            <td class="text-center">{{ $item->start_date }}</td>
                                            <td class="text-center">{{ $item->end_date }}</td>
                                            <td class="text-center">{{ ($item->status == 'active') ? 'Aktif' : 'Tidak Aktif' }}</td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-xs btn-primary btn-edit" 
                                                    data-id="{{ $item->id }}" 
                                                    data-start="{{ $item->start_date }}" 
                                                    data-end="{{ $item->end_date }}" 
                                                    data-status="{{ $item->status }}"
                                                    data-unit="{{ $item->unit->name }}"
                                                    data-type="{{ ($item->type) ? 'Uang Kegiatan' : 'Uang Pengembangan' }}">
                                                    <i class="fa fa-pencil"></i> Edit
                                                </button>
                                            </td>
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

    
    <div id="edit-periode-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalEditPeriodeLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.finance-periode.update') }}" method="POST" class="form-horizontal">
                    @csrf
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="modalEditPeriodeLabel" style="font-weight: 600;"><i class="fa fa-pencil"></i> Edit Periode Pembayaran</h4>
                    </div>
                    <div class="modal-body" style="background-color: #f9f9f9;">
                        <div class="panel panel-default" style="margin-bottom: 0; border-radius: 5px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                            <div class="panel-heading" style="background-color: #fff; border-bottom: 1px solid #f0f0f0;">
                                <h3 class="panel-title" style="font-weight: 600; font-size: 14px;">
                                    <i class="fa fa-money text-success" style="margin-right: 5px;"></i> <span id="edit_title"></span>
                                </h3>
                            </div>
                            <div class="panel-body">
                                <div class="form-group" style="margin-bottom: 10px;">
                                    <label class="control-label col-sm-4" style="font-weight: 500;">Periode Awal</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar-o"></i></span>
                                            <input type="date" class="form-control input-sm" id="edit_start_date" name="start_date" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" style="margin-bottom: 10px;">
                                    <label class="control-label col-sm-4" style="font-weight: 500;">Periode Akhir</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar-o"></i></span>
                                            <input type="date" class="form-control input-sm" id="edit_end_date" name="end_date" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="control-label col-sm-4" style="font-weight: 500;">Status</label>
                                    <div class="col-sm-8">
                                        <select name="status" id="edit_status" class="form-control input-sm">
                                            <option value="active">Aktif</option>
                                            <option value="inactive">Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="margin-top: 0;">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fa fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap.min.css') }}">
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

        $(document).on('click', '.btn-edit', function() {
            var id = $(this).data('id');
            var start = $(this).data('start');
            var end = $(this).data('end');
            var status = $(this).data('status');
            var unit = $(this).data('unit');
            var type = $(this).data('type');

            $('#edit_id').val(id);
            $('#edit_start_date').val(start);
            $('#edit_end_date').val(end);
            $('#edit_status').val(status);
            $('#edit_title').text(type + ' - ' + unit);

            $('#edit-periode-modal').modal('show');
        });
    </script>
@endpush
