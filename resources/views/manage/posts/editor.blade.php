@extends("manage.frame.use.0")

@section('html_header')
	{!! Html::script('assets/js/postEditorScripts.js') !!}
@endsection

@section('section')
	@include('forms.opener' , [
		'id' => 'frmEditor',
		'url' => 'manage/posts/save',
		'files' =>false,
		'class' => 'js'
	])

		@include("manage.posts.editor-tabs")

		@include("forms.feed")

		@include("forms.hiddens" , ['fields' => [
			['id' , $model->id] ,
			['type' , $model->encrypted_type],
		]])

		<div class="row w100" style="margin-bottom: 100px">
			<div class="col-md-9" >
				@include("manage.posts.editor-1")
			</div>
			<div class="col-md-3">
				@include("manage.posts.editor-2")
			</div>
		</div>
	
	@include("forms.closer")

	<script>postsInit()</script>
@endsection