<ul class="pull-start list-inline no-margin">
    @if(user()->exists)
        <li class="has-child">
            <a href="/">{{ str_replace('::user', user()->name_first, trans('front.profile_messages.welcome_user')) }}</a>
            <ul class="list-unstyled bg-primary">
                @if(user()->is_admin()) {{-- This user is a volunteer --}}
                    <li><a href="{{ url('/manage') }}">{{ trans('front.volunteer_section.section') }}</a></li>
                @endif
                @if(user()->is_an('card-holder')) {{-- This user has card --}}
                    <li>
                        <a href="{{ url_locale('members/my_card') }}">{{ trans('front.organ_donation_card_section.preview') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ user()->cards('full', 'download') }}">
                            {{ trans('front.organ_donation_card_section.download') }}
                        </a>
                    </li>
                    <li><a href="{{ user()->cards('full', 'print') }}">{{ trans('front.organ_donation_card_section.print') }}</a></li>
                    <li><a href="{{ url_locale('members/my_card/edit') }}">{{ trans('front.member_section.profile_edit') }}</a></li>
                @endif
                <li><a href="{{ url('/logout') }}">{{ trans('front.member_section.sign_out') }}</a></li>
            </ul>
        </li>
    @else
        <li>
            <a href="{{ url('/login') }}">{{ trans('front.member_section.sign_in') }}</a>
        </li>
    @endif
    {{--<li>--}}
        {{--<a href="/">ورود استان&zwnj;ها</a>--}}
    {{--</li>--}}
</ul>