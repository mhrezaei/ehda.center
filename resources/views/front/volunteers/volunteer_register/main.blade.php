{{--{{ dd(__FILE__) }}--}}

@extends('front.frame.frame')

{{ null, $post->spreadMeta() }}

@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ $post->title }}</title>
@append

@section('content')
    <div class="container-fluid">

        @include('front.frame.position_info', [
            'group' => $post->header_title,
            'groupColor' => 'green',
            'category' => $post->category_title,
            'title' => trans('front.volunteer_section.register')
        ])

        @include('front.volunteers.volunteer_register.form')
    </div>
@endsection