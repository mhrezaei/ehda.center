{{--<div class="form-buttonset">--}}
{{--@foreach($options as $opValue => $opText)--}}
{{--{{ null , $opId = $prefix . "-" . array_search($opValue, array_keys($options)) }}--}}
{{--<input id="{{ $opId }}" type="radio" name="{{ $name }}" class="{{ $class or '' }}"--}}
{{--value="{{ $opValue }}"--}}
{{--{{ $other or '' }}--}}
{{--@if(isset($value) and $value == $opValue)--}}
{{--error-value="{{ isset($error_value) ? $error_value : trans('validation.javascript_validation.' . $name)  }}"--}}
{{--checked="checked"--}}
{{--@endif--}}
{{-->--}}
{{--<label for="{{ $opId }}"> {{ $opText }} </label>--}}
{{--@endforeach--}}
{{--</div>--}}


<div class="row">
    <div class="col-xs-12 form-buttonset">
        <table class="buttonset">
            <tr>
                @foreach($options as $opValue => $opText)
                    <td>
                        {{ null , $opId = $prefix . "-" . array_search($opValue, array_keys($options)) }}
                        <input id="{{ $opId }}" type="radio" name="{{ $name }}"
                            class="{{ $class or '' }} form-radio"
                            value="{{ $opValue }}"
                            {{ $other or '' }}
                            error-value="{{ isset($error_value) ? $error_value : trans('validation.javascript_validation.' . $name)  }}"
                            @if(isset($value) and $value == $opValue)
                                checked="checked"
                            @endif
                        >
                        <label for="{{ $opId }}"> {{ $opText }} </label>
                    </td>
                @endforeach
            </tr>
        </table>
    </div>
</div>