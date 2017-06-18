@extends('site.frame.frame')
<title>{{ trans('global.siteTitle') }} | {{ $volunteer->title }}</title>
@section('content')
    <div class="container-fluid">
        @include('site.frame.page_title', [
        'category' => $volunteer->say('header'),
        'parent' => $volunteer->say('category_name'),
        'sub' => $volunteer->title
        ])
        @include('site.volunteers.volunteers_exam.exam_content')
    </div>
@endsection