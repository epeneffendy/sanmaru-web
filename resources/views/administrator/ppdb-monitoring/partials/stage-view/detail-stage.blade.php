@extends('layouts.admin.main')
@section('content')
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
