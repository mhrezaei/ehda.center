@extends('front.frame.frame')

@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ trans('front.products') }}</title>
@endsection

@section('navbar')
    @include('front.frame.navbar',
    [
        'array' => [
            [trans('front.home'), url_locale('')],
            [trans('posts.features.basket'), request()->url()],
        ],
        'title' => trans('posts.features.basket'),
    ])
@endsection


@section('content')
    <div class="page-content">
        <div class="container">
            @if($cart->items) {{-- check if there is any item in cart --}}
                @include('front.cart.full')
            @else
                @include('front.cart.empty')
            @endif

        </div>
    </div>
@endsection