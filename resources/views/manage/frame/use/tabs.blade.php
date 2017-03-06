{{-- $tabs = [ 0:url 1:caption 2:badge 3:condition 4:badge-color ] --}}
<ul class="nav nav-tabs">
	@if(isset($refresh_url))
		<div class="refresh">{{ url($refresh_url."/".urlencode(str_replace('/','-',$current))) }}</div>
	@endif
	@foreach($tabs as $tab)
		<?php
			$url = $tab[0] ;
			$caption = $tab[1] ? $tab[1] : trans('manage/'.$page[0][0].".$tab.trans") ;
			$condition = isset($tab[3]) ? $tab[3] : true ;
			if($url==$current)
				$active = true ;
			else
				$active = false ;
		?>
		@if($condition)
			<li class="{{ $active ? 'active' : '' }}">
				<a href="{{ url("manage/".$page[0][0]."/".$url) }}">
					{{$caption}}
					@if(isset($tab[2]) and $tab[2]>0)
						<span class="ph5 text-{{$tab[4] or ''}}">
							<span style="font-size: smaller">[</span>
							<span style="font-size: larger">@pd($tab[2])</span>
							<span style="font-size: smaller">]</span>
						</span>
					@endif
				</a>
			</li>
		@endif
	@endforeach
</ul>
