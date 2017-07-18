@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ trans('front.angels.plural') }}</title>
    {!! Html::style('assets/libs/bootstrap-select/bootstrap-select.min.css') !!}
    <style>
        .ui-menu.ui-autocomplete {
            max-height: 100px;
            overflow-y: auto;
            overflow-x: hidden;
        }
    </style>
@append