<div class="row mv10 mh5 bordered p10 bg-white" style="{{ $good->isAvailableIn($model->locale)? '' : 'opacity:0.4' }}">
	{{ '' , $model->default_good = 1 }}

	{{--
	|--------------------------------------------------------------------------
	| Default Selector Switch
	|--------------------------------------------------------------------------
	|
	--}}
	<div class="col-md-1">
		@if($model->default_good == $good->id)
			<span class="fa fa-check-circle text-green f25"></span>
		@else
			<span class="fa fa-circle-o f25 text-gray clickable"></span>
		@endif
	</div>


	{{--
	|--------------------------------------------------------------------------
	| Title
	|--------------------------------------------------------------------------
	|
	--}}
	<div class="col-md-6 f16 pv5">
		@if($good->title)
			{{ $good->title }}
		@else
		@endif
	</div>


	{{--
	|--------------------------------------------------------------------------
	| Price
	|--------------------------------------------------------------------------
	|
	--}}
	<div class="col-md-4 pv10">
		@if($good->has_discount)
			<del class="f14 text-gray">
				{{ pd(number_format($good->price)) }}
			</del>
			<span class="fa fa-long-arrow-left mh5"></span>
			<span class="f14">
				{{ pd(number_format($good->sale_price)) }}
			</span>
		@else
			<span class="f14">
				{{ pd(number_format($good->price)) }}
			</span>
		@endif

		<span class="f14">
			{{ setting('currency')->in($model->locale)->gain() }}
		</span>

	</div>

	{{--
	|--------------------------------------------------------------------------
	| Edit Button
	|--------------------------------------------------------------------------
	|
	--}}
	<div class="col-md-1 text-left">
		<span class="fa fa-pencil text-info f16 clickable mv10" onclick='masterModal(url("manage/posts/act/1/editor-goods/id={{$good->id}}-post={{$model->id}}-sisterhood={{$model->sisterhood}}-type={{$model->type}}-locale={{$model->locale}}"))'></span>
	</div>

</div>
