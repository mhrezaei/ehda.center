@php
    $targetFolderName = 'commenting';

    $currentFolderNameParts = explode('.', $viewFolder);
    $currentFolderName = end($currentFolderNameParts);

    $viewFolder = preg_replace("/" . $currentFolderName . "$/", $targetFolderName, trim($viewFolder));
@endphp

@include('front.posts.single.special.commenting.main')