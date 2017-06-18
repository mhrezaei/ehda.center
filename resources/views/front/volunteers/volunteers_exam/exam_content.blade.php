{!! Html::script ('assets/libs/jquery.form.min.js') !!}
{!! Html::script ('assets/js/forms.js') !!}

<div class="row article">
    <div class="col-xs-12">
        <div class="container">
            <div class="row">
                    {!! Form::open([
                            'url'	=> 'volunteer/second_step' ,
                            'method'=> 'post',
                            'class' => 'clearfix js',
                            'name' => 'volunteer_form_step_second',
                            'id' => 'volunteer_form_step_second',
                        ]) !!}

                    <div class="form-group" style="margin-bottom: 40px; color: #0f0f0f;">
                        <div>{{ trans('site.global.volunteer_exam_detail') }}</div>
                    </div>

                    @foreach($tests as $key => $test)
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    @pd($key+1)- {!! $test['question'] !!}
                                    <div style="width: 90%; color: #002166; margin: 0 auto;">
                                        @foreach($test['options'] as $k => $answer)
                                            @include('site.volunteers.volunteers_exam.exam_radio_form', [
                                            'id' => $test['id'],
                                            'value' => encrypt($answer[1]),
                                            'label' => trans('forms.alphabet.' . $k),
                                            'title' => $answer[0]
                                            ])
                                        @endforeach
                                    </div>
                                    <hr style="background-color: #0A3C6E;">
                                </div>
                            </div>
                        </div>
                    @endforeach

                <p id="exam_count" class="hide-element">{{ trans('site.global.volunteer_exam_count') }}</p>
                <input type="hidden" name="exam" id="exam" value="{{ encrypt(count($tests)) }}">
                    @include('forms.feed')
                    @include('forms.button', [
                        'shape' => 'primary',
                        'label' => trans('site.global.volunteer_db_check_send_sheet'),
                        'type' => 'submit',
                        'class' => 'hide-element',
                    ])

                    @include('forms.button', [
                        'shape' => 'success',
                        'label' => trans('forms.button.send_answer_sheet'),
                        'type' => 'button',
                        'link' => 'volunteer_send_sheet(this)'
                    ])

                    {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>