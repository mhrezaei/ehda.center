<div class="panel panel-toolbar row w100">
	<div class="col-md-6"><p class="title">{{ $title or $page[0][1].' / '.$page[1][1]}}</p></div>
	<div class="col-md-6 tools">

		@if(isset($buttons))
			@foreach($buttons as $button)
				@include("manage.frame.widgets.toolbar_button" , $button)
			@endforeach
		@endif
		@if(isset($search))
			@include('manage.frame.widgets.toolbar_search_inline' , $search)
		@endif

	</div>
</div>

