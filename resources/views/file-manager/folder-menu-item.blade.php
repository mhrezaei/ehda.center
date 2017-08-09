<li class="folder" data-instance="{{ $item['instance'] }}" data-key="{{ $item['key'] }}">
    <a href="#">
        <span class="folder-icon fa fa-folder"></span>
        {{ $item['title'] }}
    </a>
    @isset($item['children'])
        @include('file-manager.folder-menu-list', ['items' => $item['children']])
    @endisset
</li>
