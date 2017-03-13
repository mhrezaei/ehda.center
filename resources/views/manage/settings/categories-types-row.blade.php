@include('manage.frame.widgets.grid-rowHeader')

<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => $model->title,
	])
</td>

<td>
	{{ '' , $base = url("manage/categories") }}
	@foreach($model->locales_array as $lang)
		<a class="btn btn-default" style="min-width: 100px" href="{{ "$base/browse/$model->slug/$lang" }}">
			<img src="{{ asset("assets/images/lang-$lang.png") }}" style="width: 20px;margin-left: 5px">
			{{ trans("forms.lang.$lang") }}
		</a>
	@endforeach

</td>