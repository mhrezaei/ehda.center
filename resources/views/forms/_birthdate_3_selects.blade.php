@php
    if (!isset($extra))
        $extra = '';
    if (!isset($name))
        $name = 'birth_date';

    if (isset($class)) {
        if (str_contains($class, 'form-required')) {
            $required = true;
        }
    } else
        $class = '';

    if (isset($disabled) and $disabled) {
        $required = false;
        $extra .= ' disabled ';
    }


    if (isset($value) and is_object($value))
        $value = $value->$name;

    if(!isset($value)){
        $value = '';
    }

    if($value) {
        list($yearValue, $monthValue, $dayValue) = explode('/', $value);
    } else {
        $yearValue = '';
        $monthValue = '';
        $dayValue = '';
    }

    if (!isset($in_form))
        $in_form = true;

    $thisDatePickerId = 'date-' . str_random(5) . time();

@endphp
@if(!isset($condition) or $condition)
    <div class="form-group {{ (isset($container['class']) ? $container['class'] : '') }}"
    {{ isset($container['id']) ? "id=$container[id]" : '' }}
    @if(isset($container['other']))
        @foreach($container['other'] as $attrName => $attrValue)
            {{ $attrName }}="{{ $attrValue }}"
        @endforeach
    @endif
    >
    @if(!isset($label))
        {{ null, $label = Lang::has("validation.attributes.$name") ? trans("validation.attributes.$name") : $name }}
    @elseif($label)
        {{ null, $label = is_string($label) ? $label : trans("validation.attributes.$name") }}
    @endif

    @if($label)
        <label
                for="{{$name}}"
                class="col-sm-12 control-label {{$label_class or ''}}"
        >
            {{ $label }}
            @if(isset($required) and $required)
                @include('front.forms.require-sign')
            @endif
        </label>

    @endif
    <div class="col-sm-12 " id="{{ $thisDatePickerId }}">
        <div class="row">
            @php $dayOptions = [['id' => '', 'title' => trans('front.day')]] @endphp
            @php $monthOptions = [['id' => '', 'title' => trans('front.month')]] @endphp
            @php $yearOptions = [['id' => '', 'title' => trans('front.year')]] @endphp

            @php
                $currentYear = echoDate(\Carbon\Carbon::now(), 'Y');
            @endphp

            @foreach(range(1, 31) as $n)
                @php $dayOptions[] = ['id' => $n, 'title' => ad($n)] @endphp
            @endforeach
            @foreach(range(1, 12) as $n)
                @php $monthOptions[] = ['id' => $n, 'title' => ad($n)] @endphp
            @endforeach
            @foreach(range($currentYear - 99, $currentYear) as $n)
                @php $yearOptions[] = ['id' => $n, 'title' => ad($n)] @endphp
            @endforeach
            <div class="col-md-4 col-xs-12 mb15 ">
                @include('front.forms.select_self',[
                    'options' => $dayOptions,
                    'name' => '',
                    'id' => $thisDatePickerId . '-day',
                    'value' => $dayValue,
                ])
            </div>
            <div class="col-md-4 col-xs-12 mb15">
                @include('front.forms.select_self',[
                    'options' => $monthOptions,
                    'name' => '',
                    'id' => $thisDatePickerId . '-month',
                    'value' => $monthValue,
                ])
            </div>
            <div class="col-md-4 col-xs-12 mb15">
                @include('front.forms.select_self',[
                    'options' => $yearOptions,
                    'name' => '',
                    'id' => $thisDatePickerId . '-year',
                    'value' => $yearValue,
                ])
            </div>
            @include('front.forms.hidden', ['id' => $thisDatePickerId . '-val'])
        </div>
        <span class="help-block persian {{$hint_class or ''}}" style="{{$hint_style or ''}}">
                    {{ $hint or '' }}
                </span>

        @if ($errors->has($name))
            <span class="help-block error">
                <strong>{{ $errors->first($name) }}</strong>
            </span>
        @endif
    </div>
    </div>

@section('endOfBody')
    <script>
        $(document).ready(function () {
            $('#{{ $thisDatePickerId }}').find('select').change(function () {
                let year = $('#{{ $thisDatePickerId . '-year' }}').val();
                let month = $('#{{ $thisDatePickerId . '-month' }}').val();
                let day = $('#{{ $thisDatePickerId . '-day' }}').val();
                if (year && month && day) {
                    let val = year + '/' + month + '/' + day;
                    $('#{{ $thisDatePickerId . '-val' }}').val(val);
                }
            }).last().change();
        });

    </script>
@append

@endif