@extends('layouts.admin.main')

@inject('str', 'Illuminate\Support\Str')

@section('content')
    <div class="page-header">
        <h1 class="title">Notifiactions</h1>
        <ol class="breadcrumb">
            <li>PPDB</li>
            <li class="active">Notifiactions</li>
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
                        Data Master Notifiactions
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

                        <div class="fixed-table-head period">
                            <table id="datatables-master-notifications" class="table display">
                                <thead>
                                <tr>
                                    <th>Penerima</th>
                                    <th>Judul</th>
                                    <th>Pesan</th>
                                    <th class="text-center">Dibaca</th>
                                    <th class="text-center">Dikirim Email</th>
                                    <th>Pengirim</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($notifications as $notification)
                                    <tr>
                                        <td>{{ optional($notification->notifiable)->name }}</td>
                                        <td>{{ $notification->data['title'] }}</td>
                                        <td>{!! nl2br(e($str->limit($notification->data['body'], 50)) ) !!}</td>
                                        <td class="text-center">
                                            @if ($notification->read())
                                                <span class="label label-success">Ya</span>
                                            @else
                                                <span class="label label-danger">Tidak</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($notification->send_email)
                                                <span class="label label-success">Ya</span>
                                                <br>
                                                {{ date('Y-m-d H:i', strtotime($notification->sended_email)) }}
                                            @else
                                                <span class="label label-danger">Tidak</span>
                                            @endif
                                        </td>
                                        <td>{{ optional($notification->sender)->username }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{ $notifications->appends(request()->except('page'))->links() }}

                        <div class="btn-group padding-t-10 pull-right">
                            <a href="{{ route('admin.notification.create') }}" class="btn btn-sm btn-success">
                                <i class="fa fa-plus"></i> Tambah Data
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
    <!-- Modal -->
@endsection
@push('styles')
    <link rel="stylesheet" href="{{asset('css/plugin/datatables/datatables.css')}}">
    <style>
        .button-collection {
            margin-bottom: 5px;
        }
        .btn-circle {
            width: 30px;
            height: 30px;
            text-align: center;
            padding: 6px 0;
            font-size: 12px;
            line-height: 1.42;
            border-radius: 15px;
        }

        .btn-circle .fa {
            margin-right: 0;
        }
    </style>
@endpush
