<div class="field">
    <input type="{{ $type or '' }}" name="{{ $name or '' }}" id="{{ $name or '' }}" class="{{ $class or '' }}"
           placeholder="{{ trans('validation.attributes.' . $name) }}"
           title="{{ trans('validation.attributes_example.' . $name)}}"
           minlength="{{ $min or '' }}"
           maxlength="{{ $max or '' }}"
           value="{{ $value or '' }}"
           error-value="{{ trans('validation.javascript_validation.' . $name) }}"
            {{ $other or '' }}>
</div>