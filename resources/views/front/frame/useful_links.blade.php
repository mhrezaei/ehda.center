<div class="col-xs-12 col-md-4">
    @php
        $links = \App\Providers\PostsServiceProvider::selectPosts(['type' => 'useful-links'])->get();
    @endphp
    @if($links and $links->count())
        <h5>{{ trans('front.useful_links') }}</h5>
        @foreach($links as $key => $link)
            @php $link->spreadMeta() @endphp
            @if($key != 0) <br /> @endif
            <a href="{{ $link['link'] }}" target="_blank">{{ $link['title'] }}</a>
        @endforeach
    @endif
</div>