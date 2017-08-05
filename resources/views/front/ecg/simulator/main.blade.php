@if(!isset($dev))
    @php $dev = false @endphp
@endif

@extends('front.ecg.frame.frame')

@section('head')
    <title>ECG Simulator</title>
    {{ Html::style('assets/css/ecg-simulator.min.css') }}
@append

@section('body')
    <div class="container-main">
        <div class="container-inner monitor">
            <div class="col-lg-2 col-md-3 col-xs-12 monitor-column-1">
                @include('front.ecg.simulator.vital-singns')
            </div>
            <div class="col-lg-10 col-md-9 col-xs-12 monitor-column-2">
                @include('front.ecg.simulator.ecg-preview')
                @include('front.ecg.simulator.shocker-box')
                @include('front.ecg.simulator.management-panel')
                @include('front.ecg.simulator.second-preview')
            </div>
        </div>
    </div>

    @include('front.ecg.simulator.loading-window-cover')
@append

@section('end-of-body')
    @include('front.ecg.simulator.scripts')
@append