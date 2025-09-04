@extends('layouts.admin.main')
@section('content')
    <div class="page-header">
        <h1 class="title">Setting FAQ</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li class="active">Setting FAQ</li>
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
                        Setting FAQ
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
                            <table id="datatables-faq" class="table display">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kategori</th>
                                    <th>Pertanyaan</th>
                                    <th width="15%">Tanggal Publish</th>
                                    <th>Publish</th>
                                    <th width="25%">Option</th>
                                </tr>
                                </thead>
                                <tbody>
                                
                                @foreach($faqs as $key => $faq)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{ @$faq->category_name }}</td>
                                        <td>
                                            <div style="display: inline-block; vertical-align: top">
                                                <p>
                                                <a href="{{ route('admin.faq.show', $faq['id']) }}" class="faq-title">
                                                    {{ $faq->title }}
                                                </a><br>
                                                {{ $faq->short_desc }}<br>
                                                @foreach($faq->tags as $key => $tag)
                                                    <span class="label label-info tag">{{$tag->name}}</span>
                                                @endforeach
                                                </p>
                                            </div>
                                        </td>
                                        <td>{{ date('j F Y', strtotime($faq['publish_date'])) }}</td>
                                        <td>{!! $faq['published_label'] !!}</td>
                                        <td>
                                            @if (\App\Helpers\Helper::canPublishArticle())
                                            <a href="{{ route('admin.faq.toggle',$faq->id) }}" class="btn btn-xs {{ $faq->isPublished() ? 'btn-warning' : 'btn-info'}}">
                                                <icon class="icon-plus">
                                                    {!! $faq->isPublished() ? '<i class="fa fa-toggle-off"></i> Unpublish' : '<i class="fa fa-toggle-on"></i> Publish' !!}
                                                </icon>
                                            </a>
                                            @endif
                                            <a href="{{ route('admin.faq.show', $faq['id']) }}" class="btn btn-xs btn-primary">
                                                <icon class="icon-plus"><i class="fa fa-eye"></i></icon>
                                            </a>
                                            <a href="{{ route('admin.faq.edit',$faq['id']) }}" class="btn btn-xs btn-default">
                                                <icon class="icon-plus"><i class="fa fa-pencil"></i></icon>
                                            </a>
                                            <a onclick="confirmDelete({{$faq['id']}})" title="Delete" class="btn btn-xs btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <form id="form-delete-{{$faq['id']}}" action="{{ route('admin.faq.delete',$faq['id']) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="btn-group padding-t-10 pull-right">
                            <a href="{{ route('admin.faq.add') }}" class="btn btn-sm btn-success">
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
@push('styles')
    <link rel="stylesheet" href="{{asset('css/plugin/datatables/datatables.css')}}">
    <style>
        .button-collection {
            margin-bottom: 5px;
        }
        .faq-title {
            font-size: 18px;
        }
        .tag {
            font-weight: normal;
            color: #3366ff;
            background-color: #b3c6ff;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{asset('js/datatables/datatables.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#datatables-faq').DataTable({
                "order": [[ 2, "desc" ]]
            });
        });

        function confirmDelete(id) {
            if(confirm('Are you sure you want to delete this item?'))
                document.getElementById('form-delete-' + id).submit();
        }
    </script>
@endpush
