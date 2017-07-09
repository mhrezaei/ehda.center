<div class="panel panel-green">

	<div class="panel-footer">

		@if(isset($ajax))
			{{--
			|--------------------------------------------------------------------------
			| Real Chart
			|--------------------------------------------------------------------------
			|
			--}}

			@include("manage.frame.widgets.t-charts.pie" , [
				'calculations' => [
					$total = model('user')::selector(['role' => "card-holder" ,])->count() ,
					$females = model('user')::selector(['role' => "card-holder" ,])->where('gender',2)->count(),
					$males = model('user')::selector(['role' => "card-holder" ,])->where('gender',1)->count(),
//					$others = model('user')::selector(['role' => "card-holder" ,])->where('gender',3)->count(),
//					$unknown = ($total - $females - $males - $others )x
				] ,
				'height' => "120",
				'data' => [
					trans("forms.gender.2") => $females/$total ,
					trans("forms.gender.1") => $males/$total ,
//					trans("forms.gender.3") => $others/$total ,
//					trans("forms.status_text.unknown") => $others/$total ,
				] ,
				'label_size' => "10" ,
			]     )

		@else
			{{--
			|--------------------------------------------------------------------------
			| Chart Placeholder
			|--------------------------------------------------------------------------
			|
			--}}
			@include("manage.frame.widgets.t-charts.pie" , [
				'height' => "120",
				'data' => [
					trans("forms.gender.2") => '' ,
					trans("forms.gender.1") => '' ,
				] ,
				'label_size' => "10" ,
			]     )


		@endif

	</div>



	<div class="panel-heading text-center">
		@if(isset($ajax))

			<a href="{{url('manage/cards')}}" style="text-decoration: none">
				<div class="panel-heading f14">
					{{ pd(number_format($total)) }}
					{{ trans("ehda.donation_card") }}
				</div>
			</a>



		@else

			<a href="{{url('manage/cards')}}" style="text-decoration: none">
				<div class="panel-heading f14">
					{{ trans("ehda.donation_cards") }}
				</div>
			</a>
			<script>divReload('divCardsByGender')</script>

		@endif
	</div>

</div>