<div class="container">
    <div class="row">
        <div class="col-sm-8 col-center">
            <section class="panel">
                <header>
                    <div class="title"><span class="icon-tag"></span> {{ trans('front.accepted_codes') }} </div>
                    <div class="functions">
                        <button class="blue" data-modal="add-code-modal"> {{ trans('front.add_code') }} </button>
                    </div>
                </header>
                <article>
                    @if(!arrayHasRequired(\App\Models\User::$required_fields, user()->toArray()))
                        <div class="col-xs-12 pt20">
                            <div class="row">
                                <div class="alert alert-danger text-right">
                                    {{ trans('front.profile_messages.complete_to_join_drawing') }}
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <a href="{{ url(\App\Providers\SettingServiceProvider::getLocale() .'/user/profile') }}">{{ trans('front.edit_profile') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(user()->receipts()->count())
                        <table class="table">
                            <thead>
                            <tr>
                                <th> {{ trans('front.code') }} </th>
                                <th> {{ trans('front.created_at') }} </th>
                                <th> {{ trans('front.purchased_at') }} </th>
                                <th> {{ trans('front.price') }} ({{ trans('front.rials') }})</th>
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
            @include('front.user.drawing.modal_form')
        </div>
    </div>
</div>
@include('front.user.drawing.script')