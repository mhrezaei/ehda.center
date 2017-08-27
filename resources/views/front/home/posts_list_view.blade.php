@if(!isset($color) or !$color)
    {{ null, $color = "black" }} {{-- default color --}}
@endif

{{ null, $showMoreColorClass = "link-$color" }}
{{ null, $itemsColorClass = "border-start-$color" }}

@if(isset($title) and $title)
    @include('front.frame.underlined_heading', [
        'text' => $title,
        'color' => $color
    ])
@endif
@if(isset($moreLink) and $moreLink)
    <a href="{{ $moreLink }}"
       class="floating-top-25 floating-end-15 {{ $showMoreColorClass }}">{{ trans('front.more') }}</a>
@endif
<div class="media-list">
    @foreach($posts as $post)
        @include('front.home.posts_list_view_item')
    @endforeach
</div>