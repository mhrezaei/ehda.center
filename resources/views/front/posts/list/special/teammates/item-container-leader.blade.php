<div class="team-item leader">
    @if($person->text)
        <a href="{{ $person->direct_url }}">
            @endif
            @include($viewFolder . '.item-body', ['showText' => true])
            @if($person->text or $person->abstract)
        </a>
    @endif
</div>