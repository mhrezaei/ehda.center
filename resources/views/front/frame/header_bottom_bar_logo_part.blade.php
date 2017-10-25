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

        @php
            $stateLogoPost = model('post')::selector(['type' => 'states-logos'])->whereSlug(getDomain() . '-logo')->first();
        @endphp
        @if($stateLogoPost and $stateLogoPost->exists and $stateLogoPost->featured_image)
            <img src="{{ url($stateLogoPost->featured_image) }}" style="max-height: 45px">
        @endif
        <span class="hidden">انجمن اهدای عضو ایرانیان</span>
    </h1>
</a>