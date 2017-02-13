<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<script language="javascript">
		function base_url($ext) {
			if(!$ext) $ext = "" ;
			var $result = '{{ URL::to('/') }}' + $ext ;
			return $result  ;
		}
	</script>

	{{-- JQuery --}}
	{!! Html::script ('assets/libs/jquery.js') !!}
	{{--{!! HTML::script ('assets/libs/jquery.form.min.js') !!}--}}

	{{--{!! HTML::script ('assets/libs/onepage-scroll/jquery.onepage-scroll.min.js') !!}--}}
	{{--{!! HTML::style ('assets/libs/onepage-scroll/onepage-scroll.css') !!}--}}

	{{-- BOOTSTRAP --}}
	{!! Html::style('assets/libs/bootstrap/css/bootstrap.min.css') !!}
	{!! Html::style('assets/libs/bootstrap/css/bootstrap-rtl.min.css') !!}

	{!! HTML::script ('assets/libs/bootstrap/js/bootstrap.min.js') !!}

	{{-- Other libs --}}
	{{--{!! HTML::style('assets/libs/font-awesome/css/font-awesome.min.css') !!}--}}

	{{-- Personal stuff --}}
	{!! Html::style('assets/css/fontiran.css') !!}
	{!! Html::style('assets/css/home-h.min.css') !!}
	{!! Html::style('assets/css/home-t.min.css') !!}

	{{--{!! HTML::script('assets/js/forms.js') !!}--}}
	{!! HTML::script('assets/js/hadi.js') !!}
	{!! HTML::script('assets/js/taha.js') !!}

	@yield('assets')

	<title>{{ $pageTitle or trans('global.siteTitle') }}</title>
</head>
<body>

@yield('content'  )
@yield('modal')
</body>
</html>