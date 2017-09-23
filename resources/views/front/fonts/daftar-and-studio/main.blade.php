@extends('front.frame.frame')

@php $pageTitle = setting()->ask('site_title')->gain() . ' | ' . $staticPost->title  @endphp
@section('head')
    <title>{{ $pageTitle }}</title>
@append

@section('content')
    <div class="container-fluid">
        @include('front.frame.position_info', [
            'group' => $staticPost->header_title,
            'groupColor' => 'green',
            'category' => $staticPost->category_title,
            'categoryColor' => 'green',
        ])
        <div class="container content">
            <div class="row">
                <div class="article">
                    @include('front.fonts.daftar-and-studio.content')
                    @include('front.fonts.daftar-and-studio.sharing')
                </div>
            </div>
        </div>
    </div>
@endsection

@include('front.fonts.daftar-and-studio.scripts')