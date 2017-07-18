@if($posts->count())

    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="row">

                {{ null, $selectOptions = [] }}
                @foreach($posts as $post)
                    {{ null, $post->spreadMeta() }}

                    {{-- generating array for options of select element that will be used for selecting file type--}}
                    {{ null, $selectOptions[] = ['id' => $post->id, 'title' => trans("front.file_types.$post->fileType.title")] }}
                    @if($post->fields)
                        {{ null, $fields[$post->id] = explodeNotEmpty(',' , $post->fields) }}
                        {{ null, $postRules = \App\Providers\CommentServiceProvider::translateRules($post->rules) }}
                        @foreach($fields[$post->id] as $fieldIndex => $fieldValue)
                            @unset($fields[$post->id][$fieldIndex])
                            {{ null, $fieldValue = trim($fieldValue) }}

                            @if(str_contains($fieldValue, '-label'))
                                {{ null, $fieldValue = str_replace('-label', '', $fieldValue) }}
                                {{ null, $tmpField['label'] = true }}
                            @else
                                {{ null, $tmpField['label'] = false }}
                            @endif

                            @if(str_contains($fieldValue, ':'))
                                {{ null, $fieldValueParts = explodeNotEmpty(':', $fieldValue) }}
                                {{ null, $fieldValue = $fieldValueParts[0] }}
                                {{ null, $tmpField['size'] = $fieldValueParts[1] }}
                            @else
                                {{ null, $tmpField['size'] = '' }}
                            @endif

                            @if(array_key_exists($fieldValue, $postRules) and (in_array('required', $postRules[$fieldValue])))
                                {{ null, $tmpField['required'] = true }}
                            @else
                                {{ null, $tmpField['required'] = false }}
                            @endif

                            {{ null, $fields[$post->id][$fieldValue] = $tmpField }}
                        @endforeach
                    @endif
                @endforeach

                {{-- generate an indexed array of file types --}}
                {{ null, $fileTypes = $posts->pluck('fileType')->toArray() }}

                @include('front.forms.select', [
                    'id' => 'file-type',
                    'name' => 'file_type',
                    'options' => $selectOptions
                ])

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            @section('endOfBody')
                {!! Html::script('assets/libs/jquery.form.min.js') !!}
                {!! Html::script('assets/js/forms.min.js') !!}
                <script>

                    // convert $fields array to json to be used in js code
                    var fields = {!! json_encode($fields) !!};

                    /**
                     * kick the element out from the page
                     * hide it and disable it to not be submitted
                     */
                    $.fn.kickOut = function () {
                        $(this).hide();
                        $(this).findFromThis(':input').each(function () {
                            $(this).attr('disabled', 'disabled');
                        });
                    };

                    /**
                     * turn the element over to the page
                     */
                    $.fn.turnOver = function (required) {
                        $(this).findFromThis(':input').each(function () {
                            if (isDefined(required) && required) {
                                $(this).changeRequirement(true);
                            } else {
                                $(this).changeRequirement(false);
                            }
                            $(this).removeAttr('disabled');
                        });

                        $(this).show();
                    };

                    /**
                     * Changes requirement of an alement
                     * @param {bool} requirement
                     */
                    $.fn.changeRequirement = function (requirement) {
                        var requiredSign = $(this).closest('.form-group').find('.required-sign');
                        if (requirement && $(this).is('[type!=hidden]')) {
                            $(this).addClass('form-required');
                            requiredSign.show();
                        } else {
                            $(this).removeClass('form-required');
                            requiredSign.hide();
                        }
                    };

                    function customResetForm() {
                        $.each(Dropzone.instances, function (index, obj) {
                            obj.removeAllFiles();
                        });
                        $('form#commentForm').find('input[type=hidden].optional-input, :input:visible')
                            .val('');
                    }

                    $(document).ready(function () {

                        $('#file-type').change(function () {
                            var id = $(this).val();
                            var postFields = fields[id];
                            $('#post-id').val(id);

                            $('.optional-input').each(function () {
                                var elem = $(this);
                                var fieldName = elem.attr('data-field');
                                if ($.inArray(fieldName, Object.keys(postFields)) > -1) {
                                    elem.turnOver(postFields[fieldName]['required']);
                                } else {
                                    elem.kickOut();
                                }
                            })
                        }).change();
                    });
                </script>
            @append

            {{ null, \App\Providers\UploadServiceProvider::setDefaultJsConfigs([
    //                'events' => [
    //                    'complete' => <<< JS
    //                    function (file) {
    //                        console.log(this.getAcceptedFiles());
    //                        window.tf = this.getAcceptedFiles();
    //                    }
    //JS
    //                ]
            ]) }}
            {{-- generate uploaders panel for all files types --}}
            @foreach($fileTypes as $fileType)
                {{ null, $dataField = "{$fileType}_uploader" }}
                {{-- id of whole dropzone element --}}
                {{ null, $id = "$fileType-uploader" }}
                {{-- name of dropzone object that will be created (if not set it will be automatically generated) --}}
                {{ null, $varName = camel_case($id . '-dropzone') }}
                {!! UploadServiceProvider::dropzoneUploader($fileType, [
                    'id' => $id,
                    'varName' => $varName,
                    'dataAttributes' => [
                        'field' => $dataField,
                    ],
                    'target' => "file-$fileType",
                ]) !!}


            @section('hiddenFields')
                @include('front.forms.hidden',[
                    'id' => "file-$fileType",
                    'name' => "{$fileType}_files",
                    'extra' => "data-field=$dataField",
                    'class' => 'optional-input',
                ])
            @append

            @endforeach


            {!! Form::open([
                'url' => route('comment.submit', ['lang' => getLocale()]),
                'method'=> 'post',
                'class' => 'js',
                'name' => 'commentForm',
                'id' => 'commentForm',
            ]) !!}
            @include('front.forms.hidden',[
                'name' => 'post_id',
                'value' => '',
                'id' => 'post-id',
            ])

            @yield('hiddenFields')
            <div class="row">
                @unset($id)

                <div class="col-xs-12 optional-input" data-field="text_content">
                    <div class="row">
                        @include('front.forms.textarea', [
                            'name' => 'text_content',
                            'rows' => 4,
                            'placeholder' => trans('validation.attributes.text_content'),
                            'label' => trans('validation.attributes.text_content'),
                            'required' => true,
                        ])
                    </div>
                </div>

                <div class="col-md-6 col-xs-12 optional-input" data-field="subject">
                    <div class="row">
                        @include('front.forms.input', [
                            'name' => 'subject',
                            'placeholder' => trans('validation.attributes.title'),
                            'label' => trans('validation.attributes.submission_work_subject'),
                            'required' => true,
                        ])
                    </div>
                </div>

                <div class="col-md-6 col-xs-12 optional-input" data-field="name">
                    <div class="row">
                        @include('front.forms.input', [
                            'name' => 'name',
                            'placeholder' => trans('validation.attributes.first_and_last_name'),
                            'label' => trans('validation.attributes.submission_work_owner_name'),
                            'required' => true,
                        ])
                    </div>
                </div>

                <div class="col-md-6 col-xs-12 optional-input" data-field="mobile">
                    <div class="row">
                        @include('front.forms.input', [
                            'name' => 'mobile',
                            'placeholder' => trans('validation.attributes.mobile'),
                            'label' => trans('validation.attributes.submission_work_owner_mobile'),
                            'required' => true,
                        ])
                    </div>
                </div>

                <div class="col-md-6 col-xs-12 optional-input" data-field="email">
                    <div class="row">
                        @include('front.forms.input', [
                            'name' => 'email',
                            'placeholder' => trans('validation.attributes.email'),
                            'label' => trans('validation.attributes.submission_work_owner_email'),
                            'required' => true,
                        ])
                    </div>
                </div>

                <div class="col-xs-12 optional-input" data-field="description">
                    <div class="row">
                        @include('front.forms.textarea', [
                            'name' => 'description',
                            'rows' => 4,
                            'placeholder' => trans('validation.attributes.description'),
                            'label' => trans('validation.attributes.description'),
                            'required' => true,
                        ])
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="form-group pt15">
                        <div class="action tal">
                            <button class="btn btn-primary pull-left">{{ trans('front.send_work') }}</button>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 pt15">
                    @include('front.forms.feed')
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

@endif

