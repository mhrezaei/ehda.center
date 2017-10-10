@include('templates.modal.start' , [
	'partial' => true ,
//	'form_url' => url('manage/posts/save/soft_delete'),
	'modal_title' => trans('ehda.cards.register_full'),
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
					<td>{{ trans('ehda.cards.web_users') }}</td>
					<td>{{ trans('ehda.cards.bot_users') }}</td>
					<td>سایر</td>
				</tr>
				</thead>

				<tbody>
				@foreach($daily_registers as $key => $item)
					<tr>
						<td>{{ pd($key+1) }}</td>
						<td>{{ pd($item['date']) }}</td>
						<td>
							@if($item['count_total'])
								{{ pd(number_format($item['count_total'])) }}
							@else
								<span class="text-grey"> - </span>
							@endif
						</td>
						<td>
							@if($item['count_site'])
								{{ pd(number_format($item['count_site'])) }}
							@else
								<span class="text-grey"> - </span>
							@endif
						</td>
						<td>
							@if($item['count_bot'])
								{{ pd(number_format($item['count_bot'])) }}
							@else
								<span class="text-grey"> - </span>
							@endif
						</td>
						<td>
							@if($item['count_volunteer'])
								{{ pd(number_format($item['count_volunteer'])) }}
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
					<td>{{ pd(number_format($total_registers['count_total'])) }}</td>
					<td>{{ pd(number_format($total_registers['count_site'])) }}</td>
					<td>{{ pd(number_format($total_registers['count_bot'])) }}</td>
					<td>{{ pd(number_format($total_registers['count_volunteer'])) }}</td>
				</tr>
				</thead>

				</tbody>
			</table>
		</div>
	</div>

</div>

@include('templates.modal.end')