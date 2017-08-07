@section('endOfBody')
    {!! Html::script ('assets/js/forms.min.js') !!}
    {!! Html::script ('assets/js/circle-progress.min.js') !!}
@append
<div class="timers container">
    {!! $deadlinesHTML !!}
</div>