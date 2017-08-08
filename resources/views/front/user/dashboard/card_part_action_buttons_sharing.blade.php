<div class="col-xs-12 mt10 share" style="display: none">
    @include('front.frame.widgets.add-to-any', [
        'shareUrl' => user()->cards('social'),
        'shareTitle' => setting()->ask('site_title')->gain() .
            ' | ' . trans('front.organ_donation_card_section.user_card', ['user' => user()->full_name])
    ])
</div>