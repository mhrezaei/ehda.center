<div class="yt-dialog"
     @if(isset($id)) id="{{ $id }}" @endif>
    <div class="yt-dialog-body">
        @if(isset($dialogImage) and $dialogImage)
            <div class="yt-dialog-image">
                <img src="@yield('dialog-image-link')">
            </div>
        @endif
        <p class="yt-dialog-text">
            @yield('dialog-text')
        </p>
    </div>
</div>
