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
			{{ trans('posts.album.singular') }}
			&nbsp;(<span id="spnPhotoCount">{{ pd(count($model->photos)) }}</span>)
		</div>
		
		<div class="panel-body">

			{{--
			|--------------------------------------------------------------------------
			| Already uploaded photos
			|--------------------------------------------------------------------------
			|
			--}}
			<div id="divPhotos">


				@foreach($model->photos as $key => $photo)
					@include('manage.posts.editor-album-one' , [
						'key' => $key ,
						'src' => $photo['src'] ,
						'label' => $photo['label'] ,
						'link' => isset($photo['link'])? $photo['link'] : '',
					])
				@endforeach
			</div>

			<div id="divNewPhoto">
				@include('manage.posts.editor-album-one' , [
					'key' => 'NEW' ,
					'class' => 'noDisplay'
				])
			</div>
			<input type="hidden" id="txtLastKey" value="{{$key or 0}}">

			{{--
			|--------------------------------------------------------------------------
			| New Panel
			|--------------------------------------------------------------------------
			|
			--}}
			<div class="m10 text-center" style="">
				<button id="btnAddPhoto" data-input="txtAddPhoto" data-preview="imgAddPhoto" data-callback="postPhotoAdded()" class="btn btn-default btn-lg">
					{{ trans('posts.album.add_photo') }}
				</button>
				<input type="hidden" id="txtAddPhoto">
				<img id="imgAddPhoto" class="noDisplay" src="">
			</div>


		</div>
	</div>

	{{--
	|--------------------------------------------------------------------------
	| Javascript function
	|--------------------------------------------------------------------------
	|
	--}}

	<script>
	    $('#btnAddPhoto').filemanager('image');
	</script>

@endif