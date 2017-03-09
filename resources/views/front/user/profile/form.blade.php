<div class="container">
    <div class="row">
        <div class="col-sm-8 col-center">
            <section class="panel">
                <header>
                    <div class="title"> <span class="icon-pencil"></span> {{ trans('front.edit_profile') }} </div>
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
                                'class' => 'form-mobile',
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
                            <div class="field"> <label> رمز عبور جدید </label> <input type="text"> </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="field"> <label> تکرار رمز عبور </label> <input type="text"> </div>
                        </div>
                    </div>
                </article>
                <footer class="tal"> <a href="#" class="button green"> ذخیره </a> </footer>
                {!! Form::close() !!}
            </section>
        </div>
    </div>
</div>