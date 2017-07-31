<p style="text-align: justify">
    {{
     str_replace([
                ':name',
                ':membershipNumber',
                ':site',
            ], [
                $user->full_name,
                $user->card_no,
                setting()->ask('site_url')->gain(),
            ],
                trans('front.organ_donation_card_section.register_success_message.email'))
     }}
</p>
<p style="text-align: center">
    <img src="{{ $user->cards('social') }}">
</p>