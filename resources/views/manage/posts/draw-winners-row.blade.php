{{ '' , $user = \App\Models\User::find($winner) }}
<tr>
	<td>{{ pd($key + 1) }}</td>

	@if($user and $user->id)
		<td>{{ $user->full_name }}</td>
		<td>{{ pd($user->mobile) }}</td>
		<td>{{ pd(number_format($user->totalReceiptsAmountInEvent($model)/10)) . ' ' . getSetting('currency') }}</td>
	@else
		<td colspan="3"><span class="noContent"> {{ trans('people.form.user_deleted') }}</span></td>
	@endif

	<td>
		@if($model->isDrawingReady())
			<a class="text-danger f10" href="#" onclick="drawingDelete('{{$key}}' , '{{$model->id}}')">x</a>
		@endif
	</td>
</tr>
