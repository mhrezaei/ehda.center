@php $file->spreadMeta() @endphp

<h2>{{ trans('file-manager.title-file-details') }}</h2>
<div class="attachment-info">
    <div class="thumbnail-image">
        {!! \App\Providers\UploadServiceProvider::getFileView($file) !!}
    </div>
    <div class="details text-start">
        <div class="filename ltr">{{ $file->file_name }}</div>
        <div class="upload-date">{{ ad(echoDate($file->created_at, 'j F Y')) }}</div>
        <div class="upload-size">{{ formatBytes($file->size) }}</div>
        @if($file->image_width and $file->image_height)
            <div class="dimension ltr">
                {{ ad($file->image_width) }} x {{ ad($file->image_height) }}
            </div>
        @endif
        {{--@if($file->can('edit'))--}}
        {{--<a href="#" class="edit-attachment">ویرایش تصویر</a>--}}
        {{--@endif--}}
        @if($file->can('delete'))
            <span class="deletion-message panel-lightRed pl5 pr5 rounded-corners-5" style="display: none"></span>
            <button type="button" class="delete-btn btn-link delete-file-btn ">
                {{ trans('file-manager.menu-delete') }}
            </button>
        @endif
    </div>
</div>
<label class="setting">
    <span class="name">{{ trans('validation.attributes.name') }}</span>
    <input type="text" name="name" value="{{ $file->name }}"/>
</label>
<label class="setting">
    <span class="name">{{ trans('validation.attributes.title') }}</span>
    <input type="text" name="title" value="{{ $file->title }}"/>
</label>
<label class="setting">
    <span class="name">{{ trans('validation.attributes.alternative_text') }}</span>
    <input type="text" name="alternative" value="{{ $file->alternative }}"/>
</label>
<label class="setting">
    <span class="name">{{ trans('validation.attributes.description') }}</span>
    <textarea name="description">{{ $file->description }}</textarea>
</label>
