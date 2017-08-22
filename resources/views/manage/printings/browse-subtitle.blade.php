@if(session()->pull('excelDownload' , false))

	<div class="w70 margin-auto alert alert-info text-center">
		<div class="mv20">
			{{ trans("ehda.printings.popup_warning") }}
		</div>
		<div>
			<button onclick="window.location='{{url("manage/cards/printings/download_excel/$event_id")}}'"
					class="btn btn-lg btn-primary">
				<i class="fa fa-download mh10"></i>
				{{ trans("forms.button.download_excel_file") }}
			</button>

		</div>
		<div>
			<button onclick="$(this).parent().parent().slideUp('fast')" class="btn btn-link btn-sm">
				{{ trans("forms.general.no_need") }}
			</button>
		</div>

	</div>

	<script>
	    setTimeout(function () {
		    window.location = '{{url("manage/cards/printings/download_excel/$event_id")}}'
	    }, 2000);
	</script>
@endif
