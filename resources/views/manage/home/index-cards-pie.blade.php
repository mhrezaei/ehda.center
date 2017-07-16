<div class="panel panel-pink">

	{{--
	|--------------------------------------------------------------------------
	| Calculations
	|--------------------------------------------------------------------------
	|
	--}}
	@if(isset($ajax))
		{{ '' , $females = model('user')::whereNotNull('card_registered_at')->where('gender',2)->count() }}
		{{ '' , $males = model('user')::whereNotNull('card_registered_at')->where('gender',1)->count() }}
		{{ '' , $total = $females + $males }}
	@endif


	{{--
	|--------------------------------------------------------------------------
	| Header
	|--------------------------------------------------------------------------
	|
	--}}
	<div class="panel-heading">
		<i class="fa fa-credit-card"></i>
		<span class="mh5">
			{{ trans("ehda.donation_cards") }}
		</span>
		<span class="pull-left">
			<i class="fa fa-refresh clickable -refresh" onclick="divReload('divCardsByGender');$('#divCardsByGender .-refresh').slideToggle()"></i>
		</span>
	</div>


	{{--
	|--------------------------------------------------------------------------
	| Body
	|--------------------------------------------------------------------------
	|
	--}}

	<div class="panel-footer">


		@if(isset($ajax))

			@include("manage.frame.widgets.t-charts.pie" , [
				'height' => "200",
				'data' => [
					trans("forms.gender.2") => $females/$total ,
					trans("forms.gender.1") => $males/$total ,
				] ,
					'label_size' => "10" ,
			])

			<div class="text-center w100 p5" style="margin-top: 10px">
				<a class="btn btn-default" href="{{user()->as('admin')->can('users-card-holder.browse')? url('manage/cards') : v0()}}">
					{{ pd(number_format($total)) }}
					&nbsp;
					{{ trans("ehda.donation_card") }}
				</a>
			</div>
			<div class="text-center w100">
				@include("manage.frame.widgets.loading" , [
					'class' => "-refresh noDisplay" ,
				])
			</div>

		@else

			<div class="m30 margin-auto text-center">
				@include("manage.frame.widgets.loading")
				<script>divReload('divCardsByGender')</script>
			</div>

		@endif


	</div>

</div>