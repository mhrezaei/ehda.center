{{--
|--------------------------------------------------------------------------
| Newly Uploaded Files
|--------------------------------------------------------------------------
|
--}}
<input name="txtNewFiles" value="">

@if(isset($option))
	@foreach(array_filter(explode('-' , $option)) as $key => $item)
		@if($file = model('file', $item) and $file->id)
			@include('manage.posts.editor-album-one' , [
				'key' => $key ,
				'src' => $file->pathname ,
				'label' => null ,
				'link' => null ,
			])
		@endif
	@endforeach
@endif


{{--
|--------------------------------------------------------------------------
| Drop Zone
|--------------------------------------------------------------------------
|
--}}
{{--<button id="btnAddPhoto" class="btn btn-default btn-lg" onclick="$('#btnAddPhoto,#divDropzone').slideToggle('fast')">--}}
{{--{{ trans('posts.files.add_file') }}--}}
{{--</button>--}}
<div id="divDropzone" class="noDisplay-">
	{!!  FileManager::posttypeUploader($model->posttype , [
		'callbackOnQueueComplete' => "filemanagerUploadFinish" ,
		'target' => "txtNewFiles" ,
	])  !!}
</div>





