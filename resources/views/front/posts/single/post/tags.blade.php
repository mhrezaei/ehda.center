@if($post->categories->count())
    <div class="share col-xs-12 f10">
        {{ trans('posts.features.tags') }}:
        @foreach($post->categories as $category)
            <a class="btn btn-sm btn-info f12 mb5" href="{{ url_locale(implode(DIRECTORY_SEPARATOR, [
            'archive',
            $category->folder->posttype->slug,
            $category->slug,
        ])) }}">{{ $category->title }}</a>
        @endforeach

        {{-- @todo: add links for tags --}}
    </div>
@endif