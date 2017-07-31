<div class="panel panel-black">

	<div class="panel-heading">
		<i class="fa fa-github-alt"></i>
		<span class="mh5">
			{{ trans('settings.upstream') }}
		</span>
	</div>


	<div class="panel-footer">

		@foreach(Manage::upstreamSettings() as $setting)
			@include("manage.frame.widgets.grid-text" , [
				'icon' => "github-alt",
				'link' => url("manage/upstream/".$setting[0]) ,
				'text' => $setting[1] ,
				'size' => "12" ,
				'fake' => $model = user() ,
				'div_class' => "mv10" ,
			]     )
		@endforeach


	</div>

</div>