@include('manage.frame.widgets.grid-start')

	@foreach($models as $i => $model)
		<tr id="tr-{{$model->id}}" class="grid"
				@if(isset($handle) and str_contains($handle , 'selector'))
					ondblclick="gridSelector('tr','{{$model->id}}')"
				@endif
		>
			@include($row_view , ['model'=>$model])
		</tr>
	@endforeach

	@include('manage.frame.widgets.browse-null')

	@include('manage.frame.widgets.grid-end')

<div class="paginate">
	{!! $models->render() !!}
</div>
