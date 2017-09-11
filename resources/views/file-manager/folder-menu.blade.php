<?php
$folders = collect();
$postTypes->each(function ($postType) use (&$folders) {
    $folders->push(\App\Providers\FileManagerServiceProvider::getPointData($postType));
});
?>

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