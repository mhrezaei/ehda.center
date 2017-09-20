{{-- TODO: Hadi :D --}}

<div class="btn-group select-lang-btn pb10">
    <a href="#" class="globe-list-btn">
        <i class="fa fa-globe"></i>
    </a>
    <ul class="list-inline globe-list">
        <li class="pull-start">
            <a href="{{ localeLink('fa') }}" @if(getLocale() == 'fa') class="selected" @endif >Fa</a>
        </li>
        <li class="pull-start">
            <a href="{{ localeLink('en') }}" @if(getLocale() == 'en') class="selected" @endif >En</a>
        </li>
    </ul>
</div>