@extends('site.frame.frame')
<title>{{ trans('global.siteTitle') }} | {{ trans('site.global.card_edit_page') }}</title>
@section('content')
    <div class="container-fluid">
        @include('site.frame.page_title', [
        'category' => trans('site.menu.join'),
        'parent' => trans('site.know_menu.organ_donation_card'),
        'sub' => trans('site.global.card_edit_page')
        ])
        @include('site.members.edit_my_card.form')
    </div>
@endsection