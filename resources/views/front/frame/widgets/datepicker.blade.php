<div class="field @if(isset($hidden) and $hidden) hidden @endif"
     @if(isset($container['style'])) style="{{ $container['style'] }}" @endif>
    <label> {{ trans('validation.attributes.' . $name) }} </label>
    <input type="{{ $type or '' }}" name="{{ $name or '' }}" id="{{ $name or '' }}"
           class="{{ $class or '' }} form-datepicker"
           placeholder="{{ trans('validation.attributes_placeholder.' . $name) }}"
           title="{{ trans('validation.attributes_example.' . $name)}}"
           minlength="{{ $min or '' }}"
           maxlength="{{ $max or '' }}"
           value="{{ $value or '' }}"
           error-value="{{ trans('validation.javascript_validation.' . $name) }}"
           {{ $other or '' }}
           @if(isset($options))
           @foreach($options as $optionName => $optionValue)
           data-datepicker-{{ kebab_case($optionName) }}="{{ $optionValue }}"
            @endforeach
            @endif
    >
</div>