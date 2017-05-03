@if(!$ajaxRequest)
    <div class="page-content category">
        <div class="container">
            <header id="category-header">
                <div class="field search"><input type="text" placeholder="{{ trans('front.search') }}"> <span
                            class="icon-search"></span></div>
                <div class="field sort"><label> {{ trans('front.sort') }} </label>
                    <div class="select rounded">
                        <select>
                            <option> {{ trans('front.price_max_to_min') }} </option>
                            <option> {{ trans('front.price_min_to_max') }} </option>
                            <option> {{ trans('front.best_seller') }} </option>
                            <option> {{ trans('front.favorites') }} </option>
                        </select>
                    </div>
                </div>
            </header>
            <div class="row">
                @if($showFilter)
                    @include($viewFolder . '.sidebar')
                    <div class="col-sm-9">
                        @else
                            <div class="col-sm-12">
                                @endif
                                @endif
                                <div class="product-list">
                                    <div class="row">
                                        @if($posts)
                                            @foreach($posts as $post)
                                                @include($viewFolder . '.item')
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="pagination-wrapper mt20">
                                        {!! $posts->render() !!}
                                    </div>
                                </div>
                                @if(!$ajaxRequest)
                            </div>
                    </div>
            </div>
        </div>
@endif
