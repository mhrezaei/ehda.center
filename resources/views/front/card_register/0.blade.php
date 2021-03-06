@extends('site.frame.frame')
<title>{{ trans('global.siteTitle') }} | {{ trans('site.global.card_register_page') }}</title>
@section('content')
    <div class="container-fluid">
        @include('site.frame.page_title', [
        'category' => trans('site.menu.join'),
        'parent' => trans('site.know_menu.organ_donation_card'),
        'sub' => trans('site.global.card_register_page')
        ])
        @include('site.card_register.form')
    </div>
@endsection