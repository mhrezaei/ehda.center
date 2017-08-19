@if($model->has('featured_image'))
	<div class="panel panel-default">
		<div class="panel-heading">
			{{ trans('validation.attributes.featured_image') }}
		</div>

		<div class="m10 text-center" style="">
			<button type="button" id="btnFeaturedImage" data-input="txtFeaturedImage" data-preview="imgFeaturedImage" data-callback="featuredImage('inserted')" class="btn btn-{{ $model->featured_image? 'default' : 'primary' }}">
				{{ trans('forms.button.browse_image') }}
			</button>
			<input id="txtFeaturedImage" type="hidden" name="featured_image" value="{{ $model->featured_image? url($model->featured_image) : '' }}">
			<div id="divFeaturedImage" class="{{ $model->featured_image? '' : 'noDisplay' }}">
				<div class="text-center">
				</div>
				<button type="button" id="btnDeleteFeaturedImage" class="btn btn-link btn-xs" onclick="featuredImage('deleted')">
				<span class="text-danger clickable">
					{{ trans('forms.button.flush_image') }}
				</span>
				</button>
			</div>
		</div>

		<script>
		  $('#btnFeaturedImage').filemanager('image');
		</script>

	</div>
@endif