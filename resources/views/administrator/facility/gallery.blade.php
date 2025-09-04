<div class="row">
    <div class="col-md-12">
        @foreach($datas as $data)
        <div class="col-md-4">
            <div class="preview-image {{ $data->content_url ? NULL : 'hide' }}">
                <img class="responsive" src="{{ $data->getContentImageUrl() }}" />
                <h6>{{ $data->title }}</h6>
                <input type="hidden" name="gallery_id[]" value="{{$data->id}}">
            </div>
        </div>
        @endforeach
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        {{ $datas->appends(request()->except('page'))->links() }}
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <hr>
        <h4>Upload Image</h4>
        <div class="upload-error"></div>
        <form id="form-gallery-upload" class="form-horizontal" enctype="multipart/form-data">
            <div class="form-group">
                <label class="control-label col-sm-2" for="upload_title">Title:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="upload_title" id="upload_title" value="" placeholder="Title" required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="upload_content_url">Image:</label>
                <div class="col-sm-10">
                    <input accept="image/x-png,image/jpeg" type="file" name="upload_content_url" id="upload_content_url" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="upload_desc">Description:</label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="upload_desc" id="upload_desc"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="button" class="btn btn-default upload-image">Upload</button>
                </div>
            </div>
        </form>
    </div>
</div>