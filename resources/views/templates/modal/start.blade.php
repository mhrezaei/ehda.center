@if(!isset($modal_id))
	<?php $modal_id = "modal".rand(1,10000); ?>
@endif
@if(isset($partial) and !$partial)
<div id="{{$modal_id}}" class="modal fade {{$modal_class or ''}}">
	<div class="modal-dialog  modal-{{ $modal_size or 'lg' }}" >
		<div class="modal-content">
			@endif

			@if(isset($modal_title))
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 id="{{ $modal_id }}-title" class="modal-title">
						{{ $modal_title }}
					</h4>
				</div>
			@endif

			@include('forms.opener',[
				'id' => $modal_id.'-form' ,
				'url' => isset($form_url)? url($form_url) : '#' ,
				'method' => isset($form_method)? $form_method : 'post' ,
				'files' => isset($form_files)? $form_files : 'false' ,
				'class' => isset($form_class)? "js $form_class" : 'js ' ,
			])

			@include('forms.hidden' , [
				'name' => '_modal_id' ,
				'value' => $modal_id ,
			])

			@if(isset($hidden_vars))
				@foreach($hidden_vars as $idx => $hidden_var)
						<label class="hidden _{{$idx}}">{{$hidden_var}}</label>
					@endforeach
			@endif

			{{-- divs classed `modal-body` and `moda-footer` should be included in the page. Sorry but either this or no blade at all. --}}

			@if(0)
		</div>
	</div>
</div>
@endif