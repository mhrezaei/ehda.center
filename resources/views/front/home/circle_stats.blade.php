{{-- TODO: remove beautiful version and set minified file --}}
{{--{!! Html::script ('assets/js/circle-progress.min.js') !!}--}}
@section('endOfBody')
    {!! Html::script ('assets/js/forms.min.js') !!}
    {!! Html::script ('assets/js/circle-progress.js') !!}
@append
<div class="timers container">
    {!! $deadlinesHTML !!}
</div>