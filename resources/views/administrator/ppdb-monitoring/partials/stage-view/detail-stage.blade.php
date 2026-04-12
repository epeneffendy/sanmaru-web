@extends('layouts.admin.main')
@section('content')
    @push('styles')
        <style>
            /* Card Container */
            .selection-card {
                background: #fff;
                border-radius: 12px;
                box-shadow: 0 5px 25px rgba(0,0,0,0.05);
                overflow: hidden;
                margin-top: 20px;
            }

            /* Tab Navigation */
            .selection-header {
                background: #f8f9fa;
                padding: 15px 25px 0 25px;
                border-bottom: 1px solid #edf2f7;
            }

            .custom-pills .nav-link, .custom-pills li a {
                border-radius: 8px 8px 0 0 !important;
                padding: 12px 25px;
                font-weight: 600;
                color: #718096;
                border: none !important;
                transition: all 0.3s;
            }

            .custom-pills li.active a {
                background: #fff !important;
                color: #26703B !important; /* Hijau Utama */
                box-shadow: 0 -3px 0 #26703B inset;
            }

            .selection-body {
                padding: 30px;
            }

            /* Search Box */
            .search-wrapper {
                position: relative;
            }

            .search-wrapper i {
                position: absolute;
                left: 15px;
                top: 50%;
                transform: translateY(-50%);
                color: #a0aec0;
            }

            .search-wrapper .form-control {
                padding-left: 45px;
                height: 48px;
                border-radius: 10px;
                border: 1px solid #e2e8f0;
                background: #fdfdfd;
            }

            /* Total Badge */
            .total-badge {
                display: inline-block;
                padding: 10px 20px;
                background: #f0fdf4;
                border: 1px solid #dcfce7;
                border-radius: 10px;
                color: #166534;
                font-size: 13px;
                font-weight: 600;
            }

            /* Table Customization */
            .table-custom thead th {
                background: #f8fafc;
                color: #4a5568;
                font-size: 11px;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                padding: 15px;
                border-top: none;
            }

            .table-custom tbody tr {
                transition: background 0.2s;
            }

            .table-custom tbody td {
                padding: 18px 15px;
                vertical-align: middle;
            }

            /* Modern Radio/Status Styling */
            .radio-custom input[type="radio"] {
                width: 20px;
                height: 20px;
                cursor: pointer;
            }

            /* Import Section */
            .import-container {
                border: 2px dashed #e2e8f0;
                border-radius: 20px;
                background: #f8fafc;
            }

            .import-icon-box {
                font-size: 48px;
            }

            /* Button Modern */
            .btn-success {
                background: #26703B !important;
                border: none;
                border-radius: 10px;
                padding: 10px 25px;
                font-weight: 600;
                transition: transform 0.2s;
            }

            .btn-success:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(38, 112, 59, 0.3);
            }
        </style>
    @endpush
    <div class="page-header">
        <h1 class="title">Monitoring PPDB</h1>
        <ol class="breadcrumb">
            <li>PPDB</li>
            <li>{{isset($period) ? $period->name : ''}}</li>
            @if($type == 'administration')
                <li class="active">Seleksi Administrasi</li>
            @else
                <li class="active">Seleksi Tahap</li>
            @endif

        </ol>
    </div>

    <div class="container-padding">

        <div class="panel panel-default">
            <div class="panel-title">
                Seleksi Administrasi
            </div>

            <div class="widget-content">
                <div class="form-horizontal">

                    <div class="card">
                        <div class="card-body">
                            <table width="100%">
                                <tr>
                                    <td style="font-weight: bold">{{$period->name}}</td>
                                </tr>
                                <tr>
                                    <td>Periode : {{ \Carbon\Carbon::parse($period->start_date)->format('d M Y') }}
                                        - {{ \Carbon\Carbon::parse($period->start_end)->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <td>Unit : {{$period->unit->name}} </td>
                                </tr>
                                <tr>
                                    <td>Tahap : {{ ($type == 'administration') ? "Administrasi" : $stage->name }}</td>
                                </tr>
                            </table>
                            </br>
                            <div class="row">
                                @if(($type == 'administration') || ($type == 'development-statement'))
                                    @include('administrator.ppdb-monitoring.partials.stage-view.administration')
                                @else
                                    @include('administrator.ppdb-monitoring.partials.stage-view.stage')
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
