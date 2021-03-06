@if($model->has('price'))
	<div class="panel panel-primary mv20">
		<div class="panel-heading">
			<i class="fa fa-money mh5"></i>
			{{ trans('validation.attributes.price') }}
			<i class="fa fa-refresh text-ultralight mh10 clickable" onclick="divReload('divEditorGoods')"></i>
		</div>

		<div id="divEditorGoods" class="panel-body bg-ultralight">
			@include("manage.posts.editor-goods-index")
		</div>
	</div>

@endif
