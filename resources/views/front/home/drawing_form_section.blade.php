<div class="col-sm-4">
    <h2> {{ trans('front.drawing_code_register') }} </h2>
    <form class="form-horizontal">
        {{ csrf_field() }}
    <div class="gift-inputs">
        <input type="text" class="gift-input" id="gift-input1" maxlength="5" placeholder="----">
        <input type="text" class="gift-input" maxlength="5" id="gift-input2" placeholder="----">
        <input type="text" class="gift-input" maxlength="5" id="gift-input3" placeholder="----">
        <input type="text" class="gift-input" maxlength="5" id="gift-input4" placeholder="----">
    </div>
    <div class="action"> 
        <button class="block green" type="button" onclick="drawingCode();"> {{ trans('front.check_code') }} </button>
    </div>
    <div class="result">
        <div class="load" style="display: none; width: 100%; text-align: center;">
            <img src="{{ url('/assets/images/load.gif') }}">
        </div>
        <div class="result-item" style="display: none;"> {{ trans('front.drawing_check_code_fail') }} </div>
    </div>
    </form>
</div>

@include('front.user.drawing.script')