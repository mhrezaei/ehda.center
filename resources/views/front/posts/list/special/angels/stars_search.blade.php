@if(getLocale() == 'fa')
    <div class="search-angel col-xs-12 col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-4">
        <button class="btn-search mb10" type="button"><i class="icon icon-search"></i></button>
        <input type="search" name="angels_name" id="angels_name" value=""
               placeholder="{{ trans('front.angels.search') }}"
               autocomplete="off"
               style="width: 80%">
        @include('front.posts.list.special.angels.stars_not_found_alert')
    </div>
@endif