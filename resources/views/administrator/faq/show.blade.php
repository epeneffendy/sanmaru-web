@extends('layouts.admin.main')
@section('content')
@php($status=="Show")
@php($status_header="Show")

    <!-- Start Page Header -->
    <div class="page-header">
        <h1 class="title">Setting FAQ</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li><a href="{{route('admin.faq.index')}}">Setting FAQ</a></li>
            <li class="active">{{$status_header}}</li>
        </ol>
    </div>
    <!-- End Page Header -->

    <!-- START CONTAINER -->
    <div class="container-padding">
        <!-- Start Row -->
        <div class="row">
            <!-- Start Panel -->
            <div class="col-md-12">
                <div class="widget ">
                    <div class="widget-header">
                        <h3>{{$status_header}} Setting FAQ</h3>
                    </div> <!-- /widget-header -->
                    <div class="widget-content">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">Judul:</label>
                                <div class="col-sm-10">
                                    <div class="form-control">{{ $faq->title }}</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="category" class="control-label col-sm-2">Kategori</label>
                                <div class="col-sm-10">
                                    <div class="form-control">{{ $faq->category_name }}</div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="content">Pertanyaan:</label>
                                <div class="col-sm-10">
                                    <p>{{ @$faq->content }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="answer">Jawaban:</label>
                                 <div class="col-sm-10">
                                    {!! @$faq->html_answer !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Tanggal Publish:</label>
                                <div class="col-sm-10">
                                    <div class="form-control">{{ date('j F Y H:i' , strtotime($faq->publish_date)) }}</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="status">Tags:</label>
                                <div class="col-sm-10">
                                    @foreach($faq->tags as $key => $tag)
                                    <span class="label label-info tag">{{$tag->name}}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="status">Status:</label>
                                <div class="col-sm-10">
                                    {!! $faq->published_label !!}
                                </div>
                            </div>
                            
                            
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <a href="{{ route('admin.faq.index') }}" class="btn btn-primary">
                                        <i class="fa fa-arrow-left"></i>Back To List
                                    </a>
                                    <a href="{{ route('admin.faq.edit', $faq->id) }}" class="btn btn-default">
                                        <i class="fa fa-edit"></i>Edit</a>
                                    <a onclick="confirmDelete({{$faq['id']}})" title="Delete" class="btn btn-danger">
                                        <i class="fa fa-trash"></i>Delete
                                    </a>
                                    <form id="form-delete-{{$faq->id}}" action="{{ route('admin.faq.delete',$faq->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    
                                </div>
                            </div>
                            <!-- /bottom-wizard -->
                        </div>
                    </div>
                </div> <!-- /widget-content -->
            </div>
            <!-- End Panel -->
        </div>
        <!-- End Row -->
    </div>
    <!-- END CONTAINER -->
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/content-styles.css') }}">
    <style>
        img{
            max-width: 100%;
            max-height: 100%;
            display: block;
        }
    </style>
@endpush
@push('scripts')
    <script>
        function confirmDelete(id) {
            if(confirm('Are you sure you want to delete this item?'))
                document.getElementById('form-delete-' + id).submit();
        }
    </script>
@endpush
