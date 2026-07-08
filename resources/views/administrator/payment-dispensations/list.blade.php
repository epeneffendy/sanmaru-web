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
        <h1 class="title">Kelola Dispensasi Pembayaran Siswa</h1>
        <ol class="breadcrumb">
            <li>Keuangan</li>
            <li class="active">Kelola Dispensasi Pembayaran Siswa</li>
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
                                        <th class="text-center">Tipe Dispensasi</th>
                                        <th class="text-center">Nominal</th>
                                        <th class="text-center">Nominal Setelah Potongan</th>
                                        <th class="text-center">Mode Dispensasi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @php($no = 1)
                                    @foreach ($dispensations as $key => $dispensation)
                                        <tr>
                                            <td class="text-center">{{ $no++ }}</td>
                                            <td class="text-center">{{ $dispensation['name'] }}</td>
                                            <td class="text-center">{{ $dispensation['unit_name'] }}</td>
                                            <td class="text-center">{{ $dispensation['dispensation_type'] }}</td>
                                            <td class="text-center">
                                                {{ number_format($dispensation['actual_cost'], 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                {{ number_format($dispensation['total_final_fee'], 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                {{ $dispensation['dispensation_mode'] }}<br>
                                                @if ($dispensation['dispensation'] == 'full_setup')
                                                    <span class="badge-modern badge-soft-success"
                                                        style="border-radius: 20px;">DP :
                                                        {{ number_format($dispensation['down_payment'], 0, ',', '.') }}</span>
                                                    <br>
                                                    <span class="badge-modern badge-soft-secondary"
                                                        style="border-radius: 20px;">
                                                        Tenor : {{ $dispensation['tenor'] }}x Cicilan</span>
                                                    <br>
                                                @endif
                                                <span class="badge-modern badge-soft-info" style="border-radius: 20px;">Sisa
                                                    Pembayaran :
                                                    {{ number_format($dispensation['remaining_balance'], 0, ',', '.') }}</span>

                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                        <div class="btn-group padding-t-10 pull-right">
                            <a href="{{ route('admin.dispensation.add') }}" class="btn btn-sm btn-success">
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
