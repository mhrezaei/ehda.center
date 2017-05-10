@foreach($employees as $index => $person)
    @if(($index % 2) == 0)
        <div class="row">
            @endif
            @include($viewFolder . '.item-container-employee')
            @if((($index % 2) == 1) or ($index == (count($employees) - 1)))
        </div>
    @endif
@endforeach
