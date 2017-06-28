@extends('front.frame.frame')

@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ trans('front.faqs') }}</title>
@endsection

@section('content')
    <div class="container-fluid">
        @include('front.frame.position_info', $positionInfo + [
            'groupColor' => 'green',
        ])
        <div class="container content">
            <div class="row">
                {!! $listHTML !!}
            </div>
        </div>
    </div>
@endsection