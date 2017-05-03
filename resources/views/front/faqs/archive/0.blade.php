@extends('front.frame.frame')

@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ trans('front.news') }}</title>
@endsection

@section('navbar')
    @include('front.frame.navbar',
    [
        'array' => $breadCrumb,
    ])
@endsection

@section('content')
    {!! \App\Providers\PostsServiceProvider::showList($selectConditions) !!}
    {{--@include('front.news.archive.content')--}}
@endsection