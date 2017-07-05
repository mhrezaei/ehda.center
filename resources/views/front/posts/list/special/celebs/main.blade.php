@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ trans('front.special_volunteers') }}</title>
    @include($viewFolder . '.styles')
@endsection

<div class="container content">
    <div class="yt-gallery">
        <ul id="waterfall" class="waterfall">
            @if($posts->count())
                @foreach($posts as $post)
                    @include($viewFolder . '.item')
                @endforeach
            @endif
        </ul>
    </div>
</div>

@include($viewFolder. '.scripts')