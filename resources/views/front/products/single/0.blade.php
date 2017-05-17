@extends('front.frame.frame')

@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ trans('front.products') }}</title>
    @include('front.frame.open_graph_meta_tags', $ogData)
@endsection

@section('navbar')
    @include('front.frame.navbar',
    [
        'array' => $breadCrumb,
        'title' => end($breadCrumb)[0],
    ])
@endsection

@section('content')
    <div class="page-content product-single">
        <div class="container">
            {!! $postHTML !!}
        </div>
    </div>
@endsection