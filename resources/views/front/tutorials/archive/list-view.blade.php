<div class="col-xs-12 result-container" style="min-height: 150px;">
    <div class="row">
        {!! $postsListHtml or '' !!}
    </div>
    @if(!isset($filterNeeded) or $filterNeeded)
        <div id="loading-dialog" class="cover text-center text-blue pt30">
            <p>
                {{ trans('forms.feed.wait') }}
            </p>
            <img src="{{ asset('assets/images/template/AjaxLoader.gif') }}">
        </div>
    @endif
</div>