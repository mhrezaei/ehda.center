{{-- TODO: Hadi :D --}}

<div class="btn-group select-lang-btn pull-end pb10">
    <a href="{{ localeLink('fa') }}" @if(getLocale() == 'fa') class="selected" @endif >Fa</a>
    <a href="{{ localeLink('en') }}" @if(getLocale() == 'en') class="selected" @endif >En</a>
</div>