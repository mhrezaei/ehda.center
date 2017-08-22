{{--
|--------------------------------------------------------------------------
| Events Filter
|--------------------------------------------------------------------------
|
--}}
@if(count($events_array) > 1)
	@include("manage.frame.widgets.grid-action" , [
		'id' => "Filter",
		'button_size' => "sm" ,
		'button_class' => "default" ,
		'button_label' => $event_title ,
		'actions' => $events_array ,
	]     )
@endif



{{--
|--------------------------------------------------------------------------
| Excel Download
|--------------------------------------------------------------------------
|
--}}

<button id="btnDownloadExcel"
		onclick="window.location='{{url("manage/cards/printings/download_excel/$event_id")}}'"
		{{--onchange="window.open('{{url("manage/cards/printings/download_excel/$event_id")}}')"--}}
		class="btn btn-sm btn-primary {{ $request_tab != 'under_excel_printing' ? 'noDisplay' : ''  }}" >
	<i class="fa fa-download mh10"></i>
	{{ trans("forms.button.download_excel_file") }}
</button>
