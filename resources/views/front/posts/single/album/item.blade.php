<div class="flex-item">
    @php $url = \App\Providers\UploadServiceProvider::getFileUrl($item['src']) @endphp
    <a @if($url) href="{{ $url }}" @endif class="inner">
        {!! \App\Providers\UploadServiceProvider::getFileView($item['src'], 'thumbnail') !!}
    </a>
</div>

{{--@TODO: this file need to edit gallery files should load with service provider--}}