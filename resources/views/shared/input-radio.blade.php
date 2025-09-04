<div class="form-group {{@$class}}">
    <label class="control-label col-sm-2" for="{{ $name }}">{{ $label }}:</label>
    <div class="col-sm-10">
        @foreach ($options as $id => $option)
            @php
                $id = (isset($use_value_as_index) && $use_value_as_index) ? $option : $id;
            @endphp
            <div class="radio radio-inline">
                <input class="form-control" id="{{ $name.'_'. $id }}" type="radio" name="{{ $name }}" {{ (old($name, @$data->$name) == $id ? 'checked' : null) }} value="{{ $id }}" />
                <label for="{{ $name .'_'. $id }}">{{ $option }}</label
            ></div>
        @endforeach
    </div>
</div>