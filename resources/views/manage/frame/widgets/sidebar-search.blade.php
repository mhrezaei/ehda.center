<li class="sidebar-search">
	{!! Form::open([
		'url'	=> $url ,
		'method'=> 'get',
]		) !!}

	<div class="input-group custom-search-form">
		<input type="text" class="form-control" placeholder="{{$placeholder or ''}}">

		<span class="input-group-btn">
			<button class="btn btn-default" type="button">
				<i class="fa fa-search"></i>
			</button>
		</span>
	</div>

	{!! Form::close() !!}
</li>