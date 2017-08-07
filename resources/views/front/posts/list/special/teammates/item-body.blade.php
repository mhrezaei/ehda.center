<div class="avatar">
    <img src="{{ $person->viewable_featured_image ? url($person->viewable_featured_image) : null }}"></div>
<div class="content">
    <h3> {{ ad($person->title) }} </h3>
    <h4> {{ ad($person->seat) }} </h4>
    @if(isset($showText) and $showText)
        <p> {!! ad($person->abstract) !!} </p>
    @endif
</div>
