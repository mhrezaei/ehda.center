{{ '' , $user = \App\Models\User::find($winner) }}
@if($user and $user->id)
	<tr>
		<td>{{ pd($key + 1) }}</td>
		<td>{{ $user->full_name }}</td>
		<td>{{ pd($user->mobile) }}</td>
		<td>&nbsp;</td>
	</tr>
@else
	<tr>
		<td>{{ pd($key + 1) }}</td>
		<td colspan="3"><span class="noContent"> {{ trans('people.form.user_deleted') }}</span></td>
	</tr>
@endif