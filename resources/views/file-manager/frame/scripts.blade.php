<script>
    var route_prefix = "{{ url('/') }}";
    var route_preview = "{{ route('fileManager.preview') }}";
    var lang = {!! json_encode(trans('file-manager')) !!};
    var urls = {
        getList: '{{ route('fileManager.getList') }}',
        getFileDetails: '{{ route('fileManager.getFileDetails') }}',
        setFileDetails: '{{ route('fileManager.setFileDetails') }}',
        deleteFile: '{{ route('fileManager.deleteFile') }}',
        restoreFile: '{{ route('fileManager.restoreFile') }}',
    };
</script>

{!! Html::script ('https://use.fontawesome.com/f4fcfb493d.js') !!}
{!! Html::script ('assets/libs/jquery.min.js') !!}
{!! Html::script ('assets/libs/jquery-ui/jquery-ui.min.js') !!}
{!! Html::script ('assets/libs/bootstrap/js/bootstrap.min.js') !!}
{!! Html::script ('assets/js/tools.min.js') !!}
{!! Html::script ('assets/js/timer.min.js') !!}
{!! Html::script ('assets/libs/file-manager/file-manager.min.js') !!}