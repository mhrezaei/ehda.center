@if($model->has('album'))
	<div class="panel panel-violet mv20">
		{{--
		|--------------------------------------------------------------------------
		| Heading
		|--------------------------------------------------------------------------
		|
		--}}

		<div class="panel-heading">
			<i class="fa fa-address-book-o mh5"></i>
			{{ trans('posts.files.title') }}
			&nbsp;(<span id="spnFileCount">{{ pd(count($model->files)) }}</span>)
		</div>

		<div class="panel-body">
			{{--
			|--------------------------------------------------------------------------
			| Already uploaded photos
			|--------------------------------------------------------------------------
			|
			--}}
			<div id="divCurrentFiles">


				@foreach($model->files as $key => $file)
					@include('manage.posts.editor-album-one' , [
						'key' => $key ,
						'src' => model('file', $file['src'])->pathname ,
						'label' => $file['label'] ,
						'link' => isset($file['link'])? $file['link'] : '',
					])
				@endforeach
			</div>
			<input type="hidden" id="txtLastKey" value="{{$key or 0}}">


			{{--
			|--------------------------------------------------------------------------
			| New Files and Dropzone
			|--------------------------------------------------------------------------
			|
			--}}
			<div id="divNewFiles" class="m10 text-center" data-src="manage/posts/act/{{$model->hashid}}/editor-album2-new/" >
				@include("manage.posts.editor-album2-new")
			</div>

		</div>

	</div>







@endif

{{--{{ \App\Providers\UploadServiceProvider::removeFile('gOoY3') }}--}}