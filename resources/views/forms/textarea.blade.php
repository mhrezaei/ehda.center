<?php
if (!isset($extra))
    $extra = '';
if (!isset($in_form))
    $in_form = true;

if (isset($class) && str_contains($class, 'form-required')) {
    $required = true;
}

if (isset($value) and is_object($value))
    $value = $value->$name;

if (isset($disabled) and $disabled) {
    $required = false;
    $extra .= ' disabled ';
}

?>
@if(!isset($condition) or $condition)

    @if($in_form)
        <div class="form-group">
            @if(!isset($label))
                {{ null, $label = Lang::has("validation.attributes.$name") ? trans("validation.attributes.$name") : $name }}
            @endif
            @if($label)
                <label
                        for="{{$name}}"
                        class="col-sm-2 control-label {{$label_class or ''}}"
                >
                    {{ $label }}
                    @if(isset($required) and $required)
                        <span class="fa fa-star required-sign " title="{{trans('forms.logic.required')}}"></span>
                    @endif
                </label>

                <div class="col-sm-10">
                    @else
                        <div class="col-sm-12">
                            @endif
                            @endif
                            @if(isset($top_label))
                                <label for="{{$name}}" class="control-label mv10 text-gray">{{ $top_label }}...</label>
                            @endif
                            <textarea
                                    id="{{$id or ''}}"
                                    name="{{$name}}" value="{{$value or ''}}"
                                    class="form-control {{$class or ''}}"
                                    placeholder="{{$placeholder or trans('validation.attributes_placeholder.'.$name)}}"
                                    rows="{{$rows or 5}}"
                                    {{$extra or ''}}
                            >{{$value or ''}}</textarea>
                            @if($in_form)
                                <span class="help-block">
                {{ $hint or '' }}
            </span>
                        </div>
                </div>
    @endif
@endif