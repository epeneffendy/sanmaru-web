@extends('layouts.admin.main')
@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">History Stock</h1>
        <ol class="breadcrumb">
            <li>Shop</li>
            <li><a href="{{ route('admin.product.index') }}">Product</a></li>
            <li class="active">History Stock</li>
        </ol>
    </div>
    <!-- End Page Header -->

    <!-- START CONTAINER -->
    <div class="container-padding">
        <!-- Start Row -->
        <div class="row">
            <!-- Start Panel -->
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-title">
                        History Stock
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
                                <form role="form" autocomplete="off" method="GET" action="{{ route('admin.product.history-stock') }}">
                                    <input autocomplete="false" name="hidden" disabled type="text" style="display:none;">
                                    <div class="form-group col-md-3">
                                        <input type="text" name="username" placeholder="Username" value="{{ @$params['username'] }}" class="form-control input-sm" />
                                    </div>
                                    <div class="form-group col-md-3">
                                        <select name="stock" class="form-control input-sm">
                                            <option value="0">== Semua Stok ==</option>
                                            <option value="1" {{ @$params['stock'] == "1" ? 'selected' : null }}>bertambah</option>
                                            <option value="2" {{ @$params['stock'] == "2" ? 'selected' : null }}>berkurang / terjual</option>
                                        </select>
                                    </div>
                                    <a href="{{ route('admin.product.history-stock') }}" class="pull-right btn btn-sm btn-warning">
                                        <i class="fa fa-refresh"></i> clear
                                    </a>
                                    <button type="submit" class="pull-right btn btn-sm btn-success">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </form>
                            </div>
                        </div>
                        <table id="datatables-history-stock" class="table display">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Model ID</th>
                                <th>Activity</th>
                                <th>Username</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $number = ($activityLogs->currentPage() - 1) * $activityLogs->perPage();
                            @endphp
                            @foreach($activityLogs as $activityLog)
                                <tr>
                                    <td>{{++$number}}</td>
                                    <td>{{ $activityLog->action }}</td>
                                    <td>{{ $activityLog->model_id }}</td>
                                    <td>{!! $activityLog->activity_description !!}</td>
                                    <td><b>{{@$activityLog->user->username}}</b></td>
                                    <td>{{ $activityLog->created_at }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $activityLogs->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            </div>
            <!-- End Panel -->
        </div>
        <!-- End Row -->
    </div>
    <!-- END CONTAINER -->

@endsection