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
                $fountTemplateName = $templateName;
                $fountTemplateData = $templateData[$templateIndex];
                break;
            @endphp
        @endif
    @endforeach

    @isset($fountTemplateName)
        @if($comment->$fieldKey)
            @php
                $widgetData = [];
                switch ($fountTemplateName) {
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
            @include("manage.frame.widgets.grid-tiny" , $widgetData)
        @endif
    @endisset
@endforeach

@include("manage.frame.widgets.grid-text" , [
    'text' => $comment->text ,
    'size' => "12" ,
    'class' => "text-align" ,
])