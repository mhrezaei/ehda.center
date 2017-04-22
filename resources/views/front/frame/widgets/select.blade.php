<div class="field">
    <label> {{ trans('validation.attributes.' . $name) }} </label>
    <select name="{{ $name or '' }}" id="{{ $name or '' }}"
            class="{{ $class or '' }}"
            title="{{ trans('validation.attributes_example.' . $name)}}"
            error-value="{{ trans('validation.javascript_validation.' . $name) }}"
            {{ $other or '' }}
    >
        @if(isset($options))
            @foreach($options as $opValue => $opText)
                <option value="{{ $opValue }}" @if($value == $opValue) selected @endif>{{ $opText }}</option>
            @endforeach
        @endif
    </select>
</div>