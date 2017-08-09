@if(user()->is_a('card-holder'))
	<div class="w100 ph10 text-center mv10">
		<a href="{{ url("fa/user/dashboard") }}" target="_blank">
			<img src="{{ user()->cards('social' , 'show') }}" style="max-width: 100%;max-height: 300px">
		</a>
	</div>
@else

	<div class="w100 ph10 mv5 text-center alert alert-danger" style="min-height: 220px">
		<div class="f16 mv20 text-center">{{ trans("ehda.cards.even_you_dont_have_card") }}</div>
		<button class="btn btn-lg btn-primary" onclick="masterModal('{{ url('manage/account/act/card-register') }}')" >{{ trans("ehda.cards.register_for_yourself_now") }}</button>
	</div>

@endif
