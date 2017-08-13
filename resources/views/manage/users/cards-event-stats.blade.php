@include('templates.modal.start' , [
	'partial' => true ,
//	'form_url' => url('manage/posts/save/soft_delete'),
	'modal_title' => trans('posts.features.feedback'). ' ' . $model->title,
])
<div class='modal-body'>

	<div class="panel panel-default m20">
		<div class="panel-body">
			<table class="table {{$table_class or 'table-hover'}}">
				<thead>
				<tr>
					<td>#</td>
					<td>{{ trans('validation.attributes.date') }}</td>
					<td>{{ trans('ehda.cards.register') }}</td>
					<td>{{ trans('ehda.printings.request') }}</td>
				</tr>
				</thead>

				<tbody>
				@foreach($daily_registers as $key => $item)
					<tr>
						<td>@pd($key + 1)</td>
						<td>@pd(jDate::forge($item[0])->format('j F Y'))</td>
						<td>
							@if($item[1])
								@pd($item[1])
							@else
								<span class="text-grey"> - </span>
							@endif
						</td>
						<td>
							@if($item[2])
								@pd($item[2])
							@else
								<span class="text-grey"> - </span>
							@endif
						</td>
					</tr>
				@endforeach

				<thead>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>@pd(number_format($total_count))</td>
					<td>@pd(number_format($model->printings()->count()))</td>
				</tr>
				</thead>

				</tbody>
			</table>
		</div>
	</div>

</div>


@if(user()->as('admin')->can('card-holder.print'))
	<div class="modal-footer">
		<a href="{{ url("manage/cards/printings/pending/$model->id") }}" class="btn btn-default">
			{{ trans("ehda.printings.pending_cards") }}
		</a>
	</div>
@endif

@include('templates.modal.end')