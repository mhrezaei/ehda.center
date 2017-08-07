@extends('front.frame.frame')

@php $post->spreadMeta() @endphp

@php $pageTitle = setting()->ask('site_title')->gain() . ' | ' . $post->title @endphp
@section('head')
    <title>{{ $pageTitle }}</title>
@append
@php $metaTags = [ 'title' => $pageTitle ] @endphp
@include('front.frame.meta_tags')
@include('front.frame.open_graph_meta_tags')

@section('content')
    <div class="container-fluid">

        @include('front.frame.position_info', [
            'group' => $post->header_title,
            'groupColor' => 'green',
            'category' => $post->category_title,
            'title' => $post->title,
        ])

        @include('front.volunteers.volunteers_info.volunteers_content')
    </div>
@endsection