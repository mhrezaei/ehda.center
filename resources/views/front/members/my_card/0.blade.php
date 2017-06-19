@extends('site.frame.frame')
<title>{{ trans('global.siteTitle') }} | {{ $card_detail->title }}</title>
@section('content')
    <div class="container-fluid">
        @include('site.frame.page_title', [
        'category' => $card_detail->meta('header_title'),
        'parent' => $card_detail->meta('category_title'),
        'sub' => $card_detail->title
        ])
        @include('site.members.my_card.card_info_content')
    </div>
@endsection