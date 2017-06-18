{!! Html::script ('assets/libs/jquery.form.min.js') !!}
{!! Html::script ('assets/js/forms.js') !!}

<style>
    .btn{
        width: 170px !important;
    }
</style>

<div class="row article">
    <div class="col-xs-12">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-center col-xs-12 col-md-12">
                        <img src="{{ url('/card/show_card/mini/' . encrypt(Auth::user()->code_melli)) }}" alt="{{ trans('site.know_menu.organ_donation_card') }}" class="ehda-card-image">
                    </p>
                </div>
                <div class="col-md-6">
                    <p>
                        {!! $card_detail->text !!}
                        <div style="width: 100%; margin-top: 15px; text-align: center;">
                            @include('forms.button', [
                                'shape' => 'info',
                                'link' => url('/card/show_card/full/' . encrypt(Auth::user()->code_melli) . '/download'),
                                'label' => trans('forms.button.card_save'),
                            ])
                            <a class="btn btn-info" href="{{ url('/members/my_card/print') }}" target="_blank">{{ trans('forms.button.card_print') }}</a>
                            <div style="clear: both; margin-top: 10px;"></div>
                            @include('forms.button', [
                                'shape' => 'primary',
                                'link' => url('/members/my_card/edit'),
                                'label' => trans('site.global.users_edit_data'),
                            ])

                            @include('forms.button', [
                                'shape' => 'success',
                                'link' => url('/volunteers'),
                                'label' => trans('manage.modules.volunteers'),
                            ])
                        </div>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>