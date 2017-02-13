{{-- $tabs = [ 0:url 1:caption 2:permit 3:badge 4:badge-color ] --}}
<ul class="nav nav-tabs">
	@foreach($tabs as $tab)
		<?php
			$url = $tab[0] ;
			$caption = $tab[1] ? $tab[1] : trans('manage/'.$page[0][0].".$tab.trans") ;
			$permit = isset($tab[2]) ? $tab[2] : 'any' ;
			if($url==$current)
				$active = true ;
			else
				$active = false ;
		?>
		@if(Auth::user()->can($permit))
			<li class="{{ $active ? 'active' : '' }}">
				<a href="{{ url("manage/".$page[0][0]."/".$url) }}">
					{{$caption}}
					@if(isset($tab[3]) and $tab[3]>0)
						<span class="label label-{{$tab[4] or 'warning'}} p5">
							@pd($tab[3])
						</span>
					@endif
				</a>
			</li>
		@endif
	@endforeach
</ul>
