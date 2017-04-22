{{ null , $prefix = $name or str_random(5) }}

<div class="col-xs-12 field {{ (isset($container['class']) ? $container['class'] : '') }}"
     title="{{ trans('validation.attributes_example.' . $name)}}"
     error-value="{{ trans('validation.javascript_validation.' . $name) }}"
        {{ isset($container['id']) ? "id=$container[id]" : '' }}>
    @if(!isset($label))
        {{ null , $label = Lang::has("validation.attributes.$name") ? trans("validation.attributes.$name") : $name}}
    @endif
    @if($label)
        <label> {{ trans('validation.attributes.' . $name) }} </label>
    @endif
    @foreach($options as $opValue => $opText)
        {{ null , $opId = $prefix . "-" . array_search($opValue, array_keys($options)) }}
        <div class="radio d-ib blue">
            <input id="{{ $opId }}" type="radio" name="{{ $name }}" class="{{ $class or '' }}"
                   value="{{ $opValue }}"
                   {{ $other or '' }}
                   @if($value == $opValue)
                   checked="checked"
                    @endif
            >
            <label for="{{ $opId }}"> {{ $opText }} </label>
        </div>
    @endforeach
</div>