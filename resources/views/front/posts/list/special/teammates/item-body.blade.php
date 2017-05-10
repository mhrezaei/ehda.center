<div class="avatar">
    <img src="{{ url($person->featured_image) }}"></div>
<div class="content">
    <h3> {{ $person->title }} </h3>
    <h4> {{ $person->seat }} </h4>
    @if(isset($showText) and $showText)
        <p> {!! $person->abstract !!} </p>
    @endif
</div>
