@php $pageTitle = setting()->ask('site_title')->gain() . ' | ' . trans('front.special_volunteers') @endphp
@section('head')
    <title>{{ $pageTitle }}</title>
    @include($viewFolder . '.styles')
@append
@php $metaTags = [ 'title' => $pageTitle ] @endphp
@include('front.frame.meta_tags')
@include('front.frame.open_graph_meta_tags')

<div class="yt-gallery">
    <ul id="waterfall" class="waterfall">
        @if($posts->count())
            @foreach($posts as $post)
                @include($viewFolder . '.item')
            @endforeach
        @endif
    </ul>
</div>

@include($viewFolder. '.scripts')