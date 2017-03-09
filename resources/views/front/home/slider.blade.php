@if(sizeof($slideshow))
<div id="home-slides">
    <ul class="home-slides">
        @foreach($slideshow as $slide)
            {{ '', $slide->spreadMeta() }}
        <li class="home-slide" style="background-image: url({{ url($slide->featured_image) }});">
            <div class="container">
                <div class="content">
                    @if(strlen($slide->title2))
                        <h1> {{ $slide->title2 }} </h1>
                    @endif
                    @if(strlen($slide->abstract))
                        <p> {{ $slide->abstract }} </p>
                    @endif
                </div>
            </div>
        </li>
        @endforeach
    </ul>
</div>
@endif