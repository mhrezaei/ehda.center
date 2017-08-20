@if($image = model('file', $item['src']) and $image->id > 0)
<div class="flex-item">
    <a @if($image) href="{{ url($image->directory . DIRECTORY_SEPARATOR . $image->physical_name) }}" @endif class="inner">
        <img src="{{ url($image->directory . DIRECTORY_SEPARATOR . $image->related_files['thumbnail']) }}" alt="{{ $item['label'] }}">
    </a>
</div>
@endif

{{--@TODO: this file need to edit gallery files should load with service provider--}}