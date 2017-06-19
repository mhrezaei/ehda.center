@extends('front.frame.frame')

@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ trans('front.send_works') }}</title>
@append

@section('content')
    <style>
        .ehda-card {
            display: none
        }
    </style>
    <div class="container-fluid">
        @include('front.frame.position_info', [
                'group' => trans('front.send_works'),
                'groupColor' => 'green',
            ])
        <div class="container content">
            <div class="row">
                <div class="col-xs-12">
                    {!! $postContentHTML !!}
                </div>
            </div>
        </div>
    </div>
@endsection