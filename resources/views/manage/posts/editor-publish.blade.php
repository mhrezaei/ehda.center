<div class="refresh">{{ url("manage/posts/act/$model->id/editor-publish") }}</div>
<div class="panel panel-default text-center">
	<div class="panel-heading text-right" ondblclick="divReload('divPublishPanel')">{{ trans('posts.form.publish') }}</div>
	<div class="panel-body bg-ultralight w100">

		{{--
		|--------------------------------------------------------------------------
		| Current Status
		|--------------------------------------------------------------------------
		|
		--}}
		{{ '' , $status_color = trans("forms.status_color.$model->status") }}

		<div class="text-center alert panel-{{ $status_color }} bg-{{$status_color}}">
			{{ trans("forms.status_text.$model->status") }}
			@if($model->isCopy())
				<span class="badge badge-inverse f8 mh5" title="{{ trans('posts.form.copy_status_hint') }}">{{ trans('posts.form.copy') }}</span>
			@endif
		</div>

		{{--
		|--------------------------------------------------------------------------
		| Main Publish Button
		|--------------------------------------------------------------------------
		|
		--}}
		<div class="btn-group">


			{{-- Main Button --}}
			@if($model->canPublish() and $model->isApproved())
				<button type="submit" name="_submit" value="publish" class="btn btn-primary">{{ trans('posts.form.update_button') }}</button>
			@elseif($model->canPublish() and !$model->isApproved())
				<button type="submit" name="_submit" value="publish" class="btn btn-primary">{{ trans('posts.form.publish') }}</button>
			@else
				<button type="submit" name="_submit" value="approval" class="btn btn-primary">{{ trans('posts.form.send_for_moderation') }}</button>
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
						'condition' => $model->has('schedule') and !$model->isPublished() and !$model->isScheduled(),
						'id' => 'lnkSchedule',
					],
					[
						'command' => "send_for_approval",
						'condition' => $model->canPublish(),
					],
					[
						'command' => "-",
						'condition' => $model->canPublish(),
					],
					[
						'command' => "refer_back",
						'condition' => $model->canPublish() and !$model->isOwner() and !$model->isApproved(),
					],
					[
						'command' => "refer_to",
						'condition' => $model->canPublish() and !$model->isApproved(),
					],
					[
						'command' => "unpublish",
						'condition' => $model->canPublish() and $model->isPublished(),
					],
					[
						'command' => "delete",
						'condition' => $model->canDelete(),
					],
					[
						'command' => "-",
						'condition' => $model->has('history_system'),
					],
					[
						'command' => "history",
						'condition' => $model->has('history_system'),
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

		<div class="row">
			<div class="col-md-7  text-right">
				<button type="submit" name="_submit" value="save" class="btn btn-link btn-xs w100 minWidthAuto">{{ trans('posts.form.save_draft') }}</button>
			</div>
			<div class="col-md-5 text-left">
				@if($model->has('preview'))
					<a href="{{$model->preview_link}}" target="_blank" class="btn btn-xs btn-link w100 minWidthAuto">{{ trans('posts.form.preview') }}</a>
				@endif
			</div>
		</div>



	</div>

</div>
