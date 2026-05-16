@extends('layouts.admin.main')
@section('content')
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
                                        <th class="text-center">DP Minimum (%)</th>
                                        <th class="text-center">DP Kelipatan (%)</th>
                                        <th class="text-center">DP Rekomendasi (%)</th>
                                        <th class="text-center">Max Cicilan Absolut</th>
                                        <th class="text-center">Tanggal Berlaku</th>
                                        <th class="text-center">Action</th>
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
                                            <td class="text-center">
                                                @if ($item->status == 1)
                                                    <a href="{{ route('admin.uniform-deadline.edit', $item->id) }}"
                                                        class="btn btn-xs btn-default">
                                                        <icon class="icon-plus"><i class="fa fa-pencil"></i></icon>
                                                    </a>

                                                    <a href="{{ route('admin.uniform-deadline.delete', $item->id) }}"
                                                        class="btn btn-xs btn-danger"
                                                        onclick="return confirm('Are you sure you want to change it to inactive?');">
                                                        <icon class="icon-plus"><i class="fa fa-trash"></i></icon>
                                                    </a>
                                                @endif
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
