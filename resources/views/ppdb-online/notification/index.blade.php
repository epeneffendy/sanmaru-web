@extends('layouts.ppdb-online.main')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.3.45/css/materialdesignicons.css" integrity="sha256-NAxhqDvtY0l4xn+YVa6WjAcmd94NNfttjNsDmNatFVc=" crossorigin="anonymous" />

<div class="row row-height container-desktop">
    <div class="col content-top" id="start">
        <div id="wizard_container">
            <div class="wrapper-content-desktop">
                <h2>Notifikasi</h2>
            </div>

            @if (session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            @foreach ($notifications as $notification)
            <div class="row">
                <div class="col-lg-12 right">
                    <div class="box bg-white mb-3">
                        <div class="box-body p-0">
                            <div class="p-3 d-flex align-items-center border-bottom osahan-post-header">
                                <div class="dropdown-list-image mr-3 d-flex align-items-top bg-danger justify-content-center rounded-circle mb-auto">
                                    @if (!$notification->read())
                                    <img class="icon-active" src="{{asset('frontend-ppdb-online/img/Icon/notification-bullet.png')}}" alt="" width="15px;">
                                    @endif
                                </div>
                                <div class="font-weight-bold mr-3">
                                    <div class="text-title-1 text-truncate {{ $notification->read() ? 'text-grey' : 'text-black' }}">{!! $notification->data['title'] !!}</div>
                                    <div class="text-title-3 {{ $notification->read() ? 'text-grey' : '' }}">{{ date('j F Y', strtotime($notification->created_at)) }}</div>
                                    <div class="text-description text-danger">
                                        {!! nl2br(e($notification->data['body']) ) !!}
                                    </div>
                                </div>
                                <span class="ml-auto mb-auto">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-light btn-xs m-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="min-width: 50px; background-color: white; border: none;">
                                            <img class="icon-active" src="{{asset('frontend-ppdb-online/img/Icon/vertical-dots.png')}}" alt="" style="width: 20px">
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" style="">
                                            <a href="{{ route('ppdb.notification.mark-read', $notification->id) }}" class="dropdown-item" type="button">Mark as read</a>
                                            <a href="{{ route('ppdb.notification.delete', $notification->id) }}" class="dropdown-item text-danger" type="button">Delete</a>
                                        </div>
                                    </div>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="clear-50"></div>
        </div>
        <!-- /Wizard container -->
    </div>
    <!-- /content-right-->
</div>
@endsection

@push('styles')
    <style>
        .btn{
            min-width: 150px;
        }
        .btn-register{
            padding: 0 1rem;
            font-size: 14px !important;
            color: white !important;
            width: auto !important;
        }
    </style>
@endpush

@push('scripts')
    <!-- Wizard script -->
    <script src="{{asset('js/sweet-alert/sweet-alert.min.js')}}"></script>
@endpush
