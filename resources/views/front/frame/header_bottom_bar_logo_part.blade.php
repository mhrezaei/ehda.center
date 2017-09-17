<a href="{{ url_locale() }}" class="pull-start">
    <h1 class="main-logo" style="display: inline-block">
        @php $logo = setting()->ask('site_logo')->gain() @endphp
        @php $smallLog = setting()->ask('site_logo_small')->gain() @endphp
        @if($logo)
            <img src="{{ url($logo) }}" alt="انجمن اهدای عضو ایرانیان" id="logo">
        @endif
        @if($smallLog)
            <img src="{{ url($smallLog) }}" alt="انجمن اهدای عضو ایرانیان" id="logo-small">
        @endif
        <span class="hidden">انجمن اهدای عضو ایرانیان</span>
    </h1>
</a>