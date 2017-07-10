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
		]     )

		@include("forms.input-self" , [
			'name' => "keyword",
			'placeholder' => trans("forms.button.search_for").'...' ,
		]     )

		@include("forms.button" , [
			'label' => trans("forms.button.find"),
			'shape' => "primary" ,
			'class' => "w100 mv5" ,
			'type' => "submit" ,
		]     )


		@include("forms.closer")

	</div>

</div>