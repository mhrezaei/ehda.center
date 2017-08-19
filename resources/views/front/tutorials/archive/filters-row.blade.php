@php
    $categories = $postType->categoriesIn(getLocale());
    $selectOptions = $categories->map(function ($item) {
            return ['id' => $item->id , 'title' => $item->title];
        })->toArray();
    $selectOptions = array_merge([
            ['title' => trans('front.tutorials.all')]
        ], $selectOptions);
@endphp

{{--@php--}}
{{--$selectOptions = $postTypes->map(function ($item) {--}}
{{--return ['id' => $item->id , 'title' => $item->title];--}}
{{--})->toArray();--}}
{{--$selectOptions = array_merge([--}}
{{--['title' => trans('front.tutorials.all')]--}}
{{--], $selectOptions);--}}
{{--@endphp--}}

<div class="row pt20 filters filters-panel" data-filter-url="{{ route_locale('education.get-posts.ajax') }}">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="row filter-select" data-identifier="type">
            @include('front.forms.select' , [
                'size' => 10,
                'name' => 'postType',
                'label' => false,
                'value' => '',
                'options' => $selectOptions,
            ])
        </div>
    </div>
</div>

@section('endOfBody')
    @include('front.posts.list.product.ajax-filter-assets')
@append