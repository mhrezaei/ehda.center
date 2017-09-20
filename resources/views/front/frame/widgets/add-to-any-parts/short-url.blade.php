@if(isset($shortUrl))
    <div class="form-group text-left pull-end mt10" style="position: inherit">
        <label for="short_link" style="font-size: 10px;">{{ trans('front.short_link') }}: </label>
        <input id="short_link" value="{{ $shortUrl }}" style="float: left; width: 200px;" class="form-control">
    </div>
@endif