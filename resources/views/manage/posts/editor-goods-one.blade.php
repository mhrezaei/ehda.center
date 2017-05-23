<div id="divGood-{{$good->id}}" about="{{$good->id}}" class="row mv10 mh5 bordered p10 bg-white ui-state-default" style="cursor: move; {{ $good->isAvailableIn($model->locale)? '' : 'opacity:0.4' }}">
	{{ '' , $model->default_good = 1 }}

	{{--
	|--------------------------------------------------------------------------
	| Default Selector Switch
	|--------------------------------------------------------------------------
	|
	--}}
	{{--<div class="col-md-1">--}}
		{{--@if($good->default)--}}
			{{--<span class="fa fa-check-circle text-green f35"></span>--}}
		{{--@endif--}}
	{{--</div>--}}


	{{--
	|--------------------------------------------------------------------------
	| Title
	|--------------------------------------------------------------------------
	|
	--}}
	<div class="col-md-7 f20 pv5">
		@if($good->titleIn($model->locale))
			{{ $good->titleIn($model->locale) }}
		@elseif($good->anyTitle())
			{{ $good->anyTitle() }}
		@else
			---
		@endif

		@if(!$good->isAvailableIn($model->locale))
			<span class="text-danger mh10">
				[{{ trans('forms.general.disabled') }}]
			</span>
		@endif

		@if($good->default)
			<span class="text-green mh10">
				[{{ trans('cart.goods.default_price') }}]
			</span>
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
