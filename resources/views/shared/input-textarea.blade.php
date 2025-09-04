<div class="form-group {{@$class}}">
    <label class="control-label col-sm-2" for="{{ $name }}">{{ $label }}:</label>
    <div class="col-sm-10">
        <textarea class="form-control" placeholder="{{ $label }}" name="{{ $name }}" id="{{ $name }}">{{ old($name, @$data->$name) }}</textarea>
    </div>
</div>