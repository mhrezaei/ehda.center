@if($homePageTopParagraph and $homePageTopParagraph->exists)
    <div class="row">
        <div id="current-members" class="text-center">
            <h3 style="font-size: 50px;white-space: normal">{{ $homePageTopParagraph->title }}</h3>
        </div>
    </div>
@endif