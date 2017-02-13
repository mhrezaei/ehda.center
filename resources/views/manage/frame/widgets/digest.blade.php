<div class="col-lg-3 col-md-6">
	<div class="panel panel-{{$theme or 'primary'}}">
		<div class="panel-heading">
			<div class="row">
				<div class="col-xs-3">
					<i class="fa fa-{{$icon or ''}} fa-5x"></i>
				</div>
				<div class="col-xs-9 text-left">
					<div class="huge">@pd($number)</div>
					<div>{{$text}}</div>
				</div>
			</div>
		</div>
		@if(isset($link) and $link != 'NO')
			<a href="{{ $link }}">
				<div class="panel-footer">
					<span class="pull-left">{{ $button_text or trans('forms.button.details') }}</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		@else
			<div class="panel-footer" style="opacity: 0.5;color:#9d9d9d">
				{{--<span class="pull-left" style="font-style: italic">{{ trans('manage.global.page_title') }}</span>--}}
				<div class="text-center"><i class="fa fa-bell-slash-o"></i></div>
				{{--<span class="pull-left" style="font-style: italic"><i class="fa fa-heart-o"></i></span>--}}
				{{--<span class="pull-right"><i class="fa fa-heart-o"></i></span>--}}
				<div class="clearfix"></div>
			</div>
		@endif
	</div>
</div>
