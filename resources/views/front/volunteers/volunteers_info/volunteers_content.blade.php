{!! Html::script ('assets/libs/jquery.form.min.js') !!}
{!! Html::script ('assets/js/forms.js') !!}

<div class="row article">
    <div class="col-xs-12">
        <div class="container">
            <div class="row">
                <p style="text-align: justify;">
                {!! $volunteer->text !!}
                    @if(Auth::check())
                        @if(Auth::user()->volunteer_status >= 3 or Auth::user()->volunteer_status < 0)
                            @include('forms.button', [
                            'shape' => 'success',
                            'link' => url('/volunteers/exam'),
                            'label' => trans('site.global.volunteer_register_page'),
                            'extra' => 'disabled=disabled',
                            ])
                        @elseif(Auth::user()->volunteer_status == 2)
                            @include('forms.button', [
                            'shape' => 'success',
                            'link' => url('/volunteers/final_step'),
                            'label' => trans('site.global.volunteer_complete_form'),
                            ])
                        @elseif(Auth::user()->volunteer_status == 1)
                            @if(\Carbon\Carbon::parse(Auth::user()->exam_passed_at)->diffInHours(\Carbon\Carbon::now()) >= 24)
                                @include('forms.button', [
                                'shape' => 'success',
                                'link' => url('/volunteers/exam'),
                                'label' => trans('site.global.volunteer_exam'),
                                ])
                            @else
                                @include('forms.button', [
                                'shape' => 'success',
                                'link' => url('/volunteers/exam'),
                                'label' => 24 - \Carbon\Carbon::parse(Auth::user()->exam_passed_at)->diffInHours(\Carbon\Carbon::now()) . trans('site.global.volunteer_waiting_time_for_exam'),
                                'extra' => 'disabled=disabled',
                                ])
                            @endif
                        @elseif(Auth::user()->volunteer_status == 0)
                            @include('forms.button', [
                                'shape' => 'success',
                                'link' => url('/volunteers/exam'),
                                'label' => trans('site.global.volunteer_exam'),
                                ])
                        @else
                            @include('forms.button', [
                            'shape' => 'success stepOneBtn',
                            'link' => 'volunteer_register_step_one("start")',
                            'label' => trans('site.global.volunteer_register_page')
                            ])
                        @endif
                    @else
                        @include('forms.button', [
                        'shape' => 'success stepOneBtn',
                        'link' => 'volunteer_register_step_one("start")',
                        'label' => trans('site.global.volunteer_register_page')
                        ])
                    @endif

                    @include('forms.button', [
                        'shape' => 'info pdf-book',
                        'link' => url('') . '/assets/files/safiran-learning.pdf',
                        'label' => trans('site.global.volunteer_resource_pdf'),
                    ])
                </p>
            </div>
            <div class="col-xs-12 col-md-8 col-md-offset-2 stepOneForm">
                {!! Form::open([
                            'url'	=> 'volunteer/first_step' ,
                            'method'=> 'post',
                            'class' => 'clearfix js',
                            'name' => 'volunteer_form_step_one',
                            'id' => 'volunteer_form_step_one',
                        ]) !!}

                <div class="form-group">
                    <div>اطلاعات فردی</div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="name_first">{{ trans('validation.attributes.name_first') }}: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-persian form-required" id="name_first" name="name_first" data-toggle="tooltip" data-placement="top" placeholder="{{ trans('validation.attributes_placeholder.name_first') }}" title="{{ trans('validation.attributes_example.name_first') }}" minlength="2" error-value="{{ trans('validation.javascript_validation.name_first') }}">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="name_last">{{ trans('validation.attributes.name_last') }}: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-persian form-required" id="name_last" name="name_last" data-toggle="tooltip" data-placement="top" placeholder="{{ trans('validation.attributes_placeholder.name_last') }}" title="{{ trans('validation.attributes_example.name_last') }}" minlength="2" error-value="{{ trans('validation.javascript_validation.name_last') }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="code_melli">{{ trans('validation.attributes.code_melli') }}: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-national form-required" id="code_melli" name="code_melli" data-toggle="tooltip" data-placement="top" placeholder="{{ trans('validation.attributes_placeholder.code_melli') }}" title="{{ trans('validation.attributes_example.code_melli') }}" minlength="10" maxlength="10" error-value="{{ trans('validation.javascript_validation.code_melli') }}">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="tel_mobile">{{ trans('validation.attributes.tel_mobile') }}: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-mobile form-required" id="tel_mobile" name="tel_mobile" data-toggle="tooltip" data-placement="top" placeholder="{{ trans('validation.attributes_placeholder.tel_mobile') }}" title="{{ trans('validation.attributes_example.tel_mobile') }}" minlength="11" maxlength="11" error-value="{{ trans('validation.javascript_validation.tel_mobile') }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="email">{{ trans('validation.attributes.email') }}: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-email form-required" id="email" name="email" data-toggle="tooltip" data-placement="top" placeholder="{{ trans('validation.attributes_placeholder.email') }}" title="{{ trans('validation.attributes_example.email') }}" error-value="{{ trans('validation.javascript_validation.email') }}">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="security">{{ $captcha['question'] }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-number form-required" id="security" name="security" data-toggle="tooltip" data-placement="top" placeholder="{{ trans('validation.attributes_placeholder.security') }}" title="{{ trans('validation.attributes_example.security') }}" minlength="1" error-value="{{ trans('validation.javascript_validation.security') }}">
                            <input type="hidden" name="key" value="{{$captcha['key']}}">
                        </div>
                    </div>
                </div>
                @include('forms.feed')
                <p>
                    @include('forms.button', [
                        'shape' => 'success',
                        'label' => trans('forms.button.send'),
                        'type' => 'submit',
                    ])

                    @include('forms.button', [
                        'shape' => 'warning',
                        'link' => 'register_card_step_one("stop")',
                        'label' => trans('forms.button.cancel'),
                    ])
                </p>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>