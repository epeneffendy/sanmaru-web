<div class="form-group {{ @$class }}">
    <label class="control-label col-sm-2" for="{{ $name }}">{{ $label }}</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="{{ $name }}" id="{{ $name }}" value="{{ old($name, @$data->$name) }}" placeholder="{{ $label }}">
    </div>
</div>