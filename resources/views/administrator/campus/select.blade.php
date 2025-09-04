@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Kampus</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li class="active">Kampus</li>
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
                        Kampus
                    </div>
                    
                    <div class="panel-body">
                        <div class="col-md-12 mb-3">
                            <div class="row category-collection">
                        @forelse($campuses as $index => $campus)
                            @if($index % 4 == 0)
                            </div>
                            <div class="row category-collection">
                            @endif
                                <div class="col-xs-6 col-lg-3">
                                    
                                    <div class="panel panel-primary card">
                                        <a href="{{ route('admin.campus.unit.index', $campus['id']) }}">
                                            <div class="panel-title">
                                                {{ $campus['name'] }}
                                            </div>
                                            <div class="panel-body">
                                                <strong>Jumlah unit: </strong>{{ $campus->campus_units_count }}
                                            </div>
                                        </a>
                                    </div>     
                                    
                                </div>
                        @empty
                            No data
                        @endforelse
                            </div>
                        </div>
                        <div class="btn-group padding-t-10 pull-right">
                            <a href="{{ route('admin.campus.index') }}" class="btn btn-sm btn-default">
                                <i class="fa fa-cog"></i> Manage Campus
                            </a>
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
@push('styles')
    <style>
        .category-collection {
            margin-bottom: 1rem;
        }
        .card:hover {
            box-shadow: 0px 2px 20px rgba(0,0,0,0.3);
        }
        .card a:link, .card a:hover, .card a:visited, .card a:active {
            color: #000;
            text-decoration: none;
        }
    </style>
@endpush
