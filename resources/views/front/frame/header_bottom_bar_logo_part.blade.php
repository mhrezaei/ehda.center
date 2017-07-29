<a href="{{ url_locale() }}" class="col-xs-4 col-sm-4 col-md-3">
    <h1 class="main-logo">
        @php $logo = setting()->ask('site_logo')->gain() @endphp
        @if($logo)
            <img src="{{ url($logo) }}" alt="انجمن اهدای عضو ایرانیان" id="logo">
        @endif
        <span class="hidden">انجمن اهدای عضو ایرانیان</span>
    </h1>
</a>