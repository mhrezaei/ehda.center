<form action="{{ url_locale('posts/search') }}" target="_blank">
    <div class="field search">
        <input type="text" placeholder="{{ trans('front.search') }}" name="s" required="required">
        <span class="icon-search like-submit-button"></span>
    </div>
</form>
