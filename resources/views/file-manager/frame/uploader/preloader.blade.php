@php $freshConfigs = UploadServiceProvider::getDefaultJsConfigs() @endphp

@section('html_header')
    {!! Html::style('assets/css/dropzone.min.css') !!}
    <style>
        .dz-hidden-input { /* this is in body */
            direction: ltr !important;
        }
    </style>
@append

@section('end-of-body')
    {!! Html::script('assets/libs/dropzone/dropzone.js') !!}
    <script>
        // Setting Dynamic Variables
        var csrfToken = "{{ csrf_token() }}";
        var dropzoneRoutes = {
            upload: "{{ route('dropzone.upload') }}",
            remove: "{{ route('dropzone.remove') }}",
        };
        var messages = {
            errors: {
                size: "{{ trans('front.upload.errors.size') }}",
                type: "{{ trans('front.upload.errors.type') }}",
                server: "{{ trans('front.upload.errors.server') }}",
                limit: "{{ trans('front.upload.errors.limit') }}",
            },
            statuses: {
                uploading: "{{ trans('front.upload.statuses.uploading') }}",
                failed: "{{ trans('front.upload.statuses.failed') }}",
                success: "{{ trans('front.upload.statuses.success') }}",
            }
        };
    </script>
    {!! Html::script('assets/libs/dropzone/dropzone-additives.min.js') !!}
    <script>
        Dropzone.prototype.defaultOptions.init = function () {
            var dropzoneObj = this;
            $.each(dropzoneOptions.init, function (key, value) {
                dropzoneObj.on(key, value);
            });

            @if(array_key_exists('events', $freshConfigs))
                @foreach($freshConfigs['events'] as $eventName => $eventValue)
                    dropzoneObj.on("{{ $eventName }}", {{ $eventValue }});
                @endforeach

                @unset($freshConfigs['events'])
            @endif
        };

        @foreach($freshConfigs as $configKey => $configValue)
            Dropzone.prototype.defaultOptions.{{ $configKey }} = "{{ $configValue }}";
        @endforeach
    </script>
@append