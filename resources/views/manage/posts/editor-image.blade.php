@if($model->has('featured_image'))
	<div class="panel panel-default">
		<div class="panel-heading">
			{{ trans('validation.attributes.featured_image') }}
		</div>

		<div class="m10 text-center" style="">
			<button type="button" id="btnFeaturedImage" data-file-manager-input="txtFeaturedImage" data-file-manager-preview="divFeaturedImageInside"
					data-file-manager-callback="featuredImage('inserted')"
					class="btn btn-{{ $model->featured_image? 'default' : 'primary' }}">
				{{ trans('forms.button.browse_image') }}
			</button>
			<input id="txtFeaturedImage" type="hidden" name="featured_image"
				   value="{{ $model->featured_image? url($model->featured_image) : '' }}">
			<div id="divFeaturedImage" class="{{ $model->featured_image? '' : 'noDisplay' }}">
				<div id="divFeaturedImageInside" class="w90 m10" style="border-radius: 10px"></div>

				{{--<div class="text-center">--}}
					{{--<img id="imgFeaturedImage" src="{{ $model->featured_image? url($model->featured_image) : '' }}"--}}
						 {{--style="margin-top:15px;max-height:100px;max-width: 100%">--}}
				{{--</div>--}}
				<button type="button" id="btnDeleteFeaturedImage" class="btn btn-link btn-xs"
						onclick="featuredImage('deleted')">
				<span class="text-danger clickable">
					{{ trans('forms.button.flush_image') }}
				</span>
				</button>
			</div>
		</div>

		<script>
			  $("#btnFeaturedImage").fileManagerModal('Files', {
				  prefix: "{{ route('fileManager.index') }}",
			  });
		</script>

	</div>
@endif