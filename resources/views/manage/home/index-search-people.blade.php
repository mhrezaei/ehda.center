<div class="panel panel-violet">

	<div class="panel-heading">
		<i class="fa fa-users"></i>
		<span class="mh5">
			{{ trans("manage.search-people") }}
		</span>
	</div>


	<div class="panel-footer">

		@include("forms.opener" , [
			'url' => url('manage/act/search-people'),
			'method' => "get" ,
			'class' => "js" ,
		]     )

		@include("forms.input-self" , [
			'name' => "keyword",
			'placeholder' => trans("people.smart_finder_placeholder") ,
			'class' => "ltr text-center" ,
		]     )

		@include("forms.button" , [
			'label' => trans("forms.button.find"),
			'shape' => "primary" ,
			'class' => "w100 mv5" ,
			'type' => "submit" ,
		]     )

		@include("forms.feed")

		@include("forms.closer")

	</div>

</div>