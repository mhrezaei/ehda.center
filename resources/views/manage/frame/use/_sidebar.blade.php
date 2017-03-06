<li class="sidebar-search">
	<div class="input-group custom-search-form">
		<input type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
									<i class="fa fa-search"></i>
								</button>
                            </span>
	</div>
	<!-- /input-group -->
</li>
<li {{ (Request::is('/') ? 'class="active"' : '') }}>
	<a href="{{ url ('') }}"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
</li>
<li {{ (Request::is('*charts') ? 'class="active"' : '') }}>
	<a href="{{ url ('charts') }}"><i class="fa fa-bar-chart-o fa-fw"></i> Charts</a>
	<!-- /.nav-second-level -->
</li>
<li {{ (Request::is('*tables') ? 'class="active"' : '') }}>
	<a href="{{ url ('tables') }}"><i class="fa fa-table fa-fw"></i> Tables</a>
</li>
<li {{ (Request::is('*forms') ? 'class="active"' : '') }}>
	<a href="{{ url ('forms') }}"><i class="fa fa-edit fa-fw"></i> Forms</a>
</li>
<li>
	<a href="#"><i class="fa fa-wrench fa-fw"></i> UI Elements<span class="fa arrow"></span></a>
	<ul class="nav nav-second-level">
		<li {{ (Request::is('*panels') ? 'class="active"' : '') }}>
			<a href="{{ url ('panels') }}">Panels and Collapsibles</a>
		</li>
		<li {{ (Request::is('*buttons') ? 'class="active"' : '') }}>
			<a href="{{ url ('buttons' ) }}">Buttons</a>
		</li>
		<li {{ (Request::is('*notifications') ? 'class="active"' : '') }}>
			<a href="{{ url('notifications') }}">Alerts</a>
		</li>
		<li {{ (Request::is('*typography') ? 'class="active"' : '') }}>
			<a href="{{ url ('typography') }}">Typography</a>
		</li>
		<li {{ (Request::is('*icons') ? 'class="active"' : '') }}>
			<a href="{{ url ('icons') }}"> Icons</a>
		</li>
		<li {{ (Request::is('*grid') ? 'class="active"' : '') }}>
			<a href="{{ url ('grid') }}">Grid</a>
		</li>
	</ul>
	<!-- /.nav-second-level -->
</li>
<li>
	<a href="#"><i class="fa fa-sitemap fa-fw"></i> Multi-Level Dropdown<span class="fa arrow"></span></a>
	<ul class="nav nav-second-level">
		<li>
			<a href="#">Second Level Item</a>
		</li>
		<li>
			<a href="#">Second Level Item</a>
		</li>
		<li>
			<a href="#">Third Level <span class="fa arrow"></span></a>
			<ul class="nav nav-third-level">
				<li>
					<a href="#">Third Level Item</a>
				</li>
				<li>
					<a href="#">Third Level Item</a>
				</li>
				<li>
					<a href="#">Third Level Item</a>
				</li>
				<li>
					<a href="#">Third Level Item</a>
				</li>
			</ul>
			<!-- /.nav-third-level -->
		</li>
	</ul>
	<!-- /.nav-second-level -->
</li>
<li>
	<a href="#"><i class="fa fa-files-o fa-fw"></i> Sample Pages<span class="fa arrow"></span></a>
	<ul class="nav nav-second-level">
		<li {{ (Request::is('*blank') ? 'class="active"' : '') }}>
			<a href="{{ url ('blank') }}">Blank Page</a>
		</li>
		<li>
			<a href="{{ url ('login') }}">Login Page</a>
		</li>
	</ul>
	<!-- /.nav-second-level -->
</li>
<li {{ (Request::is('*documentation') ? 'class="active"' : '') }}>
	<a href="{{ url ('documentation') }}"><i class="fa fa-file-word-o fa-fw"></i> Documentation</a>
</li>
