<div class="panel panel-yellow">

	<div class="panel-footer w100 row" >

		{{--
		|--------------------------------------------------------------------------
		| Right Panel
		|--------------------------------------------------------------------------
		| Name and Volunteer Status
		--}}
		<div class="col-md-8">
			<h3>
				{{ user()->full_name }}
			</h3>

			<div class="pv10">

				@foreach(user()->withDisabled()->rolesQuery() as $role)
					@if($role['pivot']['deleted_at'])
						{{ '' , $color = 'danger' }}
						{{ '' , $icon = 'times '}}
					@elseif($role['pivot']['status'] >= 8)
						{{ '' , $color = 'success' }}
						{{ '' , $icon = 'check '}}
					@else
						{{ '' , $color = 'warning' }}
						{{ '' , $icon = 'hourglass-half'}}
					@endif
					@include("manage.frame.widgets.grid-badge" , [
						'condition' => $role['is_admin'],
						'text' => $role['title'] ,
						'color' => $color ,
						'icon' => $icon ,
						'size' => "12" ,
					]     )

				@endforeach

			</div>
		</div>


		{{--
		|--------------------------------------------------------------------------
		| Donation Card
		|--------------------------------------------------------------------------
		| Card image, or user name, together with a card creation button
		--}}

		<div class="col-md-4">

			@include("manage.account.card-inside")


		</div>



	</div>

</div>