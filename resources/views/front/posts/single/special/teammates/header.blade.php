<div class="team-item leader">
    <div class="avatar">
        <img src="{{ $post->viewable_featured_image ? url($post->viewable_featured_image) : null }}"></div>
    <div class="content">
        <h3> {{ $post->title }} </h3>
        <h4> {{ $post->seat }} </h4>
        <p> {!! $post->abstract !!} </p>
    </div>
</div>