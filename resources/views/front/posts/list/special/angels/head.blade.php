@php $pageTitle = setting()->ask('site_title')->gain() . ' | ' . trans('front.angels.plural') @endphp

@section('head')
    <title>{{ $pageTitle }}</title>
    {!! Html::style('assets/libs/bootstrap-select/bootstrap-select.min.css') !!}
    <style>
        .ui-menu.ui-autocomplete {
            max-height: 100px;
            overflow-y: auto;
            overflow-x: hidden;
        }
    </style>
@append
@php $metaTags = [ 'title' => $pageTitle ] @endphp
@include('front.frame.meta_tags')
@include('front.frame.open_graph_meta_tags')
