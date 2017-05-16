@extends('front.frame.frame')

@section('head')
    <title>{{ $pageTitle }}</title>
@endsection

@section('navbar')
    @include('front.frame.navbar',
    [
        'array' => $breadCrumb,
    ])
@endsection

@section('content')
    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-sm-10 col-center">
                    {!! $searchResultHTML !!}
                </div>
            </div>
        </div>
    </div>
@endsection