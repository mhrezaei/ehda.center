{{ '' , $goods = $model->goods(true)->get() }}
<div class="refresh">{{ url("manage/posts/act/1/editor-goods-index/post=$model->id-sisterhood=$model->sisterhood-type=$model->type-locale=$model->locale") }}</div>

{{--
|--------------------------------------------------------------------------
| Nothing Added Yet
|--------------------------------------------------------------------------
|
--}}

@if(!$goods->count())
	<div class="text-center text-danger">
		{{ trans("cart.goods.nothing_added_yet") }}
	</div>
@endif


{{--
|--------------------------------------------------------------------------
| Browse Current Prices
|--------------------------------------------------------------------------
|
--}}
@foreach($goods as $good)
	@include("manage.posts.editor-goods-one")
@endforeach


{{--
|--------------------------------------------------------------------------
| Add New Button
|--------------------------------------------------------------------------
|
--}}
<div class="text-center mv20">
	<div class="btn btn-default btn-lg" onclick='masterModal(url("manage/posts/act/1/editor-goods/post={{$model->id}}-sisterhood={{$model->sisterhood}}-type={{$model->type}}-locale={{$model->locale}}"))'>{{ trans('cart.goods.create') }}</div>
</div>
