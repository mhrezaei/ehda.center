{!! Html::script ('assets/js/timer.min.js') !!}
{!! Html::script ('assets/js/ajax-filter.js') !!}
<script>
    window.Laravel = {!! json_encode([
        'csrfToken' => csrf_token(),
    ]) !!};
</script>