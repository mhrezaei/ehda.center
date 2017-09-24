@extends('front.frame.frame')

@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ trans('front.about') }}</title>
@endsection

@section('content')
    <style>
        .ehda-card {
            display: none
        }
    </style>
    <div class="container-fluid">
        @include('front.frame.position_info', [
            'group' => trans('front.contact_us'),
            'groupColor' => 'green',
        ])
        <div class="container content">
            <div class="row">
                <div class="col-xs-12">
                    <br>
                    @include('front.test.about.contact_lines')
                    @include('front.test.about.contact_form')
                </div>
            </div>
            @include('front.about.map')
        </div>
    </div>
@endsection