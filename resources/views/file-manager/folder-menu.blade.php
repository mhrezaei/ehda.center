@php
    $folders = collect();
    $acceptedFilesCategories = [];
    $postTypes->each(function ($postType) use (&$folders, &$acceptedFilesCategories) {
        $pointData = \App\Providers\FileManagerServiceProvider::getPointData($postType);
        $folders->push($pointData);

    });
@endphp

<div class="media-folder-menu">
    <div class="folder-container">
        <div class="folder-menu">
            {{--<div class="close-sidebar right-align">--}}
            {{--<span class="fa fa-chevron-right"></span>--}}
            {{--</div>--}}
            <h2 class="title">پوشه ها</h2>
            @include('file-manager.folder-menu-list', ['items' => $folders])
        </div>
    </div>
</div>

@section('end-of-body')
    <script>
        var acceptedFilesCategories = {
            @yield('acceptedFilesCategories')
        };
    </script>
@append
