@include('manage.frame.widgets.grid-start')

	@foreach($model_data as $i => $model)
		<tr id="tr-{{$model->id}}" class="grid"
				@if(isset($selector) and $selector)
					ondblclick="gridSelector('tr','{{$model->id}}')"
				@endif
		>
			@include($row_view , ['model'=>$model])
		</tr>
	@endforeach

	@include('manage.frame.widgets.browse-null')

	@include('manage.frame.widgets.grid-end')

<div class="paginate">
	{!! $model_data->render() !!}
</div>
