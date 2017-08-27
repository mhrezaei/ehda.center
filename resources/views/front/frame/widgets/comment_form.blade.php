@if($post->canReceiveComments() or true)

    {{--to add any input:--}}
    {{--    1. add its name to $availableFields--}}
    {{--    2. add its block to form with using its size--}}
    {{--    3. add its input with useing $inputData of the input--}}

    @php
        $availableFields = [
            'name',
            'email',
            'mobile',
            'subject',
            'text',
            'donation_date',
            'city',
            'submitter_name',
            'submitter_phone',
            'image',
        ];
        $fields = (!isset($fields) or !is_array($fields))
                ? CommentServiceProvider::translateFields($post->fields)
                : $fields;
        $rules = (!isset($rules) or !is_array($rules))
                ? CommentServiceProvider::translateRules($post->rules)
                : $rules;
        $inputSize = array_flip($availableFields);
        $inputSize = array_fill_keys($availableFields, 12);
    @endphp

    {!! Form::open([
        'url' => route_locale('comment.submit'),
        'method'=> 'post',
        'class' => 'js',
        'name' => 'commentForm',
        'id' => 'commentForm',
        'style' => 'padding: 15px;',
    ]) !!}
    <div class="row">
        @include('front.forms.hidden',[
            'name' => 'post_id',
            'id' => 'post_id',
            'value' => $post->hashid,
        ])


        @foreach($fields as $fieldName => $fieldInfo)
            @if(in_array($fieldName, $availableFields))
                @php
                    $inputData = []
                @endphp

                {{-- Making decision about requirenment --}}
                @if (array_key_exists($fieldName, $rules) and (array_search('required', $rules[$fieldName]) !== false))
                    @php
                        $inputClass ='form-required'
                    @endphp
                @else
                    @php
                        $inputClass =''
                    @endphp
                @endif

                {{-- Making decision about label --}}
                @if(!$fieldInfo['label'])
                    @php
                        $inputData['label'] = $fieldInfo['label']
                    @endphp
                @endif

                {{-- Making decision about column size --}}
                @if(is_numeric($fieldInfo['size'])
                    and is_int((int) $fieldInfo['size'])
                    and $fieldInfo['size'] <= 12)
                    @php
                        $inputSize = $fieldInfo['size']
                    @endphp
                @else
                    @php
                        $inputSize = 12
                    @endphp
                @endif


                {{-- Generating view of field --}}
                @if($fieldName == 'name')
                    <div class="col-xs-{{ $inputSize }}">
                        <div class="row">
                            @include('front.forms.input', [
                                'name' => $fieldName,
                                'class' => $inputClass,
                                'placeholder' => trans('validation.attributes.first_and_last_name'),
                            ] + $inputData)
                        </div>
                    </div>

                @elseif(in_array($fieldName, [
                    'email',
                    'mobile',
                    'subject',
                    'submitter_name',
                    'submitter_phone',
                ]))

                    @php $additiveClass = '' @endphp
                    @if($fieldName == 'email')
                        @php $additiveClass .= 'form-email' @endphp
                    @elseif($fieldName == 'mobile')
                        @php $additiveClass .= 'form-mobile' @endphp
                    @endif

                    <div class="col-xs-{{ $inputSize }}">
                        <div class="row">
                            @include('front.forms.input', [
                                'name' => $fieldName,
                                'class' => $inputClass . ' ' . $additiveClass,
                                'placeholder' => trans('validation.attributes.' . $fieldName),
                            ] + $inputData)
                        </div>
                    </div>

                @elseif($fieldName == 'donation_date')
                    <div class="col-xs-{{ $inputSize }}">
                        <div class="row">
                            @include('front.forms.jquery-ui-datepicker', [
                                'name' => $fieldName,
                                'class' => $inputClass,
                                'placeholder' => trans('validation.attributes.' . $fieldName),
                                'label' => trans('validation.attributes.' . $fieldName),
                                'options' => [
                                    'maxDate' => 0,
                                    'changeYear' => true,
                                    'yearRange' => '-100,0',
                                ]
                            ] + $inputData)
                        </div>
                    </div>

                @elseif($fieldName == 'city')
                    <div class="col-xs-{{ $inputSize }}">
                        <div class="row">
                            @include('front.forms._states-selectpicker', [
                                'name' => 'city' ,
                                'class' => $inputClass,
                            ] + $inputData)
                        </div>
                    </div>

                @elseif($fieldName == 'text')
                    <div class="col-xs-{{ $inputSize }}">
                        <div class="row">
                            @include('front.forms.textarea', [
                                'name' => $fieldName,
                                'class' => $inputClass,
                                'rows' => 4,
                                'placeholder' => trans('validation.attributes_placeholder.your_comment'),
                            ] + $inputData)
                        </div>
                    </div>
                @elseif($fieldName == 'image')
                    {{--generate uploader panel--}}
                    @php
                        $dataField = "{$fieldName}_uploader";
                        // id of whole dropzone element
                        $id = "$fieldName-uploader";
                        // name of dropzone object that will be created (if not set it will be automatically generated)
                        $varName = camel_case($id . '-dropzone');
                        // generate fileTypeString
                        $fileTypeString = $fieldName;
                    @endphp

                    @isset($fileTypePrefix)
                        @php $fileTypeString = $fileTypePrefix . '.' . $fileTypeString; @endphp
                    @endisset

                    <div class="col-xs-{{ $inputSize }}">
                        {!! UploadServiceProvider::dropzoneUploader($fileTypeString, [
                            'id' => $id,
                            'varName' => $varName,
                            'dataAttributes' => [
                                'field' => $dataField,
                            ],
                            'target' => "file-$fieldName",
                        ]) !!}

                        @include('front.forms.hidden',[
                            'id' => "file-$fieldName",
                            'name' => "{$fieldName}_files",
                            'extra' => "data-field=$dataField",
                            'class' => 'optional-input',
                        ])
                    </div>
                @endif
            @endif
        @endforeach


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



@section('endOfBody')
    @include('front.frame.datepicker_assets')
    {!! Html::script ('assets/libs/bootstrap-select/bootstrap-select.min.js') !!}
    {!! Html::script ('assets/libs/jquery.form.js') !!}
    {!! Html::script ('assets/js/forms.js') !!}
    <script>
        function customResetForm() {
            $('#commentForm').find(':input:visible').val('').change();
            $('#commentForm').find('.form-group').removeClass('has-success');

            $.each(Dropzone.instances, function (index, obj) {
                obj.removeAllFiles();
            });
        }
    </script>
@append