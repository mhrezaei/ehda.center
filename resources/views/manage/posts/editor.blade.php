@extends("manage.frame.use.0")

@section('html_header')
	{!! Html::script('assets/js/postEditorScripts.js') !!}
@endsection

@section('section')
	@include('forms.opener' , [
		'id' => 'frmEditor',
		'url' => 'manage/posts/save',
		'files' =>false,
		'class' => 'js',
		'no_validation' => "1" ,
	])

		@include("forms.feed")
		<div class="noDisplay">
			@include("manage.posts.editor-hiddens")
		</div>

		<div class="row w100" style="margin-bottom: 50px">
			<div class="col-md-9" >
				@include("manage.posts.editor-1")
			</div>
			<div class="col-md-3">
				@include("manage.posts.editor-2")
			</div>
		</div>


	@include("forms.closer")

	@include("manage.posts.editor-modals")


	<script>postsInit()</script>
@endsection