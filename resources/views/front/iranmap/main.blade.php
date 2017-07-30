@extends('front.frame.frame')

@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ trans('front.states') }}</title>
    @include('front.iranmap.styles')
@endsection

@section('content')
    <div class="container-fluid">
        @include('front.frame.position_info', [
            'group' => trans('front.states_entrance'),
            'groupColor' => 'green'
        ])
        <div class="container">
            <div class="row">
                @include('front.iranmap.state-info')
                @include('front.iranmap.map')
            </div>
        </div>
    </div>
@endsection

@include('front.iranmap.scripts')