@extends('layouts.admin.main')
@section('content')
    @if(@$status=="edit")
        @php($action=route('admin.voice-of-sanmar.update', $voiceOfSanmar['id']))
        @php($status="Update")
        @php($status_header="Edit")
    @else
        @php($action=route('admin.voice-of-sanmar.insert'))
        @php($status="Save")
        @php($status_header="Tambah")
    @endif
    <!-- Statrt Page Header -->
    <div class="page-header">
        <h1 class="title">Voice Of Sanmar</h1>
        <ol class="breadcrumb">
            <li>Konten</li>
            <li><a href="{{route('admin.voice-of-sanmar.index')}}">Voice Of Sanmar</a></li>
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
                <div class="widget">
                    <div class="widget-header">
                        <h3>{{$status_header}} Voice Of Sanmar</h3>
                    </div><!--  widget-header -->
                    <div class="widget-content">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form role="form" method="POST" action="{{$action}}" class="form-horizontal" enctype="multipart/form-data">
                            @if (@$status == 'Update')
                                @method('PATCH')
                            @endif

                            <input type="hidden" name="id" value="{{@$voiceOfSanmar->id}}" />


                            <div class="form-group">
                                <label for="title" class="control-label col-sm-2">Title:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="title" value="{{ old('title', @$voiceOfSanmar->title) }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="content_url" class="control-label col-sm-2">Youtube Link:</label>
                                <div class="col-sm-10">
                                    <input type="url" name="content_url" id="content_url" class="form-control" placeholder="Youtube Link" value="{{ old('content_url', @$voiceOfSanmar->video_url) }}">

                                    <div id="videobox" style="{{ (@!$voiceOfSanmar->content_url) ? 'display:none;' : 'display:block;' }}">
                                        <div id="preview-video">
                                            @if($voiceOfSanmar instanceof \App\Models\VoiceOfSanmar)
                                                {!! $voiceOfSanmar->preview !!}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button class="btn btn-default">{{$status}}</button>
                                    @if($status=="Update")
                                        <a href="{{ route('admin.voice-of-sanmar.index') }}" class="btn btn-secondary">Cancel</a>
                                    @endif
                                </div>
                            </div>
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
    
        $(document).on('keyup', '#content_url', function() {
            let preview = document.getElementById('videobox');
            if ($(this).val() === "") {
                preview.style.display = "none";
            } else {
                let contentUrl = $(this).val();
                let video_id = getVideoId(contentUrl);
                if (video_id) {
                    let ifrm = document.createElement('iframe');
                    ifrm.src = 'https://youtube.com/embed/' + video_id;
                    ifrm.width = "400";
                    ifrm.height = "300";
                    ifrm.frameborder="0";
                    ifrm.scrolling="no";
                    $('#preview-video').html(ifrm);
                    preview.style.display = "block";
                } else {
                    preview.style.display = "none";
                }
            }
        });

        function getVideoId(url) {
            var re = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
            return (url.match(re)) ? RegExp.$1 : false;
        }
    </script>
@endpush