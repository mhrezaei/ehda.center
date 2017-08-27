{!! $preloaderView or '' !!}

{{-- start -- uploader form --}}
@php
    $formData = [
            'method'=> 'post',
            'class' => "dropzone mb15 optional-input text-blue",
        ]
@endphp

@if(!isset($id) or !$id)
    @php $id = 'dropzoneUploader_' . str_random(8) @endphp
@endif

@if(!isset($varName) or !$varName)
    @php $varName = camel_case($id . '-dropzone') @endphp
@endif

@if(isset($dataAttributes) and is_array($dataAttributes) and count($dataAttributes))
    @foreach($dataAttributes as $fieldTitle => $filedValue)
        @php $formData["data-$fieldTitle"] = $filedValue @endphp
    @endforeach
@endif

<div class="row">
    <div class="dropzone mb15 optional-input uploader-container" id="{{ $id }}" data-var-name="{{ $varName }}"
         @if(isset($dataAttributes) and is_array($dataAttributes) and count($dataAttributes))
         @foreach($dataAttributes as $fieldTitle => $filedValue)
         data-{{ $fieldTitle }}="{{ $filedValue }}"
         @endforeach
         @endif
         @if(isset($target) and $target)
         data-target="{{ $target }}"
            @endif
    >
        <div class="dz-message" data-dz-message>
            <i class="fa fa-cloud-upload upload-icon f70 text-white"></i>
            <br/>
            @if(isset($uploadConfig['icon']) and ($boxIconName = $uploadConfig['icon']))
                <i class="fa fa-{{ $boxIconName }}"></i> &nbsp;
            @endif
            <span>{{ trans("front.file_types.$fileType.dropzone_text") }}</span>
        </div>
        @include('front.forms.hidden', [
            'id' => '',
            'name' => '_uploadIdentifier',
            'value' => encrypt(implode(',', $uploadIdentifiers)),
        ])
        @include('front.forms.hidden', [
            'id' => '',
            'name' => '_groupName',
            'value' => time() . str_random(8),
        ])
    </div>

</div>

<div class="row files-uploading-status" data-var-name="{{ $varName }}">
    {{--<div class="media-uploader-status">--}}
    {{--<h2>در حال بارگذاری...</h2>--}}
    {{--<button type="button" class="fa fa-times-circle upload-dismiss"></button>--}}
    {{--<div class="media-progress">--}}
    {{--<div></div>--}}
    {{--</div>--}}
    {{--<div class="upload-detail">--}}
    {{--<span class="upload-count">--}}
    {{--<span class="upload-index">1</span>--}}
    {{--/--}}
    {{--<span class="upload-total">1</span>--}}
    {{--</span>--}}
    {{--<span class="upload-detail-separator">_</span>--}}
    {{--<span class="upload-filename">blabla.jpg</span>--}}
    {{--</div>--}}
    {{--<div class="upload-errors">--}}
    {{--<ul>--}}
    {{--<li>Error 1</li>--}}
    {{--<li>Error 2</li>--}}
    {{--</ul>--}}
    {{--</div>--}}
    {{--</div>--}}
</div>
{{-- start -- scripts for uploader --}}
@section('endOfBody')
    <script>
        var {{ $varName }};
        $(document).ready(function () {
            {{ $varName }} = new Dropzone("#{{ $id }}", {
                maxFileSize: {{ UploadServiceProvider::getTypeRule($fileType, "maxFileSize") }},
                maxFiles: {{ UploadServiceProvider::getTypeRule($fileType, "maxFiles") }},
                acceptedFiles: "{{ implode(',', UploadServiceProvider::getTypeRule($fileType, "acceptedFiles")) }}",
            });

            {{ $varName }}.on("removedfile", function (file) {
                removeFromServer(file, $(this.element));
            });

            @if(isset($callbackOnEachUploadComplete) and $callbackOnEachUploadComplete)
            {{ $varName }}. on("complete", function (file) {
                {{ $callbackOnEachUploadComplete }}(file);
            });
            @endif

            @if(isset($callbackOnEachUploadSuccess) and $callbackOnEachUploadSuccess)
            {{ $varName }}. on("success", function (file) {
                {{ $callbackOnEachUploadSuccess }}(file);
            });
            @endif

            @if(isset($callbackOnEachUploadError) and $callbackOnEachUploadError)
            {{ $varName }}. on("error", function (file) {
                {{ $callbackOnEachUploadError }}(file);
            });
            @endif

            @if(isset($callbackOnQueueComplete) and $callbackOnQueueComplete)
            {{ $varName }}. on("queuecomplete", function () {
                {{ $callbackOnQueueComplete }}(this.getAcceptedFiles());
            });
            @endif

            @if(isset($target) and $target)
            {{ $varName }}. on("success", function (file) {
                updateTarget(this, "{{ $target }}");
            });
            // TODO: We should try to don't remove file item from view if it doesn't remove from the server.
            {{ $varName }}. on("removedfile", function (file) {
                updateTarget(this, "{{ $target }}");
            });
            @endif


            @if(isset($events) and $events and is_array($events))
            @foreach($events as $eventName => $eventValue)
            {{ $varName }}. on("{{ $eventName }}", {!! $eventValue !!});
            @endforeach
            @endif

            // Clear predefined data in hidden inputs after refresh
            $.each(Dropzone.instances, function (index, obj) {
                updateTarget(this, $(this.element).attr('data-target'));
            });
        });
    </script>
@append
{{-- end -- scripts for uploader --}}
