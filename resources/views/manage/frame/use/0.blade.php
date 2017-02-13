@extends('manage.frame.home')

@section('navbar-brand')
	@include("manage.frame.use.brand")
@endsection
@section('navbar-menus')
	@include("manage.frame.use.topbar")
@endsection
@section('sidebar')
	@include("manage.frame.use.sidebar")
@endsection

@section('page_title')
	@if(isset($page[0][1]))
		{{$page[0][1]}} |&nbsp;
	@endif
	{{ Setting::get('site_title') }}
@endsection

@section('modal')
	<div id="masterModal-lg" class="modal fade">
		<div class="modal-dialog modal-lg" >
			<div class="modal-content">
			</div>
		</div>
	</div>

	<div id="masterModal-md" class="modal fade">
		<div class="modal-dialog" >
			<div class="modal-content">
			</div>
		</div>
	</div>

	<div id="masterModal-sm" class="modal fade">
		<div class="modal-dialog" >
			<div class="modal-content modal-sm">
			</div>
		</div>
	</div>

@endsection