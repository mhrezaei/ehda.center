<div class="row">
    @php
        /*
        * Define various types of fields to generate preview
        */
        $fieldsTemplate = [
            'text' => [
                ['name' => 'name'],
                ['name' => 'submitter_name'],
            ],
            'city' => [
                ['name' => 'city'],
            ],
            'date' => [
                ['name' => 'donation_date'],
            ],
            'numeric' => [
                [
                    'name' => 'submitter_phone',
                    'icon' => 'phone',
                ],
            ],
            'image' => [
                ['name' => 'image_files'],
            ],
            'file' => [
                ['name' => 'text_files'],
                ['name' => 'audio_files'],
                ['name' => 'video_files'],
            ],
        ];

        /*
        * Reveal fields to show
        */
        $defaultFieldsKeys = array_column(array_merge(...array_values($fieldsTemplate)), 'name');
        $defaultFieldsValues = array_fill(0, count($defaultFieldsKeys), '');
        $defaultFields = array_combine($defaultFieldsKeys, $defaultFieldsValues);

        $fields = array_normalize($comment->attributesToArray(), $defaultFields);
    @endphp

    @foreach ($fields as $fieldKey => $fieldValue)
        @foreach ($fieldsTemplate as $templateName => $templateData)
            @php $templateIndex = array_search($fieldKey, array_column($templateData, 'name')) @endphp
            @if ($templateIndex !== false)
                @php
                    $foundTemplateName = $templateName;
                    $fountTemplateData = $templateData[$templateIndex];
                    break;
                @endphp
            @endif
        @endforeach
        @isset($foundTemplateName)
            @if($comment->$fieldKey)
                @if(ends_with($fieldKey, '_files'))
                    @php $filesArray = json_decode($comment->$fieldKey, true) @endphp
                    @if($filesArray and is_array($filesArray) and count($filesArray))
                        @section('column-2')
                            @foreach($filesArray as $fileHashid)
                                @include("manage.comments.show-one-$foundTemplateName-preview")
                            @endforeach
                        @append
                    @endif
                @else
                    @php
                        $widgetData = [];
                        switch ($foundTemplateName) {
                            case 'city':
                                $value = $comment->city_name;
                                $widgetData['icon'] = 'map-marker';
                                break;

                            case 'date':
                                $value = ad(echoDate($comment->$fieldKey, 'j F Y'));
                                $widgetData['icon'] = 'calendar';
                                break;

                            case 'numeric':
                                $value = ad($comment->$fieldKey);
                                break;

                            default:
                                $value = $comment->$fieldKey;
                                break;
                        }

                        $widgetData['text'] = trans('validation.attributes.' . $fieldKey) . ': ' . $value;
                    @endphp
                    @isset($fountTemplateData['icon'])
                        @php $widgetData['icon'] = $fountTemplateData['icon'] @endphp
                    @endisset
                    @section('column-1')
                        <div class="col-xs-12">
                            @include("manage.frame.widgets.grid-tiny" , array_merge($widgetData, ['size' => 13]))
                        </div>
                    @append
                @endif
            @endif
        @endisset
    @endforeach

    <div class="col-md-6">
        @yield('column-1')
    </div>
    <div class="col-md-6">
        @yield('column-2')
    </div>

    @include("manage.frame.widgets.grid-text" , [
        'text' => $comment->text ,
        'size' => "12" ,
        'class' => "text-align" ,
    ])
</div>