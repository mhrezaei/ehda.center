<?php
if(!isset($disabled))
	$disabled = false;

if($disabled) {
	$required = false;
}
$input_id = "txt-$name";

?>
@if(!isset($condition) or $condition)
	@include('forms.group-start' , [
		'label' => isset($label)? $label : trans("validation.attributes.$name"),
	])

	<div class="row">
		@if(!$disabled)
			<div class="col-md-3">

				<button id="{{ "btn-$name" }}" type="button" data-file-manager-input="{{ $input_id }}"
						data-file-manager-callback="downstreamPhotoSelected('#{{ $input_id }}')" class="btn btn-default btn-sm">
					{{ trans('forms.button.browse_image') }}
				</button>

			</div>
		@endif
		<div class="col-md-{{$disabled?'12':'9'}}">
			<input id="{{ $input_id }}" type="text" name="{{ $name }}" value="{{ $value or ''  }}" readonly
				   class="form-control ltr clickable text-grey italic"
				   onclick="downstreamPhotoPreview('#{{ $input_id }}')">
			@if(!$disabled)
				<i class="fa fa-times text-grey clickable" style="position: relative;top:-25px;left:-10px"
				   onclick="$('#{{$input_id}}').val('')"></i>
			@endif
		</div>
	</div>


	<script>
	    {{--$('#{{ "btn-".$name }}').filemanager('image');--}}
		  $('#{{ "btn-".$name }}').fileManagerModal('Files', {
		    prefix: "{{ route('fileManager.index') }}",
	    });
	</script>

	@include('forms.group-end')
@endif