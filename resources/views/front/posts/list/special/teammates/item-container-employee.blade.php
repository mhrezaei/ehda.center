<div class="col-sm-6">
    <div class="team-item">
        @if($person->text or $person->abstract)
            <a href="{{ $person->direct_url }}">
                @endif
                @include($viewFolder . '.item-body')
                @if($person->text or $person->abstract)
            </a>
        @endif
    </div>
</div>