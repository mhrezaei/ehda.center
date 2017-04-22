<div class="field"><label>{{ trans('front.marital') }}</label>
    <div class="radio d-ib blue">
        <input id="radio3" type="radio" name="marital" value="1"
               @if(user()->marital == 1)
               checked="checked"
                @endif
        >
        <label for="radio3"> {{ trans('forms.general.single') }} </label>
    </div>
    <div class="radio d-ib blue">
        <input id="radio4" type="radio" name="marital" value="2"
               @if(user()->marital == 2)
               checked="checked"
                @endif
        >
        <label for="radio4"> {{ trans('forms.general.married') }} </label>
    </div>
</div>