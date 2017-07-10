<div class="panel panel-green">

	{{--
	|--------------------------------------------------------------------------
	| Calculations
	|--------------------------------------------------------------------------
	|
	--}}
	@if(isset($ajax))
		{{ '' , $total = model('user')::selector(['role' => "card-holder" ,])->count() }}
		{{ '' , $females = model('user')::selector(['role' => "card-holder" ,])->where('gender',2)->count() }}
		{{ '' , $males = model('user')::selector(['role' => "card-holder" ,])->where('gender',1)->count() }}
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
				'height' => "120",
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
				<button class="btn btn-link f10 -refresh" onclick="divReload('divCardsByGender2');$('#divCardsByGender2 .-refresh').slideToggle()">
					{{ trans("forms.button.refresh") }}
				</button>
				<img class="-refresh noDisplay" src="{{url('assets/images/loading/bar-red.gif')}}">
			</div>

		@else

			<div class="m30 margin-auto text-center">
				<img src="{{url('assets/images/loading/bar-red.gif')}}">
				<script>divReload('divCardsByGender2')</script>
			</div>

		@endif


	</div>

</div>