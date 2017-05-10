<div class="team-item leader">
    <div class="avatar">
        <img src="{{ url($post->viewable_featured_image) }}"></div>
    <div class="content">
        <h3> {{ $post->title }} </h3>
        <h4> {{ $post->seat }} </h4>
        <p> {!! $post->abstract !!} </p>
    </div>
</div>