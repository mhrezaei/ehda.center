{{ null, $post->spreadMeta() }}
@if($post->allow_anonymous_comment or user())
    {!! Form::open([
        'url' => \App\Providers\SettingServiceProvider::getLocale() . "/comment",
        'method'=> 'post',
        'class' => 'js',
        'name' => 'commentForm',
        'id' => 'commentForm',
        'style' => 'padding: 15px;',
    ]) !!}
    <div class="row">
        @include('forms.hidden',[
            'name' => 'post_id',
            'value' => $post->id,
        ])

        @include('forms.textarea', [
            'name' => 'text',
            'label' => false,
            'rows' => 4,
            'placeholder' => trans('validation.attributes_placeholder.your_comment'),
            'class' => 'form-required',
        ])

        <div class="col-xs-12">
            <div class="form-group pt15">
                <div class="action tal">
                    <button class="blue"> ثبت نظر</button>
                </div>
            </div>
        </div>
        <div class="col-xs-12 pt15">
            @include('forms.feed')
        </div>
    </div>
    {!! Form::close() !!}
@endif