@extends("manage.frame.use.0")

@section('html_header')
	{!! Html::script('assets/js/postEditor.js') !!}
@endsection

@section('section')
	@include('forms.opener' , [
		'id' => 'frmEditor',
		'url' => 'manage/posts/save',
		'files' =>false,
		'class' => 'js'
	])

		@include("forms.feed")

		@include("forms.hiddens" , ['fields' => [
			['id' , $model->id] ,
			['type' , $model->encrypted_type],
		]])

		<div class="row w100">
			<div class="col-md-9 col-lg-10" >
				@include("manage.posts.editor-1")
			</div>
			<div class="col-md-3 col-lg-2">
				@include("manage.posts.editor-2")
			</div>
		</div>
	
	@include("forms.closer")

	<script>postsInit()</script>
@endsection