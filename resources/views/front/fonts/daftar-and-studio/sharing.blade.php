<div class="col-xs-12 post-footer">
    <div class="row">
        <div class="share col-xs-12 pt15">
            @include('front.frame.widgets.add-to-any', [
                'shareUrl' => request()->url(),
                'shortUrl' => request()->url(),
            ])
        </div>
    </div>
</div>