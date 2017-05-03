<!-- Add code modal -->
<div class="modal-wrapper">
    <section class="panel modal sm add-code" id="add-code-modal">
        <article>
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
                    <button class="block green" type="button"
                            onclick="drawingCode();"> {{ trans('front.check_code') }} </button>
                </div>
                <div class="result">
                    <div class="result-item" style="display: none;"></div>
                </div>
            </form>
        </article>
    </section>
</div>