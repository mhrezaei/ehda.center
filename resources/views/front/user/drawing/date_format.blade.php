@if(getLocale() == 'en')
    {{ echoDate($date, 'j F Y', 'en', false) }}
@else
    {{ echoDate($date, 'j F Y', 'auto', true) }}
@endif