<div class="col-xs-12">
    <div class="row align-horizontal-center">
        <div class="col-xs-11 align-vertical-center">
            <div class="col-xs-6">
                <a href="{{ route_locale('user.profile.edit') }}"
                   class="btn btn-blue btn-block">
                    {{ trans('front.member_section.profile_edit') }}
                </a>
            </div>
            <div class="col-xs-6">
                {{-- @TODO: should make its link to go to suitable link for voluteer --}}
                <a href="{{ route_locale('volunteer.register.step.final.get') }}"
                   class="btn btn-success btn-block">
                    {{ trans('front.volunteer_section.plural') }}
                </a>
            </div>
        </div>
    </div>
</div>