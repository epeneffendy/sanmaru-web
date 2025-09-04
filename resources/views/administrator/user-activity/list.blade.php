@extends('layouts.admin.main')
@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Data User Activity</h1>
        <ol class="breadcrumb">
            <li>Master</li>
            <li class="active">User Activity</li>
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
                        Data User Activity
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
                                <form role="form" autocomplete="off" method="GET" action="{{ route('admin.user-activity.index') }}">
                                    <input autocomplete="false" name="hidden" disabled type="text" style="display:none;">
                                    <div class="form-group col-md-3">
                                        <input type="text" name="username" placeholder="Username" value="{{ @$params['username'] }}" class="form-control input-sm" />
                                    </div>
                                    <div class="form-group col-md-3">
                                        <select class="form-control input-sm" name="model_type">
                                            <option value="">-- ALL --</option>
                                            @foreach ($modelTypes as $modelType)
                                                <option value="{{ $modelType }}" {{ @$params['model_type'] == $modelType ? 'selected' : NULL }}>{{ $modelType }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <input type="text" name="model_id" placeholder="Model ID" value="{{ @$params['model_id'] }}" class="form-control input-sm" />
                                    </div>
                                    <a href="{{ route('admin.user-activity.index') }}" class="pull-right btn btn-sm btn-warning">
                                        <i class="fa fa-refresh"></i> clear
                                    </a>
                                    <button type="submit" class="pull-right btn btn-sm btn-success">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="fixed-table-head">
                            <table id="datatables-user-activity" class="table display">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Action</th>
                                    <th>Model</th>
                                    <th>Model ID</th>
                                    <th>Activity</th>
                                    <th>Username</th>
                                    <th>Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $number = ($data->currentPage() - 1) * $data->perPage();
                                @endphp
                                @foreach($data as $key => $value)
                                    <tr>
                                        <td>{{++$number}}</td>
                                        <td>{{ $value->action }}</td>
                                        <td>{{ $value->model_type }}</td>
                                        <td>{{ $value->model_id }}</td>
                                        <td>{!! $value->activity_description !!}</td>
                                        <td><b>{{$value->username}}</b></td>
                                        <td>{{ $value->created_at }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        {{ $data->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            </div>
            <!-- End Panel -->
        </div>
        <!-- End Row -->
    </div>
    <!-- END CONTAINER -->

@endsection