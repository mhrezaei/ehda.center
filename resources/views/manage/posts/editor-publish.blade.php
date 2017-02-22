<div class="panel panel-default text-center">
	<div class="panel-heading text-right">{{ trans('posts.form.publish') }}</div>

	{{--
	|--------------------------------------------------------------------------
	| Current Status
	|--------------------------------------------------------------------------
	|
	--}}

	<div class="text-center m10 alert alert-{{ trans("forms.status_color.$model->status") }}">
		{{ trans("forms.status_text.$model->status") }}
	</div>


	{{--
	|--------------------------------------------------------------------------
	| Main Publish Button
	|--------------------------------------------------------------------------
	|
	--}}
	<div class="btn-group m5">


		{{-- Main Button --}}
		@if($model->canPublish())
			<button type="submit" name="_submit" value="publish" class="btn btn-primary">{{ trans('posts.form.publish') }}</button>
		@else
			<button type="submit" name="_submit" value="moderate" class="btn btn-primary">{{ trans('posts.form.send_for_moderation') }}</button>
		@endif

		{{-- Carret --}}
		<button type="button" class="btn btn-primary dropdown-toggle minWidthAuto" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<span class="caret"></span>
			<span class="sr-only">other_save_options</span>
		</button>





		{{-- Small Buttons --}}
		<ul class="dropdown-menu">
			@include("manage.posts.editor-subButton" , [ 'buttons' => [
				[
					'command' => "adjust_publish_time",
				],
				[
					'command' => "send_for_moderation",
					'condition' => $model->canPublish(),
				],
				[
					'command' => "-",
					'condition' => $model->canPublish(),
				],
				[
					'command' => "refer_back",
					'condition' => $model->canPublish() and !$model->isOwner(),
				],
				[
					'command' => "refer_to",
					'condition' => $model->canPublish(),
				],
				[
					'command' => "unpublish",
					'condition' => $model->canPublish() and $model->isPublished(),
				],
				[
					'command' => "delete",
					'condition' => $model->canDelete(),
				],
			]])
		</ul>

	</div>


	{{--
	|--------------------------------------------------------------------------
	| Small Buttons
	|--------------------------------------------------------------------------
	|
	--}}

	<div class="row m5">
		<div class="col-md-7  text-right">
			<button name="_submit" value="save" class="btn btn-link btn-xs w100 minWidthAuto">{{ trans('posts.form.save_draft') }}</button>
		</div>
		<div class="col-md-5 text-left">
			@if($model->has('preview'))
				<a href="{{$model->preview_link}}" target="_blank" class="btn btn-xs btn-link w100 minWidthAuto">{{ trans('posts.form.preview') }}</a>
			@endif
		</div>
	</div>





</div>
