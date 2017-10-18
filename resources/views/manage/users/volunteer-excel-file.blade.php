<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
	html, body, table {
		direction: rtl;
		font-family: Tahoma;
		text-align: right;
		line-height: 15px;
	}
</style>
<table>
	<tr>
		<td>Row</td>
		<td>Name</td>
		<td>Father</td>
		<td>Code Melli</td>
		<td>Mobile</td>
		<td>Home Tel</td>
		<td>Emergency Tel</td>
		<td>Email</td>
		<td>City</td>
		<td>Address</td>
		<td>Gender</td>
		<td>Education</td>
		<td>Activities</td>
		<td>Allocated Time</td>
		<td>Card-No</td>
	</tr>

	@php
		$r = 1 ;
		$status = session()->get('volunteer_excel_export') ;
		$table =  model("user")::selector([
				'roleString' => "admin.$status",
				'status' => $status,
			])
			->orderBy('created_at', 'desc')
			->get()
		;

	@endphp

	@foreach($table as $row)

		{{--@if($row->birth_date != '0000-00-00')--}}
			<tr>
				<td>{{ $r++ }}</td>
				<td>{{ $row->full_name }}</td>
				<td>{{ $row->name_father }}</td>
				<td>{{ $row->code_melli }}&nbsp;</td>
				<td>{{ $row->mobile }}</td>
				<td>{{ $row->home_tel }}</td>
				<td>{{ $row->tel_emergency }}</td>
				<td>{{ $row->email }}</td>
				<td>{{ $row->home_city_name }}</td>
				<td>{{ $row->home_address }}</td>
				<td>{{ $row->gender_icon }}</td>
				<td>{{ trans("people.edu_level_full." . intval($row->edu_level))}}</td>
				<td>{{ $row->activity_captions }}</td>
				<td>{{ $row->alloc_time }}</td>
				<td>{{ $row->card_no }}&nbsp;</td>
			</tr>
		{{--@endif--}}
	@endforeach
</table>