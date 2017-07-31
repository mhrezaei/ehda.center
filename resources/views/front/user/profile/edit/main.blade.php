@extends('front.user.frame.main')

@section('head')
    {!! Html::style('assets/libs/bootstrap-select/bootstrap-select.min.css') !!}
    <style>
        a.ehda-card {
            display: none;
        }
    </style>
@append

@section('inner-content')
    <div class="row">
        <div class="col-md-8 col-xs-12">
            @include('front.user.profile.edit.form')
        </div>
        <div class="col-md-4 col-xs-12 pt30">
            @include('front.user.profile.edit.card')
        </div>
    </div>
@endsection

@section('endOfBody')
    @include('front.user.profile.edit.scripts')
@append