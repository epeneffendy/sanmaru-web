@extends('layouts.admin.main')
@section('content')
    @push('styles')
        <style>
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
        </style>
    @endpush
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Setup Tahun Ajaran & Aturan Sistem</h1>
        <ol class="breadcrumb">
            <li>SHOP</li>
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
                    <div class="panel-title">
                        Setup Tahun Ajaran & Aturan Sistem
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
                                        <th class="text-center">Nama Siswa</th>
                                        <th class="text-center">Unit</th>
                                        <th class="text-center">Dispensation Type</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @php($no = 1)
                                    @foreach ($data as $key => $dispensation)
                                        <tr>
                                            <td class="text-center">{{ $no++ }}</td>
                                            <td class="text-center">{{ $dispensation->ppdb->name }}</td>
                                            <td class="text-center">{{ $dispensation->ppdb->unit->name ?? '-' }}</td>
                                            <td class="text-center">{{ $dispensation->dispensation_type }}</td>
                                            <td class="text-center">
                                                @if($dispensation->status == 'waiting')
                                                    <label class="label label-warning">Menunggu</label>
                                                @elseif($dispensation->status == 'approved')
                                                    <label class="label label-success">Disetujui Oleh Admin</label>
                                                @elseif($dispensation->status == 'confirmed')
                                                    <label class="label label-info">Sudah Diajukan Dispensasi</label>
                                                @elseif($dispensation->status == 'submitted')
                                                    <label class="label label-primary">Dispensasi Telah Diterima Oleh Siswa</label>
                                                @elseif($dispensation->status == 'rejected')
                                                    <label class="label label-danger">Dispensasi Ditolak</label>
                                                @else
                                                    <span class="badge badge-modern badge-soft-secondary">{{ ucfirst($dispensation->status) }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.dispensation-request.show', ['id' => $dispensation->id]) }}" class="btn btn-sm btn-info">Detail</a>
                                                {{-- <a href="{{ route('admin.dispensation-request.update', ['id' => $dispensation->id]) }}" class="btn btn-sm btn-primary">Edit</a> --}}
                                            </td>
                                        </tr>

                                       
                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                        <div class="btn-group padding-t-10 pull-right">
                            <a href="{{ route('admin.dispensation-request.add') }}" class="btn btn-sm btn-success">
                                <i class="fa fa-plus"></i> Tambah Data
                            </a>
                        </div>

                    </div>
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
    </script>
@endpush
