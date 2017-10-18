<div class="panel panel-green">


	{{--
	|--------------------------------------------------------------------------
	| Header
	|--------------------------------------------------------------------------
	| 
	--}}

	<div class="panel-heading">
		<i class="fa fa-child"></i>
		<span class="mh5">
			{{ trans("ehda.volunteers.plural") }}
		</span>
		<span class="pull-left">
			<i class="fa fa-refresh clickable -refresh" onclick="divReload('divVolunteers');$('#divVolunteers .-refresh').slideToggle()"></i>
		</span>
	</div>

	{{--
	|--------------------------------------------------------------------------
	| Body
	|--------------------------------------------------------------------------
	|
	--}}

	<div class="panel-footer">

		@if(isset($ajax))

			{{ '' , $status_rule_array = array_reverse(model('role')::where('is_admin',1)->first()->status_rule_array) }}
			{{ '' , $total = 0 }}
			{{ '' , $data = [] }}

			@foreach(model('role')::where('is_admin',1)->first()->status_rule_array as $key => $status)
				{{ '' , $count = model('user')::selector(['roleString' => "volunteer-" ,])->where('cache_roles' , 'like' , "%". model('user')::deface(".$key") ."%")->count() }}
				{{ '' , $total += $count }}
				{{ '' , $data[trans("people.criteria.$status")] = $count  }}
				@if($status=='active')
					{{ '' , $active_count = $count }}
				@endif
			@endforeach


			@include("manage.frame.widgets.t-charts.pie" , [
				'height' => "250",
				'data' => $data ,
				'label_size' => "10" ,
			])
			<div class="text-center w100 p5" style="margin-top: 10px">
				{{ '' , $can_link = count(user()->userRolesArray('browse' , [] , model('role')::adminRoles())) }}
				<a class="btn btn-default" {{ $can_link? '' : 'disabled' }} href="{{ v0() }}" onclick="masterModal('{{ url("manage/volunteers/excel") }}')">
				{{--<a class="btn btn-default" {{ $can_link? '' : 'disabled' }} href="{{$can_link? url('manage/volunteers/browse/all') : v0()}}">--}}
					{{ pd(number_format($active_count)) }}
					&nbsp;
					{{ trans("ehda.volunteers.single_active") }}
				</a>
			</div>
			<div class="text-center w100">
				@include("manage.frame.widgets.loading" , [
					'class' => "-refresh noDisplay" ,
				])
			</div>


		@else

			<div class="m30 margin-auto text-center">
				@include("manage.frame.widgets.loading")
				<script>divReload('divVolunteers')</script>
			</div>

		@endif

 	</div>

</div>