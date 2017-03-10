<div class="dropdown bottom-left langs">
    @if(getLocale() == 'fa')
        <a href="{{ url('/fa') }}" class="dropdown-toggle">
            <span>
                <img src="{{ url('/assets/images/flags/fa.png') }}">
            </span> {{ trans('front.persian') }}
        </a>
    @elseif(getLocale() == 'en')
        <a href="{{ url('/en') }}" class="dropdown-toggle">
            <span>
                <img src="{{ url('/assets/images/flags/en.png') }}">
            </span> {{ trans('front.english') }}
        </a>
    @elseif(getLocale() == 'ar')
        <a href="{{ url('/ar') }}" class="dropdown-toggle">
            <span>
                <img src="{{ url('/assets/images/flags/ar.png') }}">
            </span> {{ trans('front.arabic') }}
        </a>
    @endif
    <div class="menu">
        <a href="{{ url('/fa') }}">
            <span>
                <img src="{{ url('/assets/images/flags/fa.png') }}">
            </span> {{ trans('front.persian') }} </a>
        <a href="{{ url('/en') }}">
            <span>
                <img src="{{ url('/assets/images/flags/en.png') }}">
            </span> {{ trans('front.english') }} </a>
        <a href="{{ url('/ar') }}">
            <span>
                <img src="{{ url('/assets/images/flags/ar.png') }}">
            </span> {{ trans('front.arabic') }} </a>
    </div>
</div>