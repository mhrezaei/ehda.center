<div class="col-xs-12">
    <div class="col-xs-12 border-1 border-lightGray rounded-corners-5 pt30 pb50">
        <div class="row">
            <div class="col-xs-12 mb20">
                <h4 class="text-green">{{ trans('cart.purchase_tracking') }}</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                {!! Form::open([
                    'url'	=> route_locale('products.tracking') ,
                    'method'=> 'post',
                    'class' => 'clearfix ehda-card-form js',
                    'name' => 'purchase_form',
                    'id' => 'purchase_form',
                    'novalidate' => 'novalidate',
                ]) !!}


                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            @include('front.forms.input', [
                                'name' => 'tracking_number',
                            ])
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 pt15 text-center">
                        @include('forms.button', [
                            'shape' => 'success',
                            'label' => trans('cart.tracking'),
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

@section('endOfBody')
    {!! Html::script ('assets/libs/jquery.form.min.js') !!}
    {!! Html::script ('assets/js/forms.js') !!}
@append