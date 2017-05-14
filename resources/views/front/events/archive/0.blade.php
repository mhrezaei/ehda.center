@extends('front.frame.frame')

@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ trans('front.news') }}</title>
    @include('front.frame.open_graph_meta_tags', $ogData)
@endsection

@section('navbar')
    @include('front.frame.navbar', [
        'array' => $breadCrumb,
    ])
@endsection

@section('content')
    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-center">
                    {!! $accordion !!}
                </div>
            </div>
        </div>
    </div>
@endsection