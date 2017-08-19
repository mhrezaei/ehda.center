<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="fa" class="no-js">
<!--<![endif]-->
<head>
	<meta charset="utf-8"/>
	<title>@yield('page_title')</title>

	<script language="javascript">
	    function assets($additive) {
		    if (!$additive) $additive = '';
		    return url('assets/' + $additive);
	    }
	    function url($additive) {
		    if (!$additive) $additive = '';
		    return '{{ url('-additive-') }}'.replace('-additive-', $additive);
	    }
	</script>

	{{-- JQuery --}}
	{!! Html::script ('assets/libs/jquery.min.js') !!}
	{!! Html::script ('assets/libs/jquery.form.min.js') !!}
	{!! Html::script ('assets/libs/jquery.inputmask.bundle.js') !!}
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

	{{-- Charts --}}
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>
	{!! HTML::script ('assets/libs/chartsjs/Chart.PieceLabel.js') !!}


	{{-- BOOTSTRAP --}}
	{!! Html::style('assets/libs/bootstrap/css/bootstrap.min.css') !!}
	{!! Html::style('assets/libs/bootstrap/css/bootstrap-rtl.min.css') !!}
	{!! HTML::script ('assets/libs/bootstrap/js/bootstrap.min.js') !!}

	{!! HTML::script ('assets/js/pinterest_grid.js') !!}

	{{-- fonts stuff --}}
	{!! Html::style('assets/css/fontiran.css') !!}
	{!! Html::style('assets/libs/font-awesome/css/font-awesome.min.css') !!}

	{{-- TinyMCE--}}
	{!! HTML::script ('assets/libs/tinymce/tinymce.min.js') !!}
	{!! HTML::script ('assets/libs/tinymce/tinymce.starter.js') !!}

	{{-- sb-admin --}}
	{!! Html::style('assets/libs/sb-admin/metisMenu.css') !!}
	{!! Html::style('assets/libs/sb-admin/sb-admin-2.css') !!}
	{!! Html::style('assets/libs/sb-admin/timeline.css') !!}
	{{--{!! HTML::script ('assets/libs/sb-admin/Chart.js') !!}--}}
	{{--{!! HTML::script ('assets/libs/sb-admin/frontend.js') !!}--}}
	{!! HTML::script ('assets/libs/sb-admin/metisMenu.js') !!}
	{!! HTML::script ('assets/libs/sb-admin/sb-admin-2.js') !!}

	{{-- Bootstrap-select --}}
	{!! Html::style('assets/libs/bootstrap-select/bootstrap-select.min.css') !!}
	{!! HTML::script ('assets/libs/bootstrap-select/bootstrap-select.min.js') !!}
	{!! HTML::script ('assets/libs/bootstrap-select/defaults-fa_IR.min.js') !!}

	{!! HTML::style ('assets/libs/datepicker/js-persian-cal.css') !!}
	{!! HTML::script ('assets/libs/datepicker/js-persian-cal.js') !!}

	{{-- Jquery Sortable --}}
	{{--	{!! HTML::script ('assets/libs/jquery-sortable/jquery-sortable.js') !!}--}}


	{{--Laravel File-Manage--}}
	{!! HTML::script ('/vendor/laravel-filemanager/js/lfm.js') !!}

	{{-- Custom --}}
	{!! Html::script ('assets/libs/file-manager/file-manager-modal.min.js') !!}
	{!! Html::style('assets/css/manage.min.css') !!}
	{!! HTML::script ('assets/js/forms.js') !!}
	{!! HTML::script ('assets/js/manage.js') !!}
	{!! HTML::script ('assets/js/tools.min.js') !!}


	@yield('html_header')


	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1" name="viewport"/>
	<meta content="" name="description"/>
	<meta content="" name="author"/>

</head>
<body>
@yield('body')
@yield('end-of-body')

<div class="modal fade file-manager-modal" id="file-manager-modal" role="dialog">

	{{--
	|--------------------------------------------------------------------------
	| File Manager Modal
	|--------------------------------------------------------------------------
	|
	--}}

	<div class="modal-dialog">
		<div class="modal-content">
			<button class="btn-close" data-dismiss="modal">
				<span class="fa fa-times"></span>
			</button>
			<div class="modal-body">
				<iframe class="file-manager-iframe" frameborder="0"></iframe>
			</div>
		</div>

	</div>
</div>




</body>
</html>