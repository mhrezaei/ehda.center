<?php
if (isset($class)) {
    if (str_contains($class, 'form-required')) {
        $required = true;
    }
} else {
    $class = '';
}
?>

{{ null , $prefix = $name or str_random(5) }}

<div class="form-group {{ $container['class'] or '' }}"
     title="{{ trans('validation.attributes_example.' . $name)}}" }}"
        {{ (isset($container['id']) and $container['id']) ? "id=$container[id]" : '' }}>
    @if(!isset($label))
        {{ null , $label = Lang::has("validation.attributes.$name") ? trans("validation.attributes.$name") : $name}}
    @endif
    @if($label)
        <label class="col-xs-12 control-label">
            {{ trans('validation.attributes.' . $name) }}
            @if(isset($required) and $required)
                @include('front.forms.require-sign')
            @endif
        </label>
    @endif
    <div class="col-xs-12" style="min-height: 44px;">
        @include('front.forms.buttonset-self')
    </div>
</div>