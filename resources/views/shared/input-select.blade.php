<div class="form-group {{@$class}}">
    <label class="control-label col-sm-2" for="{{ $name }}">{{ $label }}:</label>
    <div class="col-sm-10">
        <select name="{{ $name }}" class="form-control">
        @foreach ($options as $id => $option)
            @php
                $id = (isset($use_value_as_index) && $use_value_as_index) ? $option : $id;
            @endphp
            <option value="{{ $id }}" {{ (old($name, @$data->$name) == $id) ? 'selected' : null }}>{{ $option }}</option>
        @endforeach
        </select>
    </div>
</div>