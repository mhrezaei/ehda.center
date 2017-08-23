<div class="col-xs-12 col-md-8">
    <h5>{{ trans('front.notes.follow_us_in_social') }}</h5>
    <ul class="social-links list-inline">
        {{ null, $telegram = setting()->ask('telegram_link')->gain() }}
        @if($telegram)
            <li><a href="{{ $telegram }}" target="_blank"><i class="icon icon-telegram"></i></a></li>
        @endif
        {{ null, $twitter = setting()->ask('twitter_link')->gain() }}
        @if($twitter)
            <li><a href="{{ $twitter }}" target="_blank"><i class="icon icon-twitter"></i></a></li>
        @endif
        {{ null, $facebook = setting()->ask('facebook_link')->gain() }}
        @if($facebook)
            <li><a href="{{ $facebook }}" target="_blank"><i class="icon icon-facebook"></i></a></li>
        @endif
        {{ null, $instagram = setting()->ask('instagram_link')->gain() }}
        @if($instagram)
            <li><a href="{{ $instagram }}" target="_blank"><i class="icon icon-instagram"></i></a></li>
        @endif
        {{ null, $aparat = setting()->ask('aparat_link')->gain() }}
        @if($aparat)
            <li><a href="{{ $aparat }}" target="_blank"><i class="icon icon-aparat"></i></a></li>
        @endif
    </ul>
    <p class="address">
    {{ null, $address = setting()->ask('address')->in(getLocale())->gain() }}
    @if($address)
        <h5>{{ trans('validation.attributes.address') }}:</h5>
        {{ $address }}
    @endif
    <br>
    {{ null, $location = setting()->ask('location')->gain() }}
    @if($location and is_array($location) and (count($location) == 2))
        <a href="{{ route_locale('contact') }}#map" class="link-white">{{ trans('front.view_on_map') }}</a>
        <br>
    @endif
    {{ null, $tels = setting()->ask('telephone')->gain() }}
    @if($tels)
        @if(!is_array($tels))
            {{ null, $tels = [$tels ] }}
        @endif
        @foreach($tels as $key => $tel)
            @if($key)
                ،
            @endif
            <a href="tel:{{ $tel }}">
                {{ ad($tel) }}
            </a>
        @endforeach
    @endif
    <br> {{ null, $emails = setting()->ask('email')->gain() }}
    @if($emails)
        @if(!is_array($emails))
            {{ null, $emails = [$emails ] }}
        @endif
        @foreach($emails as $key => $email)
            @if($key)
                <br/>
            @endif
            <a href="mailto:{{ $email }}">
                {{ $email }}
            </a>
            @endforeach
            @endif
            </p>
</div>