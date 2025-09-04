@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Cek Pembayaran</h1>
        <ol class="breadcrumb">
            <li>Master</li>
            <li><a href="{{ route('admin.payment.index') }}">Cek Pembayaran</a></li>
            <li class="active">History Import</li>
        </ol>
    </div>
    <!-- End Page Header -->

    <!-- START CONTAINER -->
    <div class="container-padding">
        <!-- Start Row -->
        <div class="row">
            <!-- Start Panel -->
            <div class="col-md-12 pading-b-20 margin-b-20">
                <div class="panel panel-default">
                    <div class="panel-title">
                        History Import - Cek Pembayaran Pendaftaran
                    </div>
                    <div class="panel-body table-responsive">
                        @if (session('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif
                        @if (session('errors'))
                            <div class="alert alert-danger">
                                {!! session('errors')->first() !!}
                            </div>
                        @endif
                        <div class="panel panel-primary">
                            <div class="panel-heading">Filter</div>
                            <div class="panel-body">
                                <form role="form" autocomplete="off" method="GET" action="{{ route('admin.payment.history') }}">
                                    <input autocomplete="false" name="hidden" disabled type="text" style="display:none;">
                                    <div class="form-group col-md-3">
                                        <input type="text" name="username" placeholder="Search" value="{{ @$params['username'] }}" class="form-control input-sm" />
                                    </div>
                                    <div class="form-group col-md-3">
                                        <select name="payment_method" class="form-control input-sm">
                                            <option value="0">-- semua metode pembayaran --</option>
                                            <option value="cimb" {{ @$params['payment_method'] == 'cimb' ? 'selected' : NULL }} >CIMB Niaga</option>
                                            <option value="mandiri" {{ @$params['payment_method'] == 'mandiri' ? 'selected' : NULL }}>Mandiri</option>
                                        </select>
                                    </div>
                                    <a href="{{ route('admin.payment.history') }}" class="pull-right btn btn-sm btn-warning">
                                        <i class="fa fa-refresh"></i> clear
                                    </a>
                                    <button type="submit" class="pull-right btn btn-sm btn-success">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </form>
                            </div>
                        </div>
                        <table class="table table-responsive table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>File</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>User</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php ($number = ($importJobs->currentPage() - 1) * $importJobs->perPage())
                                @foreach ($importJobs as $key => $importJob)
                                    <tr>
                                        <td>{{ ++$number }}</td>
                                        <td>
                                            {{ $importJob->filename }}<br>
                                            &nbsp;&nbsp;<small class="text-success">success: {{ $importJob->total_success }}</small>
                                            &nbsp;&nbsp;<small class="text-danger">errors: {{ $importJob->total_errors }}</small>
                                        </td>
                                        <td>{{ $importJob->created_at->format('d/m/Y') }}</td>
                                        <td>{{ $importJob->created_at->format('H:i') }}</td>
                                        <td>{{ $importJob->user->username }}</td>
                                        <td>{{ $importJob->status }}</td>
                                        <td>
                                            <a href="{{ route('admin.payment.detail-history', ['importJobId' => $importJob->id]) }}">details</a><br>
                                            <a href="{{ route('show_import', ['file' => $importJob->path]) }}" download target="_blank">download disini</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $importJobs->appends(request()->except('page'))->links() }}   
                    </div>
                </div>
            </div>
            <!-- End Panel -->
        </div>
        <!-- End Row -->
    </div>
    <!-- END CONTAINER -->

@endsection
@push('styles')
@endpush
@push('scripts')
@endpush
