<ul class="pull-start list-inline no-margin">
    @if(!\App\Providers\FrontServiceProvider::isHome())
        <li class="pull-start">
            <a href="{{ url_locale() }}" title="{{ trans('front.home') }}">
                <i class="fa fa-home f22" style="line-height: 18px"></i>
            </a>
        </li>
    @endif
    @if(user()->exists)
        <li class="has-child pull-start">
            <a href="/">{{ str_replace('::user', user()->name_first, trans('front.profile_phrases.welcome_user')) }}</a>
            <ul class="list-unstyled bg-primary">
                @if(user()->is_admin()) {{-- This user is a volunteer --}}
                <li><a href="{{ url('/manage') }}">{{ trans('front.volunteer_section.section') }}</a></li>
                @endif
                @if(user()->is_an('card-holder')) {{-- This user has card --}}
                <li>
                    <a href="{{ route_locale('user.dashboard') }}">{{ trans('front.organ_donation_card_section.singular') }}
                    </a>
                </li>
                <li>
                    <a href="{{ url_locale('members/my_card/edit') }}">{{ trans('front.member_section.profile_edit') }}</a>
                </li>
                @endif
                <li><a href="{{ url('/logout') }}">{{ trans('front.member_section.sign_out') }}</a></li>
            </ul>
        </li>
    @else
        <li class="pull-start">
            <a href="{{ url('/login') }}">{{ trans('front.member_section.sign_in') }}</a>
        </li>
    @endif
    <li>
        <a href="{{ route_locale('states.index') }}">{{ trans('front.community_branches') }}</a>
    </li>
</ul>