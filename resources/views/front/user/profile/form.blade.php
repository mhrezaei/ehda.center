<div class="container">
    <div class="row">
        <div class="col-sm-8 col-center">
            <section class="panel">
                <header>
                    <div class="title"><span class="icon-pencil"></span> {{ trans('front.edit_profile') }} </div>
                </header>
                {!! Form::open([
                    'url'	=> '/user/profile/update' ,
                    'method'=> 'post',
                    'class' => 'js',
                    'name' => 'editForm',
                    'id' => 'editForm',
                    'style' => 'padding: 15px;',
                    
                ]) !!}
                <article>
                    <div class="row">
                        <div class="col-sm-6">
                            @include('front.user.profile.field', [
                                'name' => 'name_first',
                                'type' => 'text',
                                'value' => user()->name_first,
                                'class' => 'form-required form-persian',
                                'min' => 2,
                            ])
                        </div>
                        <div class="col-sm-6">
                            @include('front.user.profile.field', [
                                'name' => 'name_last',
                                'type' => 'text',
                                'value' => user()->name_last,
                                'class' => 'form-required form-persian',
                                'min' => 2,
                            ])
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            @include('front.user.profile.field', [
                                'name' => 'code_melli',
                                'type' => 'text',
                                'value' => user()->code_melli,
                                'class' => 'form-required form-national',
                                'other' => 'disabled=disabled',
                            ])
                        </div>
                        <div class="col-sm-6">
                            @include('front.user.profile.field', [
                                'name' => 'name_father',
                                'type' => 'text',
                                'value' => user()->name_father,
                                'class' => 'form-required form-persian',
                                'min' => 2,
                            ])
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            @include('front.frame.widgets.datepicker_input', [
                                'name' => 'birth_date',
                                'type' => 'text',
                                'value' => user()->birth_date,
                                'class' => 'form-required',
                                'options' => [
                                    'maxDate' => 0,
                                    'changeYear' => true,
                                    'yearRange' => '-100,+0',
                                ]
                            ])
                        </div>
                        <div class="col-sm-6">
                            @include('front.frame.widgets.select', [
                                'name' => 'name_father',
                                'type' => 'text',
                                'value' => user()->name_father,
                                'class' => 'form-required form-persian',
                                'min' => 2,
                            ])
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-6">
                            @include('front.user.profile.field', [
                                'name' => 'mobile',
                                'type' => 'text',
                                'value' => user()->mobile,
                                'class' => 'form-required form-mobile',
                            ])
                        </div>
                        <div class="col-sm-6">
                            @include('front.user.profile.field', [
                                'name' => 'home_tel',
                                'type' => 'text',
                                'value' => user()->home_tel,
                                'class' => 'form-required form-phone',
                            ])
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            @include('front.user.profile.field', [
                                'name' => 'email',
                                'type' => 'text',
                                'value' => user()->email,
                                'class' => 'form-email',
                            ])
                        </div>
                        <div class="col-sm-6"></div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-6">
                            @include('front.user.profile.sex')
                        </div>
                        <div class="col-sm-6">
                            @include('front.user.profile.marital')
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-6">
                            @include('front.user.profile.field', [
                                'name' => 'new_password',
                                'type' => 'password',
                                'class' => 'form-mobile form-password',
                            ])
                        </div>
                        <div class="col-sm-6">
                            @include('front.user.profile.field', [
                                'name' => 'new_password2',
                                'type' => 'password',
                                'class' => 'form-mobile',
                            ])
                        </div>
                    </div>
                </article>
                @include('forms.feed')
                <footer class="tal">
                    {{--<a href="#" class="button green"> ذخیره </a>--}}
                    <button type="submit" class="button green">{{ trans('forms.button.save') }}</button>
                </footer>
                {!! Form::close() !!}
            </section>
        </div>
    </div>
</div>
<script>
    $('#name_last').datepicker({
        'maxDate': 0,
    })
</script>