@extends('front.frame.frame')

@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ trans('front.posts') }}</title>
@endsection

@section('content')
    <style>
        .ehda-card {
            display: none
        }
    </style>
    <div class="container-fluid">
        @include('front.frame.position_info', [
            'group' => 'بایگانی خبرها',
            'groupColor' => 'green',
            'category' => 'دسته بندی',
            'categoryColor' => 'red',
            'title' => 'عنوان',
            'titleColor' => 'blue',
            'description' => ' توضیحات توضیحات توضیحات توضیحات توضیحات توضیحات توضیحات توضیحات',
            'descriptionColor' => 'orange',
        ])
        <div class="container">
            <div class="row archive">
                @for($i = 0; $i < 3; $i++)
                    @include('front.test.archive_post.item')
                @endfor
            </div>
        </div>
    </div>
@endsection