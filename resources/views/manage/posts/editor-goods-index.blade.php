{{ '' , $goods = $model->goods(true)->orderBy('order')->get() }}
{{ '' , $refresh_switches = "post=$model->id-sisterhood=$model->sisterhood-type=$model->type-locale=$model->locale" }}
<div class="refresh ltr">{{ url("manage/posts/act/1/editor-goods-index/".$refresh_switches) }}</div>

{{--
|--------------------------------------------------------------------------
| Browse Current Prices
|--------------------------------------------------------------------------
|
--}}
<div id="divEditorGoods-sortable">
	{{ '' , $default_found = false }}
	{{ '' , $default_price = 0 }}
	{{ '' , $default_sale_price = 0 }}

	@foreach($goods as $good)
		@if($good->isAvailableIn($model->locale) && !$default_found)
			{{ '' , $good->default = true }}
			{{ '' , $default_found = true }}
			{{ '' , $default_price = $good->price }}
			{{ '' , $default_sale_price = $good->sale_price }}
		@endif
		@include("manage.posts.editor-goods-one")
	@endforeach

</div>

{{--
|--------------------------------------------------------------------------
| Warnings
|--------------------------------------------------------------------------
| Nothing Added Yet  &  No default set
--}}

@if(!$goods->count())
	<div class="text-center text-danger">
		{{ trans("cart.goods.nothing_added_yet") }}
	</div>
@elseif(!$default_found)
	<div class="text-center text-danger">
		{{ trans("cart.goods.no_default_set") }}
	</div>
@endif


{{--
|--------------------------------------------------------------------------
| Add New Button
|--------------------------------------------------------------------------
|
--}}
<div class="text-center mv20">
	<div class="btn btn-default btn-lg"
		 onclick='masterModal(url("manage/posts/act/1/editor-goods/post={{ $refresh_switches }}"))'>{{ trans('cart.goods.create') }}</div>
</div>

{{--
|--------------------------------------------------------------------------
| Hidden Fields for Default Prices
|--------------------------------------------------------------------------
|
--}}
@include("forms.hidden" , [
	'name' => "price",
	'value' => $default_price,
])
@include("forms.hidden" , [
	'name' => "sale_price",
	'value' => $default_sale_price,
])


{{--
|--------------------------------------------------------------------------
| Script
|--------------------------------------------------------------------------
|
--}}
<script>
	$(function () {
		$("#divEditorGoods-sortable").sortable({
			placeholder: "ui-state-highlight",
			cursor     : "move",
			update     : function (event, ui) {
				var data = $(this).sortable('serialize' );
				data = data.replaceAll('=' , ':');
				$('#divEditorGoods .refresh').html(url("manage/posts/act/1/editor-goods-index/{{ $refresh_switches }}-sort=" + data));
				forms_log(data);
				divReload('divEditorGoods');
			}
		});

		$("#sortable").disableSelection();
	})
	;
</script>

