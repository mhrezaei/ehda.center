{{ null , $prefix = $name or str_random(5) }}

<div class="field"
     title="{{ trans('validation.attributes_example.' . $name)}}"
     error-value="{{ trans('validation.javascript_validation.' . $name) }}">
    <label> {{ trans('validation.attributes.' . $name) }} </label>
    @foreach($options as $opValue => $opText)
        {{ null , $opId = $prefix . "-" . array_search($opValue, array_keys($options)) }}
        <div class="radio d-ib blue {{ $class or '' }}">
            <input id="{{ $opId }}" type="radio" name="{{ $name }}"
                   value="{{ $opValue }}"
                   {{ $other or '' }}
                   @if(($value or '') == $opValue)
                   checked="checked"
                    @endif
            >
            <label for="{{ $opId }}"> {{ $opText }} </label>
        </div>
    @endforeach
</div>