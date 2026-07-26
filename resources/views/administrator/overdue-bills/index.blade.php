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
        <h1 class="title">Tagihan Tertunda (Overdue)</h1>
        <ol class="breadcrumb">
            <li>Keuangan</li>
            <li class="active">Tagihan Tertunda</li>
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
                    <div class="panel-title d-flex justify-content-between align-items-center">
                        <span>Daftar Tagihan Jatuh Tempo</span>
                        @if(count($overdueBills) > 0)
                            <div class="pull-right">
                                <form action="{{ route('admin.overdue-bills.broadcast') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin melakukan broadcast email ke seluruh siswa yang menunggak?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="fa fa-envelope"></i> Broadcast Email Tagihan
                                    </button>
                                </form>
                            </div>
                        @endif
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
                            <table id="datatables-overdue" class="table display">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Nama Siswa</th>
                                        <th class="text-center">Unit</th>
                                        <th class="text-center">Tipe Tagihan</th>
                                        <th class="text-center">Keterangan</th>
                                        <th class="text-center">Jatuh Tempo</th>
                                        <th class="text-center">Tagihan</th>
                                        <th class="text-center">Sisa Tagihan</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($overdueBills as $bill)
                                        @php
                                            $dispensationType = $bill->dispensation->dispensation_type == 'development' ? 'Uang Pengembangan' : 'Uang Kegiatan';
                                            $description = $bill->installment_number == 0 ? 'DP' : 'Cicilan Ke-' . $bill->installment_number;
                                            $remainingBalance = $bill->nominal - $bill->amount_paid;
                                            
                                            $statusBadge = 'badge-soft-warning';
                                            if($bill->status == 'unpaid') $statusBadge = 'badge-soft-danger';
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $no++ }}</td>
                                            <td class="text-center">{{ $bill->dispensation->ppdb->name ?? '-' }}</td>
                                            <td class="text-center">{{ $bill->dispensation->ppdb->unit->name ?? '-' }}</td>
                                            <td class="text-center">{{ $dispensationType }}</td>
                                            <td class="text-center">{{ $description }}</td>
                                            <td class="text-center">
                                                <span class="text-danger font-weight-bold">
                                                    {{ \Carbon\Carbon::parse($bill->plan_date)->format('d M Y') }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                {{ number_format($bill->nominal, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center">
                                                {{ number_format($remainingBalance, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge-modern {{ $statusBadge }}" style="border-radius: 20px;">
                                                    {{ strtoupper($bill->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/plugin/datatables/datatables.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('js/datatables/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#datatables-overdue').DataTable({
                "pageLength": 10
            });
        });
    </script>
@endpush
