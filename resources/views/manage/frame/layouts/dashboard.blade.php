@extends('manage.frame.layouts.plane')

@section('body')
	<div id="wrapper">

		<!-- Navigation -->
		<nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0 ">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				@yield('navbar-brand')
			</div>
			<!-- /.navbar-header -->

			<ul class="nav navbar-top-links navbar-left">
				@yield('navbar-menus')
			</ul>
			<!-- /.navbar-top-links -->

			<div id="divSideMother" class="navbar-default sidebar" role="navigation" style="overflow-y: scroll">
				<div class="sidebar-nav navbar-collapse">
					<ul class="nav" id="side-menu">
						@yield('sidebar')
					</ul>
				</div>
				<!-- /.sidebar-collapse -->
			</div>
			<!-- /.navbar-static-side -->
		</nav>

		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					{{--<h1 class="page-header">@yield('page_heading')</h1>--}}
					<h1></h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>
			<div class="row">
				@yield('section')
			</div>
			<!-- /#page-wrapper -->
			@yield('modal')
		</div>
	</div>
	<i id="sidebarHandle" class="fa fa-chevron-right" onclick="sidebarToggle('fast')"></i>
@endsection