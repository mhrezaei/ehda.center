@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ trans('front.special_volunteers') }}</title>
@endsection

<div class="row celebs">
    @if($posts->count())
        @foreach($posts as $postIndex =>  $post)
            @include($viewFolder . '.item')
        @endforeach
    @endif
</div>

