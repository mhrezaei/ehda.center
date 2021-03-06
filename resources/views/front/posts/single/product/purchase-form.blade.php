@if(!isset($purchaseFormIsHidden))
    @php $purchaseFormIsHidden = true @endphp
@endif

<div class="row mt20 mb20">
    <div class="col-xs-12 mt15">
        <div class="row">
            <div class="col-xs-12 col-xs-offset-0" id="purchase-form"
                 @if($purchaseFormIsHidden) style="display: none;" @endif>
                <div class="row">
                    <div class="col-xs-12">
                        {!! Form::open([
                            'url'	=> route_locale('products.purchase') ,
                            'method'=> 'post',
                            'class' => 'clearfix ehda-card-form js',
                            'name' => 'purchase_form',
                            'id' => 'purchase_form',
                            'novalidate' => 'novalidate',
                        ]) !!}

                        @include('forms.hidden', [
                            'id' => 'post_id',
                            'name' => 'post_id',
                            'value' => $post->hashid,
                        ])

                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <div class="row">
                                    @include('front.forms.input', [
                                        'name' => 'name',
                                        'class' => 'form-persian',
                                        'placeholder' => trans('validation.attributes.first_and_last_name'),
                                        'value' => (!auth()->guest() ? user()->full_name : '')
                                    ])
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="row">
                                    @include('front.forms.input', [
                                        'name' => 'email',
                                        'class' => 'form-email form-required',
                                        'value' => (!auth()->guest() ? user()->email : '')
                                    ])
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="row">
                                    @include('front.forms.input', [
                                        'name' => 'mobile',
                                        'class' => 'form-mobile',
                                        'value' => (!auth()->guest() ? user()->mobile : '')
                                    ])
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="row">
                                    @include('front.forms.input', [
                                        'name' => 'job',
                                        'value' => (!auth()->guest() ? user()->job : '')
                                    ])
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="row">
                                    @include('front.forms.input', [
                                        'name' => 'title',
                                        'class' => 'form-number',
                                        'value' => $post->title,
                                        'disabled' => true,
                                    ])
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="row">
                                    @include('front.forms.input', [
                                        'name' => 'price',
                                        'class' => 'form-number',
                                        'value' => $post->price
                                    ])
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 pt15">
                                @include('forms.button', [
                                    'shape' => 'success',
                                    'label' => trans('cart.pay'),
                                    'type' => 'submit',
                                ])
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 pt15">
                                @include('forms.feed')
                            </div>
                        </div>

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('endOfBody')
    {!! Html::script ('assets/libs/jquery.form.min.js') !!}
    {!! Html::script ('assets/js/forms.js') !!}

    <script>
        $(document).ready(function () {
            $('#btn-purchase').click(function () {
                $('#purchase-form').slideDown();
                $(this).hide()
            });
        });
    </script>
@append