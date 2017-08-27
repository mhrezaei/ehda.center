@section('head')
    {!! Html::style('assets/css/dropzone.min.css') !!}
@append
@section('endOfBody')
    {!! Html::script('assets/libs/dropzone/dropzone.js') !!}
    {!! Html::script('assets/js/tools.min.js') !!}
    <script>
        // if we miss this command, every elements with "dropzone" class will be automatically change to dropzone
        Dropzone.autoDiscover = false;

        // setting default options for all dropzone uploaders in this page
        Dropzone.prototype.defaultOptions.url = "{{ route('dropzone.upload') }}";
        Dropzone.prototype.defaultOptions.addRemoveLinks = true;
        Dropzone.prototype.defaultOptions.dictRemoveFile = "";
        Dropzone.prototype.defaultOptions.dictCancelUpload = "";
        Dropzone.prototype.defaultOptions.dictFileTooBig = "{{ trans('front.upload.errors.size') }}";
        Dropzone.prototype.defaultOptions.dictInvalidFileType = "{{ trans('front.upload.errors.type') }}";
        Dropzone.prototype.defaultOptions.dictResponseError = "{{ trans('front.upload.errors.server') }}";
        Dropzone.prototype.defaultOptions.dictMaxFilesExceeded = "{{ trans('front.upload.errors.limit') }}";

        @php $freshConfigs = UploadServiceProvider::getDefaultJsConfigs() @endphp

        Dropzone.prototype.defaultOptions.init = function () {
            this.on('sending', function (file, xhr, formData) {
                formData.append('_token', "{{ csrf_token() }}");

                var inElementData = $(this.element).find(':input').serializeArray();
                $.each(inElementData, function (index, node) {
                    formData.append(node.name, node.value);
                })
            });

            this.on('error', function (file, response, xhr) {
                if (isDefined(xhr) && xhr.status == 422 && response.file) {
                    let errorText = response.file.join('<br />');
                    $(file.previewElement).find('.dz-error-message span').html(errorText)
                }
            });

            @if(array_key_exists('events', $freshConfigs))
                @foreach($freshConfigs['events'] as $eventName => $eventValue)
                    this.on("{{ $eventName }}", {{ $eventValue }});
                @endforeach

                @unset($freshConfigs['events'])
            @endif
        };

        @foreach($freshConfigs as $configKey => $configValue)
            Dropzone.prototype.defaultOptions.{{ $configKey }} = "{{ $configValue }}";
        @endforeach

        function updateTarget(dropzoneInstance, target) {
            if (dropzoneInstance.getUploadingFiles().length === 0 && dropzoneInstance.getQueuedFiles().length === 0) {
                var accepted = dropzoneInstance.getAcceptedFiles();
                var dataArr = [];
                var targetEl = $('#' + target);
                $.each(accepted, function (index, file) {
                    var rsJson = $.parseJSON(file.xhr.response);
                    dataArr.push(rsJson.file);
                });
                if (dataArr.length) {
                    targetEl.val(JSON.stringify(dataArr));
                } else {
                    targetEl.val('');
                }
            }
        }

        function removeFromServer(file, dropzoneElement) {
            if (file.xhr) {
                var rs = $.parseJSON(file.xhr.response);
                var data = rs;
                data._token = "{{ csrf_token() }}";

                var additionalData = dropzoneElement.find(':input').serializeArray();
                $.each(additionalData, function (index, item) {
                    data[item.name] = item.value;
                });
                $.ajax({
                    url: "{{ route('dropzone.remove') }}",
                    type: 'POST',
                    data: data,
                })
            }
        }

    </script>
@append