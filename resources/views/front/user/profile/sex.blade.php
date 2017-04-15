<div class="field">
    <label>{{ trans('front.sex') }}</label>
    <div class="radio d-ib blue">
        <input id="radio1" type="radio" name="gender" value="1"
               @if(user()->gender == 1)
               checked="checked"
                @endif
        >
        <label for="radio1"> {{ trans('forms.gender.1') }} </label>
    </div>
    <div class="radio d-ib blue">
        <input id="radio2" type="radio" name="gender" value="2"
               @if(user()->gender == 2)
               checked="checked"
                @endif
        >
        <label for="radio2"> {{ trans('forms.gender.2') }} </label>
    </div>
</div>