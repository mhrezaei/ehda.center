<div class="container">
    <div class="row">
        <div class="col-sm-8 col-center">
            <section class="panel">
                <header>
                    <div class="title"> <span class="icon-tag"></span> {{ trans('front.accepted_codes') }} </div>
                    <div class="functions"> <button class="blue" data-modal="add-code-modal"> {{ trans('front.add_code') }} </button> </div>
                </header>
                <article>
                    @if(user()->receipts()->count())
                        <table class="table">
                        <thead>
                        <tr>
                            <th> {{ trans('front.code') }} </th>
                            <th> {{ trans('front.created_at') }} </th>
                            <th> {{ trans('front.purchased_at') }} </th>
                            <th> {{ trans('front.price') }} ({{ trans('front.rials') }}) </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(user()->receipts as $receipt)
                        <tr>
                            <td class="fw-b color-green">{{ pd($receipt->dashed_code) }}</td>
                            <td> @include('front.user.drawing.date_format', ['date' => $receipt->created_at]) </td>
                            <td> @include('front.user.drawing.date_format', ['date' => $receipt->purchased_at]) </td>
                            <td> {{ pd($receipt->amount_format) }} </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @else
                        <div class="alert red">{{ trans('front.drawing_code_not_found') }}</div>
                    @endif
                </article>
            </section>
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
                                <button class="block green" type="button" onclick="drawingCode();"> {{ trans('front.check_code') }} </button>
                            </div>
                            <div class="result">
                                <div class="result-item" style="display: none;"> {{ trans('front.drawing_check_code_fail') }} </div>
                            </div>
                        </form>
                    </article>
                </section>
            </div>
        </div>
    </div>
</div>
@include('front.user.drawing.script')