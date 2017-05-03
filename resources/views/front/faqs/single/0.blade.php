@extends('front.frame.frame')

@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ trans('front.faqs') }}</title>
@endsection

@section('navbar')
    @include('front.frame.navbar',
    [
        'array' => $breadCrumb,
    ])
@endsection

@section('content')
    {!! \App\Providers\PostsServiceProvider::showPost($faq) !!}
@endsection