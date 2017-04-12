<tr>
	<td>{{ pd($total - $key) }}</td>
	<td>{{ echoDate($receipt->purchased_at , 'default' , 'auto' , true) }}</td>
	<td>{{ pd(number_format($receipt->purchased_amount/10)) . getSetting('currency') }}</td>
	<td class="f10 text-gray">{{ echoDate($receipt->created_at , 'default' , 'auto' , true ) }}</td>
</tr>