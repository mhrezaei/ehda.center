<div class="row w100">
	<div class="col-md-6">
	</div>
	<div class="col-md-6" style="text-align: left">


		<button id="btnDownloadExcel"
				onclick="window.location='{{url("manage/cards/printings/download_excel/$event_id")}}'"
				onchange="window.open('{{url("manage/cards/printings/download_excel/$event_id")}}')"
				style="min-width: 250px"
				class="btn btn-lg btn-primary {{ $request_tab != 'under_excel_printing' ? 'noDisplay' : ''  }}" >
			<i class="fa fa-download mh10"></i>
			{{ trans("forms.button.download_excel_file") }}
		</button>



	</div>
</div>

