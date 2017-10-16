@include('templates.modal.start' , [
	'partial' => true ,
//	'form_url' => url('manage/posts/save/soft_delete'),
	'modal_title' => trans('ehda.volunteers.excel_export'),
])

<div class='modal-body text-center'>

	<div class="mv10">
		<a href="{{ url('manage/volunteers/excel/8') }}" class="btn btn-lg btn-success w30">
			{{ trans("ehda.volunteers.actives") }}
		</a>
	</div>
	<div class="mv10">
		<a href="{{ url('manage/volunteers/excel/3') }}" class="btn btn-lg btn-warning w30">
			{{ trans("ehda.volunteers.pendings") }}
		</a>
	</div>
	<div class="mv10">
		<a href="{{ url('manage/volunteers/excel/all') }}"  class="btn btn-lg btn-primary w30">
			{{ trans("forms.general.all") }}
		</a>
	</div>



</div>

@include('templates.modal.end')
