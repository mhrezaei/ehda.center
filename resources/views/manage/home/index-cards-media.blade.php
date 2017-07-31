<div class="panel panel-orangered">

	{{--
	|--------------------------------------------------------------------------
	| Calculations
	|--------------------------------------------------------------------------
	|
	--}}
	@if(isset($ajax))
		{{ '' , $role = model('role')::findBySlug('card-holder') }}
		{{ '' , $total = $role->users()->count() }}
		{{ '' , $bot_users = $role->users()->whereIn('created_by' , model('user')::telegramBots())->count() }}
		{{ '' , $web_users = $total - $bot_users }}
	@endif


	{{--
	|--------------------------------------------------------------------------
	| Header
	|--------------------------------------------------------------------------
	|
	--}}
	<div class="panel-heading">
		<i class="fa fa-telegram"></i>
		<span class="mh5">
			{{ trans("ehda.cards.registry_media") }}
		</span>
		<span class="pull-left">
			<i class="fa fa-refresh clickable -refresh" onclick="divReload('divCardsByMedia');$('#divCardsByMedia .-refresh').slideToggle()"></i>
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
				'height' => "250",
				'data' => [
					trans("ehda.cards.bot_users") => $bot_users/$total ,
					trans("ehda.cards.web_users") => $web_users/$total ,
				] ,
					'label_size' => "10" ,
			])

			<div class="text-center w100 p5" style="margin-top: 10px">
				<span class="btn btn-default" disabled href="{{user()->as('admin')->can('users-card-holder.browse')? url('manage/cards') : v0()}}">
					{{ pd(number_format($bot_users)) }}
					&nbsp;
					{{ trans("ehda.cards.register_via_telegram") }}
				</span>
			</div>
			<div class="text-center w100">
				@include("manage.frame.widgets.loading" , [
					'class' => "-refresh noDisplay" ,
				])
			</div>

		@else

			<div class="m30 margin-auto text-center">
				@include("manage.frame.widgets.loading")
				<script>divReload('divCardsByMedia')</script>
			</div>

		@endif


	</div>

</div>