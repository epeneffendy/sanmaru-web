@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Voice Of Sanmar</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li class="active">Voice Of Sanmar</li>
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
                        Voice Of Sanmar
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
                                <form role="form" autocomplete="off" method="GET" action="{{ route('admin.voice-of-sanmar.index') }}">
                                    <input autocomplete="false" name="hidden" disabled type="text" style="display:none;">
                                    <div class="form-group col-md-3">
                                        <input type="text" name="title" placeholder="Search" value="{{ @$params['title'] }}" class="form-control input-sm" />
                                    </div>
                                    <a href="{{ route('admin.voice-of-sanmar.index') }}" class="pull-right btn btn-sm btn-warning">
                                        <i class="fa fa-refresh"></i> clear
                                    </a>
                                    <button type="submit" class="pull-right btn btn-sm btn-success">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="fixed-table-head">
                            <table id="datatables-vos" class="table table-striped display">
                                <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Content</th>
                                    <th>Last Update</th>
                                    <th width="20%">Option</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $number = ($data->currentPage() - 1) * $data->perPage();
                                @endphp
                                @foreach($data as $key => $voiceOfSanmar)
                                    @php $number++ @endphp
                                    <tr>
                                        <td>{{ $number }}</td>
                                        <td>
                                            <div style="display: inline-block; width: 125px; vertical-align: top;">
                                                <iframe class='embed-responsive-item' src="{{ $voiceOfSanmar->embed_url }}" allowfullscreen></iframe>
                                            </div>
                                            <br>
                                            <div style="display: inline-block; vertical-align: top">
                                                <b>{{ $voiceOfSanmar->title }}</b>
                                                <p>Youtube Link : <a href="{{$voiceOfSanmar->video_url}}" target="_blank">{{ $voiceOfSanmar->video_url }}</a></p>
                                            </div>
                                        </td>
                                        <td>{{ date('d F Y', strtotime($voiceOfSanmar->updated_at)) }}</td>
                                        <td>
                                            <a href="{{ route('admin.voice-of-sanmar.edit',$voiceOfSanmar['id']) }}" class="btn btn-xs btn-default">
                                                <icon class="icon-plus"><i class="fa fa-pencil"></i></icon>
                                            </a>
                                            <a onclick="confirmDelete({{$voiceOfSanmar['id']}})" title="Delete" class="btn btn-xs btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <form id="form-delete-{{$voiceOfSanmar['id']}}" action="{{ route('admin.voice-of-sanmar.delete',$voiceOfSanmar['id']) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        {{ $data->appends(request()->except('page'))->links() }}
                        <div class="btn-group padding-t-10 pull-right">
                            <a href="{{ route('admin.voice-of-sanmar.add') }}" class="btn btn-sm btn-success">
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
@endsection

@push('scripts')
    <script>
        function confirmDelete(id) {
            if(confirm('Are you sure you want to delete this item?'))
                document.getElementById('form-delete-' + id).submit();
        }
    </script>
@endpush
