@php $pageTitle = setting()->ask('site_title')->gain() . ' | ' . trans('front.special_volunteers') @endphp
@section('head')
    <title>{{ $pageTitle }}</title>
@append
@php $metaTags = [ 'title' => $pageTitle ] @endphp
@include('front.frame.meta_tags')
@include('front.frame.open_graph_meta_tags')

<div class="row celebs">
    @if($posts->count())
        @foreach($posts as $postIndex =>  $post)
            @include($viewFolder . '.item')
        @endforeach
    @endif
</div>

