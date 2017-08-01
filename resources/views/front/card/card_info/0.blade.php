@extends('front.frame.frame')

{{ null, $post->spreadMeta() }}

@php $pageTitle = setting()->ask('site_title')->gain() . ' | ' . $post->title @endphp
@section('head')
    <title>{{ $pageTitle }}</title>
@append
@include('front.frame.meta_tags', [
    'metaTags' => [
        'title' => $pageTitle,
    ]
])
@include('front.frame.open_graph_meta_tags',[
    'metaTags' => [
        'title' => $post->title,
        'url' => url('organ_donation_card'),
        'image' => url('/assets/site/images/cardMini.png'),
        'description' => $post->abstract,
    ]
])

@section('content')
    <div class="container-fluid">
        @include('front.frame.position_info', [
            'group' => $post->header_title,
            'category' => $post->category_title,
            'groupColor' => 'green',
        ])
        @include('front.card.card_info.card_info_content')
    </div>
@endsection