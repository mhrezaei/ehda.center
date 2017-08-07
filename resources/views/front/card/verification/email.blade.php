<p style="text-align: justify">
    {!! trans('front.organ_donation_card_section.register_success_message.email', [
                'name' => $user->full_name,
                'membershipNumber' => $user->card_no,
            ]
    ) !!}
</p>
<a href="{{ setting()->ask('site_url')->gain() }}" target="_blank">
    {{ setting()->ask('site_title')->gain() }}
</a>
<p style="text-align: center">
    <img src="{{ $user->cards('social') }}">
</p>