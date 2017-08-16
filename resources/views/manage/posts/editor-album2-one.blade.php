@if($file = model('file' , $src) and $file->id)
	<div id="divPhoto-{{$key}}" class="row w100 m10 p10 {{$class or ''}}" -style="height: 100px">


		<div class="col-md-3 text-center">
			<img src="{{ url($file->pathname) }}" style="margin-top:15px;max-height:100px;max-width: 100px">
		</div>



		<div class="col-md-8 text-center">
			<input name="_photo_label_{{$key}}" value="{{$label or ''}}" class="-label form-control text-center" placeholder="{{trans('posts.files.label_placeholder')}}" style="margin-top: 15px">
			<input name="_photo_link_{{$key}}" value="{{$link or ''}}" class="-label form-control text-center ltr" placeholder="{{trans('posts.files.link_placeholder')}}" style="margin-top:5px">
			<input type="hidden" name="_photo_src_{{$key}}" value="{{ $file->hash_id }}" class="-src form-control">
			<button type="button" class="btn btn-link" onclick="postPhotoRemoved('{{$key}}' , '{{$file->hash_id}}')">
				<span class="text-danger">
					<i class="fa fa-remove"></i>
					{{ trans('posts.files.remove') }}
				</span>
			</button>
		</div>
		<div class="col-md-1 text-center">
		</div>
	</div>
@endif