@if(!$ajaxRequest)
    <div class="page-content category">
        <div class="container">
            <header id="category-header">
                @include('front.posts.general.search-form')
                <div class="field sort"><label> {{ trans('front.sort') }} </label>
                    <div class="select rounded">
                        <select class="ajax-sort">
                            <option data-identifier="price" value="desc"> {{ trans('front.price_max_to_min') }} </option>
                            <option data-identifier="price" value="asc"> {{ trans('front.price_min_to_max') }} </option>
                            {{--<option> {{ trans('front.best_seller') }} </option>--}}
                            {{--<option> {{ trans('front.favorites') }} </option>--}}
                        </select>
                    </div>
                </div>
            </header>
            <div class="row">
                @if($showFilter)
                    @include($viewFolder . '.sidebar')
                    <div class="col-sm-9 has-dialog">
                        @else
                            <div class="col-sm-12 has-dialog">
                                @endif
                                @endif
                                <div class="product-list result-container">
                                    @if(!$isBasePage)
                                        <div class="row">
                                            @if($posts)
                                                @foreach($posts as $index => $post)
                                                    @if(($index % 3) == 0)
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                @endif
                                                                @include($viewFolder . '.item')
                                                                @if(($index % 3) == 2)
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="pagination-wrapper mt20">
                                            {!! $posts->render() !!}
                                        </div>
                                    @endif
                                </div>
                                @if(!$ajaxRequest)
                                @include('front.dialog.loading', [
                                    'id' => 'loading-dialog'
                                ])
                            </div>
                    </div>
            </div>
        </div>
@endif
