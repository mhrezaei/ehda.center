<div class="col-sm-4">
    <h2> {{ trans('front.drawing_code_register') }} </h2>
    <div class="gift-inputs"> 
        <input type="text" class="gift-input" maxlength="5" placeholder="----"> 
        <input type="text" class="gift-input" maxlength="5" placeholder="----"> 
        <input type="text" class="gift-input" maxlength="5" placeholder="----"> 
        <input type="text" class="gift-input" maxlength="5" placeholder="----"> 
    </div>
    <div class="action"> 
        <button class="block green"> {{ trans('front.check_code') }} </button>
    </div>
    <div class="result">
        <div class="result-item fail"> {{ trans('front.drawing_check_code_fail') }} </div>
    </div>
</div>