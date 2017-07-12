<div class="panel panel-pink">

	<div class="panel-footer">

		@if(isset($ajax))
			{{--
			|--------------------------------------------------------------------------
			| Real Chart
			|--------------------------------------------------------------------------
			|
			--}}
		    <?php
		    $data = [];

		    for($i = 0; $i <= 10; $i++) {
			    $today                                                   = \Carbon\Carbon::today()->subMonth($i);
			    $yesterday                                               = \Carbon\Carbon::today()->subMonth($i - 1);
			    $count                                                   = model('user')::where('card_registered_at', '>', $today)->where('card_registered_at', '<', $yesterday)->count();
			    $data[ pd(jDate::forge($today->toDateString())->ago()) ] = $count;
		    }
		    $data = array_reverse($data);

		    ?>

			@include("manage.frame.widgets.t-charts.line" , [
				'height' => "185",
				'data' => $data ,
				'label_size' => "10" ,
				'label' => trans("ehda.cards.register_full") ,
			]     )

		@else
			{{--
			|--------------------------------------------------------------------------
			| Chart Placeholder
			|--------------------------------------------------------------------------
			|
			--}}
			@include("manage.frame.widgets.t-charts.line" , [
				'label' => trans("ehda.cards.register_full") ,
				'height' => "185",
				'data' => [
				] ,
				'label_size' => "10" ,
			]     )

			<script>divReload('divCardsTimebar')</script>



		@endif

	</div>

</div>