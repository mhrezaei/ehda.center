<div class="col-xs-12 align-vertical-center align-horizontal-center">
    <div class="col-xs-5">
        <a href="{{ route_locale('user.profile.edit') }}"
           class="btn btn-blue btn-block">
            {{ trans('front.member_section.profile_edit') }}
        </a>
    </div>
    <div class="col-xs-5">
        {{-- @TODO: should make its link to go to suitable link for voluteer --}}
        <a href="#"
           class="btn btn-success btn-block">
            {{ trans('front.volunteer_section.plural') }}
        </a>
    </div>
</div>