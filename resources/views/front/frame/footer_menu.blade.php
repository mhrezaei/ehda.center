<div class="col-xs-12 col-md-4">
    <ul class="list-unstyled clearfix">
        <div class="col-xs-6">
            <li><a href="{{ url_locale('/') }}">{{ trans('front.home') }}</a></li>
            <li><a href="{{ url_locale('about') }}">{{ trans('front.organ_donation_card_section.singular') }}</a></li>
            <li><a href="{{ url_locale('organ_donation_card') }}">{{ trans('front.contact_us') }}</a></li>
        </div>
        <div class="col-xs-6">
            <li><a href="{{ route_locale('volunteer.register.step.1.get') }}">{{ trans('front.volunteer_section.plural') }}</a></li>
            <li><a href="{{ route_locale('gallery.categories', [
                                    'postType' => 'gallery',
                                ]) }}">{{ trans('front.gallery') }}</a></li>
            <li><a href="#">{{ trans('front.angels.plural') }}</a></li>
        </div>
    </ul>
</div>