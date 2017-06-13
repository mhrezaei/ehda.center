@if($post->canRecieveComments() or true)

    {{--to add any input:--}}
    {{--    1. add its name to $availableFields--}}
    {{--    2. add its block to form with using its size--}}
    {{--    3. add its input with useing $inputData of the input--}}

    {{ null, $availableFields = [
            'name',
            'email',
            'mobile',
            'title',
            'text',
        ] }}

    {{ null,
        $fields = (!isset($fields) or !is_array($fields))
            ? CommentServiceProvider::translateFields($post->fields)
            : $fields
        }}

    {{ null,
        $rules = (!isset($rules) or !is_array($rules))
            ? CommentServiceProvider::translateRules($post->rules)
            : $rules
        }}

    {{ null, $inputSize = array_flip($availableFields) }}
    {{ null, $inputSize = array_fill_keys($availableFields, 12) }}


    @foreach($availableFields as $fieldName)
        @if(array_key_exists($fieldName, $fields))
            {{ null, $inputData[$fieldName]['condition'] = true }}
            {{ null, $inputData[$fieldName]['class'] =
                ((array_key_exists($fieldName, $rules) and (array_search('required', $rules[$fieldName]) !== false)) ? 'form-required' : '') }}
            {{ null, $inputData[$fieldName]['label'] = $fields[$fieldName]['label'] }}
            @if(is_numeric($fields[$fieldName]['size'])
                and is_int((int) $fields[$fieldName]['size'])
                and $fields[$fieldName]['size'] <= 12)
                {{ null, $inputSize[$fieldName] = $fields[$fieldName]['size'] }}
            @endif
        @else
            {{ null, $inputData[$fieldName]['condition'] = false }}
        @endif
    @endforeach

    {!! Form::open([
        'url' => \App\Providers\SettingServiceProvider::getLocale() . "/comment",
        'method'=> 'post',
        'class' => 'js',
        'name' => 'commentForm',
        'id' => 'commentForm',
        'style' => 'padding: 15px;',
    ]) !!}
    <div class="row">
        @include('front.forms.hidden',[
            'name' => 'post_id',
            'value' => $post->id,
        ])

        <div class="col-xs-{{ $inputSize['name'] }}">
            <div class="row">
                @include('front.forms.input', [
                    'name' => 'name',
                    'placeholder' => trans('validation.attributes.first_and_last_name'),
                    'label' => trans('validation.attributes.first_and_last_name'),
                ] + $inputData['name'])
            </div>
        </div>

        <div class="col-xs-{{ $inputSize['email'] }}">
            <div class="row">
                @include('front.forms.input', [
                    'name' => 'email',
                    'placeholder' => trans('validation.attributes.email'),
                ] + $inputData['email'])
            </div>
        </div>

        <div class="col-xs-{{ $inputSize['mobile'] }}">
            <div class="row">
                @include('front.forms.input', [
                    'name' => 'mobile',
                    'placeholder' => trans('validation.attributes.mobile'),
                ] + $inputData['mobile'])
            </div>
        </div>

        <div class="col-xs-{{ $inputSize['title'] }}">
            <div class="row">
                @include('front.forms.input', [
                    'name' => 'title',
                    'placeholder' => trans('validation.attributes.title'),
                ] + $inputData['title'])
            </div>
        </div>

        <div class="col-xs-{{ $inputSize['text'] }}">
            <div class="row">
                @include('front.forms.textarea', [
                    'name' => 'text',
                    'rows' => 4,
                    'placeholder' => trans('validation.attributes_placeholder.your_comment'),
                ] + $inputData['text'])
            </div>
        </div>


        <div class="col-xs-12">
            <div class="form-group pt15">
                <div class="action tal">
                    <button class="btn btn-primary pull-left">{{ trans('forms.general.submit') }}</button>
                </div>
            </div>
        </div>
        <div class="col-xs-12 pt15">
            @include('front.forms.feed')
        </div>
    </div>
    {!! Form::close() !!}
@endif