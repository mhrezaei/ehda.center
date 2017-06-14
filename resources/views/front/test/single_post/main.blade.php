@extends('front.frame.frame')

@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ trans('front.posts') }}</title>
@endsection

@section('content')
    <style>
        .ehda-card {
            display: none
        }
    </style>
    <div class="container-fluid">
        @include('front.frame.position_info', [
            'group' => 'اخبار',
        ])
        <div class="container">
            <div class="row">
                <div class="article @if($showSideBar) col-xs-12 col-md-8 @endif">
                    @include('front.test.single_post.content')
                    @include('front.test.single_post.post_footer')
                </div>
                @if($showSideBar)
                    @include('front.test.single_post.sidebar')
                @endif
            </div>
        </div>
    </div>
@endsection