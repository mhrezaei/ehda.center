@extends('front.user.frame.main')


@section('head')
    @include('front.user.dashboard.styles')
@append

{{-- Donwload Links to Be Used in JS --}}
{{ null, $downloadLinks = [] }}

{{ null, $cardTypes = ['single', 'mini', 'social', 'full'] }}

@foreach($cardTypes as $key => $cardType)
    {{-- Generate donwload links to be used in js --}}
    {{ null, $downloadLinks[$cardType] = user()->cards($cardType, 'download') }}

    {{-- Generate nav tab for each card type --}}
@section('nav-tabs')
    <li @if($key == 0) class="active" @endif data-card-type="{{ $cardType }}">
        <a data-toggle="tab" href="#card-{{ $cardType }}"
           class="has-popover-tab" data-card-type="{{ $cardType }}">
            {{ trans('front.organ_donation_card_section.types.' . $cardType . '.title') }}
        </a>
    </li>
@append

{{-- Generate tab pane for each card type --}}
@section('tab-content')
    <div id="card-{{ $cardType }}" class="tab-pane fade @if($key == 0) in active @endif"
         data-card-type="{{ $cardType }}">
        <div class="row">
            <div class="col-xs-12 text-center">
                <a href="{{ user()->cards($cardType) }}" target="_blank">
                    <img src="{{ user()->cards($cardType) }}" class="img-responsive border-1 border-lightGray">
                    <button class="btn btn-white expand-btn">
                        <i class="fa fa-expand"></i>
                    </button>
                </a>
                <p class="text-justify">
                    {{ trans('front.organ_donation_card_section.types.' . $cardType . '.description') }}
                </p>
            </div>
        </div>
    </div>
@append
@endforeach

@section('inner-content')
    <div class="row mt40">
        <div class="col-md-6 col-xs-12">
            @include('front.user.dashboard.card_part')
        </div>
        <div class="col-md-6 col-xs-12">
            @include('front.user.dashboard.panel_part')
        </div>
    </div>
@endsection

@section('endOfBody')
    @include('front.user.dashboard.scripts')
@append

