@extends('layouts.admin.main')
@section('content')
    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Deploy</h1>
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
                        Deployment
                    </div>
                    <div class="panel-body table-responsive">
                        @if ($processReturn)
                            <div class="alert alert-{{ $processReturn['status'] == 'success' ? 'success' : 'danger' }}">
                                {!! $processReturn['message'] !!}
                            </div>
                        @endif

                        <div class="button-collection">
                            <a href="{{ route('admin.deploy.index', ['wet-run']) }}" class="btn btn-success btn-sm"><i class="fa fa-cloud-upload"></i> Deploy Sanmaru {{ \App\Helpers\Helper::isProduction() ? 'Production' : 'Staging' }} </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Panel -->
        </div>
        <!-- End Row -->
    </div>
    <!-- END CONTAINER -->
@endsection
