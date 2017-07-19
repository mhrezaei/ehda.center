<div class="panel panel-red">

	<div class="panel-heading">
		<i class="fa fa-plus-circle"></i>
		<span class="mh5">
			{{ trans("posts.form.new_content") }}
		</span>
	</div>


	<div class="panel-footer">


		{{-- The Big Button ----------------------------- --}}
		<div id="divIndexCreateHandle" class="w100 text-center">
			<button class="btn btn-lg btn-default w80" onclick="indexCreateToggle()">{{ trans("posts.form.create_new_post") }}</button>
		</div>


		{{-- The Content ----------------------------- --}}
		<div id="divIndexCreateItems" class="noDisplay w100">
			@foreach($topbar_create_menu as $item)
				@if($item[0]=='-')
					{{--<hr>--}}
				@else
					<div class="row mv5">
						<a href="{{ url($item[0]) }}">
							<div class="col-md-1">
								<i class="fa fa-{{$item[2] or 'dot'}}"></i>
							</div>
							<div class="col-md-10">
								{{ $item[1] }}
							</div>
						</a>
					</div>
				@endif
			@endforeach
		</div>


	</div>

</div>


{{--
|--------------------------------------------------------------------------
| Javascript
|--------------------------------------------------------------------------
|
--}}

<script>
	function indexCreateToggle(not_any_more = false) {
		$('#divIndexCreateItems,#divIndexCreateHandle').slideToggle('fast');
		if (!not_any_more) {
			setTimeout(function () {
				indexCreateToggle(true);
			}, 30000)
		}
	}
</script>